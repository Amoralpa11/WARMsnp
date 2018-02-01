<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="scss/custom.css">

	<title>WARMsnp Home</title>
</head>
<body>


	<nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
		<div class="container">
		<a class="navbar-brand" href="#">
			<img src="Home_images/flame.svg" width="30" height="30" class="d-inline-block align-top" alt="">
			WARMsnp
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="#">About<span class="sr-only">(About)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">FAQ</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" href="#">Contact</a>
				</li>

<!--   				<li class="nav-item dropdown">
  					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  						Dropdown
  					</a>
  					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
  						<a class="dropdown-item" href="#">Action</a>
  						<a class="dropdown-item" href="#">Another action</a>
  						<div class="dropdown-divider"></div>
  						<a class="dropdown-item" href="#">Something else here</a>
  					</div>
  				</li>
  				<li class="nav-item">
  					<a class="nav-link disabled" href="#">Disabled</a>
  				</li> -->

  			</ul>
  			<!-- <form class="form-inline my-2 my-lg-0">
  				<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
  				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
  			</form> -->
  			<span class="navbar-text">
  				We do the search for you!
  			</span>
  		</div>
  		</div>
  	</nav>

		<!-- Page Content -->
		<?php
		#start Session to hold input data
		session_start();
		# Check if input comes from an uploaded file

		if ($_FILES['uploadFile']['name']) {
		    $_REQUEST['query']=  file_get_contents($_FILES['uploadFile']['tmp_name']);
		}
		if (!$_REQUEST['query']) { ?>
		<html>
		    <head>
		        <title>Error: No request</title>
		    </head>
		    <body>
		        <h2>Error: Received request was empty</h2>
		    </body>
		</html>
		<?php
		} else {
		# Process input
		    # Check if this is an ensembl or snp id
		    if (!isFasta($_REQUEST['query'])) { # Not fasta: take as list of ids
		        # Parsing list
		        $idList = explode("\n", $_REQUEST['query']);
		        $fasta = '';
		        foreach ($idList as $id) {
		            if (!$id)
		                continue;
		            # Clean id spaces and lines
		            $id = preg_replace("/[ \r]/","",$id);
		            # get Uniprot Fasta
		            $thisFasta = file_get_contents("http://www.uniprot.org/uniprot/$id.fasta");
		            if (!isFasta($thisFasta)) {
		                print "<p>Error: $id not found</p>";
		            } else {
		                $fasta .= $thisFasta;
		            }
		        }

		    } else {
		        $fasta = $_REQUEST['fasta'];
		    }
		    $_SESSION['data'] = parse_Fasta($fasta);
		}
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
		   </head>
		    <body>
		        <p>Processed <?php print count($_SESSION['data'])?> unique sequence(s)</p>
		        <table border="0" cellspacing="2" cellpadding="4" id="dataTable">
		            <thead>
		                <tr>
		                    <th>Id</th>
		                    <th>Database</th>
		                    <th>SwissProt Id</th>
		                    <th>Header</th>
		 <!--                   <th>Sequence</th>-->
		                </tr>
		            </thead>
		            <tbody>
		                <?php foreach ($_SESSION['data'] as $p) {?>
		                <tr>
		                    <td><a href="getFasta.php?id=<?php print $p['id']?>"><?php print $p['id'] ?></a></td>
		                    <td><?php print $p['db'] ?></td>
		                    <td><?php print $p['swpid'] ?></td>
		                    <td><?php print $p['info'] ?></td>
		                    <!--<td><pre><?php print $p['sequence'] ?></pre></td>-->
		                </tr>
		                <?php } ?>
		            </tbody>
		        </table>
		    </body>
		</html>
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
		?>


  	<div>
  		Icons made by
  		<a href="https://www.flaticon.com/authors/kirill-kazachek" title="Kirill Kazachek">Kirill Kazachek</a>
  		from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by
  		<a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>
  	</div>

  	<!-- Optional JavaScript -->
  	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
  	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
  </html>
