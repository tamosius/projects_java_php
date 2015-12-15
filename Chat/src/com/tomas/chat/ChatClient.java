package com.tomas.chat;

import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.net.InetAddress;
import java.net.Socket;
import java.util.Scanner;

public class ChatClient {
	
	private Socket socket;  // Socket constructor throws a 'java.net.UnknownHostException' 
	                        // if the host cannot be found
	private ObjectOutputStream output;
	private ObjectInputStream input;
	
	Scanner inputText = new Scanner(System.in);
	
	public ChatClient(String host){
		System.out.println("Hello from client.");
		
		
		try{
			
			connectToServer(host);
			getStreams();
			processConnection();
			
		}catch(ClassNotFoundException e){
			
			e.printStackTrace();
			
		}catch(IOException e){
			
			e.printStackTrace();
			
		}finally{
			
			closeConnections();
		}
	}
	
	private void connectToServer(String host) throws IOException{
		
		System.out.println("Attempting to connect...");
		socket = new Socket(InetAddress.getByName(host), 8005);
		System.out.println("Connected to server on: " + socket.getInetAddress());
		
	}
	
	private void getStreams() throws IOException{
		
		output = new ObjectOutputStream(socket.getOutputStream());
		output.flush();
		System.out.println("Getting Streams...");
		input = new ObjectInputStream(socket.getInputStream());
		
		System.out.println("Streams alright.");
	}
	
	private void processConnection() throws IOException, ClassNotFoundException{
		
		String message;
		
		while(true){
			message = (String)input.readObject();
			System.out.println(message);
			
			//String messageOut = inputText.nextLine();
			//output.writeObject(messageOut);
			//output.flush();
		}
		
	}
	
	private void closeConnections(){
		
		try{
			
			socket.close();
			input.close();
			output.close();
			
		}catch(IOException e){
			
			e.printStackTrace();
		}
	}
}
