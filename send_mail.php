<?php
#start Session to hold input data
session_start();
$_SESSION['queryData'] = $_REQUEST;

if (!isset($_SESSION['queryData']))
    header('Location: WARMsnp_prova2.php');

$to = "warmsnp@gmail.com";
$subject = $_SESSION['queryData']['Name'];
$txt = $_SESSION['queryData']['Message'];
$headers = "From: " . $_SESSION['queryData']['Email'] . "\r\n"; 

$msg = wordwrap($txt,70);

mail($to, $subject, $msg, $headers);


header('Location: WARMsnp_prova2.php')

?>

