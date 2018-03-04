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


	<title>SNP results</title>
</head>

<?php


include "navbar.html";				#incluimos la barra de navegación y el head de la pagina
include 'databasecon.php';			#incluimos la página en la que nos conectamos con la base de datos

session_start();

$_SESSION['SNP_page'] = $_REQUEST;

$sql = "select    *
        from      SNP as s, Gene as g ,
                  Gene_has_SNP as gs, Variants as v
        where     s.idSNP = gs.SNP_idSNP and
                  gs.Gene_Gene_id = g.Gene_id and
                  v.idSNP = s.idSNP
                  and
                  ". join(" AND ", $ANDconds);

// print $sql."<br>";

$rs = mysqli_query($mysqli, $sql) or print mysqli_error($mysqli);

?>

<div class="container" style="padding-top: 25px">
	<div>
		<div class="row">
			<h3 style="margin-right: 10px">SNP: </h3><h4> <?php print $_SESSION['SNP_page']['ref'] ?></h4>
		</div>

		<div>

			<div class="row">
				<p>Location:</p>
				<p>  </p>
			</div>
			<div class="row">
				<p>Gene:</p>
				<p>.............</p>
			</div>
			<div class="row">
				<p>Main allele:</p>
				<p>.............</p>
			</div>
			<div class="row">
				<p>Frequency:</p>
				<p>.............</p>
			</div>
			<div class="row">
				<p>Variant:</p>
				<p>.............</p>
			</div>
			<div class="row">
				<p>p-value:</p>
				<p>.............</p>
			</div>
			<div class="row">
				<p>beta:</p>
				<p>.............</p>
			</div>


		</div>
	</div>
</div>
