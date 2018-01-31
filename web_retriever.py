import urllib.request, urllib.parse

def snp_parser(snp_file):
	"""this function splits a file containing a list of SNP ids into chunks of 100K SNP"""
	
	i = 0
	#j = 1
	fr = open(snp_file,"r")
	#fw = open("snp_file"+str(j),"w")
	our_input = ""

	for line in fr:
		
		if i < 99999:
			#fw.write(line)
			our_input += line

		else:
			#fw.close()
			#j +=1
			#fw = open("snp_file"+ str(j),"w")

			
			data = {
			'email' : 'ramonmassoni@gmail.com',
			'batch_text' : our_input,
			'refseq' : 'on',
			'ensembl' : 'on',
			'sift' : 'on',
			'polyphen' : 'on',
			'afr' : 'on',
			'amr' : 'on',
			'eas' : 'on',
			'eur' : 'on',
			'sas' : 'on',
			'clinvar' : 'on',
			'RUN' : 'on'
			 }

			data = bytes( urllib.parse.urlencode( data ).encode() )
			handler = urllib.request.urlopen( 'http://snp-nexus.org', data );
			print( handler.read().decode( 'utf-8' ) );
			
			our_input = ""
			i = 0
			

		i += 1

	fr.close()

snp_parser("snp_prova.file")
