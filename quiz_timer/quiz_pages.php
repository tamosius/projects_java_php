<?php
session_start();  // start the session

require_once "initialize_quiz.php";


if(!isset($_SESSION['page'])){ // if session is not set yet, set quiz page 0
    $_SESSION['page'] = 0;
    $_SESSION['time'] = 5; // set the time for quiz
    $_SESSION['quiz_is_finished'] = "FALSE";
}

if(isset($_GET['page_number'])){
    

    if($_GET['page_number'] == 0 && $_SESSION['quiz_is_finished'] == "FALSE"){
        
        initialize_quiz(); // call function 'initialize_quiz()' in 'initialize_quiz.php' file 
                           // and assign questions to array
        $username = $_SERVER['PHP_AUTH_USER']; // get the user name, that enters at the start
        $count_of_questions = count($_SESSION['quiz']);  // get the size of array in 'initialize_quiz.php' file
        $page_number = $_SESSION['page'];
        $time = $_SESSION['time']; // time to complete quiz
        
        echo <<< _END
        <form action="#" id="form" method="get">
              <div class="start_page">
                  <input type="hidden" id="page_number" value="$page_number" />
                  <input type="hidden" id="time" value="$time" />
                  <hr>
                  <div class="welcome_message">
                     $username, welcome to the Quiz! 
                  </div><hr>
                  <div class="middle_message">
                      You will have $count_of_questions questions to answer<br>and You will have $time minutes to complete a quiz.<br>Wishing you best of luck!
                  </div>
                  
              </div>
        </form>
_END;
}
}
    
if(isset($_GET['next_submit']) || isset($_GET['back_submit'])){
        if(isset($_GET['answer'])){
        // assign the answer option chosen by user
        $_SESSION['answer'][$_SESSION['page']] = filter_input(INPUT_GET, 'answer');
        }
        
        if(isset($_GET['next_submit'])){
            $_SESSION['page']++;  // go to the next page
        }
        else if(isset($_GET['back_submit'])){
            $_SESSION['page']--; // go back one page
        }
    
        $question_number = $_SESSION['page'];
        $total_questions = count($_SESSION['quiz']);
        $question = $_SESSION['quiz'][$question_number - 1][0][0];
        $option1 = $_SESSION['quiz'][$question_number - 1][1][0];
        $option2  = $_SESSION['quiz'][$question_number - 1][2][0];
        $option3 = $_SESSION['quiz'][$question_number - 1][3][0];
        $option4 = $_SESSION['quiz'][$question_number - 1][4][0];
        $page_number = $_SESSION['page'];
        $correct_answer = $_SESSION['correct_answers'][$_SESSION['page']];
        $answer_option = $_SESSION['answer'][$_SESSION['page']];
        $quiz_is_finished = $_SESSION['quiz_is_finished'];
        
        echo <<< _END
        <form action="#" id="form" method="get">
            <div class="question_container">
                <input type="hidden" id="page_number" value="$page_number" />
                <input type="hidden" id="answer_option" value="$answer_option" />
                <input type="hidden" id="quiz_is_finished" value="$quiz_is_finished" />
                <input type="hidden" id="correct_answer" value="$correct_answer" />
                <input type="hidden" id="total_questions" value="$total_questions"/>
                <hr>
                <div class="question_number">Question $question_number out of $total_questions<hr></div>
                <span class="question_span">Question: </span><span class="question">$question</span><hr>
                <div class="option"><input type="radio" id="1" name="answer" value="1" />$option1</div>
                <div class="option"><input type="radio" id="2" name="answer" value="2" />$option2</div>
                <div class="option"><input type="radio" id="3" name="answer" value="3" />$option3</div>
                <div class="option"><input type="radio" id="4" name="answer" value="4" />$option4</div><hr>
            </div>
        </form>
_END;

}else if(isset($_GET['finish_submit'])){
    
        if(isset($_GET['answer'])){
        // assign the answer option chosen by user
        $_SESSION['answer'][$_SESSION['page']] = filter_input(INPUT_GET, 'answer');
        }
    
        $_SESSION['page'] = 0;
        $_SESSION['quiz_is_finished'] = "TRUE";
        $total_questions = count($_SESSION['quiz']);
        $page_number = $_SESSION['page'];
        
        if(!isset($_SESSION['quiz_results'])){ // get results when quiz is finished
            $_SESSION['quiz_results']['correct_answers'] = calculate_correct_answers(); // get number of correct answers
            $_SESSION['quiz_results']['percentage'] = calculate_percentage($_SESSION['quiz_results']['correct_answers'], $total_questions); // get percentage of correct answers
            $_SESSION['quiz_results']['grade_message'] = get_grade_message($_SESSION['quiz_results']['percentage']);  // 
        }
        
        $correct_answers = $_SESSION['quiz_results']['correct_answers'];
        $percentage = $_SESSION['quiz_results']['percentage'];
        $grade_message = $_SESSION['quiz_results']['grade_message'];
                      
        echo <<< _END
        <form action="#" id="form" method="post">
              <div class="start_page">
                  <input type="hidden" id="page_number" value="$page_number" />
                  <hr>
                  <div class="welcome_message">
                     You have $correct_answers correct answers out of $total_questions questions. 
                  </div><hr>
                  <div class="middle_message">
                      Quiz result - $percentage%<br>$grade_message
                  </div>
              </div>
        </form>
_END;
      
}
function calculate_correct_answers(){
    $correct_answers = 0;
    for($i = 1; $i <= count($_SESSION['quiz']); $i++){
        if($_SESSION['answer'][$i] == $_SESSION['correct_answers'][$i]){
            $correct_answers++;
        }
    }
    return $correct_answers;
}
// function to calculate the percentage of correct answers
function calculate_percentage($correct_answers, $total_questions){
    return ceil(($correct_answers / $total_questions) * 100);
}

function get_grade_message($percentage){
    if($percentage == 100){
        return "Congratulations! Excellent result, well done!!!";
    }else if($percentage < 100 && $percentage >= 80){
        return "Very good result, well done!";
    }else if($percentage < 80 && $percentage >= 70){
        return "Good result, good work!";
    }else if($percentage < 70 && $percentage >= 60){
        return "Still good result, good luck!";
    }else if($percentage < 60 && $percentage >= 50){
        return "Average result, wish you better results next time!";
    }else if($percentage < 50 && $percentage >= 40){
        return "You still pass, do better next time.";
    }else if($percentage < 40){
        return "Sorry, you have failed!!";
    }
}

?>

