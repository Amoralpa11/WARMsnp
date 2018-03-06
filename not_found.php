<?php 

include 'nabvar.html';

$ref = $_REQUEST['ref'];

 if (strtoupper(substr( $ref, 0, 2 )) === "RS"){
	$ref_type = "SNP";
} else if (strtoupper(substr( $ref, 0, 2 )) === "ENS"){
	$ref_type = "gene";
}

 ?>



 <?php 

 include 'footer.html'