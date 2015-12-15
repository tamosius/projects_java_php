
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.Socket;
import javafx.application.Application;
import static javafx.application.Application.launch;
import javafx.application.Platform;
import javafx.geometry.Pos;
import javafx.scene.Scene;
import javafx.scene.control.Label;
import javafx.scene.layout.BorderPane;
import javafx.scene.layout.GridPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Pane;
import javafx.scene.shape.Circle;
import javafx.scene.shape.Line;
import javafx.scene.text.Text;
import javafx.stage.Stage;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author tamosius5
 */
public class Player1 extends Application implements TicTacToeConstants{
    private GridPane mainPane;
    private BorderPane borderPane;
    private HBox bottomBox;
    private Label topLabel;  // label to show player token
    private Label playerTurnLabel; // label to show who's turn
    private Text gameText;         // text to show game count
    
    private Socket socket; // socket to connect to server
    private DataInputStream fromServer;  // output data to server
    private DataOutputStream toServer;  // input data from server
    
    private Cell[][] cell = new Cell[3][3];
    private boolean[][] cellSelected = new boolean[3][3];
    
    private char token;
    private int playerNumber;  // number to assign to player( 1 or 2 )
    private boolean yourTurn;  // check is your turn or not
    private boolean continueToPlay = true;  // continue to play until someone wins or draw
    private boolean wait = true;
    
    private int status;  // receive game status from server
    private int rowReceived;  // row index received from a server
    private int columnReceived; // column received from a server
    private int rowSelected;   // row index selected by a player
    private int columnSelected;  // column index selected by a player
    
    @Override
    public void start(Stage primaryStage){
        bottomBox = new HBox(30);
        gameText = new Text("You are on game : ");
        gameText.setStyle("-fx-text-decoration: underline;");
        
        
        topLabel = new Label();
        playerTurnLabel = new Label();
        playerTurnLabel.setStyle("-fx-font-size: 15px");
        bottomBox.getChildren().add(playerTurnLabel);
        bottomBox.setAlignment(Pos.CENTER);
        //bottomLabel.setStyle("-fx-font-size: 30px");
        //bottomLabel.setStyle("-fx-align: center");
        
        
        mainPane = new GridPane();
        mainPane.setAlignment(Pos.CENTER);
        mainPane.setHgap(0);
        mainPane.setVgap(0);
        
        for(int row = 0; row < 3; row++)
            for(int column = 0; column < 3; column++)
                mainPane.add(cell[row][column] = new Cell(row, column), column, row);
        
        borderPane = new BorderPane();
        borderPane.setTop(topLabel);
        borderPane.setCenter(mainPane);
        borderPane.setBottom(bottomBox);
        
        Scene scene = new Scene(borderPane, 300, 300);
        primaryStage.setTitle("TicTacToe game");
        primaryStage.setScene(scene);
        primaryStage.show();
        
        new Thread(()-> {
            connectToServer();  // connect to server and set players number
            while(continueToPlay){
                if(playerNumber == 1){
                    waitForAction();  // wait for player1 to select a cell
                    sendMove();  // send the move coordinates to the server
                    receiveMove();  // receive the move coordinates from the server
                }
                else if(playerNumber == 2){
                    receiveMove();  // receive the move coordinates from the server
                    waitForAction(); // wait for player2 to select a cell
                    sendMove();  // send a move coordinates to the server
                }
            }
        }).start(); // start a thread
    }
    private void connectToServer(){ 
        try{
            socket = new Socket("localhost", 8002);   // connect to server
            fromServer = new DataInputStream(socket.getInputStream()); // create input from server
            toServer = new DataOutputStream(socket.getOutputStream()); // create output to server
            playerNumber = fromServer.readInt();
            
            if(playerNumber == 1){ // set token and number for the player
                token = 'X';
                displayMessage("Waiting for player2 to join...");
                fromServer.readInt();  // player2 notifies that he is connected
                displayMessage("Player2 joined. Your move.");
                yourTurn = true;
            }
            else if(playerNumber == 2){
                token = 'O';
                displayMessage("Waiting for player1 to move...");
                yourTurn = false;
            } 
            System.out.println("player number is " + playerNumber);
        }
        catch(IOException e){
            Platform.runLater(()->{
                topLabel.setText("Unable to connect to server..");
            });
            
        }
        Platform.runLater(()->{
            topLabel.setText("You are player" + playerNumber +
                    " and your token is '" + token + "'");
        });
    }
    private void waitForAction(){
        while(wait){
            try{
                //displayMessage("Your turn to move..");
                Thread.sleep(100);  // put tread for sleep until player selects the cell
            }
            catch(InterruptedException e){
                displayMessage("The Thread is interrupted!!..");
            }
        }
        wait = true;
    }
    private void sendMove(){
        try{
            toServer.writeInt(rowSelected); // send information to server about row and column selected
            toServer.flush();
            toServer.writeInt(columnSelected);
            toServer.flush();
            yourTurn = false;
            displayMessage("Waiting player" + (playerNumber == 1 ? 2 : 1) + " to move...");
            
        }
        catch(IOException e){
            displayMessage("Error to send a move...");
        }
    }
    private void receiveMove(){
        try{
            
            status = fromServer.readInt();  // receive game status from a server
            System.out.println("status = " + status);
            if(status == PLAYER1_WON){
                continueToPlay = false;
                displayMessage(playerNumber == 1 ? "I won!! Well done!!" : "I lost!! Maybe next time...");
            }
            else if(status == PLAYER2_WON){
                continueToPlay = false;
                displayMessage(playerNumber == 1 ? "I lost!! Maybe next time..." : "I won!! Well done!!");
            }
            else if(status == DRAW){
                continueToPlay = false;
                displayMessage("Draw! No winner...");
            }
            else if(status == CONTINUE){
                displayMessage(yourTurn == false ? "Your turn to move!:)" : "Wait for player" + 
                        (playerNumber == 1 ? 1 : 2) + " to move...");
            }
            rowReceived = fromServer.readInt();   // receive row and column index selected from another player
            columnReceived = fromServer.readInt();
            yourTurn = true;
        }
        catch(IOException e){
            displayMessage("Error reading from a server!!...");
        }
        System.out.println("Row: " + rowReceived + " column: " + columnReceived);
        Platform.runLater(()->{
            // set token on a cell coordinates received from a server
            cell[rowReceived][columnReceived].setToken(token == 'X' ? 'O' : 'X');
        });
    }
    // display messages on bottom label
    private void displayMessage(String message){
        Platform.runLater(()->{
            playerTurnLabel.setText(message);
        });
    }
    // main method
    public static void main(String[] args){
        launch(args);
    }
// inner class for cell
class Cell extends Pane{
    private int row;
    private int column;
    
    public Cell(int row, int column){
        this.column = column;
        this.row = row;
        
        this.setStyle("-fx-border-color: black");
        this.setPrefSize(2000, 2000);
        this.setOnMouseClicked(e -> handleMouseClick());
    }
    private void setToken(char token){
        if(token == 'X'){
            Line line = new Line(10, 10, this.getWidth() - 10, this.getHeight() - 10);
            line.setStrokeWidth(2);
            line.endXProperty().bind(this.widthProperty().subtract(10));
            line.endYProperty().bind(this.heightProperty().subtract(10));
            //line.setStroke(Color.GREEN);
            line.setStyle("-fx-stroke: green");
            this.getChildren().add(line);
            
            Line line2 = new Line(getWidth() - 10, 10, 10, getHeight() - 10);
            line2.setStrokeWidth(2);
            line2.startXProperty().bind(this.widthProperty().subtract(10));
            line2.endYProperty().bind(this.heightProperty().subtract(10));
            line2.setStyle("-fx-stroke: green");
            this.getChildren().add(line2);
        }
        else{
            Circle circle = new Circle();
            circle.centerXProperty().bind(this.widthProperty().divide(2));
            circle.centerYProperty().bind(this.heightProperty().divide(2));
            //circle.setRadius(30);
            circle.radiusProperty().bind(this.heightProperty().divide(2).subtract(10));
            circle.radiusProperty().bind(this.widthProperty().divide(2).subtract(10));
            circle.setStyle("-fx-stroke: red; -fx-fill: white");
            this.getChildren().add(circle);
        }
    }
    public void handleMouseClick(){
        if(yourTurn && !cellSelected[row][column] && status != PLAYER1_WON &&
                status != PLAYER2_WON && status != DRAW){
            setToken(token); // set token on a selected cell
            cellSelected[row][column] = true;  // set a cell as selected and not available anymore
            rowSelected = row; // receive row index to send to the server
            columnSelected = column;  // receive column index to send to the server
            wait = false;  // let the player to send coordinates to the server
        }
        else if(yourTurn && cellSelected[row][column] && status != PLAYER1_WON &&
                status != PLAYER2_WON && status != DRAW){
            displayMessage("This cell is already selected!!..");
        }
        if(!yourTurn && status != PLAYER1_WON && status != PLAYER2_WON && status != DRAW)
            displayMessage("It's not your turn to move!!..");
    }
  } // end of inner class Cell
}

