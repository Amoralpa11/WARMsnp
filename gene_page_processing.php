<?php  
session_start();
include 'databasecon.php';

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

$rsT_GO = mysqli_fetch_all($rs_GO,MYSQLI_ASSOC);

$_SESSION['gene_page']['rsT_GO'] = $rsT_GO;

$rsT_tissue = mysqli_fetch_all($rs_tissue,MYSQLI_ASSOC);
$_SESSION['gene_page']['rsT_tissue'] = $rsT_tissue;



$rsT_gene = mysqli_fetch_assoc($rs_gene);


$_SESSION['gene_page']['rsT_gene'] = $rsT_gene;

// Aqui van los datos para ramón 

$rsT_snp = mysqli_fetch_all($rs_snp,MYSQLI_ASSOC);

$_SESSION['gene_page']['rsT_snp'] = $rsT_snp;

header('Location: gene_page.php');