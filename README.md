# Klasopdracht Webdevelopment 3 - Graduaat Internet of Things

The idea is that in A306, A308, B302, B303, B304 and B306 IoT devices will be deployed with a SCD30 sensor that periodicaly will register Temperature, Humidity and CO2 to a PHP application on the server by use of API key's. The data will be stored in a MySQL database.

## Setup Server

On the server we use XAMPP so Apache with PHP and MySQL is available.

### Setup the database

In order to setup of restore the database follow [these instructions](/database/).

### Setup the application

In order to setup the web application follow [these instructions](/server/)

## Setup a IoT device

In order to setup a new IoT Device follow [these instructions](/iotdevice/)

## Use the WEBapi 

[Here](/client/) you can see a basic use of the WEBapi in a client-side web application.

## Docker

Create `.env`file by copying `.env.example` and fill in the details.

```bash
cp .env.example .env
```

Launch the whole stack using docker-compose:

```bash
docker-compose up -d
```

If something changed in the source files you may need to force a build:

```bash
docker-compose up --build -d
```

To stop the stack:

```bash
docker-compose down
```

### Services

The services in this stack are mapped as follows:

* Client: [http://localhost:80](http://localhost:80)
* Server: [http://localhost:8000](http://localhost:8000)
* PhpMyAdmin: [http://localhost:8080](http://localhost:8080)
