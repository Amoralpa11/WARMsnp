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