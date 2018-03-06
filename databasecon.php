<?php

/*
 * bdconn.inc.php
 * DB Connection
 */

/*Definimos las variables necesarias para establecer la conexión con la base de datos*/
$host = "localhost";
$dbname = "WARMsnp";
$user = "Winonaladrona";
$password = "WinnieMouse";

/*También guardamos en una variable la string del inicio de las querys para mysql, donde nos conectamos a la base de datosl*/
($mysqli = mysqli_connect($host, $user, $password)) or die(mysqli_error());
/*También nos conectamos con el servidor y seleccionamos la base de datos que queremos utilizar*/
mysqli_select_db($mysqli, $dbname) or die(mysqli_error($mysqli));

?>