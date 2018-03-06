<?php
#start Session to hold input data
session_start();
$_SESSION['queryData'] = $_REQUEST;

if (isset($_SESSION['queryData']['Email'])) {

$to = "warmsnp@gmail.com";
$subject = $_SESSION['queryData']['Name'];
$txt = $_SESSION['queryData']['Message'];
$headers = "From:" . $_SESSION['queryData']['Email'] . "\r\n"; 

$msg = wordwrap($txt, 70, "\r\n");

mail($to, $subject, $msg, $headers);


?> 
<html>
<head>
<link rel="icon" href="Home_images/flame.png">
<title>WARMsnp</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif; text-align: center;}
body, html {
    height: 100%;
    color: #777;
    line-height: 1.8;
}
.button {
    border: none;
    outline: 0;
    display: inline-block;
    padding: 8px;
    color: white;
    background-color: #000;
    text-align: center;
    cursor: pointer;
    width: 100%;
}

.button:hover {
    background-color: #555;
}
</style>
</head>
<body>

<header class="w3-container" style="padding-top:22px">
    <h3><b> Thank you for contacting us! </b></h3>
  </header>
<a href="WARMsnp_index.php"><button class="w3-btn w3-xlarge w3-dark-grey w3-hover-light-grey" style="font-weight:900;"><i class="fa fa-search"></i>GO TO INDEX</button></a>

<?php }

else {
header('Location: WARMsnp_index.php');

}

?>

<!-- "\r\n" -->