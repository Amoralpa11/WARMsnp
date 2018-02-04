<?php
/*
* This page shows the data from the db related to the SNP selected
*/
include "navbar.html";
// include globals.inc.php

$sql = "SELECT SNP.* from SNP WHERE SNP.idCode='".$_REQUEST['query']."'";
$rs = mysqli_query($mysqli, $sql) or print mysqli_error($mysqli);

if (!mysql_num_rows(rs)) { // check if its empty
  print errorPage('NotFound', 'The requested SNP is not available')
} else {
  $data = mysqli_fetch_assoc($rs);
  print headerDBW($_REQUEST['query'])
}
?>


<head>
  <body>
    <h1><?php print $_REQUEST['query']?></h1>
    <table style="width:100%">
      <tr>
        <th>Gene</th>
        <th>Position</th>
        <th>Variant frequency</th>
        <th>Beta</th>
        <th>p value</th>
        <th>Disease</th>
      </tr>
      <tr>
        <td>SELECT Gene_id FROM GENE WHEN SNP.idSNP = $_REQUEST['query']"</td>
        <td>SELECT position FROM SNP WHEN idSNP = $_REQUEST['query']"</td>
        <td>SELECT Frequency FROM Variants WHEN SNP.idSNP = $_REQUEST['query']"</td>
        <td>SELECT beta FROM Variants WHEN SNP.idSNP = $_REQUEST['query']"</td>
        <td>SELECT p-value FROM Variants WHEN idSNP = $_REQUEST['query']"</td>
        <td>SELECT Name FROM Disease WHEN SNP.idSNP = $_REQUEST['query']"</td>
      </tr>
      <!-- <tr>
        <td>Eve</td>
        <td>Jackson</td>
        <td>94</td>
      </tr> -->
    </table>
  </body>
</head>



<?php
include "footer.html";
?>
