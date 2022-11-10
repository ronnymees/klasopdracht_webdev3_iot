<?php
    // check is user is logged in
    session_start();   

    if (!isset($_SESSION['name'])) 
    {
        header("Location: login.php");
        exit;
    }

    // include credentials for database and sensor
    include './includes/db_credentials.php';
    include './includes/sensor_credentials.php';
    
    // Connect to database
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    // Is there a HTTP Post Request ?
    if($_SERVER['REQUEST_METHOD']=='POST')
    { 
        // Is this a DELETE action ?
        if($_POST["action"]=='delete') {
            for($i=0; $i < count($_POST['checkboxes']); $i++){
                $delete = "DELETE FROM sensordata WHERE id='{$_POST['checkboxes'][$i]}';";
                mysqli_query($connection,$delete);
            }
        } else {
            // Retreive sensordata
            $query = "SELECT * FROM sensordata WHERE userid='{$_SESSION['id']}' AND insertdate>='{$_POST['startdatum']}' AND insertdate<'{$_POST['einddatum']}' ;";
            $result_set = mysqli_query($connection, $query);    
        }
    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info page</title>
    <link rel="stylesheet" type="text/css" href="./styles/style.css">
    <link rel="stylesheet" type="text/css" href="styles/formstyle.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="navbar">
        <ul>
            <li><a  href="index.php">Home</a></li>
            <li><a href="webapidoc.php">WebApi</a></li>
            <li><a class="active" href="info.php">User info</a></li>
        </ul>
    </div>
    <div class="container">
        <div class="title">User info</div>
        <div class="content">
            <table id="userinfotabel">
                <tr>
                    <td>Name</td>
                    <td class="left"><?php echo $_SESSION['name'] ?></td>
                
                    <td>Location</td>
                    <td class="left"><?php echo $_SESSION['location'] ?></td>            
                </tr>
                <tr>
                    <td>ApiKey</td>
                    <td colspan="3">
                        <div class="fluo"><?php echo $_SESSION['apikey'] ?></div>                
                    </td>
                </tr>
            </table> 
        </div>
    </div>
    <div class="container">
        <div class="title">Select period</div>
        <div class="content">
            <form action="info.php" method="POST">    
                <label for="startdatum">Starting from (incl.)</label>
                <input id="startdatum" type="datetime-local" name="startdatum">
                <label for="einddatum">Ending with (excl.)</label>
                <input id="einddatum" type="datetime-local" name="einddatum">   
                <input type="hidden" name="action" value="search" />     
                <input type="submit" value="search"/><br/>
            </form>
        </div>
    </div>  
    <!-- If page was requested with POST request -->
    <?php if($_SERVER['REQUEST_METHOD']=='POST' && $_POST["action"]=='search') : ?> 
        <!-- Sensor data table form -->        
        <div class="container">
            <div class="title">Sensor data</div>
            <div class="content">
                <form action="info.php" method="POST">
                    <div id=datatabel>
                        <table>
                            <thead>
                                <tr><th>Date-Time</th><th>Co<sup>2</sup> [ppm]</th><th>Temperature [°C]</th><th>Humidity [%]</th><th>Delete</th></tr>
                            </thead>
                            <tbody id="tabelBody">
                                <?php
                                    while($datarow = mysqli_fetch_assoc($result_set)) {
                                        echo "<tr>\n";
                                        echo "<td>{$datarow["insertdate"]}</td>";
                                        echo "<td>{$datarow["co2"]}</td>";
                                        echo "<td>{$datarow["temp"]}</td>";
                                        echo "<td>{$datarow["humi"]}</td>";
                                        if($_SESSION['isadmin']==1) echo "<td><input type='checkbox' name='checkboxes[]' value={$datarow['id']} /></td>";
                                        echo "</tr>\n";
                                    }
                                ?>
                            </tbody>    
                        </table>
                    </div> 
                    <?php if($_SESSION['isadmin']==1): ?>               
                        <input type="hidden" name="action" value="delete" />
                        <input type="submit" value="delete"/><br/>
                    <?php endif ?>
                </form>
            </div>
        </div>    
    <?php endif ?>
    <?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
    // Was this a HTTP Post Request ?
    if($_SERVER['REQUEST_METHOD']=='POST' && $_POST["action"]=='search')
    { 
        // Release the results
        mysqli_free_result($result_set);
    }

    // Close the connection to the database
    mysqli_close($connection);
?>