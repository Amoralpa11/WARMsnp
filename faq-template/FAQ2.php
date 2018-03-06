<?php

include "navbar3.html";

?>

<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="css/style.css"> <!-- Resource style -->
	<script src="js/modernizr.js"></script> <!-- Modernizr -->
	<link rel="icon" href="../Home_images/flame.png">
	<title>WARMsnp FAQs</title>
</head>

<body>

<header>
	<h1>FAQs WARMsnp</h1>
</header>
<section class="cd-faq" style="margin-top:10px;">
	<ul class="cd-faq-categories">
		<li><a class="selected" href="#basics">Basics</a></li>
		<li><a href="#formats">Formats</a></li>
		<li><a href="#results">Results</a></li>
	</ul> <!-- cd-faq-categories -->

	<div class="cd-faq-items">
		<ul id="basics" class="cd-faq-group">
			<li class="cd-faq-title"><h2>Basics</h2></li>
			<li class="cd-faq-title" style="text-align:justify">Since the advent of next-generation sequencing technologies, a great deal of studies have reported thousands of Single Nucleotide Polymorphisms (SNPs) that are associated with the leading cause of death worldwide: cardiovascular disease (CD). At WARM-snp S.A., we strive to provide the scientific community with a comprehensive, user-friendly and interactive catalog of all the SNPs that may affect CD development. Below, we answer the most frequently asked questions we receive to help you take the most out of WARM-snp</li>
			<li>
				<a class="cd-faq-trigger" href="#0" style="color:#6a7a32;">What is a SNP?</a>
				<div class="cd-faq-content">
					<p>A SNP is a specific position of the genomic DNA that varies among individuals. They are estimated to occur at 1 out of every 1,000 bases in the human genome and may have different effects at the phenotypic level. For instance, a SNP in a coding region can alter the structure or function of a particular protein. Thus, they can also increase or decrease the chances of developing a disease.</p>
				</div> <!-- cd-faq-content -->
			</li>

			<li>
				<a class="cd-faq-trigger" href="#0" style="color:#6a7a32;">What is the role of genomic variation in cardiovascular disease development?</a>
				<div class="cd-faq-content">
					<p>CD is a complex set of diseases caused by a combination of risk factors, such as hypertension, dyslipidemia, obesity, and type 2 diabetes. In this setting, genetic and genomic variations may explain the differences in terms of incidence and presentation of CD among individuals with the same lifestyle. Of note, over the last decade many Genome Wide Associated Studies (GWAS) were conducted to address this question. GWAS look for associations between SNPs (usually hundreds of thousands) and specific diseases. Our mission is to integrate the results of all such studies to allow its retrieval, analysis and visualization.</p>
				</div> <!-- cd-faq-content -->
			</li>

			<li>
				<a class="cd-faq-trigger" href="#0" style="color:#6a7a32;">What is the p-value?</a>
				<div class="cd-faq-content">
					<p>The p-value is the probability of observing a result as extrem just by chance. It's the same as talking about the significance of our results, it tells us to what extent we can trust our observations.</p>
				</div> <!-- cd-faq-content -->
			</li>

			<li>
				<a class="cd-faq-trigger" href="#0" style="color:#6a7a32;">What is the Beta value?</a>
				<div class="cd-faq-content">
					<p>This value refers to the effect size, the intensity in which a specific SNP promotes or protects for CD. The sign refers to whether it protects (-) or promotes (+) CD development, and the absolute value to the magnitude of the effect.</p>
				</div> <!-- cd-faq-content -->
			</li>
		</ul> <!-- cd-faq-group -->

		<ul id="formats" class="cd-faq-group">
			<li class="cd-faq-title"><h2>Formats</h2></li>
			<li>
				<a class="cd-faq-trigger" href="#0" style="color:#6a7a32;">What input formats does WARM-snp support?</a>
				<div class="cd-faq-content">
					<p>WARM-snp allows the user to provide either gene or SNP identifiers, either by copying them on the text box or by uploading a text file. In the case multiples SNPs or genes are provided, they can be in different lines or separated by spaces or tabulators.</p>
				</div> <!-- cd-faq-content -->
			</li>

			<li>
				<a class="cd-faq-trigger" href="#0" style="color:#6a7a32;">How do I upload files?</a>
				<div class="cd-faq-content">
					<p>At the bottom of the Main search container there is a button (Select file) where you can upload a file from your device to do the search.</p>
				</div> <!-- cd-faq-content -->
			</li>
		</ul> <!-- cd-faq-group -->

		<ul id="results" class="cd-faq-group">
			<li class="cd-faq-title"><h2>Results</h2></li>
			<li>
				<a class="cd-faq-trigger" href="#0" style="color:#6a7a32;">What tools does WARM-snp provide to interpret and visualize the effect of a particular SNP on CD?</a>
				<div class="cd-faq-content">
					<p> To analyze GWAS data, one has to consider two parameters: the significance and the effect size. The significance tells us to what extent we can trust our observations, and is quantified with the p-value: the probability that we observe a result as extreme just by chance. Alternatively, the effect size refers to the intensity in which a specific SNP promotes or protects for CD. Effect size is quantified with a beta value: the sign refers to whether it protects (-) or promotes (+) CD development, and the absolute value to the magnitude of the effect. <br><br> To visualize the aforementioned parameters, we take advantage of the power of Manhattan plots. The -log10 of the p-value is ploted on the y axis, whilst the SNP coordinate  is ploted in the x axis. We map the significance of each SNP to the color of the point (green for significant SNPs and red for non-significant), and the absolute value of the Beta to its size. Thus, the higher the SNP, the more significant; and the larger the point, the greater the beta value. In the case a user inputs a gene as a query, the Manhattan plot shows all the SNPs contained on that particular gene; and if he/she queries a particular SNP, all the SNPs contained within +- 40000 bp will be ploted. Finally, one can also filter the Manhattan plot by p-value or by the sign of the beta value (protective or damaging), and access the page of any SNP in the plot by clicking on it.</p>
				</div> <!-- cd-faq-content -->
			</li>

			<li>
				<a class="cd-faq-trigger" href="#0" style="color:#6a7a32;">What data does WARM-snp provide for a particular SNP besides the p-value, beta value and genomic coordinate?</a>
				<div class="cd-faq-content">
					<p> We also provide the next values. <br><br>
	1. Gene Ontology (GO) terms associated to each gene. GO terms provide a comprehensive annotation of the biological processes, molecular function, and localization of each gene.<br><br>
	2. Variant frequency. A SNP can have two or more variants, each of which are found at a particular frequency in the population.<br><br>
	3. Tissue expression. The expression of most genes varies across tissues. Importantly, different varies of the same SNP will have no different effect if the gene is not expressed at all. We display such information with a bar chart, where for every tissue the expression of the gene is plotted in Transcripts per Million (TPM, number of transcripts belonging to that gene out of a million).</p>
				</div> <!-- cd-faq-content -->
			</li>


	</div> <!-- cd-faq-items -->
	<a href="#0" class="cd-close-panel">Close</a>
</section> <!-- cd-faq -->
<script src="js/jquery-2.1.1.js"></script>
<script src="js/jquery.mobile.custom.min.js"></script>
<script src="js/main.js"></script> <!-- Resource jQuery -->
</body>

<?php
include "footer.html";
?>
</html>