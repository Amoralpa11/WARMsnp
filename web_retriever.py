import urllib.request, urllib.parse

data = {
'email' = 'ramonmassoni@gmail.com'
'batch_text' : '',
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
 }
url = 'http://snp-nexus.org'
data = bytes( urllib.parse.urlencode( data ).encode() )
handler = urllib.request.urlopen( url, data );
print( handler.read().decode( 'utf-8' ) );


#Imagine this works?
