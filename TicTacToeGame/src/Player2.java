
import javafx.application.Application;
import static javafx.application.Application.launch;
import javafx.scene.Scene;
import javafx.scene.layout.GridPane;
import javafx.scene.shape.Circle;
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
public class Player2 extends Application{
    private GridPane mainPane = new GridPane();
    
    //private Cell[][] cell = new Cell[3][3];
    @Override
    public void start(Stage primaryStage){
       
        
        Scene scene = new Scene(mainPane, 300, 300);
        primaryStage.setTitle("Player2");
        primaryStage.setScene(scene);
        primaryStage.show();
        
        mainPane.setOnMouseClicked(e -> handleMouseClick());
    }
    public void handleMouseClick(){
        Circle circle = new Circle();
        circle.centerXProperty().bind(mainPane.widthProperty().divide(2));
        circle.centerYProperty().bind(mainPane.heightProperty().divide(2));
        circle.setRadius(100);
        circle.setStyle("-fx-stroke: red; -fx-fill: yellow");
        
        mainPane.getChildren().add(circle);
    }
    public static void main(String[] args){
        launch(args);
    }
}
