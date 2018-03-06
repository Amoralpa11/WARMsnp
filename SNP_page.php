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


if(!$rsT = mysqli_fetch_assoc($rs)){
  header('Location: not_found.php?ref='.$_REQUEST['ref']);
}

$rsT_gene = mysqli_fetch_all($rs_gene,MYSQLI_ASSOC);

$rsT_gene = transpose($rsT_gene);




if (is_null($rsT['chr'])){
	$rsT['chr'] = $rsT_gene['Chromosome'][0];
}

#DATOS PARA RAMON

$sql_plot1 = "select pos, beta, v.p_value, s.idSNP
from 	SNP as s, Variants as v
where	s.pos between ".$rsT['pos']."-40000 and ".$rsT['pos']."+40000
and chr = ".$rsT['chr']."  and s.idSNP = v.idSNP;";

$sql_plot2 = "select pos, beta, p_value, s.idSNP
from 	SNP as s, Variants as v, Gene_has_SNP as gs, Gene as g
where	s.pos between ".$rsT['pos']."-40000 and ".$rsT['pos']."+40000
and Chromosome = ".$rsT['chr']." and s.idSNP = v.idSNP and
s.idSNP = gs.SNP_idSNP and g.Gene_id = gs.Gene_Gene_id;";

// print $sql_plot1 ;
// print $sql_plot2 ;
// print "<br><br>";

$rs_plot1 = mysqli_query($mysqli, $sql_plot1) or print mysqli_error($mysqli);

$rs_plot2 = mysqli_query($mysqli, $sql_plot2) or print mysqli_error($mysqli);

$rsT_plot1 = mysqli_fetch_all($rs_plot1,MYSQLI_ASSOC);

$rsT_plot2 = mysqli_fetch_all($rs_plot2,MYSQLI_ASSOC);

$rsT_plot = array_merge($rsT_plot1,$rsT_plot2);


function cmp($a, $b)
{
	 if ($a["pos"] == $b["pos"]) {
			 return 0;
	 }
	 return ($a["pos"] < $b["pos"]) ? -1 : 1;
}

usort($rsT_plot,"cmp");

$rsT_plot = transpose($rsT_plot);


$locations_pre = $rsT_plot['pos'];
$locations = [];
foreach ($locations_pre as &$pos){
 array_push($locations, intval($pos));
}

$beta_pre = $rsT_plot['beta'];
$beta = [];
foreach ($beta_pre as &$i){
 array_push($beta, floatval($i));
}

$snps_pre = $rsT_plot['idSNP'];
$snps = [];
foreach ($snps_pre as &$i){
 array_push($snps, $i);
}

$pvalues_pre = $rsT_plot['p_value'];
$pvalues = [];
foreach ($pvalues_pre as &$p){
 array_push($pvalues, floatval($p));
}

$current_snp = $_SESSION['SNP_page']['ref'];
$chr =  $rsT['chr'];
?>


<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
  .slider {
    width: 75%;
    height: 5px;
    background-color: #3d3d3d;
    outline: none;
    opacity: 0.7;
		margin-left: 5%;
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
		<br>
		<h3 style="margin-left: 10px"><img src="Home_images/dna.svg" alt="dna icon" width="30" height="30"> SNP: <a href=<?php print "https://www.ncbi.nlm.nih.gov/SNP/snp_ref.cgi?searchType=adhoc_search&type=rs&rs=".$_SESSION['SNP_page']['ref'] ?>><?php print $_SESSION['SNP_page']['ref'] ?></a></h3>
		<div class="row" style="margin-top:2%">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="tab">
					<button class="tablinks" onclick="gene_tabs(event, 'attributes')" id="defaultOpen">SNP attributes</button>
					<button class="tablinks" onclick="gene_tabs(event, 'plot')">Manhattan plot</button>
				</div>
			</div>
      <div class="col-md-1"></div>
		</div>

		<div class="row" style="min-height:62%; margin-bottom:20px">
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<div class="tab">
					<div id="attributes" class="tabcontent">
						<header class="w3-container" style="padding-top: 22px;"><h4><b><i class="fa fa-eye"></i> SNP attributes</h4></b>
						<div class="row">
						<div class="col-md-5" style="border: 2px solid; border-radius:25px; border-color:#343a3f; background-color:#ffab63; margin-right:5%; margin-left: 5%">
							<header class="w3-container"><h5 style="margin-top:5%; text-align: center"><b>Genomic attributes:</h5></b></header>
							<hr>
							<p style="text-align: left; margin-left: 5%; margin-right: 5%"><b>Location: </b><span style="float: right;"> chr- <?php print $rsT['chr']." : ".$rsT['pos']?></span></p>
							<p style="text-align: left; margin-left: 5%; margin-right: 5%"><b>Gene: </b><span style="float: right;"> <?php
							$link_array = [];
							foreach ($rsT_gene['Gene_id'] as $gene){
							$link_array[] = "<a href ='gene_page.php?ref=$gene'>$gene</a>";
							}
							print implode(", ", $link_array);
							?></p></span>
						</div>
						<div class="col-md-5" style="border: 2px solid; border-radius:25px; border-color:#343a3f; background-color:#ffab63; margin-right:5%; ">
							<header class="w3-container"><h5 style="margin-top:5%; text-align: center"><b>SNP attributes:</b></h5></header>
							<hr>
							<p style="text-align: left; margin-left: 5%; margin-right: 5%"><b>Main allele: </b><span style="float: right;"><?php print " ".$rsT['Main_allele']?></p></span>
							<p style="text-align: left; margin-left: 5%; margin-right: 5%"><b>Variant: </b><span style="float: right;"><?php print $rsT['Sequence']?></p></span>
							<p style="text-align: left; margin-left: 5%; margin-right: 5%"><b>Main allele frequency: </b><span style="float: right;"><?php print $rsT['Frequency']?></p></span>
						</div>
						</div>
						<div class="row">
						<div class="col-md-5" style="border: 2px solid; border-radius:25px; border-color:#343a3f; background-color:#ffab63; margin-right:5%; margin-left: 5%; margin-top: 5%; margin-bottom: 5%">
							<header class="w3-container"><h5 style="margin-top:5%; text-align: center"><b>CAD & MI association:</b></h5></header>
							<hr>
							<p style="text-align: left; margin-left: 5%; margin-right: 5%"><b>p-value: </b><span style="float: right;"><?php print $rsT['p_value']?></p></span>
							<p style="text-align: left; margin-left: 5%; margin-right: 5%"><b>beta: </b><span style="float: right;"><?php print $rsT['beta']?></p></span>
						</div>
							<div class="col-md-5" style="border: 2px solid; border-radius:25px; border-color:#343a3f; background-color:#ffab63; margin-top: 5%; margin-bottom: 5%">
								<header class="w3-container"><h5 style="margin-top:5%; text-align: center"><b>Other related disease:</b></h5></header>
								<hr>
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
							<h4>Manhattan plot</h4>
							<div class="container-fluid">
							<div class="col-md-13">
								<div class="row">
						     <div class="col-md-3" style="background-color:#F0F0F0;">
						      <form id="frm1">
                    <h5>Advanced search:</h5>
						        <b>Filter by P-value</b> <br>
										<div class="row">
							        <input type="range" name="pvalue" min="0" max="1" value="1" class="slider" step=0.01 id="pvalue" onchange="updateSlider()">
							        <br>
							        <div id="sliderAmount"></div>
										</div>

						        <b>Filter by the effect of the SNP:</b></p>
						        <input type="radio" name="snpeffect" value="protective" onclick='SNPeffect("protective")' id="protective"> Protective
						        <input type="radio" name="snpeffect" value="damaging" onclick='SNPeffect("damaging")' id="damaging"> Damaging<br>
						        <input type="radio" name="snpeffect" value="damaging" onclick='SNPeffect("both")' id="both"> Both<br>
						        <br>

						        <b>Enter a new gene or SNP:</b></br>
						        <input type="text" name="snp">
						        <input type="submit" value="Submit" style="border-radius: 12px;background-color: #e9e9e6">
						      </form>
						    </div>
						      <div>
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
