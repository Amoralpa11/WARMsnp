
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

	<title>Gene results</title>
</head>

<?php


include "navbar.html";				#incluimos la barra de navegación y el head de la pagina
include 'databasecon.php';			#incluimos la página en la que nos conectamos con la base de datos

session_start();

if ($_REQUEST) {
	$_SESSION['gene_page'] = $_REQUEST;
}


$sql_GO = "select GO.GO_name, GO.GO_id
from GO, Gene_Go as gg, Gene as g
where gg.GO_id = GO.GO_id and gg.Gene_id = g.Gene_id and
g.Gene_id like '".$_REQUEST['ref']."'";


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

$rsT_tissue = mysqli_fetch_all($rs_tissue, MYSQLI_ASSOC);

$tissue_name = [];
$tissue_expression = [];
foreach ($rsT_tissue as &$valor) {
    array_push($tissue_name, $valor['name']);
		array_push($tissue_expression, floatval($valor['expression_level']));
}


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

// Aqui van los datos para ramón

$array_manhattan = mysqli_fetch_all($rs_gene,MYSQLI_ASSOC);

$array_manhattan = transpose($array_manhattan);


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

		<h3 style="margin-right: 10px">Gene: <?php print $rsT_gene['hgnc_name'] ?><span class=""> <a href="<?php print "https://www.ensembl.org/Homo_sapiens/Location/View?db=core;g=".$_SESSION['gene_page']['ref'].";r=".$rsT['chr'].":".$rsT_gene['Start_position']."-".$rsT_gene['End_position'] ?>" ><?php print $_SESSION['gene_page']['ref'] ?></a></span> </h3>


		<div class="row" style="margin-top:4%">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="tab">
					<button class="tablinks" onclick="gene_tabs(event, 'GO')" id="defaultOpen">Gene attributes</button>
					<button class="tablinks" onclick="gene_tabs(event, 'SNP')">SNPs</button>
					<button class="tablinks" onclick="gene_tabs(event, 'Tissue">Tissue expression</button>
				</div>
			</div>
		</div>

		<div class="row" style="min-height:62%; margin-bottom:20px">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="tab">
					<div id="GO" class="tabcontent">
						<h4>Gene attributes</h4>

						<p> Location:
							<a href="<?php print "https://www.ensembl.org/Homo_sapiens/Location/View?db=core;g=".$_SESSION['gene_page']['ref'].";r=".$rsT['chr'].":".$rsT_gene['Start_position']."-".$rsT_gene['End_position'] ?>" >
								chr: <?php print $rsT['chr']." : ".$rsT_gene['Start_position']." : ".$rsT_gene['End_position']?>
							</a></p>
							<div>
								<p>GO: <?php
								$link_array = [];
								while ($rsT_Go = mysqli_fetch_assoc($rs_GO)){
									$link_array[] = "<a href ='http://amigo.geneontology.org/amigo/term/".$rsT_Go['GO_id']."'>".$rsT_Go['GO_name']."</a>";
								}
								print implode(", ", $link_array);
								?></p>
							</div>
							<div id="plot" class="tabcontent">
							<h4>Manhattan plot</h4>
							<div class="container-fluid">
								<div class="col-md-13">
									<div class="row">
									 <div class="col-md-4" style="background-color:#F0F0F0;">
										<form id="frm1">
											<b>Filter by P-value</b> <br>
											<div class="row">
												<input type="range" name="pvalue" min="0" max="1" value="1" class="slider" step=0.01 id="pvalue" onchange="updateSlider()">
												<br>
												<div id="sliderAmount"></div>
											</div>

											<b>Filter by the effect of the SNP:</b></p>
											<input type="radio" name="snpeffect" value="protective" onclick='SNPeffect("protective")' id="protective"> Protective
											<input type="radio" name="snpeffect" value="damaging" onclick='SNPeffect("damaging")' id="damaging"> Damaging
											<input type="radio" name="snpeffect" value="damaging" onclick='SNPeffect("both")' id="both"> Both<br></br
											<br>

											<b>Enter a new gene or SNP:</b></br>
											<input type="text" name="snp">
											<input type="submit" value="Submit">
										</form>
									</div>
										<div class="col-md-8">
											<div id="location">
												<script type="text/javascript">
													var locations = <?php echo '["'. implode('", "', $locations) . '"]'?>;
												 </script>
											</div>
											<div id="beta">
												<script type="text/javascript">
													var beta = <?php echo '["'. implode('", "', $beta) . '"]'?>;
													// var debug = document.getElementById("sliderAmount");
													// debug.innerHTML = beta;
												 </script>
											</div>
											<div id="snps">
												<script type="text/javascript">
													var snps = <?php echo '["'. implode('", "', $snps) . '"]'?>;
												 </script>
											</div>
											<div id="pvalues">
												<script type="text/javascript">
													var pvalues = <?php echo '["'. implode('", "', $pvalues) . '"]'?>;
												 </script>
											</div>
											<div id="current_snp">
												<script type="text/javascript">
													var current_snp = <?php echo json_encode($current_snp); ?>;
												 </script>
											</div>
											<div id="chr">
												<script type="text/javascript">
													var chr = <?php echo json_encode($chr);  ?>;
												 </script>
											</div>
											<div id="myDiv"><!-- Plotly chart will be drawn inside this DIV -->
												<script src="./manhattan4.js"> </script>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

						<div id="SNP" class="tabcontent">
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

						<div id="Tissue" class="tabcontent">
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
