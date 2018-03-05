
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

// print_r($_SESSION['gene_page']);

$rsT_GO = $_SESSION['gene_page']['rsT_GO'];
$rsT_tissue = $_SESSION['gene_page']['rsT_tissue'];
$rsT_gene = $_SESSION['gene_page']['rsT_gene'];
$rsT_snp = $_SESSION['gene_page']['rsT_snp'];

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

$manhattan = transpose($rsT_snp);

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

		<h3 style="margin-left: 7%; margin-top:20px">Gene: <?php print $rsT_gene['hgnc_name'] ?><span class=""> <a href="<?php print "https://www.ensembl.org/Homo_sapiens/Location/View?db=core;g=".$_SESSION['gene_page']['ref'].";r=".$rsT_gene['Chromosome'].":".$rsT_gene['Start_position']."-".$rsT_gene['End_position'] ?>" ><?php print $_SESSION['gene_page']['ref'] ?></a></span> </h3>


		<div class="row" style="">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="tab">
					<button class="tablinks" onclick="gene_tabs(event, 'London')" id="defaultOpen">Gene attributes</button>
					<button class="tablinks" onclick="gene_tabs(event, 'Paris')">SNPs</button>
					<button class="tablinks" onclick="gene_tabs(event, 'Tokyo')">Tissue expression</button>
				</div>
			</div>
		</div>

		<div class="row" style="min-height:62%; margin-bottom:20px">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="tab">
					<div id="London" class="tabcontent">
						<h4>Gene attributes</h4>

						<p> Location:
							<a href="<?php print "https://www.ensembl.org/Homo_sapiens/Location/View?db=core;g=".$_SESSION['gene_page']['ref'].";r=".$rsT_gene['Chromosome'].":".$rsT_gene['Start_position']."-".$rsT_gene['End_position'] ?>" style="color: #000000">chr: <?php print $rsT_gene['Chromosome']." : ".$rsT_gene['Start_position']." : ".$rsT_gene['End_position']?></a>
								
							</p>


							<div>
								<p style="color: #000000">GO: <?php
								$link_array = [];
								foreach($rsT_GO as $row){
									$link_array[] = "<a href ='http://amigo.geneontology.org/amigo/term/".$row['GO_id']."'>".$row['GO_name']."</a>";
								}

								print implode(", ", $link_array);
								?></p>

							</div>

						</div>

						<div id="Paris" class="tabcontent">
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

									<?php

									foreach ($rsT_snp as $rsF){

										$SNP_id =  $rsF['idSNP'];
										$Main_allele =  $rsF['Main_allele'];
										$variant_allele =  $rsF['Sequence'];
										$position = $rsF['pos'];
										$frequency = $rsF['Frequency'];
										$beta = $rsF['beta'];
										$pval = $rsF['p_value'];

										?>
										<tr>
											<?php  print "<td><a target='_blank' href='SNP_page_processing.php?ref=$SNP_id'>   $SNP_id  </a></td>" ?>
											<td> <?php print $position ?> </td>
											<td> <?php print $Main_allele ?> </td>
											<td> <?php print $variant_allele ?> </td>
											<td> <?php print 1 - $frequency ?> </td>
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
							<table border="0" cellspacing="2" cellpadding="4" id="tissueTable">
								<thead>
									<tr>
										<th>Tissue</th>
										<th>Expression level (tpm)</th>
									</tr>
								</thead>
								<tbody>

									<?php

									foreach ($rsT_tissue as $row){

										?><tr><?php
										foreach ($row as $field) {

											?>

											<td><?php print $field ?></td>

											<?php
										}
										?><tr><?php
									}

									?>

								</tbody>
							</table>

						</div></div>
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
