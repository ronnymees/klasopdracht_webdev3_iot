-- aanmaken vives database
CREATE DATABASE vives;
USE vives;

-- instellen webuser en rechten toekennen
CREATE USER 'webuser'@'localhost' IDENTIFIED BY "secretpassword";
GRANT ALL PRIVILEGES ON vives.* TO 'webuser'@'localhost';

-- aanmaken sensordata tabel
CREATE TABLE sensordata (id INT(11) NOT NULL AUTO_INCREMENT,
insertdate datetime NOT NULL DEFAULT current_timestamp(),
co2 FLOAT(10),
temp FLOAT(10),
humi FLOAT(10),
userid int(11),
PRIMARY KEY (id)
);

-- aanmaken users tabel
CREATE TABLE users (id INT(11) NOT NULL AUTO_INCREMENT,
name VARCHAR(255),
location VARCHAR(255),                 
password VARCHAR(255),   
apikey VARCHAR(255), 
isadmin INT(1),                
PRIMARY KEY (id)
);