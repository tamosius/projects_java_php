/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package publisherReceiver;

import java.net.URL;
import java.util.ResourceBundle;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Alert;
import javafx.scene.control.Alert.AlertType;
import javafx.scene.control.Button;
import javafx.scene.control.TextField;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

import rsa.RSA;
import sender.SenderController;

/**
 * FXML Controller class
 *
 * @author Tomas
 */
public class PublisherReceiverController implements Initializable {

    @FXML
    private TextField valueBitsPrimary;  // TextField to enter how many bits long you want a prime numbers to generate
    @FXML
    private TextField firstPrimary;   // TextField to show first prime number
    @FXML
    private TextField secondPrimary;  // TextField to show second prime number
    @FXML
    private TextField modulusN;  // TextField to show modulus N (public key)
    @FXML
    private TextField phi;  // TextField to show phi (mathematical constant)
    @FXML
    private TextField publicE;  // TextField to show public key e
    @FXML
    private TextField privateKeyD;  // TextField to show private key
    @FXML
    private TextField cipherText;  // TextField to show received ciphertext from sender
    @FXML
    private TextField decryptedCipherText;  // TextField to show decrypted ciphertext

    @FXML
    private Button generatePrime;  // Button to generate prime numbers
    @FXML
    private Button checkPrime;   // Button to check if numbers are prime numbers
    @FXML
    private Button publishKeys;   // Button to check if numbers are prime numbers
    @FXML
    private Button decryptCipherText;   // Button to decrypt ciphertext received from sender
    @FXML
    private Button clearField;   // Button to clear all the fields

    @FXML
    private ImageView primeOrNotPrime1; // image view showing that the numbers are prime or not prime (green or red picture)
    @FXML
    private ImageView primeOrNotPrime2; // image view showing that the numbers are prime or not prime (green or red picture)

    private String bitsValue;  // bits value chosen by the user to generate appropriate prime numbers
    private String firstPrime; // first prime number generated
    private String secondPrime; // second prime number generated
    private String publicKeyN;  // first public key
    private String constantPHI; // mathematical constant phi
    private String publicKeyE;  // second public key
    private String privateD; // private key

    private RSA rsa;  // reference to main RSA algorithm class
    private SenderController sender;  // reference to SenderController class

    @Override
    public void initialize(URL url, ResourceBundle rb) {

        rsa = new RSA(); // create an object for main RSA algorithm class
    }

    // this method is called by 'StartApp' to give a reference to 'SenderController' class
    public void setSenderController(SenderController sender) {

        this.sender = sender;
    }

    // generate prime numbers on 'Generate Prime Numbers' Button click
    @FXML
    private void generatePrimeNumbers() {
        bitsValue = valueBitsPrimary.getText();  // get a bits value entered by the user
        //firstPrime = firstPrimary.getText();
        //secondPrime = secondPrimary.getText();

        if (bitsValue.trim().equals("")) {

            makeAlert("You have not selected a bits value!!", "Please select a bits value to generate prime number.");
            return;  // if the bits value are not entered in the field, break the function and do not proceed further
        }

        rsa.generatePrimes(bitsValue);

        // get first prime number
        firstPrimary.setText(rsa.getFirstPrime());
        // get second prime number
        secondPrimary.setText(rsa.getSecondPrime());
    }

    // check if a numbers are prime on 'Check Prime Numbers' Button click
    @FXML
    private void checkPrimeNumbers() {
        boolean firstPrime = rsa.isPrime(firstPrimary.getText());  // check if the number is prime and assign a boolean value
        boolean secondPrime = rsa.isPrime(secondPrimary.getText());  // check if the number is prime and assign a boolean value

        if (firstPrime && secondPrime) {  // boolean for both numbers is true
            rsa.setFirstPrime(firstPrimary.getText());
            rsa.setSecondPrime(secondPrimary.getText());

            publicKeyN = rsa.getModulus(); // get modulus (public key n)
            constantPHI = rsa.getPHI();  // get phi
            publicKeyE = rsa.getSecondPublicKey(); // get second public key e
            privateD = rsa.getPrivateKey();  // get private key

            modulusN.setText(publicKeyN);
            phi.setText(constantPHI);
            publicE.setText(publicKeyE);
            privateKeyD.setText(privateD);

            firstPrimary.setStyle("-fx-border-color: green;");  // set border color to green if the number is a prime number
            secondPrimary.setStyle("-fx-border-color: green;");  // set border color to green if the number is a prime number

            primeOrNotPrime1.setStyle("-fx-opacity: 1;");
            primeOrNotPrime2.setStyle("-fx-opacity: 1;");

            primeOrNotPrime1.setImage(new Image("images/green_accept.jpg")); // display green tick if the number is prime
            primeOrNotPrime2.setImage(new Image("images/green_accept.jpg"));
            
        } else if (!firstPrime && secondPrime) { // first number is not a prime number
            firstPrimary.setStyle("-fx-border-color: red;");  // set border color to red if the number is not a prime number
            secondPrimary.setStyle("-fx-border-color: green;"); // set border color to green if the number is a prime number

            primeOrNotPrime1.setStyle("-fx-opacity: 1;");
            primeOrNotPrime2.setStyle("-fx-opacity: 1;");

            primeOrNotPrime1.setImage(new Image("images/error.jpg"));  // display red sign if the number is not a prime
            primeOrNotPrime2.setImage(new Image("images/green_accept.jpg"));
            
            // call 'makeAlert' function with appropriate message
            makeAlert("Error!!", "The first number is not a prime number, please generate or select prime number!");
            
        } else if (firstPrime && !secondPrime) { // second number is not a prime number
            firstPrimary.setStyle("-fx-border-color: green;");  // set border color to green if the number is a prime number
            secondPrimary.setStyle("-fx-border-color: red;");   // set border color to red if the number is not a prime number

            primeOrNotPrime1.setStyle("-fx-opacity: 1;");
            primeOrNotPrime2.setStyle("-fx-opacity: 1;");

            primeOrNotPrime1.setImage(new Image("images/green_accept.jpg"));
            primeOrNotPrime2.setImage(new Image("images/error.jpg"));
            
            // call 'makeAlert' function with appropriate message
            makeAlert("Error!!", "The second number is not a prime number, please generate or select prime number!");
            
        } else { // both numbers are not a prime numbers
            firstPrimary.setStyle("-fx-border-color: red;");  // set border color to red if the number is not a prime number
            secondPrimary.setStyle("-fx-border-color: red;"); // set border color to red if the number is not a prime number

            primeOrNotPrime1.setStyle("-fx-opacity: 1;");
            primeOrNotPrime2.setStyle("-fx-opacity: 1;");

            primeOrNotPrime1.setImage(new Image("images/error.jpg"));
            primeOrNotPrime2.setImage(new Image("images/error.jpg"));
            
            // call 'makeAlert' function with appropriate message
            makeAlert("Error!!", "The both numbers are not a prime numbers, please generate or select prime numbers!");
        }
    }

    // method to publish the public keys to the public (send to 'SenderController')
    @FXML
    private void publishKeys() {

        sender.displayKeyN(this.publicKeyN);

        sender.displayKeyE(this.publicKeyE);
    }

    // display ciphertext received from sender
    public void displayCipherText(String cipherTextMessage) {
        cipherText.setText(cipherTextMessage);
    }

    // call the 'decryptMessage' method in RSA class and decrypt cipher text message
    @FXML
    private void decryptCipher() {
        String cipherTextMessage = cipherText.getText();

        String encryptedMessage = rsa.decryptMessage(cipherTextMessage, publicKeyN, privateD);

        decryptedCipherText.setText(encryptedMessage);
    }

    // method to clear all fields
    @FXML
    private void clearFields() {

        valueBitsPrimary.setText("");
        firstPrimary.setText("");
        secondPrimary.setText("");
        modulusN.setText("");
        phi.setText("");
        publicE.setText("");
        privateKeyD.setText("");
        cipherText.setText("");
        decryptedCipherText.setText("");

        firstPrimary.setStyle("-fx-border-color: transparent;");
        secondPrimary.setStyle("-fx-border-color: transparent;");

        primeOrNotPrime1.setStyle("-fx-opacity: 0;");
        primeOrNotPrime2.setStyle("-fx-opacity: 0;");
    }

    // pop-up alert window on some inappropriate actions
    public void makeAlert(String warning, String message) {
        Alert alert = new Alert(AlertType.WARNING);

        alert.setTitle("Information");
        alert.setHeaderText(warning);
        alert.setContentText(message);

        alert.showAndWait();
    }
}
