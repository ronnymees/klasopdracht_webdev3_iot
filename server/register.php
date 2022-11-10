<!-- PHP VALIDATION AND REGISTRATION -->

<?php
    // include credentials for database and sensor
    include './includes/db_credentials.php';  

    // functie voor APIkey
    function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid =
                substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            return $uuid;
        }
    }

    // Definitie error message
    $error="";

    // HTTP POST Request ?
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        // Verbinden met de database
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

        // Escapen van de inputdata en toevoegen aan een associatieve array
        $user = array(); 
        $user += ['name' => mysqli_real_escape_string($connection,$_POST['name'])];
        $user += ['location' => mysqli_real_escape_string($connection,$_POST['location'])];
                        
        $password1=mysqli_real_escape_string($connection,$_POST['password1']);
        $password2=mysqli_real_escape_string($connection,$_POST['password2']);

        // Password 2x hetwelfde?
        if ($password1!=$password2) {
            $error .= "<li>Passwords dont match</li>";
        }

        // Het wachtwoord is minstens 8 tekens lang en bevat minstens 1 hoofdletter, 1 cijfer en 1 ander teken. 
        if(!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})/', $password1)) {
            $error .= "<li>Your password must contain 1 cappital letter, 1 number, 1 symbol and be 8 long</li>";
        }

        // Name nog niet geregistreerd?
        // sql-statement
        $query="SELECT * FROM users WHERE name='{$user['name']}';";
            
        // Het SQL statement uitvoeren
        $result=mysqli_query($connection,$query);
        
        // Naam reeds geregistreerd
        if(mysqli_num_rows($result) > 0){
            $error .= "<li>This name is already registered</li>";
        }
        
        // Als er geen errors zijn registreren we de nieuwe gebruiker
        if (empty($error)) {
            // Versleutelen password
            $user += ['password' => password_hash($password1,PASSWORD_DEFAULT)];

            // API-key genereren
            $user += ['apikey' => getGUID()];

            // Het SQL statement opbouwen
            $insert = "INSERT INTO users (name, location, password, apikey, isadmin) 
            VALUES ('{$user['name']}','{$user['location']}', '{$user['password']}', '{$user['apikey']}', 0);";
            
            // Het SQL statement uitvoeren
            mysqli_query($connection,$insert);
    
            // De verbinding met de database afsluiten
            mysqli_close($connection);    

            // Ga naar login page
            header("Location: login.php");
            exit;
        }
        
        // De verbinding met de database afsluiten
        mysqli_close($connection);
        
    }
?>

<!-- HTML FORM -->

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Klasopdracht">   
        <title>Register page</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <link rel="stylesheet" type="text/css" href="styles/formstyle.css">
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <div class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="webapidoc.php">WebApi</a></li>
                <li><a class="active" href="register.php">Register</a></li>
            </ul>
        </div>
        <div class="container">
            <div class="title">Registration</div>
            <div class="content">
                <form action="register.php" method="POST">
                    <?php
                        if(!empty($error)){                            
                            echo "<br><div id='formerror'>";
                            echo "<ul>{$error}</ul>";
                            echo "</div>";
                        }
                    ?>
                    <div class="user-details">
                        <div class="input-box">
                            <span class="details">Name</span>
                            <input name="name"type="text" placeholder="Enter your name" <?php if(isset($user['name'])){echo "value='{$user['name']}'";} ?> required>
                        </div>
                        <div class="input-box">
                            <span class="details">Location</span>
                            <input name="location"type="text" placeholder="Enter your location" <?php if(isset($user['name'])){echo "value='{$user['location']}'";} ?> required>
                        </div>
                        <div class="input-box">
                            <span class="details">Password</span>
                            <input name="password1" type="password" placeholder="Enter your password" required>
                        </div>
                        <div class="input-box">
                            <span class="details">Confirm Password</span>
                            <input name="password2" type="password" placeholder="Confirm your password" required>
                        </div>
                        </div>        
                        <div class="button">
                        <input type="submit" value="Register">
                    </div>
                </form>
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>