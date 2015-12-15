package com.tomas.chatDataBase;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;

public class DataBaseConnections {
	
	private final String URL = "jdbc:mysql://localhost/chat";
	private final String USERNAME = "root";
	private final String PASSWORD = "root";
	
	private Connection connection = null;
	private PreparedStatement selectLast20 = null;  // get last 20 messages from database
	private PreparedStatement selectAll = null;
	private PreparedStatement insertMessage = null;  // insert name, message and time message has been written 
	private PreparedStatement deleteMessages = null;  // delete all messages by user's name
	
	public DataBaseConnections(){
		
		try{
			
			// load the JDBC driver (but is not necessary for java 6 and above)
			Class.forName("com.mysql.jdbc.Driver");
			System.out.println("Driver loaded.");
			// establish connection to database
			connection = DriverManager.getConnection(URL, USERNAME, PASSWORD);
			
			// create queries
			selectLast20 = connection.prepareStatement("select firstName, message, date(sentOn), time(sentOn)"
					+ " from testChat order by sentOn desc limit 20");
			insertMessage = connection.prepareStatement("insert into testChat(firstName, message, sentOn) values"
					+ "(?, ?, now())");
			deleteMessages = connection.prepareStatement("delete from testChat where firstName = ?");
			
		}catch(SQLException e){
			e.printStackTrace();
		}catch(ClassNotFoundException e){
			e.printStackTrace();
		}
	}
	
	public JSONArray getMessages(){
		
		ResultSet resultSet = null;
		
		JSONArray messages = new  JSONArray();
		JSONObject oneMessage;
		
		try{
			resultSet = selectLast20.executeQuery();
			
			resultSet.afterLast();  // move the cursor at the end of resultSet
			
			while(resultSet.previous()){  // move the cursor backwards 
				
				oneMessage = new JSONObject(); // create new Object for this data
				
				oneMessage.put("name", resultSet.getString(1)); // get user's name
				
				oneMessage.put("message", resultSet.getString(2)); // get user's message
				
				oneMessage.put("date", resultSet.getString(3));  // get message's date e.g.'2015-10-29'
				
				oneMessage.put("time", resultSet.getString(4));  // get message's time
				
				
				messages.add(oneMessage);  // add this object to JSON array
				
			}
			
		}catch(SQLException e){
			e.printStackTrace();
		}finally{
			try{
				resultSet.close();
			}catch(SQLException e){
				//e.printStackTrace();
			}
		}
		
		return messages;
	}
	
	public void addRecord(String firstName, String message){
		
		try{
			insertMessage.setString(1, firstName);
			insertMessage.setString(2, message);
			
			insertMessage.executeUpdate();
			
		}catch(SQLException e){
			e.printStackTrace();
		}
	}
	public void printMessages(){
		ResultSet resultSet = null;
		try{
			resultSet = selectLast20.executeQuery();
			while(resultSet.next()){
				System.out.println("Name: " + resultSet.getString(1) + "\nMessage: " + resultSet.getString(2) + 
						"\nTime: " + resultSet.getString(3));
			}
		}catch(SQLException e){
			e.printStackTrace();
		}finally{
			try{
				resultSet.close();
			}catch(SQLException e){
				//e.printStackTrace();
			}
		}
	}
	public void deleteMessages(String firstName){
		
		try{
			deleteMessages.setString(1, firstName);
			
			deleteMessages.executeUpdate();
			
		}catch(SQLException e){
			e.printStackTrace();
		}
	}

}
