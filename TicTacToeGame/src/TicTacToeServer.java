
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.Date;
import javafx.application.Application;
import javafx.application.Platform;
import javafx.scene.Scene;
import javafx.scene.control.Label;
import javafx.scene.control.TextArea;
import javafx.scene.layout.BorderPane;
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
public class TicTacToeServer extends Application{
    private int games = 0;  // number of game started on the server
    private int gamesCurrentlyRunning = 0; // games currently running on the server
    
    private TextArea textArea = new TextArea(); // text area to display the messages on server
    private Label gamesCount = new Label();
    private BorderPane borderPane = new BorderPane(); 
    
    private ServerSocket serverSocket;  // create server
    private Socket player1;  // socket for player1
    private Socket player2;  // socket for player2
    
    
    
    @Override
    public void start(Stage primaryStage){
        textArea.setEditable(false);
        borderPane.setCenter(textArea);
        borderPane.setBottom(gamesCount);
        
        gamesCount.setText("Games currently running : " + games);
        
        Scene scene = new Scene(borderPane, 300, 300);
        primaryStage.setTitle("TicTacToe Server");
        primaryStage.setScene(scene);
        primaryStage.show();
        
        new Thread(()->{
            try{
                serverSocket = new ServerSocket(8002);  // create server
                displayMessage("Server started at " + new Date());
                
                while(true){
                    displayMessage("Waiting for connections...");
                    connectPlayer1();  // connect player1
                    connectPlayer2();  // connect player2
                    new Thread(new HandleGameSession(player1, player2)).start(); // start new thread for new game session
                }
            }
            catch(IOException e){
                displayMessage("Failed to establish a game");
            }
        }).start();
    }
    private void connectPlayer1()throws IOException{
        player1 = serverSocket.accept(); // wait for connection / connect player1
        
        new DataOutputStream(player1.getOutputStream()).writeInt(1);  // notify player that he is player 1
        games++;
        displayMessage("Player1 is connected for game " + games + 
                "\nPlayer1 IP address is " + player1.getInetAddress().getHostAddress() +
                "\nWaiting for player2 to connect..");
    }
    private void connectPlayer2()throws IOException{
        player2 = serverSocket.accept();  // wait for connection / connect player2
        
        new DataOutputStream(player2.getOutputStream()).writeInt(2);  // notify player that he is player 2
        new DataOutputStream(player1.getOutputStream()).writeInt(1);  // notify player1 that player2 is connected
        gamesCurrentlyRunning++;
        displayMessage("Player2 is connected for game " + games + 
                "\nPlayer2 IP addess is " + player2.getInetAddress().getHostAddress() +
                "\nGame " + games + " has started..");
        Platform.runLater(()->{
            gamesCount.setText("Games currently running : " + gamesCurrentlyRunning);
        });
    }
    private void displayMessage(String message){
        Platform.runLater(() ->{
            textArea.appendText(message + "\n");
        });
    }
    // private inner class to handle game session
    private class HandleGameSession implements Runnable, TicTacToeConstants{
        private Socket player1;
        private Socket player2;
        
        private DataInputStream inputFromPlayer1;  // get input from player1 move
        private DataOutputStream outputToPlayer1;  // output to player1 players2 move
        private DataInputStream inputFromPlayer2;  // get input from player2 move
        private DataOutputStream outputToPlayer2;  // output to player2 players1 move
        
        private char cell[][] = new char[3][3];
        
        private boolean continueToPlay = true; //
        
        public HandleGameSession(Socket player1, Socket player2){
            this.player1 = player1;
            this.player2 = player2;
            // initialize cell array
            for(int row = 0; row < cell.length; row++)
                for(int column = 0; column < cell[row].length; column++)
                    cell[row][column] = ' ';
        }
        @Override
        public void run(){
            try{
                inputFromPlayer1 = new DataInputStream(player1.getInputStream());  // create streams
                outputToPlayer1 = new DataOutputStream(player1.getOutputStream());
                inputFromPlayer2 = new DataInputStream(player2.getInputStream());
                outputToPlayer2 = new DataOutputStream(player2.getOutputStream());
                
                runGame();  // start the game
            }
            catch(IOException e){
                //displayMessage("Unable to create Streams..");
            }
        }
        private void runGame(){
            try{
            while(true){
                    // receive a move from player1
                    int row = inputFromPlayer1.readInt();
                    int column = inputFromPlayer1.readInt();
                    System.out.println("player1: " + row + " " + column);
                    cell[row][column] = 'X';
                    
                    if(isWon('X')){
                        outputToPlayer1.writeInt(PLAYER1_WON); // notify player1 that he won the game
                        outputToPlayer2.writeInt(PLAYER1_WON); // notify player2 that he has lost the game
                        sendMove(outputToPlayer2, row, column); // send players1 move to player2
                        break;
                    }
                    if(isFull()){
                        outputToPlayer1.writeInt(DRAW);  // notify both players that game is draw
                        outputToPlayer2.writeInt(DRAW);
                        sendMove(outputToPlayer2, row, column);
                        break; 
                    }
                    else{
                        //outputToPlayer1.writeInt(CONTINUE); // notify both players to continue the game
                        outputToPlayer2.writeInt(CONTINUE);
                        sendMove(outputToPlayer2, row, column); // send players1 move to player2
                    }
                    System.out.println("Row: " + row + " column: " + column);
                    // receive a move from player2
                    row = inputFromPlayer2.readInt();
                    column = inputFromPlayer2.readInt();
                    System.out.println("Player2: " + row + " " + column);
                    cell[row][column] = 'O';
                    
                    if(isWon('O')){
                        outputToPlayer1.writeInt(PLAYER2_WON);
                        outputToPlayer2.writeInt(PLAYER2_WON);
                        sendMove(outputToPlayer1, row, column);
                        break;
                    }
                    if(isFull()){
                        outputToPlayer1.writeInt(DRAW);
                        outputToPlayer2.writeInt(DRAW);
                        sendMove(outputToPlayer1, row, column);
                        break;
                    }
                    else{
                        //outputToPlayer2.writeInt(CONTINUE);
                        outputToPlayer1.writeInt(CONTINUE);
                        sendMove(outputToPlayer1, row, column);
                    }
                }
                
            }
            catch(IOException e){
                   displayMessage("Unable to read/write from/to players...");
            }
        }
        // method to send move to players
        private void sendMove(DataOutputStream out, int row, int column)throws IOException{
            out.writeInt(row);
            
            out.writeInt(column);
            
        }
        // check if someone won the game
        private boolean isWon(char token){
            // check all rows
            for(int i = 0; i < 3; i++){
                if((cell[i][0] == token) && (cell[i][1] == token) && (cell[i][2] == token))
                    return true;
            }
            // check all columns
            for(int i = 0; i < 3; i++){
                if((cell[0][i] == token) && (cell[1][i] == token) && (cell[2][i] == token))
                    return true;
            }
            // check major diagonal
            if((cell[0][0] == token) && (cell[1][1] == token) && (cell[2][2] == token))
                return true;
            // check subdiagonal
            if((cell[2][0] == token) && (cell[1][1] == token) && (cell[0][2] == token))
                return true;
            
            return false; // the game is not won yet
        }
        private boolean isFull(){
            for(int row = 0; row < cell.length; row++)
                for(int column = 0; column < cell[row].length; column++)
                    if(cell[row][column] == ' ')
                        return false;
            return true; // return true if table is full and notify draw
        }
    } // end of HandleGameSession inner class
    public static void main(String[] args){
        Application.launch(args);
    }
}
