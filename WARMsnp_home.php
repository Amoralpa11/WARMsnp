<?php

include "navbar.html";
session_start();
//setting default values
if (isset($_REQUEST['new']) or !isset($_SESSION['queryData'])) {
    $_SESSION['queryData'] = [
        'query' => '',
        'minbeta' => '0',
        'maxpval' => '0.05',
        'maxfreq' => '1',
        'minfreq' => '0',
        'p_error' => '0',
        'freq_error' => '0',
        'query_error' => '0'];
}
?>

<!-- Page Content -->
<html>
<head>
    <link rel="icon" href="Home_images/flame.png">
    <title>WARMsnp</title>
    <meta charset="UTF-8">
</head>
<body>
  <br>
<div class="container">
  <div class="row">
    <div class="col-md-8">
    <h2>WARMsnp</h2>
    <p style="text-align: justify; text-justify: inter-word;">
      WARMsnp is a web app designed by and for researchers. It is aimed to deliver SNP enriched information, it allows you to get SNP related information consolidated from several other web sties.
    </p>

    <form id="input_form" name="input" action="SNP_out.php" method="post" enctype="multipart/form-data">
        <p><em>Enter gene ensembl id or snp id:</em></p>
        <textarea id="text_in" name="query" cols="40" rows="3" placeholder="ensemble id or snp id" style="margin-left: 1%;">
          <?php print $_SESSION["queryData"]["query"]?>
        </textarea>
        <span id="query_not_type_message" class="error" style="color:#ff0000; display:none; margin-left: 2%; margin-bottom:30px;">
          * Input text must be a a gene ensembl id (e.g. ENS0001 or a snp id (e.g. rs0001)
        </span>
        <br><input id="file_in"name="uploadFile" type="file" style="margin-left: 1%;">
          <span id="query_err_message" class="error" style="color:#ff0000; display:none;">
            * Input text or file are required
          </span><br>
        <br>
        <fieldset class="advanced search" style="border: 1px dashed black;">
        <h3 style="margin-left: 15px; margin-top: 5px;">
          Advanced search
        </h3>
        <div style="margin-left: 30px;">
            <p>
              Select parameters to further filter the search:
            </p>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="font-weight: bold;">Beta</label>
                        <p>
                            Higher than:
                            <input type="text" name="minbeta"id="minbeta" value= "<?php print $_SESSION['queryData']['minbeta'] ?>" size="5">
                            <span id="beta_num" class="error" style="color:#ff0000; display:none;">
                              * Beta must be a number.
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="font-weight: bold;">P value</label>
                        <p>
                            Lower than: <input type="text" id="maxpval" name="maxpval" value= "<?php print $_SESSION['queryData']['maxpval'] ?>" size="5">
                            <span id="pval_err" class="error" style="color:#ff0000; display:none;">
                              * P value is out of range, please set it between 0 and 1.
                            </span>
                            <span id="pval_num" class="error" style="color:#ff0000; display:none;">
                              * P value must be a number.
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="font-weight: bold;">
                          Variant Frequency
                        </label>
                        <p>
                            From: <input type="text" id="minfreq" name="minfreq" value='<?php print $_SESSION['queryData']['minfreq'] ?>' size="5">
                            To: <input type="text" id="maxfreq" name="maxfreq" value= '<?php print $_SESSION['queryData']['maxfreq'] ?>' size="5" >
                            <span id="freq_error" class="error" style="color:#ff0000; display:none;">
                              * Frequencies are set out of range, please set them between 0 and 1.
                            </span>
                            <span id="freq_inv" class="error" style="color:#ff0000; display:none;">
                              * Minimum frequency is bigger than maximum frequency.
                            </span><br>
                            <span id="freq_num" class="error" style="color:#ff0000; display:none;">
                              * Minimum frequency and maximum frequencies must be numbers.
                            </span>

                        </p>
                    </div>
                </div>
                <br>
            </div>
          </div>
        </fieldset>
    </form>

    <button onclick="check()" style="border-radius: 12px;background-color: #e9e9e6">
      Submit
    </button>
    <button onclick="reset()" style="border-radius: 12px;background-color: #e9e9e6">
      Reset
    </button><br><br>


   </div>
    <div class="col-md-4", style="margin-bottom: 25px;">
      <h3 style="text-align: center;">
        Interesting facts
      </h3>
        <div style="border: 1px solid black; overflow:scroll; height: 410px">
         <ul style="margin-top: 15px; text-align: justify; text-justify: inter-word;margin-right: 25px;">
          <li>
            If you were to recite the entire ATCG sequence, pronouncing each of its 3 billion letters the genetic material notation is made of at a rate of 100 ATCG sequences per minute without sleeping, eating or drinking, you would cite for 57 years.
          </li>
          <hr>
          <li>
            About one in every 4 million lobsters is born with a rare genetic defect that turns it blue. Sadly, these prized critters rarely survive to adulthood. After all, a bright blue crustacean crawling around the ocean floor is simply easier for predators to spot.
          </li>
          <hr>
          <li>
            Here is a side column where we can write interesting facts:
          Which is the gene with the most snps,
          which is the snp with the highest beta,
          Which is disease with the most snps,
          </li>
          <hr>
          <li>
            Here is a side column where we can write interesting facts:
          Which is the gene with the most snps,
          which is the snp with the highest beta,
          Which is disease with the most snps,
          </li>
          <hr>
          <li>
            Here is a side column where we can write interesting facts:
          Which is the gene with the most snps,
          which is the snp with the highest beta,
          Which is disease with the most snps,
        </li>
        <hr>
        <li>
            Here is a side column where we can write interesting facts:
          Which is the gene with the most snps,
          which is the snp with the highest beta,
          Which is disease with the most snps,
        </li>
        <hr>
        <li>
            Here is a side column where we can write interesting facts:
          Which is the gene with the most snps,
          which is the snp with the highest beta,
          Which is disease with the most snps,
        </li>
        <hr>
        <li>
            Here is a side column where we can write interesting facts:
          Which is the gene with the most snps,
          which is the snp with the highest beta,
          Which is disease with the most snps,
        </li>
        <hr>
        <li>Tea</li>
        <hr>
        <li>Here is a cat:</li>
        <img src="images/cat.png", style="max-width:90%;max-height:100%;">
      </ul>
    </div>
   </div>
  </div>
</div>
</body>
</html>

<!-- Optional JavaScript -->
<!-- DataTable activation -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
function reset() {
    $("#minfreq").val('0');
    $("#maxfreq").val('1');
    $("#minbeta").val('0');
    $("#maxpval").val('0.05');
    $("#text_in").val('');

}

</script>

<script>

function check() {
    var okay = 0;
    if ($.isNumeric( $("#minfreq").val() ) && $.isNumeric( $("#maxfreq").val() )) {
      $("#freq_num").hide();
      if ($("#minfreq").val() < 0 || $("#minfreq").val() > 1 || $("#maxfreq").val() > 1 || $("#maxfreq").val() < 0) {
        $("#freq_error").show();
        okay = 1;
      } else {
        $("#freq_error").hide();
        if ($("#minfreq").val() > $("#maxfreq").val()) {
          $("#freq_inv").show();
          okay = 1;
        } else {
          $("#freq_inv").hide();
        }
      }
    } else {
      $("#freq_num").show();
      okay = 1;
    }
    if ($("#maxpval").val() < 0 || $("#maxpval").val() > 1) {
      $("#pval_err").show();
      okay = 1;
    } else {
      $("#pval_err").hide();
    }
    if ($("#text_in").val() == '' && $("#file_in").val() == '') {
      $("#query_err_message").show();
      okay = 1;
    } else {
      $("#query_err_message").hide();
      if ($("#text_in").val().toUpperCase().match("RS") || $("#text_in").val().toUpperCase().match("ENS") ) {
        $("#query_not_type_message").hide();
      } else {
        $("#query_not_type_message").show();
        okay = 1;
      }
    }
    if ( $.isNumeric( $("#minbeta").val() ) ) {
      $("#beta_num").hide();
    } else {
      $("#beta_num").show();
      okay = 1;
    }
    if ($.isNumeric( $("#maxpval").val() )) {
      $("#pval_num").hide();
    } else {
      $("#pval_num").show();
      okay = 1;
    }
    if (okay == 0) {
      $("#input_form").submit();
    }
}
</script>



<?php include "footer.html"?>
