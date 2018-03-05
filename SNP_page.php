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

	<link rel="icon" href="Home_images/flame.png">
	<title>SNP results</title>
</head>
<?php


include "navbar.html";				#incluimos la barra de navegación y el head de la pagina


session_start();



$rsT_disease=$_SESSION['snp_page']['rsT_disease'];
$rsT=$_SESSION['snp_page']['rsT'];
$rsT_gene=$_SESSION['snp_gene']['rsT_gene'];

?>

<div class="container" style="padding-top: 25px">
	<div>
		<div class="row">
			<h3 style="margin-right: 10px">SNP: </h3><h3> <a href=<?php print "https://www.ncbi.nlm.nih.gov/SNP/snp_ref.cgi?searchType=adhoc_search&type=rs&rs=".$_SESSION['SNP_page']['ref'] ?> style="color:#1e1f21"><?php print $_SESSION['SNP_page']['ref'] ?></a></h3>
		</div>

		<div>
		<div class="row">
	    <div class="col-md-3" style="border: 1px dashed; border-radius:25px; background-color:#f2f2f2; margin-right:5%">
				<h5 style="margin-top:5%; text-align: center">Genomic attributes:</h5>
				<p>Location: chr: <?php print $rsT['chr']." : ".$rsT['pos']?></p>
				<p>Gene: <?php
				$link_array = [];
				foreach ($rsT_gene['Gene_id'] as $gene){
				$link_array[] = "<a href ='gene_page.php?ref=$gene'>$gene</a>";
				}
				print implode(", ", $link_array);
				?></p>
			</div>
			<div class="col-md-3" style="border: 1px dashed; border-radius:25px; background-color:#f2f2f2; margin-right:5%; ">
				<h5 style="margin-top:5%; text-align: center">SNP attributes:</h5>
				<p>Main allele: <?php print " ".$rsT['Main_allele']?></p>
				<p>Variant: <?php print $rsT['Sequence']?></p>
				<p>Main allele frequency: <?php print $rsT['Frequency']?></p>
			</div>
			<div class="col-md-3" style="border: 1px dashed; border-radius:25px; background-color:#f2f2f2; margin-right:5%">
				<h5 style="margin-top:5%; text-align: center">CAD & MI association:</h5>
				<p>p-value: <?php print $rsT['p_value']?></p>
				<p>beta: <?php print $rsT['beta']?></p>
			</div>
		</div>
		<div class="row" style="margin-top:8%">
			<div class="col-md-3" style="border: 1px dashed; border-radius:25px; background-color:#f2f2f2">
				<h5 style="margin-top:5%; text-align: center">Other related disease:</h5>
				<?php

				if (count($rsT_disease) != 0) {
					foreach ($rsT_disease as $row) {
						print "<p>".$row["Name"]."</p>";
					}

				} else {
					print "<p>No other related disease.</p>";
				}
				 ?>
			</div>
		</div>
</div>

<!--
si seleccionamso snp 40000 arrgiba y abajo con p-value beta value, posiciones.
3 arrays asociativos en el que la clave sea el id del snp
 -->

 <?php

 #DATOS PARA RAMON

$sql_plot1 = "select pos, beta, v.p_value, s.idSNP
from 	SNP as s, Variants as v, Gene_has_SNP as gs, Gene as g
where	s.pos between ".$rsT['pos']."-40000 and ".$rsT['pos']."+40000
and chr = ".$rsT['chr']."  and s.idSNP = v.idSNP;";

$sql_plot2 = "select pos, beta, p_value, s.idSNP
from 	SNP as s, Variants as v, Gene_has_SNP as gs, Gene as g
where	s.pos between ".$rsT['pos']."-40000 and ".$rsT['pos']."+40000
and Chromosome = ".$rsT['chr']." and s.idSNP = v.idSNP and
s.idSNP = gs.SNP_idSNP and g.Gene_id = gs.Gene_Gene_id;";


$rs_plot1 = mysqli_query($mysqli, $sql_plot1) or print mysqli_error($mysqli);
$rs_plot2 = mysqli_query($mysqli, $sql_plot2) or print mysqli_error($mysqli);

$rsT_plot = mysqli_fetch_all($rs_plot1,MYSQLI_ASSOC);

$rsT_plot += mysqli_fetch_all($rs_plot2,MYSQLI_ASSOC);



function cmp($a, $b)
{
    if ($a["pos"] == $b["pos"]) {
        return 0;
    }
    return ($a["pos"] < $b["pos"]) ? -1 : 1;
}

usort($rsT_plot,"cmp");

$rsT_plot = transpose($rsT_plot);


?>
