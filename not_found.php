<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="scss/custom.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>


	<link rel="stylesheet" href="DataTable/jquery.dataTables.min.css"/>
	<script type="text/javascript" src="DataTable/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="DataTable/jquery.dataTables.min.js"></script>
	<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>


	<link rel="icon" href="Home_images/flame.png">

	<title>Not found</title>
</head>
<?php 

include 'navbar.html';

$ref = $_REQUEST['ref'];

 if (strtoupper(substr( $ref, 0, 2 )) === "RS"){
	$ref_type = "SNP";
} else if (strtoupper(substr( $ref, 0, 2 )) === "ENS"){
	$ref_type = "gene";
}

 ?>

<div style="height: 100%"></div>


<footer 
class="footer bg-dark py-3" style=" position: fixed; 
    bottom: 0;
    left: 0;
    right: 0;
    height: 50px;">
      <div class="container">
        <span class="text-muted">
        <p class="m-0 text-center text-white">Contact us: warmsnp@gmail.com</p>
        </span>
      </div>
</footer>



</body>
</html>