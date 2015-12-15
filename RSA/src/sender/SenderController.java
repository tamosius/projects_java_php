/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package sender;

import java.net.URL;
import java.util.ResourceBundle;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.TextField;
import publisherReceiver.PublisherReceiverController;
import rsa.RSA;

/**
 * FXML Controller class
 *
 * @author Tomas
 */
public class SenderController implements Initializable {

    @FXML
    private TextField publicKeyN;  // TextField for the first public key received
    @FXML
    private TextField publicKeyE;  // TextField for the second public key received
    @FXML
    private TextField plainTextMessage;  // TextField to write plain text message and get ready for encryption
    @FXML
    private TextField cipherTextMessage;  // TextField for encrypted message (ciphertext)
    
    @FXML
    private Button encryptMessage;  // Button to encrypt the plain message
    @FXML
    private Button sendCipherText;  // Button to send encrypted message (ciphertext) to receiver
    @FXML
    private Button clearMessages;  // Button to clear plain message and ciphertext message fields
    
    private RSA rsa;  // reference to main RSA algorithm class
    private PublisherReceiverController receiver;  // reference to cipher message receiver class
    
    // no-args constructor
    public SenderController(){
        
    }
    
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        
        rsa = new RSA();  // create an object for main RSA algorithm class
    } 
    
    // this method is called by 'StartApp' to give a reference to 'PublisherReceiverController' class
    public void setPublisherController(PublisherReceiverController receiver){
        
        this.receiver = receiver;
    }
    
    
    // method to display public key (n) received from publisher
    public void displayKeyN(String keyN){
        
        publicKeyN.setText(keyN);
    }
    
    // method to display public key (e) received from publisher
    public void displayKeyE(String keyE){
        
        publicKeyE.setText(keyE);
    }
    
    // method to call the method in RSA class and encrypt plain text message
    @FXML
    private void encryptPlainMessage(){
        String plainMessage = plainTextMessage.getText();  // get the plain text message entered by user
        String publicN = publicKeyN.getText();  // first public key to be used for ecnrypting message
        String publicE = publicKeyE.getText(); // second public key to be used to encrypt message
        
        cipherTextMessage.setText(rsa.encryptMessage(plainMessage, publicN, publicE)); // encrypt the message in RSA class
    }
    
    // method to send encrypted message to receiver
    @FXML
    private void sendCipherText(){
        String cipherText = cipherTextMessage.getText();  // assign ciphertext to variable
        
        receiver.displayCipherText(cipherText);
    }
    
    // method to clear plain text and cipher text messages
    @FXML
    private void clearMessages(){
        
        plainTextMessage.setText("");
        cipherTextMessage.setText("");
    }
}
