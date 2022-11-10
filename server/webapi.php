<?php
    // include credentials for database
    include './includes/db_credentials.php';

    // Array to build JSON response
    $sqlArray = [];

    // GET input period
    if(isset($_GET["start"]) && isset($_GET["end"])) {
        $startDate = strtotime($_GET["start"]);
        $endDate = strtotime($_GET["end"]);
        $startDate=date('Y-m-d H:i:s',$startDate);
        $endDate=date('Y-m-d H:i:s',$endDate);
    }

    if(isset($_GET["apikey"])){
        $apikey = $_GET["apikey"];
    } else {
        $apikey="";
    }

    // connect to database
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    // query to search for user
    $query="SELECT * FROM users WHERE apikey='{$apikey}';"; 

    // Het SQL statement uitvoeren
    $result=mysqli_query($connection,$query);

    // Enkel verdergaan bij geldige APIkey
    if(mysqli_num_rows($result) > 0){
        // get userid
        $userid=mysqli_fetch_assoc($result)['id'];

        // Build query
        if(isset($startDate)&&isset($endDate)&&($endDate>$startDate)) {
            $query = "SELECT * FROM sensordata WHERE ( insertdate >= '{$startDate}' && insertdate <= '{$endDate}' && userid = '{$userid}') ORDER BY id DESC";
        } else {
            $query = "SELECT * FROM sensordata WHERE ( userid = '{$userid}') ORDER BY id DESC LIMIT 30";
        }
        
        // execute query
        $result_set = mysqli_query($connection, $query);
        
        // use the results
        while($subject = mysqli_fetch_assoc($result_set)) {
            array_push($sqlArray, (object)[
                'date' => $subject["insertdate"],
                'co2' => $subject["co2"],
                'temp' => $subject["temp"],
                'humi' => $subject["humi"],
            ]);
        }

        // release results
        mysqli_free_result($result_set);

        // close the connection to database
        mysqli_close($connection);

        // Output the data in json format
        header('Content-type: application/json');

        // headers needed for CORS allowance
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');

        // convert to json
        $output = json_encode($sqlArray);

        // echo to user
        echo $output;
    } else {
        http_response_code(401);
    }
?>
