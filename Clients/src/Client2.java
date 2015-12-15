
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
public class Client2 extends Application{
    private TextField chatTextField;
    private TextField textFieldForName;
    private TextArea chatTextArea;
    
    private Socket connectionToServer;
    private ObjectOutputStream outputToServer;
    private ObjectInputStream inputFromServer;
    
    
    @Override
    public void start(Stage primaryStage){
        BorderPane paneForText = new BorderPane();
        BorderPane paneForName = new BorderPane();
        BorderPane mainPane = new BorderPane();
        
        chatTextArea = new TextArea();
        chatTextArea.setPadding(new Insets(5, 5, 5, 5));
        chatTextArea.setStyle("-fx-border-color: green");
        
        chatTextField = new TextField();
        textFieldForName = new TextField();
        chatTextField.setStyle("-fx-border-color: blue");
        paneForName.setStyle("-fx-border-color: blue");
        paneForName.setLeft(new Label("Name : "));
        paneForName.setCenter(textFieldForName);
        paneForText.setCenter(chatTextField);
        paneForText.setLeft(new Label("Enter text: "));
        mainPane.setTop(paneForName);
        mainPane.setBottom(paneForText);
        mainPane.setCenter(chatTextArea);
        
        chatTextArea.setEditable(false);
        chatTextField.setEditable(false);
        
        Scene scene = new Scene(mainPane, 400, 350);
        primaryStage.setScene(scene);
        primaryStage.show();
        
        new Thread(()->{
            try{
                connectToServer();
                getStreams();
                processConnection();
            }
            catch(ClassNotFoundException ex){
                displayMessage("Unable to read Object.");
            }
            catch(IOException e){
                displayMessage("End of chat session.");
            }
            finally{
                closeConnection();
            }
        }).start();
    }
    private void connectToServer()throws IOException{
        connectionToServer = new Socket(InetAddress.getLocalHost(), 8001);
    }
    private void getStreams()throws IOException{
        outputToServer = new ObjectOutputStream(connectionToServer.getOutputStream());
        outputToServer.flush();  // flush output buffer to send header information
        inputFromServer = new ObjectInputStream(connectionToServer.getInputStream());
    }
    private void processConnection()throws ClassNotFoundException, IOException{
        String message = "";
        message = (String)inputFromServer.readObject();
        displayMessage(message);
        textFieldForName.setOnAction((e)->{
            sendData(textFieldForName.getText());
            chatTextField.setEditable(true);
            textFieldForName.setEditable(false);
        });
        do{
            message = (String)inputFromServer.readObject(); // read object from server
            displayMessage(message);
            
            chatTextField.setOnAction(e ->{
                sendData(chatTextField.getText());
            });
        }while(!message.equals("EXIT"));
    }
    private void closeConnection(){
        try{
            outputToServer.close();
            inputFromServer.close();
            connectionToServer.close();
            //displayMessage("You succesfully closed the connection to server.");
        }
        catch(IOException e){
            displayMessage("Error! Failed to close connection.");
        }
    }
    private void sendData(String message){
        try{
            outputToServer.writeObject(message);
            outputToServer.flush();
            chatTextField.setText("");
        }
        catch(IOException e){
            displayMessage("Error! Failed to write an message.");
        }
    }
    private void displayMessage(String message){
        Platform.runLater(()->{
            chatTextArea.appendText( message + "\n");
        });
    }
    
    
    // main method
    public static void main(String[] args){
        launch(args);
    }
}
