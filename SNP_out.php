<!-- Page Content -->
<?php
#start Session to hold input data
session_start();
$_SESSION['queryData'] = $_REQUEST;

if (!isset($_SESSION['queryData']))
    header('Location: WARMsnp_home.php');



include "navbar.html";
?>

<?php
# Check if input comes from an uploaded file
# If the data comes from a file get the content from the file
if ($_FILES['uploadFile']['name']) {
    $_REQUEST['query']=  file_get_contents($_FILES['uploadFile']['tmp_name']);
}

/*We are going to segregate the user's query in gene ids and 
snp ids to process them in a different way*/
$query_array = preg_split("/\s+/", $_REQUEST['query']);

$SNP_array = [];
$Gene_array = [];

foreach ($query_array as $ref){
  if (strtoupper(substr( $ref, 0, 2 )) === "RS"){
    $SNP_array[] = $ref;
  }else if (strtoupper(substr( $ref, 0, 3 )) === "ENS" ){
    $Gene_array[] = $ref;
  }
}
  include 'databasecon.php';

### Here we are going to build the conditionals for the 
### mysql query from the user input. 

if ($_REQUEST['minbeta'] != "" ) {
$ANDconds[] = "v.beta > ".$_REQUEST['minbeta'];
}

if ($_REQUEST['maxpval'] != 1 and $_REQUEST['maxpval'] != "" ) {
  $ANDconds[] = "v.p_value < ".$_REQUEST['maxpval'];
}

if ($_REQUEST['minfreq'] != 0 and $_REQUEST['minfreq'] != "") {
  $ANDconds[] = "v.Frequency > ".$_REQUEST['minfreq'];
}

if ($_REQUEST['maxfreq'] != 1 and $_REQUEST['maxfreq'] != "") {
  $ANDconds[] = "v.Frequency < ".$_REQUEST['maxfreq'];
}
#########################################################
#########################################################

#########################################################
#########################################################
##
## Here we are going to concatenate the conditions to re-
## trieve all the ids that the user has provided. 


if ($_REQUEST['query']) {
    $ORconds = [];
    foreach (array_values($SNP_array) as $ref) {
        $ORconds[] = "s.idSNP like '".$ref."'";
    }
    $ANDconds[] = "(" . join(" OR ", $ORconds) . ")";
}

$sql = "select   s.chr, g.Chromosome, s.pos,
                  v.Frequency, v.beta, v.p_value,
                  s.Main_allele, s.idSNP, g.Gene_id, v.Sequence
        from      SNP as s, Gene as g ,
                  Gene_has_SNP as gs, Variants as v 
        where     s.idSNP = gs.SNP_idSNP and
                  gs.Gene_Gene_id = g.Gene_id and
                  v.idSNP = s.idSNP
                  and
                  ". join(" AND ", $ANDconds);

$rs = mysqli_query($mysqli, $sql) or print mysqli_error($mysqli);

?>
<html>
<head>

    <link rel="stylesheet" href="DataTable/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="DataTable/jquery-2.2.0.min.js"></script>
    <script type="text/javascript" src="DataTable/jquery.dataTables.min.js"></script>
</head>
<body>


<div class="container">
<h1>RESULTS:</h1>
<table border="0" cellspacing="2" cellpadding="4" id="blastTable">
    <thead>
        <tr>
          <th>SNP Id</th>
          <th>Chromosome</th>
          <th>Position</th>
          <th>Gene</th>
          <th>Main allele</th>
          <th>Mutation</th>
          <th>Variant frequency</th>
          <th>Beta</th>
          <th>p value</th>
        </tr>
    </thead>
    <tbody>

        <?php while ($rsF = mysqli_fetch_assoc($rs)) {

          if (isset($rsF['Chromosome'])) {
            $chromosome = $rsF['Chromosome'];
          } else if (isset($rsF['chr'])) {
            $chromosome = $rsF['chr'];
          }
          $SNP_id =  $rsF['idSNP'];
          $Main_allele =  $rsF['Main_allele'];
          $variant_allele =  $rsF['Sequence'];
          $gene = $rsF['Gene_id'];
          $position = $rsF['pos'];
          $frequency = $rsF['Frequency'];
          $beta = $rsF['beta'];
          $pval = $rsF['p_value'];

          ?>
          <tr>
            <?php  print "<td><a href='SNP_page.php?ref=$SNP_id'>   $SNP_id  </a></td>" ?>
            <td> <?php print $chromosome ?> </td>
            <td> <?php print $position ?> </td>
            <?php  print "<td><a href='SNP_page.php?ref=$gene'>$gene</a></td>" ?>
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
<?php
# if we are working with genes.


#############################################################
#############################################################
################                                  ###########
################        Trabajar con genes        ###########
################                                  ###########
#############################################################
#############################################################

?>

<script type="text/javascript">
$(document).ready(function () {
    $('#blastTable').DataTable();
});
</script>

<?php
include "footer.html";
?>
</body>
</html>
