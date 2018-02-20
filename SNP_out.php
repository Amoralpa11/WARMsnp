<!-- Page Content -->
<?php
#start Session to hold input data
session_start();
$_SESSION['queryData'] = $_REQUEST;

if (!isset($_SESSION['queryData']))
    header('Location: blast_index.php');


include "navbar.html";
?>

<?php
# Check if input comes from an uploaded file
# If the data comes from a file get the content from the file
if ($_FILES['uploadFile']['name']) {
    $_REQUEST['query']=  file_get_contents($_FILES['uploadFile']['tmp_name']);
}

if ($_REQUEST['maxpval'] > 1 || $_REQUEST['maxpval'] <= 0) {
?>
<title>Error: P value out of range</title>
<h2>Error: The P value set is out of range, please set it between 0 and 1.</h2>

<?php
}

if ($_REQUEST['$minfreq'] < 0 || $_REQUEST['maxfreq'] > 1) { ?>
    <html>
    <head>
        <title>Error: frequency value out of range</title>
    </head>
    <body>
    <h2>Error: The frequency range set is out of range, please set it between 0 and 1.</h2>
    </body>
    </html>

    <?php
}


# if the query is empty show an error saying that there is no request

if (!$_REQUEST['query']) { ?>
    <html>
    <head>
        <title>Error: No request</title>
    </head>
    <body>
    <h2>Error: Received request was empty, please input data for the query.</h2>
    </body>
    </html>
    <?php
} else {
# Process input
# Check if this is an ensembl or snp id
# if we are working with SNPs.
if (substr($_REQUEST['query'],0,2) === "rs") {
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
          <th>Chromosome</th>
          <th>Gene</th>
          <th>Position</th>
          <th>Variant frequency</th>
          <th>Beta</th>
          <th>p value</th>
          <th>Disease</th>
          <th>SIFT prediction</th>
          <th>SIFT score</th>
          <th>PolyPhen prediction</th>
          <th>Polyphen score</th>
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
          $sift = "missense";
          $sift_score = "999";
          $polyphen =  "buff";
          $polyphen_score = "buff2";

          ?>
          <tr>
            <td> <?php print $gene ?> </td>
            <td> <?php print $position ?> </td>
            <td> <?php print $chromosome ?> </td>
            <td> <?php print $frequency ?> </td>
            <td> <?php print $beta ?> </td>
            <td> <?php print $pval ?> </td>
            <td> <?php print $disease ?> </td>
            <td> <?php print $sift ?> </td>
            <td> <?php print $sift_score ?> </td>
            <td> <?php print $polyphen ?> </td>
            <td> <?php print $polyphen_score ?> </td>

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
