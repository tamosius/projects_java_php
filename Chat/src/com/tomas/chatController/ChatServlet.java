package com.tomas.chatController;

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;

import com.tomas.chatDataBase.DataBaseConnections;

/**
 * Servlet implementation class ChatServlet
 */
@WebServlet("/ChatServlet")
public class ChatServlet extends HttpServlet {
	private static final long serialVersionUID = 1L;
	
	private DataBaseConnections dataBaseConnection;
	
	private JSONObject json;
	
	
	
	//private ChatServer server;
    /**
     * @see HttpServlet#HttpServlet()
     */
    public ChatServlet() {
        super();
        // TODO Auto-generated constructor stub
       //messagesArray = new JSONArray();
        dataBaseConnection = new DataBaseConnections();
    }
    
    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
    	json = new JSONObject();
    	
    	response.setContentType("application/json");
    	PrintWriter out = response.getWriter();
    	System.out.println("From doGet method");
    	json.put("json", dataBaseConnection.getMessages());
    	
    	out.println(json);
    }

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		System.out.println("From doPost method.");
		String firstName = request.getParameter("name");
		String message = request.getParameter("message");
		String delete = request.getParameter("delete"); // delete all user's messages by his name
		
		if(delete.trim() == ""){ // do not delete any messages
			
			dataBaseConnection.addRecord(firstName, message);
			
		}
		else{
			
			dataBaseConnection.deleteMessages(firstName);
			
		}
		
	}

}
