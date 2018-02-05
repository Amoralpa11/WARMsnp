<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php
include "navbar.html";
// define variables and set to empty values
?>

<!-- Page Content -->
<html>
    <head>
        <title>WARMsnp</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h2>WARMsnp</h2>
        <p>WARMsnp is a web app designed by and for researchers. It is aimed to ease the SNP information search, it allows you to get SNP related information consolidated from several other web sties.</p>

        <form action="psnp.php" id="input" name="input" method="GET" enctype="multipart/form-data">
            <p><em>Enter gene ensembl id or snp id:</em></p>
            <textarea name="query" cols="40" rows="3" placeholder="ensemble id or snp id" style="margin-left: 1%;"></textarea><br>
            <span class="error"><?php echo $nameErr?></span>

            <input name="uploadFile" type="file" style="margin-left: 1%;"><br>
            <br>
            <!-- <input type="submit" value="Send data"> <input type="reset" value="Clear data"> -->

        <!-- </form> -->

    <!-- </body>
    <head>
      <body> -->
        <h3>Advanced search</h3>
        <p>Select the parameters to further filter the search:</p>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>beta</label>
                    <p>
                        Higher than: <input type="text" name="minbeta" value="<?php print $_SESSION['queryData']['minbeta'] ?>" size="5">
                        <!-- to <input type="text" name="maxRes" value="<*?php print $_SESSION['queryData']['maxRes'] ?>" size="5" > -->
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>p value</label>
                    <p>
                        <!-- Lower than: <input type="text" name="maxpval" value="<*?php print $_SESSION['queryData']['maxpval'] ?>" size="5"> -->
                        Lower than: <input type="text" name="maxpval" value="1" size="5">
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>variant frequency</label>
                    <p>
                      <!-- from: <input type="text" name="minfreq" value="<*?php print $_SESSION['queryData']['minfreq'] ?>" size="5">
                      to: <input type="text" name="maxfreq" value="<*?php print $_SESSION['queryData']['maxfreq'] ?>" size="5" > -->
                      from: <input type="text" name="minfreq" value="0" size="5">
                      to: <input type="text" name="maxfreq" value="1" size="5" >

                    </p>
                </div>
            </div>
                <input type="submit" value="Send data" style="margin-left: 2%; margin-bottom: 2%"> <input type="reset" value="Clear data" style="margin-bottom: 2%"><br>
                <br>
        </div>
      </form>
    </body>
    <!-- </head> -->
  </html>

  <?php include "footer.html" ?>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
