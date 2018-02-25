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

# Process input
# Check if this is an ensembl or snp id
# if we are working with SNPs.
if (substr($_REQUEST['query'],0,2) === "rs") {
  include 'databasecon.php';
# To be writen
// print "<h2>we are working with a snp</h2>";
// print var_dump($_REQUEST['query']);
?>
<html>
<head>

    <link rel="stylesheet" href="DataTable/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="DataTable/jquery-2.2.0.min.js"></script>
    <script type="text/javascript" src="DataTable/jquery.dataTables.min.js"></script>
</head>
</html>


<div class="container">
<h1>RESULTS:</h1>
<table border="0" cellspacing="2" cellpadding="4" id="blastTable">
    <thead>
        <tr>
          <th>SNP id</th>
          <th>Chromosome</th>
          <th>Gene</th>
          <th>Position</th>
          <th>Variant frequency</th>
          <th>Beta</th>
          <th>p value</th>
          <th>Disease</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result_arr = preg_split("/\s+/", $_REQUEST['query']);
        foreach (array_values($result_arr) as $rr) {
          $chromosome = "probably 1";
          $gene = "hehe";
          $position = "don't know";
          $frequency = "not too high";
          $beta = "positive";
          $pval = "so low";
          $disease = "you fucked";

          ?>
            <td> <?php print $rr ?> </td>
            <td> <?php print $chromosome ?> </td>
            <td> <?php print $gene ?> </td>
            <td> <?php print $position ?> </td>
            <td> <?php print $frequency ?> </td>
            <td> <?php print $beta ?> </td>
            <td> <?php print $pval ?> </td>
            <td> <?php print $disease ?> </td>
          </tr>
          <?php
        }

        ?>
    </tbody>
</table>
</div>
<?php
# if we are working with genes.
} else {
    # To be writen
    print "<h2>we are working with a gene!</h2>";
}
?>

<script type="text/javascript">
$(document).ready(function () {
    $('#blastTable').DataTable();
});
</script>

<?php
include "footer.html";
?>

</html>
