<?php


include "navbar.html";				#incluimos la barra de navegación y el head de la pagina
include 'databasecon.php';			#incluimos la página en la que nos conectamos con la base de datos

session_start();

$sql_disease = "select d.Name
from Disease as d, SNP as s, SNP_disease as sd
where sd.idDisease = d.idDisease and sd.idSNP = s.idSNP and s.idSNP like '".$_REQUEST['ref']."'";

$sql_gene = "select g.Gene_id, g.Chromosome
from 	Gene as g, Gene_has_SNP as gs,
		SNP as s
where	g.Gene_id = gs.Gene_Gene_id and s.idSNP = gs.SNP_idSNP and s.idSNP like '".$_REQUEST['ref']."'";

$sql = "select pos, Main_allele, chr,
            Frequency, Sequence, p_value, beta, predicted_consequences,s.idSNP
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


$rsT = mysqli_fetch_assoc($rs);

$rsT_gene = mysqli_fetch_all($rs_gene,MYSQLI_ASSOC);

$rsT_gene = transpose($rsT_gene);

if (is_null($rsT['chr'])){
	$rsT['chr'] = $rsT_gene['Chromosome'][0];
}

// $_SESSION['snp_page']['rsT_disease'] = $rsT_disease;
// $_SESSION['snp_page']['rsT'] = $rsT;
// $_SESSION['snp_gene']['rsT_gene'] = $rsT_gene;

$main_array['disease'] = $rsT_disease;
$main_array['snp'] = $rsT;
$main_array['gene'] = $rsT_gene;

print_r($main_array);

$string = http_build_query(array('main_array' => $main_array));

print $string;

header('Location: SNP_page.php?'.$string);