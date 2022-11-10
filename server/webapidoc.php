<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Klasopdracht">   
        <title>WebApi documentation</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css">
        <link rel="stylesheet" type="text/css" href="styles/formstyle.css">        
    </head>
    <body onload="myFunction()">
        <?php include 'includes/header.php'; ?>
        <div class="navbar">
            <ul>
                <li><a  href="index.php">Home</a></li>
                <li><a class="active" href="webapidoc.php">WebApi</a></li>
                <li><a href="info.php">User info</a></li>
            </ul>
        </div>
        <div class="container">
            <div class="title">Sensirion SCD30 monitoring</div>
            <div class="content">
                <p>Explenation on how to use this WebApi.</p>
                <h4>Pushing data to the site</h4>
                <p>IoT-devices equiped with a Sensirion SCD30 sensor can push data to the site.</p>
                <p>Registerd sensors use there <strong>ApiKey</strong> to push data to the host <strong>[ip-adres]/iot_lab/scd30/write.php</strong>.</p>
                <p>Data should be pushed with a <strong>HTTP Post Request</strong> using <i>apikey</i>, <i>co2</i>, <i>temp</i>, <i>humi</i>.</p>
                <div class="example">
                    <h4>Example</h4>
                    <p>{ "apikey":"D1682E7E-D27F-AE08-0882-0F4721D4FD77" , "co2":"501" , "temp":"21.4" , "humi":"63" }</p>
                </div> 
                <h4>Retreiving data from the site</h4>
                <p>Users can acces data by using the WebApi <strong>[ip-adres]/iot_lab/scd30/webapi.php</strong>.</p>
                <p>The WebApi should be approached with a <strong>HTTP Get Request</strong> using <i>start</i> , <i>end</i> and <i>apikey</i>.</p>
                <div class="example">
                    <h4>Example</h4>
                    <p>http://192.168.0.100/iot_lab/scd30/webapi.php?webapi="D1682E7E-D27F-AE08-0882-0F4721D4FD77"&start=2022-10-10T10:41&end=2022-10-12T10:41&apikey=ECC148F3-9B2F-D790-EF22-79CRR9195015</p>                    
                </div> 
                <p>The WebApi will response with a JSON format result.</p>                  
                <div class="example">
                    <h4>Example</h4>
                    <pre id="json"></pre>
                </div> 
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
        <script>
            function myFunction() {
                var data = [
                            {
                            "date": "2022-10-11 11:22:16",
                            "co2": "399",
                            "temp": "23.63",
                            "humi": "40.22"
                            },
                            {
                            "date": "2022-10-11 11:22:48",
                            "co2": "411",
                            "temp": "23.66",
                            "humi": "39.79"
                            },
                            {
                            "date": "2022-10-11 11:23:18",
                            "co2": "419",
                            "temp": "23.65",
                            "humi": "39.7"
                            }
                            ]
                document.getElementById("json").textContent = JSON.stringify(data, undefined, 2);
            }
        </script>
    </body>
</html>