<?php
    // include credentials for database and sensor
    include './includes/db_credentials.php';
    // is there a HTTP Post Request ?
    if($_SERVER['REQUEST_METHOD']=='POST')
    { 
        // Verbinden met de database
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        // POST data inlezen uit raw json data
        $_POST = json_decode(file_get_contents('php://input'), true);
        // POST data binnenhalen en escapen
        $date=date_create()->format('Y-m-d H:i:s');
        $apikey=mysqli_real_escape_string($connection,$_POST['apikey']);
        $co2=mysqli_real_escape_string($connection,$_POST['co2']);
        $temp=mysqli_real_escape_string($connection,$_POST['temp']);
        $humi=mysqli_real_escape_string($connection,$_POST['humi']);       
        // User opzoeken in accounts tabel
        $query="SELECT * FROM users WHERE apikey='{$apikey}';";        
        // Het SQL statement uitvoeren
        $result=mysqli_query($connection,$query);
        // Enkel verdergaan bij geldige APIkey
        if(mysqli_num_rows($result) > 0){
            // get userid
            $userid=mysqli_fetch_assoc($result)['id'];
            // insert into database            
            // SQL-statement uitvoeren
            if(isset($co2) && isset($temp) && isset($humi)){
                $insert = "INSERT INTO sensordata (insertdate, co2, temp, humi,userid) VALUES ('{$date}','{$co2}','{$temp}','{$humi}','{$userid}');";
                mysqli_query($connection,$insert);
            }
            // De verbinding met de database afsluiten
            mysqli_close($connection);
        }
        else {
            http_response_code(401);            
        }
    }
    else {
        http_response_code(400); 
    }
?>