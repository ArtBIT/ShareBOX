IPAddress server(10,0,0,138); // IP adresa sharebox aplikacije

int merenje_id = 19;
float time = 12.0;
float flow = 244.2;
float pressure = 3.50;

String apikey="062d4153-20ea-4478-815f-a890bb5c9ab3";
String data = "time="+String(time)+"&flow="+String(flow)+"&pressure="+String(pressure);

if (client.connect(server, 80)) {
  client.println("POST /merenja/"+merenje_id+"/redovi HTTP/1.1");
  client.println("Host: 10.0.0.138");
  client.println("User-Agent: Arduino/1.0");
  client.println("Connection: close");
  client.println("X-API-KEY: "+apikey;
  client.println("Content-Type: application/x-www-form-urlencoded");
  client.print("Content-Length: ");client.println(data.length());
  client.println();
  client.println(data);
}
