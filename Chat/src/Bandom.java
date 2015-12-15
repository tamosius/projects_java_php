import java.util.HashMap;
import java.util.HashSet;
import java.util.Random;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;

public class Bandom {

	public static void main(String[] args) {
		// TODO Auto-generated method stub
		//DataBaseConnections c = new DataBaseConnections();
		
		JSONObject j = new JSONObject();
		JSONArray a = new JSONArray();
		JSONObject o = new JSONObject();
		//ArrayList<String> names = new ArrayList<String>();
		HashMap <String, String> users = new HashMap<String, String>();
		HashSet <String> h = new HashSet <String>();
		
		
		//a = c.getMessages();
		//names.add("Tomas");
		//names.add("Marius");
		
		//users.put("Tomas", "234");
		
		
		//users.clear();
		
		
		
		j.put("Tomsa", "111");
		j.put("Marius", "2222");
		j.put("Paulius", "3333");
		
		j.remove("");
		
		System.out.println("json object: " + j);
		
		
		String string = "java is    very          cool.";
		
		String builder = string.replaceAll("\\s", "");
		
		System.out.println(builder);
	
	    
		//c.printMessages();
		Random r = new Random();
		System.out.println("Random number: " + r.nextInt(100));

	}

}
