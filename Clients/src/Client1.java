
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.net.InetAddress;
import java.net.Socket;
import javafx.application.Application;
import javafx.application.Platform;
import javafx.geometry.Insets;
import javafx.scene.Scene;
import javafx.scene.control.Label;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;
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
public class Client1 extends Application{
    private TextArea textArea;
    private TextField textField;
    private Socket connection;
    private ObjectOutputStream outputToServer;
    private ObjectInputStream inputFromServer;
    
    @Override
    public void start(Stage primaryStage){
        BorderPane paneForText = new BorderPane();
        textField = new TextField();
        paneForText.setPadding(new Insets(5, 5, 5, 5));
        paneForText.setStyle("-fx-border-color: green");
        paneForText.setLeft(new Label("Enter text: "));
        paneForText.setCenter(textField);
        
        BorderPane mainPane = new BorderPane();
        textArea = new TextArea();
        mainPane.setBottom(paneForText);
        mainPane.setCenter(textArea);
        
        Scene scene = new Scene(mainPane, 450, 400);
        primaryStage.setTitle("Client1");
        primaryStage.setScene(scene);
        primaryStage.show();
        
        // start thread
        new Thread(()->{
            try{
               connectToServer();
               getStreams();
               processConnection();
            }
            catch(IOException e){
                displayMessage(e.toString());
            }
            finally{
                closeConnection();
            }
        }).start();
    }
    private void connectToServer() throws IOException{
        displayMessage("Attempting to connect...");
        connection = new Socket(InetAddress.getByName("localhost"), 8001); // connects as well
        //connection = new Socket(InetAddress.getByName("tomasUbuntu"), 8001);
        displayMessage("Connected to " + connection.getInetAddress().getHostName());
    }
    private void getStreams()throws IOException{
        outputToServer = new ObjectOutputStream(connection.getOutputStream());
        outputToServer.flush();  // flush output buffer to send header information
        inputFromServer = new ObjectInputStream(connection.getInputStream());
        displayMessage("Got I/O Streams");
    }
    private void processConnection() throws IOException{
        String message = "";
        do{
            textField.setOnAction(e ->{
                sendData(textField.getText());
                textField.setText("");
            });
            try{
                message = (String)inputFromServer.readObject();
                displayMessage(message);
            }
            catch(ClassNotFoundException e){
                displayMessage("Unknown Object type received.");
            }
        }while(!message.equals("EXIT"));
    }
    private void sendData(String message){
       try{
           outputToServer.writeObject(message);
           outputToServer.flush();  // flush data to output
       }
       catch(IOException e){
           displayMessage(e.toString());
       }
    }
    private void displayMessage(String message){
        Platform.runLater(()->{
            textArea.appendText(message + "\n");
        });
    }
    private void closeConnection(){
        try{
            outputToServer.close();
            inputFromServer.close();
            connection.close();
        }
        catch(IOException e){
            displayMessage(e.toString());
        }
    }
    
    // main method
    public static void main(String[] args){
        launch(args);
    }
}
