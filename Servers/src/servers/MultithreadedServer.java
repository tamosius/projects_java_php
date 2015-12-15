/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

package servers;

import java.io.DataInputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.ArrayList;
import java.util.Date;
import javafx.application.Application;
import javafx.application.Platform;
import javafx.geometry.Insets;
import javafx.scene.Scene;
import javafx.scene.control.Label;
import javafx.scene.control.TextArea;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Pane;
import javafx.stage.Stage;

/**
 *
 * @author tamosius5
 */
public class MultithreadedServer extends Application{
    private TextArea chatTextArea, connectionsTextArea;
    private Label numberOfConnectionsLabel;
    
    private DataInputStream clientName;
    
    private ServerSocket serverSocket;
    private Socket connection;
    //private ObjectOutputStream outputToClient;
    //private ObjectInputStream inputFromClient;
    
    // ArrayList to store output streams
    private ArrayList<ObjectOutputStream>clientsList = new ArrayList<>();
    
    @Override
    public void start(Stage primaryStage){
        Pane pane = new HBox(5);
        pane.setPadding(new Insets(5, 5, 5, 5));
        
        chatTextArea = new TextArea();
        connectionsTextArea = new TextArea();
        chatTextArea.setEditable(false);
        connectionsTextArea.setEditable(false);
        //connectionsTextArea.setFitHeight(100);
        //connectionsTextArea.setFitWidth(100);
        pane.getChildren().addAll(chatTextArea, connectionsTextArea);
        
        Scene scene = new Scene(pane, 700, 350);
        primaryStage.setTitle("Chat Server");
        primaryStage.setScene(scene);
        primaryStage.show();
        
        new Thread(()->{
            try{
                serverSocket = new ServerSocket(8001);  // create server
                displayMessage("Server started at " + new Date());
                while(true){
                    displayMessage("Waiting for connections...");
                    connection = serverSocket.accept();  // wait for connections / connect new client
                    displayMessage(connection.getInetAddress().getHostName() + " is connected.");
                    new Thread(new HandleConnection(connection)).start();
                    //host.add(connection);  // add new connection to ArrayList
                }
            }
            catch(IOException e){
                
            }
        }).start();
    }
    // private inner class to handle connection
    private class HandleConnection implements Runnable{
        private ObjectOutputStream outputToClient;
        private ObjectInputStream inputFromClient;
        private final Socket clientConnection;
        
        HandleConnection(Socket clientConnection){
                this.clientConnection = clientConnection;
        }
        @Override
        public void run(){
            try{
                getStreams();
                processConnection();
            }
            catch(ClassNotFoundException ex){
                
            }
            catch(IOException e){
                displayMessage(e.toString());
            }
            finally{
                closeConnection();
                //hostOutput.remove(outputToClient);
            }
        }
        private void getStreams()throws IOException{
            outputToClient = new ObjectOutputStream(clientConnection.getOutputStream());
            clientsList.add(outputToClient);  // add output stream to array list
            displayMessage("Currently connected : " + clientsList.size());
            outputToClient.flush(); // flush buffer to send header information
            inputFromClient = new ObjectInputStream(clientConnection.getInputStream());
        }
        private void processConnection()throws IOException, ClassNotFoundException{
            String name;  // client name
            String message = "Succesfully connected to a server.\nPlease enter your name in the top field to start chat.";
            sendData(outputToClient, message);  // send message to client that succesfully connected
            name = (String)inputFromClient.readObject();
            for(ObjectOutputStream out : clientsList){
                if(out != outputToClient)
                    sendData(out, "\n" + name + " just connected to the chat server\n");
                else
                    sendData(outputToClient, "\nWelcome to chat server, " + name + "!\n"); // send message to the client just connected
            } 
            do{
                message = (String)inputFromClient.readObject();
                displayChatMessage(name + " : " + message);
                
                if(!message.equals("EXIT")){
                    for(ObjectOutputStream out : clientsList){     // write object to all clients
                        sendData(out, name + " : " + message);     // currently connected
                    }
                }
                else{
                    sendData(outputToClient, name + ", you have closed the connection to server. Bye!");
                    clientsList.remove(outputToClient);
                    for(ObjectOutputStream out : clientsList){
                        sendData(out, "\n" + name + " has left the chat server.\n");
                    }
                }
            }while(!message.equals("EXIT"));
        }
        private void closeConnection(){
            try{
                clientConnection.close();
                outputToClient.close();
                inputFromClient.close();
                clientsList.remove(outputToClient);
                displayMessage(clientConnection.getInetAddress() + " terminated connection." +
                        "\nCurrently connected : " + clientsList.size());
            }
            catch(IOException e){
                displayMessage(e.toString());
            }
        }
        private void sendData(ObjectOutputStream out, String message){
            try{
                out.writeObject(message);
                out.flush();
            }
            catch(IOException e){
                
            }
        }
    } // end of inner class HandleConnection
    
    
    private void displayMessage(String message){
        Platform.runLater(()->{
            connectionsTextArea.appendText(message + "\n");
        });
    }
    private void displayChatMessage(String message){
        Platform.runLater(()->{
            chatTextArea.appendText(message + "\n");
        });
    }
    
    //main method
    public static void main(String[] args){
        launch(args);
    }
}
