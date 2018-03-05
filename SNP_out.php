<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="scss/custom.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
  <script src="src/table2csv.js"></script>
  <script src="//code.jquery.com/jquery.min.js"></script>

  <link rel="stylesheet" href="DataTable/jquery.dataTables.min.css"/>
  <script type="text/javascript" src="DataTable/jquery-2.2.0.min.js"></script>
  <script type="text/javascript" src="DataTable/jquery.dataTables.min.js"></script>

  <link rel="icon" href="Home_images/flame.png">
  <title>Results Table</title>
</head>


<html>
<link rel="stylesheet" type="text/css" href="scss/loading_page.css">

<head>

  <script>
  $(document).ready(function () {
   $('Table').each(function () {
       var $table = $(this);

       var $button = $("<button type='button'>");
       $button.text("Download");
       $button.insertAfter($table);

       $button.click(function () {
           var csv = $table.table2CSV({
               delivery: 'value'
           });
           window.location.href = 'data:text/csv;charset=UTF-8,'
           + encodeURIComponent(csv);
       });
   });
})
  </script>

  <link rel="stylesheet" href="DataTable/jquery.dataTables.min.css"/>
  <script type="text/javascript" src="DataTable/jquery-2.2.0.min.js"></script>
  <script type="text/javascript" src="DataTable/jquery.dataTables.min.js"></script>
</head>

<body>

  <?php
  session_start();
  $rst = $_SESSION['SNP_out'];
  include "navbar.html";
  ?>

  <div id="loader" class="loader" style="width:100%; height:100%; background-color:white; margin:0; text-align: center; position: fixed; top: 0px;">
    <!-- <div id="loader" class="loader" style="width:100%;height:100%;background-color:white;margin:0;position:fixed;text-align: center;vertical-align: middle;position: relative;top: 50%;"> -->
      <div style="position:absolute;top:50%; left:50%; transform: translate(-50%, -50%);">
        <img src="images/ajax-loader.gif" alt="Be patient..." style="vertical-align: middle">
      </div>
      <div style="position:absolute;top:55%; left:50%; transform: translate(-50%, -50%);">Hey there! we are processing your request, the results will be displayed soon.</div>
      <div id="counter" style="position:absolute;top:60%; left:50%; transform: translate(-50%, -50%);">The page is loading, please wait...</div>

    </div>

    <div class="container" style="min-height:75%; margin-bottom:20px">
      <h3 style="margin-top:2.5%">RESULTS:</h3>
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

          <?php foreach ($rst as $rsF){

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
                print "<td><a target='_blank' href='gene_page_processing.php?ref=$gene'>$gene</a></td>";
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
