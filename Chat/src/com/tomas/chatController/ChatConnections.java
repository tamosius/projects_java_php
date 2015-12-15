package com.tomas.chatController;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.json.simple.JSONObject;

/**
 * Servlet implementation class ChatConnections
 */
@WebServlet("/ChatConnections")
public class ChatConnections extends HttpServlet {
	private static final long serialVersionUID = 1L;
	
	private JSONObject connections;
	private ArrayList<String> strings;
	private JSONObject connection;
	
	//private static int counter;
       
    /**
     * @see HttpServlet#HttpServlet()
     */
    public ChatConnections() {
        super();
        // TODO Auto-generated constructor stub
        connections = new JSONObject();
        strings = new ArrayList<String>();
    }

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// TODO Auto-generated method stub
		//response.getWriter().append("Served at: ").append(request.getContextPath());
		
		
		
		
		String ipAddress = request.getRemoteAddr();
		
		//is client behind something?
		// e.g. proxy server
		// e.g. load balancer(in cloud hosting)
		//String ipAddress = request.getHeader("X-FORWARDED-FOR");
		//if(ipAddress == null){
			//ipAddress = request.getRemoteAddr();
		//}
		
		String connectionName = request.getParameter("name");
		if(connectionName != null){
			//connections.put("name", connectionName);
			//connections.put("ip", ipAddress);
			connections.put(connectionName, ipAddress);
		}
		
		response.setContentType("application/json");
		PrintWriter out = response.getWriter();
		
		out.println(connections);
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		String nameToRemove = request.getParameter("nameToRemove");
		
		connections.remove(nameToRemove);
	}

}
