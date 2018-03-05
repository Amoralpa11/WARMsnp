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

$rs_plot1 = mysqli_query($mysqli, $sql_plot1) or print mysqli_error($mysqli);
$rs_plot2 = mysqli_query($mysqli, $sql_plot2) or print mysqli_error($mysqli);

$rsT_plot1 = mysqli_fetch_all($rs_plot1,MYSQLI_ASSOC);

$rsT_plot2 = mysqli_fetch_all($rs_plot2,MYSQLI_ASSOC);

$rsT_plot = $rsT_plot1 + $rsT_plot2;

function cmp($a, $b)
{
    if ($a["pos"] == $b["pos"]) {
        return 0;
    }
    return ($a["pos"] < $b["pos"]) ? -1 : 1;
}

usort($rsT_plot,"cmp");

$rsT_plot = transpose($rsT_plot);

$locations = $rsT_plot['pos'];
$beta = $rsT_plot['beta'];
$snps = $rsT_plot['idSNP'];
$pvalues = $rsT_plot['p_value'];
$log10_p_values = [];
// var_dump($pvalues);
foreach ($pvalues as &$p) {
  array_push($log10_p_values, floatval(log10(floatval($p))));
};

// var_dump($log10_p_values);
?>


<div class="container-fluid" style="margin-top:5%; margin-bottom:5%">
  <div class="row">
    <div class="col-md-11">
    <div class="row" style="margin-left:5%">
      <div class="col-md-3" style="background-color:#F0F0F0;">
        <form id="frm1">
          <h4 style="margin-bottom:5%; text-align:center"> Advanced search </h4>
          <b>Filter by P-value</b> <br>
          <input type="range" name="pvalue" min="0" max="1" value="1" class="slider" class="slider" step=0.01 id="pvalue" onchange="updateSlider()" style="width:85%; height:5px; background-color:#d3d3d3; outline:none; opacity:0.7; margin-bottom:5%">
          <br>
          <div id="sliderAmount"></div>

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
      <div class="col-md-9">
        <div id="myDiv"><!-- Plotly chart will be drawn inside this DIV --></div>
        <script src="./manhattan4.js"></script>
      </div>
    </div>
  </div>
</div>
</div>


<?php include "footer.html"; ?>
</html>
