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
	<title>Gene results</title>
</head>

<?php


include "navbar.html";				#incluimos la barra de navegación y el head de la pagina
include 'databasecon.php';			#incluimos la página en la que nos conectamos con la base de datos

session_start();

if ($_REQUEST) {
	$_SESSION['gene_page'] = $_REQUEST;
}


$sql_GO = "select GO.GO_name
from GO, Gene_Go as gg, Gene as g, SNP as s, Gene_has_SNP as gs
where gg.GO_id = GO.GO_id and gg.Gene_id = g.Gene_id and
g.Gene_id = gs.Gene_Gene_id and s.idSNP = gs.SNP_idSNP and g.Gene_id like '".$_REQUEST['ref']."'";


$sql_tissue = "select t.name, gt.expression_level
from tissue as t, Gene_Tissue as gt, Gene as g
where  g.Gene_id = gt.idGene and expression_level > 0 and t.Tissue_id = gt.Tissue_id
and g.Gene_id like '".$_REQUEST['ref']."'
order by gt.expression_level desc";


$sql_gene = "select g.Gene_id, Chromosome, Start_position, End_position,
hgnc_name
from 	Gene as g
where	g.Gene_id like '".$_REQUEST['ref']."'";

$sql_snp = "select s.idSNP, pos, Main_allele,
Frequency, Sequence, p_value, beta, predicted_consequences
from 	SNP as s, Variants as v, Gene as g, Gene_has_SNP as gs
where	v.idSNP = s.idSNP and g.Gene_id = gs.Gene_Gene_id and
s.idSNP = gs.SNP_idSNP and g.Gene_id like '".$_REQUEST['ref']."'";


$rs_GO = mysqli_query($mysqli, $sql_GO) or print "GO: ".mysqli_error($mysqli);
$rs_tissue = mysqli_query($mysqli, $sql_tissue) or print "Tissue: ".mysqli_error($mysqli);
$rs_gene = mysqli_query($mysqli, $sql_gene) or print "Gene: ".mysqli_error($mysqli);
$rs_snp = mysqli_query($mysqli, $sql_snp) or print "SNP: ".mysqli_error($mysqli);

function transpose($data)
{
	$retData = array();
	foreach ($data as $row => $columns) {
		foreach ($columns as $row2 => $column2) {
			$retData[$row2][$row] = $column2;
		}
	}
	return $retData;
}

$rsT_gene = mysqli_fetch_assoc($rs_gene);

if (is_null($rsT['chr'])){
	$rsT['chr'] = $rsT_gene['Chromosome'][0];
}

?>

<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container" style="min-height:75%; margin-bottom:20px">
	<h3 style="margin-right: 10px">Gene: <?php print $rsT_gene['hgnc_name'] ?><span class=""> <a href=<?php print "https://www.ncbi.nlm.nih.gov/SNP/snp_ref.cgi?searchType=adhoc_search&type=rs&rs=".$_SESSION['gene_page']['ref'] ?> style="color:#000000"><?php print $_SESSION['gene_page']['ref'] ?></a></span> </h3>
  <ul class="nav nav-tabs">
    <li class="active"><a href="#home">Gene attributes</a></li>
    <li><a href="#SNP">SNPs</a></li>
    <li><a href="#Tissue">Tissue Expression</a></li>
    <li><a href="#GO">Gene Ontology</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h4><a href=<?php print "https://www.ncbi.nlm.nih.gov/SNP/snp_ref.cgi?searchType=adhoc_search&type=rs&rs=".$_SESSION['gene_page']['ref'] ?> style="color:#000000"><?php print $_SESSION['gene_page']['ref'] ?></a></h4>
      <p>Here go gene characteristics: how many snps does it have, tissue where it is expressed the mosts...</p>
    </div>
    <div id="SNP" class="tab-pane fade">
      <h4>SNPs</h4>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
			<table border="0" cellspacing="2" cellpadding="4" id="snpTable">
				<thead>
					<tr>
						<th>SNP Id</th>
						<th>Position</th>
						<th>Main allele</th>
						<th>Mutation</th>
						<th>Frequency</th>
						<th>Beta</th>
						<th>p value</th>
					</tr>
				</thead>
				<tbody>

					<?php while ($rsF = mysqli_fetch_assoc($rs_snp)) {

						$SNP_id =  $rsF['idSNP'];
						$Main_allele =  $rsF['Main_allele'];
						$variant_allele =  $rsF['Sequence'];
						$position = $rsF['pos'];
						$frequency = $rsF['Frequency'];
						$beta = $rsF['beta'];
						$pval = $rsF['p_value'];

						?>
						<tr>
							<?php  print "<td><a target='_blank' href='SNP_page.php?ref=$SNP_id'>   $SNP_id  </a></td>" ?>
							<td> <?php print $position ?> </td>
							<td> <?php print $Main_allele ?> </td>
							<td> <?php print $variant_allele ?> </td>
							<td> <?php print $frequency ?> </td>
							<td> <?php print $beta ?> </td>
							<td> <?php print $pval ?> </td>
						</tr>
						<?php
					}

					?>
				</tbody>
			</table>
    </div>
    <div id="Tissue" class="tab-pane fade">
      <h4>Tissue</h4>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
			<table border="0" cellspacing="2" cellpadding="4" id="tissueTable">
				<thead>
					<tr>
						<th>Tissue</th>
						<th>Expression level (tpm)</th>
					</tr>
				</thead>
				<tbody>

					<?php

					while ($rsT_tissue = mysqli_fetch_assoc($rs_tissue)) {

						?><tr><?php
						foreach ($rsT_tissue as $field) {

							?>

							<td><?php print $field ?></td>

							<?php
						}
						?><tr><?php
					}

					?>

				</tbody>
			</table>
    </div>
    <div id="GO" class="tab-pane fade">
      <h4>Gene ontology</h4>
      <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });
});
</script>

<script>
$(document).ready(function () {
    $('#snpTable').DataTable();
});
</script>

<script>
$(document).ready(function () {
    $('#tissueTable').DataTable();
});
</script>

<?php
include "footer.html";
?>

</body>
</html>