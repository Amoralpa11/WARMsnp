<?php 


include "navbar.html";				#incluimos la barra de navegación y el head de la pagina
include 'databasecon.php';			#incluimos la página en la que nos conectamos con la base de datos 

session_start();

$_SESSION['SNP_page'] = $_REQUEST;



$sql = "select d.Name as disease, g.Gene_id, Chromosome, Start_position, End_position,
			hgnc_name, GO_name, s.idSNP, pos, Main_allele, chr, 
            t.name, Frequency, Sequence, p_value, beta, predicted_consequences,
            expression_level
from 	Disease as d, Gene as g,  Gene_Go as gg, Gene_has_SNP as gs, 
		Gene_Tissue as gt, GO, SNP as s, SNP_disease as sd, tissue as t,
        Variants as v
where	g.Gene_id = gs.Gene_Gene_id and s.idSNP = gs.SNP_idSNP and
		g.Gene_id = gt.idGene and t.Tissue_id = gt.Tissue_id and
        gg.GO_id = GO.GO_id and gg.Gene_id = g.Gene_id and
        v.idSNP = s.idSNP and sd.idSNP = s.idSNP and 
        sd.idDisease = d.idDisease and s.idSNP like '".$_REQUEST['ref']."'";

print $sql."<br>";

$rs = mysqli_query($mysqli, $sql) or print mysqli_error($mysqli);

$rsT = mysqli_fetch_all($rs, MYSQLI_ASSOC);

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

$rsT = transpose($rsT);

foreach ($rsT as $key => $field) {
	$rsT[$key] = array_unique($rsT[$key]);
}

print_r($rsT)




?>

<div class="container" style="padding-top: 25px">
	<div>
		<div class="row">
			<h3 style="margin-right: 10px">SNP: </h3><h4> <?php print $_SESSION['SNP_page']['ref'] ?></h4>
		</div>

		<div>

			<div class="row">
				<p>Location: <?php print $rsT['pos'][0] ?></p>
				<p>  </p>
			</div>
			<div class="row">
				
				<p>Gene: <?php 
				$link_array = [];
				foreach ($rsT['Gene_id'] as $gene){
				$link_array[] = "<a href ='gene_page.php?ref=$gene'>$gene</a>";
				}

				print implode(", ", $link_array);
				?></p>

			</div>
			<div class="row">
				<p>Main allele:</p>
				<p>.............</p>
			</div>
			<div class="row">
				<p>Frequency:</p>
				<p>.............</p>
			</div>
			<div class="row">
				<p>Variant:</p>
				<p>.............</p>
			</div>
			<div class="row">
				<p>p-value:</p>
				<p>.............</p>
			</div>
			<div class="row">
				<p>beta:</p>
				<p>.............</p>
			</div>


		</div>
	</div>
</div>

<!-- 
si seleccionamso snp 40000 arrgiba y abajo con p-value beta value, posiciones. 
3 arrays asociativos en el que la clave sea el id del snp
 -->