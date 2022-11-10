<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Klasopdracht">   
        <title>Home page</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <link rel="stylesheet" type="text/css" href="styles/formstyle.css">        
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <div class="navbar">
            <ul>
                <li><a class="active" href="index.php">Home</a></li>
                <li><a href="webapidoc.php">WebApi</a></li>
                <li><a href="info.php">User info</a></li>
            </ul>
        </div>
        <div class="container">
            <div class="title">Classroom monitoring with Sensirion SCD30 sensor</div>
            <div class="content">
                <p>This is a 2<sup>de</sup> year Graduaat Internet of Things classproject, revised by the teachers.</p>
                <p>It was intented to train there back-end webdevelopment skills in a worklike envirement context.</p>
                <p>First years will use this project in there lessons of front-end webdevelopment.</p>
                <h4>Setup</h4>
                <img src="./images/setup.png">
                <table>
                    <tr>
                        <td><img src="./images/htmlicon.png"></td>
                        <td><img src="./images/cssicon.png"></td>
                        <td><img src="./images/javascripticon.png"></td>
                        <td><img src="./images/jsonicon.png"></td>
                        <td><img src="./images/phpicon.png"></td>
                        <td><img src="./images/mysql.png"></td>
                        <td><img src="./images/arduino_c.png"></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>