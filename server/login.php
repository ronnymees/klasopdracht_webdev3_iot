<!-- PHP VALIDATION AND LOGIN -->

<?php    
    // include credentials for database and sensor
    include './includes/db_credentials.php';    

    // Definitie error message
    $error="";

    // HTTP POST Request ?
    if($_SERVER['REQUEST_METHOD']=='POST')
    {       
        // Verbinden met de database
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

        // Escapen van de inputdata en toevoegen aan een associatieve array
        $name = mysqli_real_escape_string($connection,$_POST['name']);
        $password=mysqli_real_escape_string($connection,$_POST['password']);        

        // User opzoeken in accounts tabel
        $query="SELECT * FROM users WHERE name='{$name}';";
            
        // Het SQL statement uitvoeren
        $result=mysqli_query($connection,$query);
        $data=mysqli_fetch_assoc($result);        
        
        // user gevonden ?
        if(mysqli_num_rows($result) == 0){
            $error .= "<li>Name is not registerd</li>";
        } 
        // password correct ?
        elseif(!password_verify($password, $data['password'])){
            $error .= "<li>Password is incorrect</li>";
        }

        // Als er geen errors zijn kunnen we inloggen
        if (empty($error)) {
            session_start();
            $_SESSION['id'] = $data['id'];
            $_SESSION['name'] = $name;
            $_SESSION['location'] = $data['location'];
            $_SESSION['apikey'] = $data['apikey'];
            $_SESSION['isadmin'] = $data['isadmin'];
            
            // Ga naar confirmation page
            header("Location: info.php");
            // De verbinding met de database afsluiten
            mysqli_close($connection);    
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
        <title>Login page</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <link rel="stylesheet" type="text/css" href="styles/formstyle.css">
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <div class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="webapidoc.php">WebApi</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </div>
        <div class="container">
            <div class="title">Login</div>
            <div class="content">
                <form action="login.php" method="POST">
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
                            <input name="name" type="text" placeholder="Enter your name" required>
                        </div>
                        <div class="input-box">
                            <span class="details">Password</span>
                            <input name="password" type="password" placeholder="Enter your password" required>
                        </div>
                    </div>        
                    <div class="button">
                        <input type="submit" value="Login">
                    </div>
                </form>
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>