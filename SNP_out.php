<!doctype html>
		<!-- Page Content -->
		<?php
		#start Session to hold input data
		// session_start();
		include "navbar.html";


		# Check if input comes from an uploaded file
		# If the data comes from a file get the content from the file
		if ($_FILES['uploadFile']['name']) {
		    $_REQUEST['query']=  file_get_contents($_FILES['uploadFile']['tmp_name']);
		}

		if ($_REQUEST['maxpval'] > 1 || $_REQUEST['maxpval'] <= 0) { ?>
			<html>
					<head>
							<title>Error: P value out of range</title>
					</head>
					<body>
							<h2>Error: The P value set is out of range, please set it between 0 and 1.</h2>
					</body>
			</html>

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
						print "<h2>we are working with a snp</h2>";
						?>
						<html>
								<head>
										<style type="text/css">
												thead {background-color: #cccccc;color:#ffffff}
												tbody {background-color: #ffffff};
										</style>
										<link rel="stylesheet" href="DataTable/jquery.dataTables.min.css"/>
										<script type="text/javascript" src="DataTable/jquery-2.2.0.min.js"></script>
										<script type="text/javascript" src="DataTable/jquery.dataTables.min.js"></script>
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
														<td>SELECT Gene_id FROM GENE WHERE Gene_id = (SELECT ID FROM SNP WHERE idSNP = $_REQUEST['query'])</td>
														<td>SELECT position FROM SNP WHEN idSNP = $_REQUEST['query']"</td>
														<td>SELECT Frequency FROM Variants WHEN idSNP = $_REQUEST['query']"</td>
														<td>SELECT beta FROM Variants WHEN idSNP = $_REQUEST['query']"</td>
														<td>SELECT p-value FROM Variants WHEN idSNP = $_REQUEST['query']"</td>
														<td>SELECT Name FROM Disease WHEN SNP.idSNP = $_REQUEST['query']"</td>
														<td>SELECT Name FROM Disease WHERE idDisease = (SELECT idDisease FROM SNP-disease WHERE idSNP = $_REQUEST['query'])</td>
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
				# if we are working with genes.
		    } else {
						# To be writen
						print "<h2>we are working with a gene!</h2>";
		    }
		    $_SESSION['data'] = parse_Fasta($fasta);
		}
		?>


		<script type="text/javascript">
		    $(document).ready(function () {
		        $('#dataTable').DataTable();
		    });
		</script>

		<?php
		function isFasta($f) {
		    return (substr($f,0,1) == ">");
		}

		function parse_Fasta ($f) {
		    $sqs = explode(">", $f);
		    $data=Array();
		    foreach ($sqs as $s) {
		        if ($s) {
		            $lines = explode("\n",$s,2);
		            list($db,$id,$info) = explode("|",$lines[0]);
		            list ($swp,$info) = explode(" ",$info,2);
		            $data[$id] = array('db'=> $db, 'id'=> $id, 'swpid' => $swp, 'info' => $info, 'sequence' => $lines[1]);
		        }
		    }
		    return $data;
		}
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		include "footer.html";

		?>

  	<!-- Optional JavaScript -->
  	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
  	<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
  <!-- </body> -->
  </html>
