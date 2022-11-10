#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include "SparkFun_SCD30_Arduino_Library.h"

// Wifi credentials
const char *ssid = "IOTHOTSPOT";
const char *password = "IoTLab2223";

// Domain Name with full URL Path for HTTP POST Request
// dit moet nog opgelost worden, hier moet ip adres van de server komen
const char *server = "http://192.168.137.1:8000/webdev/klasopdracht/write.php";

// Sensor API key
const String my_Api_key = "D1682E7E-D27F-AE08-0882-0F4721D4FD77";

// period timer
unsigned long last_time = 0;
unsigned long timer_delay = 60*1000; // 1 minutes measuring interval

// sensor measurements
struct scd30Readings
{
    float   CO2Concentration;
    float   Temperatuur;
    float   Luchtvochtigheid; 
};

struct scd30Readings sensordata;

// Sensor communication
SCD30 airSensor;

void setup()
{
  // Setup serial monitor communication
  Serial.begin(115200);
  Serial.println("SCD30 IoTdevice 0.02");
  // Setup and make wifi connection
  WiFi.begin(ssid, password);
  Serial.print("Connecting to WIFI…");
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }
  Serial.println(" ");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());
  // Setup SCD30 sensor communication
  Wire.begin(21,22, 1000000);
  if (airSensor.begin() == false)
  {
    Serial.println("Air sensor not detected. Please check wiring. Freezing...");
    while (1);
  }  
  // Steps to put SCD30 sensor in low power mode
  // 1. Set measuring interval to 30 sec
  Serial.println("Placing sensor in 30 sec measuring interval");
  airSensor.setMeasurementInterval(30);  
  // check if sensordata is ready for first reading
  Serial.print("Waiting for sensor ready for readings...");
  while(!airSensor.dataAvailable())
  {
    delay(500);
    Serial.print(".");    
  }  
  Serial.println(" ");
  // 2.1 Set altitude
  Serial.println("What altitude (meter above sealevel) will your device be installed?");
  input = "";  
  while (Serial.available() == 0) {
     //Wait for user input
  }  
  input = Serial.readString();   
  input.trim();
  Serial.print("Setting altitude to ");
  Serial.print(input.toInt());
  Serial.println("m");
  airSensor.setAltitudeCompensation(input.toInt());  
  // 2.2 Force calibrate co2 with outside air to 408ppm
  Serial.println("Hold the sensor in outside air and confirm with 'yes'");
  String input = "";
  while (input != "yes"){
    while (Serial.available() == 0) {
       //Wait for user input
    }  
    input = Serial.readString(); 
    input.trim();    
  }  
  airSensor.setForcedRecalibrationFactor(400);
  // 3. calibrate temp in normal setting
  Serial.println("Place the sensor in it's normal location and confirm with 'yes'");
  input = "";
  while (input != "yes"){
    while (Serial.available() == 0) {
       //Wait for user input
    }  
    input = Serial.readString(); 
    input.trim();
  }
  // check if sensordata is ready for first reading
  Serial.print("Waiting for first sensorreading...");
  while(!airSensor.dataAvailable())
  {
    delay(500);
    Serial.print(".");    
  }
  Serial.println(" ");
  Serial.print("The sensor reading for temperature is ");
  Serial.print(airSensor.getTemperature());
  Serial.println("°C");
  // Compare temperature with other calibrated device
  Serial.println("Measure the temperature with a other calibrated meter and input the offset (scd30 temp - calibrated temp): ");
  input = "";  
  while (Serial.available() == 0) {
     //Wait for user input
  }  
  input = Serial.readString();   
  input.trim();
  Serial.print("Setting a offset of ");
  Serial.print(input.toFloat());
  Serial.println("°C");
  airSensor.setTemperatureOffset(input.toFloat());
  // Ready for use
  Serial.println("Calibration ready.");
  Serial.println("Every " + String(timer_delay / 1000) + " seconds a measurement will be send to the database");
}

void loop()
{
  // Send an HTTP POST request every 60 seconds
  if ((millis() - last_time) > timer_delay)
  {
    // waiting for sensor ready
    Serial.print("Waiting for sensor ready...");
    while(!airSensor.dataAvailable())
    {
      delay(500);
      Serial.print(".");    
    }
    Serial.println(" ");
    // Reading sensor
    sensordata.CO2Concentration = airSensor.getCO2();  
    sensordata.Temperatuur = airSensor.getTemperature();
    sensordata.Luchtvochtigheid = airSensor.getHumidity();
    Serial.print("Readings: Co2 = ");
    Serial.print(sensordata.CO2Concentration);
    Serial.print("ppm - Temperature = ");
    Serial.print(sensordata.Temperatuur);
    Serial.print("°C - Humidity = ");
    Serial.print(sensordata.Luchtvochtigheid);
    Serial.println("%");
    // Wifi connection ?
    if (WiFi.status() == WL_CONNECTED)
    {
      HTTPClient http;
      bool isBegin = http.begin(server);
      Serial.println("Connecting to server...");
      // Server connection ?
      if (isBegin)
      {
        Serial.println("Sending data to database...");
        // Send HTTP POST in JSON data format
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        // Data as raw json body to send with HTTP POST
        String httpRequestData = "{\"apikey\":\"" + my_Api_key + "\",\"co2\":" + sensordata.CO2Concentration + ",\"temp\":" + sensordata.Temperatuur + ",\"humi\":" + sensordata.Luchtvochtigheid + "}";
        Serial.print("HTTP Post body: ");
        Serial.println(httpRequestData);
        // Send HTTP POST request
        int httpResponseCode = http.POST(httpRequestData);
        Serial.print("HTTP Response code is: ");
        Serial.println(httpResponseCode);
        // Break off the server connection
        http.end();
      }
      else
      {
        // error: no server connection
        Serial.print("unable to start http server connection!");
      }
    }
    else
    {
      // error: no wifi connection
      Serial.println("WiFi is Disconnected!");
    }
    last_time = millis();
  }
}