/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package servers;

import java.io.EOFException;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.Date;
import javafx.application.Application;
import javafx.application.Platform;
import javafx.geometry.Insets;
import javafx.scene.Scene;
import javafx.scene.control.Label;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;
import javafx.scene.layout.BorderPane;
import javafx.stage.Stage;

/**
 *
 * @author tamosius5
 */
public class Servers extends Application {
    private ObjectOutputStream outputToClient;
    private ObjectInputStream inputFromClient;
    private ServerSocket serverSocket;
    private Socket connection;
    private TextArea textArea;
    private TextField text;
    
    @Override
    public void start(Stage primaryStage){
        BorderPane paneForText = new BorderPane();
        paneForText.setPadding(new Insets(5, 5, 5, 5));
        paneForText.setStyle("-fx-border-color: red");
        paneForText.setLeft(new Label("Enter text: "));
        
        text = new TextField();
        paneForText.setCenter(text);
        
        BorderPane mainPane = new BorderPane();
        textArea = new TextArea();
        textArea.setEditable(false);
        mainPane.setCenter(textArea);
        mainPane.setBottom(paneForText);
        
        Scene scene = new Scene(mainPane, 450, 400);
        primaryStage.setTitle("Server (single thread)");
        primaryStage.setScene(scene);
        primaryStage.show();
        
        new Thread(()-> {
            try{
                serverSocket = new ServerSocket(8000); // create server socket
                displayMessage("Server started at " + new Date());
                
                while(true){
                    try{
                        waitForConnection();
                        getStreams();
                        processConnection();
                    }
                    catch(EOFException e){
                        displayMessage("\nServer terminated connection");
                    }
                    finally{
                        closeConnection();
                    }
                }
            }
            catch(IOException e){
                displayMessage(e.toString());
            }
        }).start();  // start thread
    }
    public void waitForConnection()throws IOException{
        displayMessage("Waiting for connection...");
        connection = serverSocket.accept();  // allow server to accept connection
        displayMessage("Connection received from host " + connection.getInetAddress().
                getHostName());
    }
    public void getStreams()throws IOException{
        outputToClient = new ObjectOutputStream(connection.getOutputStream());
        outputToClient.flush();  // flush output buffer to send header information
        
        inputFromClient = new ObjectInputStream(connection.getInputStream());
        displayMessage("\nGot I/O Streams\n");
    }
    public void processConnection(){
        String message = "Connection succesfull";
        sendData(message);
        
        do{
            try{
                message = (String)inputFromClient.readObject();
                displayMessage(message);
                text.setOnAction(e->{
                    sendData(text.getText());
                });
            }
            catch(ClassNotFoundException e){
                displayMessage("Unknown Object type received.");
            }
            catch(IOException e){
                displayMessage(e.toString());
            }
        }while(!message.equals("Client >>> TERMINATE"));
    }
    public void sendData(String message){
        try{
            outputToClient.writeObject("Server >>> " + message);
            outputToClient.flush();  // flush output to client
            displayMessage("Server >>> " + message);
        }
        catch(IOException e){
            displayMessage(e.toString());
        }
    }
    public void displayMessage(String message){
        Platform.runLater(()->{
            textArea.appendText(message + "\n");
        });
    }
    private void closeConnection(){
        try{
            outputToClient.close();
            inputFromClient.close();
            connection.close();
        }
        catch(IOException e){
            displayMessage(e.toString());
        }
    }

    /**
     * The main() method is ignored in correctly deployed JavaFX application.
     * main() serves only as fallback in case the application can not be
     * launched through deployment artifacts, e.g., in IDEs with limited FX
     * support. NetBeans ignores main().
     *
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        launch(args);
    }
    
}
