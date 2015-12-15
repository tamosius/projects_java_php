/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package rsa;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.geometry.Rectangle2D;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Screen;
import javafx.stage.Stage;
import publisherReceiver.PublisherReceiverController;
import sender.SenderController;

/**
 *
 * @author Tomas
 */
public class StartApp extends Application {

    @Override
    public void start(Stage publisherStage) throws Exception {

        Stage senderStage = new Stage();
        Rectangle2D primaryScreenBounds = Screen.getPrimary().getVisualBounds();

        try {

            // display gui who encrypts the message and sends to the receiver
            /*Parent sender = FXMLLoader.load(getClass().getResource("../sender/Sender.fxml"));
            Scene senderScene = new Scene(sender);
            senderStage.setScene(senderScene);

            senderStage.setX(primaryScreenBounds.getMinX() + primaryScreenBounds.getWidth() - 650);
            senderStage.setY(primaryScreenBounds.getMinY() + primaryScreenBounds.getHeight() - 550);

            senderStage.show();*/

            //FXMLLoader senderLoader = new FXMLLoader(getClass().getResource("../sender/Sender.fxml"));
            
            FXMLLoader senderLoader = new FXMLLoader();
            senderLoader.setLocation(StartApp.class.getResource("/sender/Sender.fxml"));
            
            Parent senderRoot = (Parent)senderLoader.load();
            SenderController senderController = (SenderController) senderLoader.getController();

            Scene senderScene = new Scene(senderRoot);
            senderStage.setScene(senderScene);
            
            senderStage.setX(primaryScreenBounds.getMinX() + primaryScreenBounds.getWidth() - 650); // higher number moves stage to the left
            senderStage.setY(primaryScreenBounds.getMinY() + primaryScreenBounds.getHeight() - 550); // higher number moves stage up
            
            senderStage.show();
            
            

            
            
            // display gui for the publisher who publishes the keys and receives the encrypted message
            FXMLLoader publisherLoader = new FXMLLoader(getClass().getResource("/publisherReceiver/PublisherReceiver.fxml"));
            Parent publisherRoot = publisherLoader.load();
            PublisherReceiverController publisherController = (PublisherReceiverController) publisherLoader.getController();

            Scene publisherScene = new Scene(publisherRoot);
            publisherStage.setScene(publisherScene);
            
            publisherStage.setX(primaryScreenBounds.getMinX() + primaryScreenBounds.getWidth() - 1350); // higher number moves stage to the left
            publisherStage.setY(primaryScreenBounds.getMinY() + primaryScreenBounds.getHeight() - 650); // higher number moves stage up
            
            publisherStage.show();
            
            // give the 'PublisherReceiverController' access to 'SenderController'
            publisherController.setSenderController(senderController);
            // give the 'SenderController' access to 'PublisherReceiverController'
            senderController.setPublisherController(publisherController);

        } catch (Exception e) {

            e.printStackTrace();

        }
    }

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        launch(args);
    }

}
