<!-- Page Content -->
<?php
#start Session to hold input data
session_start();
$_SESSION['queryData'] = $_REQUEST;

if (!isset($_SESSION['queryData']))
    header('Location: WARMsnp_home.php');



include "navbar.html";

# Check if input comes from an uploaded file
# If the data comes from a file get the content from the file
if ($_FILES['uploadFile']['name']) {
    $_REQUEST['query']=  file_get_contents($_FILES['uploadFile']['tmp_name']);
}

/*We are going to segregate the user's query in gene ids and
snp ids to process them in a different way*/
$query_array = preg_split("/\s+/", $_REQUEST['query']);

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


if ($SNP_array) {
    $ORconds = [];
    foreach (array_values($SNP_array) as $ref) {
        $ORconds[] = "s.idSNP like '".$ref."'";
    }
    $ANDconds[] = "(" . join(" OR ", $ORconds) . ")";
}

if ($Gene_array) {

      $ORconds = [];
    foreach (array_values($Gene_array) as $ref) {
        $ORconds[] = "g.Gene_id like '".$ref."'";
    }
    $ANDconds[] = "(" . join(" OR ", $ORconds) . ")";

}



$sql_snp = "select   s.chr, s.pos,
                  v.Frequency, v.beta, v.p_value,
                  s.Main_allele, s.idSNP, v.Sequence
        from      SNP as s, Variants as v
        where     v.idSNP = s.idSNP
                  and
                  ". join(" AND ", $ANDconds);

$sql_gene = "select   g.Chromosome,
                   s.idSNP, g.Gene_id, s.pos,v.Frequency, v.beta, v.p_value,
                  s.Main_allele, s.idSNP, v.Sequence
        from      SNP as s, Gene as g ,
                  Gene_has_SNP as gs, Variants as v
        where     s.idSNP = gs.SNP_idSNP and
                  gs.Gene_Gene_id = g.Gene_id and
                  v.idSNP = s.idSNP and
                  ". join(" AND ", $ANDconds);
// print $sql_gene;

$rs_snp = mysqli_query($mysqli, $sql_snp) or print "snp: ".mysqli_error($mysqli);
$rs_gene = mysqli_query($mysqli, $sql_gene) or print "gene: ".mysqli_error($mysqli);

$rst_snp = mysqli_fetch_all($rs_snp,MYSQLI_ASSOC);
// print_r($rst_snp);
// print "<br>";
$rst_gene = mysqli_fetch_all($rs_gene,MYSQLI_ASSOC);
 // print_r($rst_gene);

foreach ($rst_snp as $row) {
  $rst_both[$row['idSNP']] = $row;
}

foreach ($rst_gene as $row) {
  // El siguiente codigo hace que se muestre el número de genes en caso de que el gen tenga más de uno

  if (!isset($rst_both[$row['idSNP']])) {
    $rst_both[$row['idSNP']] = $row;
  } else{
    $rst_both[$row['idSNP']]['Gene_id'][] = $row['idSNP']['Gene_id'];
  }

}

// print_r($rst_both);



?>
<html>
<link rel="stylesheet" type="text/css" href="scss/loading_page.css">

<head>

    <link rel="stylesheet" href="DataTable/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="DataTable/jquery-2.2.0.min.js"></script>
    <script type="text/javascript" src="DataTable/jquery.dataTables.min.js"></script>
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

<div class="container" style="min-height:75%; margin-bottom:20px">
<h1 style="margin-top:2.5%">RESULTS:</h1>
<table border="0" cellspacing="2" cellpadding="4" id="Table" style="margint-bottom:5%">
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

        <?php foreach ($rst_both as $rsF){

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
            <?php  print "<td><a target='_blank' href='SNP_page.php?ref=$SNP_id'>   $SNP_id  </a></td>" ?>
            <td> <?php print $chromosome ?> </td>
            <td> <?php print $position ?> </td>
            <?php
              if (count($gene) == 1 ){
              print "<td><a target='_blank' href='gene_page.php?ref=$gene'>$gene</a></td>";
              } elseif (count($gene) > 1) {
                print "<td><a target='_blank' href='SNP_page.php?ref=$SNP_id'>".count($rsF['Gene_id'])."</a></td>";
              }else{
                print "<td></td>";
              }

              ?>
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

<script>
$(document).ready(function () {
    $('#Table').DataTable();
});
</script>

<script>
$(window).load(function() {      //Do the code in the {}s when the window has loaded
  $("#loader").fadeOut("fast");
});
</script>

<script>
 function() {
 setInterval(function() {
 var someval = Math.floor(Math.random() * 100);
  $('#counter').text('Test' + someval);
}, 1000);  //Delay here = 5 seconds
};
</script>

<?php
include "footer.html";
?>
</body>
</html>
