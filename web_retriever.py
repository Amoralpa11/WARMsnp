import urllib.request, urllib.parse

data = {
'batch_text' : '',
'refseq' : '',
'ensembl' : '',
'sift' : '',
'polyphen' : '',
'afr' : '',
'amr' : '',
'eas' : '',
'eur' : '',
'sas' : '',
'clinvar' : '',
 }

data = bytes( urllib.parse.urlencode( data ).encode() )
handler = urllib.request.urlopen( 'http://snp-nexus.org', data );
print( handler.read().decode( 'utf-8' ) );
