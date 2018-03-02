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
			<h3 style="margin-right: 10px">SNP: </h3><h4> <a href=<?php print "https://www.ncbi.nlm.nih.gov/SNP/snp_ref.cgi?searchType=adhoc_search&type=rs&rs=".$_SESSION['SNP_page']['ref'] ?> ><?php print $_SESSION['SNP_page']['ref'] ?></a></h4>
		</div>

		<div>

			<div class="row">
				<p>Location: chr: <?php print $rsT['chr']." : ".$rsT['pos']?></p>
				<p>  </p>
			</div>
			<div class="row">
				
				<p>Gene: <?php 
				$link_array = [];
				foreach ($rsT_gene['Gene_id'] as $gene){
				$link_array[] = "<a href ='gene_page.php?ref=$gene'>$gene</a>";
				}

				print implode(", ", $link_array);
				?></p>

			</div>
			<div class="row">
				<p>Main allele: <?php print " ".$rsT['Main_allele']?></p>
			</div>
			<div class="row">
				<p>Frequency: <?php print $rsT['Frequency']?></p>
			</div>
			<div class="row">
				<p>Variant: <?php print $rsT['Sequence']?></p>
			</div>
			<div class="row">
				<p>p-value: <?php print $rsT['p_value']?></p>
			</div>
			<div class="row">
				<p>beta: <?php print $rsT['beta']?></p>
			</div>
			<?php 

			if (count($rsT_disease) != 0) {
				
				print 	"<div>
						<strong>Other diseases: </strong>";

				foreach ($rsT_disease as $row) {
					print "<p>".$row["Name"]."</p>";
				}
				

				print "</div>";
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

$rsT_plot = transpose($rsT_plot);

print_r($rsT_plot);

?>