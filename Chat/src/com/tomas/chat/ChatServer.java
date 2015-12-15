package com.tomas.chat;

import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.Date;
import java.util.LinkedList;

public class ChatServer {
	private static String serverStartedTime;
	private String connectionReceivedTime;
	private String connectionHostAddress;
	private static boolean serverStarted = false;
	
	private ServerSocket serverSocket;
	private Socket socket;
	
	private static LinkedList<ObjectOutputStream> clients;
	
	
	public ChatServer(){
		try{
			System.out.println("Hello from Server.");
			startServer();
			
		}catch(IOException e){
			
			System.out.println("Failed to start server.");
			e.printStackTrace(); // if port already in use, 'java.net.BindException' is thrown
		}
	}
	
	private void startServer() throws IOException{
		
		if(!serverStarted){
			System.out.println("Servers just started now.");
			System.out.println(serverStarted);
			serverSocket = new ServerSocket(8019);
			
			serverStartedTime = "Server started at " + new Date();
			System.out.println("Server started at " + new Date());
			clients = new LinkedList<ObjectOutputStream>();
			serverStarted = true;
			
			try{
				
				waitForConnections(); // wait for connections from the clients
				
			}catch(IOException e){
				
				System.out.println("Could not accept connection from client.");
			}
			
		}else{
			
			//try{
				
				System.out.println("Server has been already started.");
				System.out.println(serverStarted);
				//waitForConnections(); // wait for connections from the clients
				
				
			//}catch(IOException e){
				
				//e.printStackTrace();
			//}
		}
	}
	
	private void waitForConnections() throws IOException{
		while(true){
			// 'accept()' method returns reference to a new socket on the server that is connected
			// to the client's socket
			System.out.println("Waiting for connections..");
			socket = serverSocket.accept();
			connectionReceivedTime = "Connection received at " + new Date();
			connectionHostAddress = "Connection received from host: " + socket.getLocalAddress();
			//new Thread(new ConnectionHandler(socket)).start();
			processConnection();  // process the connection on which the client just connected
			System.out.println("Connection received?..");
		}
	}
	
	
	
	private void processConnection(){
		
		new Thread(new ConnectionHandler(socket)).start();
	}
	
	public String getServerStartedTime(){
		return serverStartedTime;
	}
	public String getConnectionReceivedTime(){
		return connectionReceivedTime;
	}
	public String getConnectionHostAddress(){
		return connectionHostAddress;
	}
	
	
	
	
	
/* ------------------------ CONNECTION HANDLER CLASS (inner) ------------------------------------------------------- */
	
		private class ConnectionHandler implements Runnable {
			
			private Socket connection;
			private ObjectOutputStream output;
			private ObjectInputStream input;
			
			
			private ConnectionHandler(Socket connection){
				
				this.connection = connection;
			}
			
			public void run(){
				
				try{
					
					getStreams();
					processThisConnection();
					
				}catch(IOException e){
					
					e.printStackTrace();
				}finally{
					
					closeConnections(); // close all connections
				}
			}
			
			private void getStreams() throws IOException{
				// obtain the Socket's streams and use them to initialize 'ObjectOutputStream' and 'ObjectInputStream'
				output = new ObjectOutputStream(socket.getOutputStream());
				// send stream header to the corresponding client's ObjectInputStream, 
				// so that client Socket can prepare to receive those objects correctly
				output.flush();
				
				clients.add(output);  // add output stream to LinkedList
				System.out.println("Clients no: " + clients.size());
				input = new ObjectInputStream(socket.getInputStream());
			}
			
			private void processThisConnection() throws IOException{
				
				String message = "Welcome to chat server. You can start chat with others on the server.";
				sendData(output, message);
				
				while(true){
					try{
						
						String messageFromClient = (String)input.readObject();
						
						for(ObjectOutputStream out : clients){
							
							sendData(out, "Client says: " + messageFromClient);
						}
						
					}catch(ClassNotFoundException e){
						
						e.printStackTrace();
					}catch(IOException e){
						
						e.printStackTrace();
					}
					
				}
				
			}
			;
			private void sendData(ObjectOutputStream out, String message){
				
				try{
					
					out.writeObject(message);
					out.flush();
					
				}catch(IOException e){
					
					e.printStackTrace();
				}
			}
			
			private void closeConnections(){
				try{
					
					connection.close();
					input.close();
					output.close();
				}catch(IOException e){
					
					System.out.println("Failed to close all connections.");
					e.printStackTrace();
				}
			}
		}
		/* The inner class is just a way to cleanly separate some functionality that really belongs to 
		 * the original outer class. They are intended to be used when you have 2 requirements:
           Some piece of functionality in your outer class would be most clear if it was implemented 
           in a separate class.
           Even though it's in a separate class, the functionality is very closely tied to way that the outer class works. */
}
