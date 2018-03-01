<?php 


include "navbar.html";				#incluimos la barra de navegación y el head de la pagina
include 'databasecon.php';			#incluimos la página en la que nos conectamos con la base de datos 

session_start();

?>
<h1>Pagina del gen <?php print $_REQUEST['ref'] ?></h1>


<?php 
// si seleccionamso snp 40000 arrgiba y abajo con p-value beta value, posiciones. 
// 3 arrays asociativos en el que la clave sea el id del snp
 