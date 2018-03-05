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

	<title>SNP results</title>
</head>

<?php


include "navbar.html";				#incluimos la barra de navegación y el head de la pagina
include 'databasecon.php';			#incluimos la página en la que nos conectamos con la base de datos

session_start();

$_SESSION['SNP_page'] = $_REQUEST;

$sql_disease = "select d.Name
from Disease as d, SNP as s, SNP_disease as sd
where sd.idDisease = d.idDisease and sd.idSNP = s.idSNP and s.idSNP like '".$_REQUEST['ref']."'";

$sql_GO = "select GO.GO_name
from GO, Gene_Go as gg, Gene as g, SNP as s, Gene_has_SNP as gs
where gg.GO_id = GO.GO_id and gg.Gene_id = g.Gene_id and
g.Gene_id = gs.Gene_Gene_id and s.idSNP = gs.SNP_idSNP and  s.idSNP like '".$_REQUEST['ref']."'";

$sql_tissue = "select t.name, gt.expression_level
from tissue as t, Gene_Tissue as gt, Gene as g, SNP as s, Gene_has_SNP as gs
where  g.Gene_id = gt.idGene and t.Tissue_id = gt.Tissue_id and
g.Gene_id = gs.Gene_Gene_id and s.idSNP = gs.SNP_idSNP and
s.idSNP like '".$_REQUEST['ref']."'";


$sql_gene = "select g.Gene_id, Chromosome, Start_position, End_position,
			hgnc_name
from 	Gene as g, Gene_has_SNP as gs,
		SNP as s
where	g.Gene_id = gs.Gene_Gene_id and s.idSNP = gs.SNP_idSNP and s.idSNP like '".$_REQUEST['ref']."'";

$sql = "select pos, Main_allele, chr,
            Frequency, Sequence, p_value, beta, predicted_consequences
from 	SNP as s, Variants as v
where	v.idSNP = s.idSNP and s.idSNP like '".$_REQUEST['ref']."'";

$rs_disease = mysqli_query($mysqli, $sql_disease) or print mysqli_error($mysqli);
$rs_GO = mysqli_query($mysqli, $sql_GO) or print mysqli_error($mysqli);
$rs_tissue = mysqli_query($mysqli, $sql_tissue) or print mysqli_error($mysqli);
$rs_gene = mysqli_query($mysqli, $sql_gene) or print mysqli_error($mysqli);
$rs = mysqli_query($mysqli, $sql) or print mysqli_error($mysqli);

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



$rsT_disease = mysqli_fetch_all($rs_disease,MYSQLI_ASSOC);


$rsT = mysqli_fetch_assoc($rs);

$rsT_gene = mysqli_fetch_all($rs_gene,MYSQLI_ASSOC);

$rsT_gene = transpose($rsT_gene);




if (is_null($rsT['chr'])){
	$rsT['chr'] = $rsT_gene['Chromosome'][0];
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
	body {font-family: Arial;}

	/* Style the tab */
	.tab {
		overflow: hidden;
		border: 1px solid #ccc;
		background-color: #f1f1f1;
	}

	/* Style the buttons inside the tab */
	.tab button {
		background-color: inherit;
		float: left;
		border: none;
		outline: none;
		cursor: pointer;
		padding: 14px 16px;
		transition: 0.3s;
		font-size: 17px;
	}

	/* Change background color of buttons on hover */
	.tab button:hover {
		background-color: #ddd;
	}

	/* Create an active/current tablink class */
	.tab button.active {
		background-color: #ccc;
	}

	/* Style the tab content */
	.tabcontent {
		display: none;
		padding: 6px 12px;
		border: 1px solid #ccc;
		border-top: none;
	}
</style>
</head>


<body>
	<div id="loader" class="loader" style="width:100%; height:100%; background-color:white; margin:0; text-align: center; position: fixed; top: 0px;">
		<!-- <div id="loader" class="loader" style="width:100%;height:100%;background-color:white;margin:0;position:fixed;text-align: center;vertical-align: middle;position: relative;top: 50%;"> -->
			<div style="position:absolute;top:50%; left:50%; transform: translate(-50%, -50%);">
				<img src="images/ajax-loader.gif" alt="Be patient..." style="vertical-align: middle">
			</div>
			<div style="position:absolute;top:55%; left:50%; transform: translate(-50%, -50%);">Hey there, we are processing you request, the results will be displayed soon.</div>
			<div id="counter" style="position:absolute;top:60%; left:50%; transform: translate(-50%, -50%);">The page is loading, please wait...</div>

		</div>

		<h3 style="margin-right: 10px">SNP: </h3><h3> <a href=<?php print "https://www.ncbi.nlm.nih.gov/SNP/snp_ref.cgi?searchType=adhoc_search&type=rs&rs=".$_SESSION['SNP_page']['ref'] ?> style="color:#1e1f21"><?php print $_SESSION['SNP_page']['ref'] ?></a></h3>
		<div class="row" style="margin-top:4%">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="tab">
					<button class="tablinks" onclick="gene_tabs(event, 'attributes')" id="defaultOpen">SNP attributes</button>
					<button class="tablinks" onclick="gene_tabs(event, 'plot')">Manhattan plot</button>
				</div>
			</div>
		</div>

		<div class="row" style="min-height:62%; margin-bottom:20px">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="tab">
					<div id="attributes" class="tabcontent">
						<h4>SNP attributes</h4>
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
						</div>
						<div class="row">
						<div class="col-md-3" style="border: 1px dashed; border-radius:25px; background-color:#f2f2f2; margin-right:5%">
							<h5 style="margin-top:5%; text-align: center">CAD & MI association:</h5>
							<p>p-value: <?php print $rsT['p_value']?></p>
							<p>beta: <?php print $rsT['beta']?></p>
						</div>
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

						<div id="plot" class="tabcontent">
							<h4>SNPs</h4>
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

						<div id="Tokyo" class="tabcontent">
							<h4>Tissue expression</h4>
							<div id="tissue">
								<script type="text/javascript">
									var tissue = <?php echo '["'. implode('", "', $tissue_name) . '"]'?>;
									// document.write(tissue);
								 </script>
							</div>
							<div id="expression">
								<script type="text/javascript">
									var expression = <?php echo '["'. implode('", "', $tissue_expression) . '"]'?>;
									// document.write(expression);
								 </script>
							</div>
							<div id="myDiv">
								<script src="./bar_plot.js"> </script>
							</div>

						</div>
					</div>
					</div>
				</div>



				<script>
					function gene_tabs(evt, cityName) {
						var i, tabcontent, tablinks;
						tabcontent = document.getElementsByClassName("tabcontent");
						for (i = 0; i < tabcontent.length; i++) {
							tabcontent[i].style.display = "none";
						}
						tablinks = document.getElementsByClassName("tablinks");
						for (i = 0; i < tablinks.length; i++) {
							tablinks[i].className = tablinks[i].className.replace(" active", "");
						}
						document.getElementById(cityName).style.display = "block";
						evt.currentTarget.className += " active";
					}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
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

<script>
$(window).load(function() {      //Do the code in the {}s when the window has loaded
	$("#loader").fadeOut("fast");
});
</script>

</body>
<?php include "footer.html" ?>

</html>
