<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
   <head>
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<script type="text/javascript" src="uzycieDOM.js"></script>
		<link rel="stylesheet" title="Simple_style" href="style.css" media="screen" type="text/css" />
      <link rel="alternate stylesheet" href="pierwszy.css" title="Pierwszy styl" type="text/css" />
      <link rel="alternate stylesheet" href="drugi.css" title="Drugi styl" type="text/css" />
      <link rel="alternate stylesheet" href="trzeci.css" title="Trzeci styl" type="text/css" />
		<title>Witaj na stronie obsługującej blogi :)</title>
		<script type="text/javascript">
  			window.onload=inicjalizacjaStyl();
		</script>	
	</head>
	<body onload="wyswietListeStyli();">
		<div id="kontener">
			<?php
				include 'menu.php';		
			?>
			<div id="tresc">
				<h1>Blogi:</h1>
     			<?php
     				$folder = dir('.');
     				while($szukanyFolder = $folder->read())
					{
						if($szukanyFolder != '.' && $szukanyFolder != '..' && is_dir($szukanyFolder))
						{
							echo "<h2><a href=\"blog.php?nazwa=$szukanyFolder\">$szukanyFolder</a></h2>";
     					}     			
     				}
     				$folder->close();
    			?>
     			<p>
			   	<a href="http://validator.w3.org/check?uri=referer"><img
			      	src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
		  	      <a href="http://jigsaw.w3.org/css-validator/check/referer"><img 
				    	style="border:0;width:88px;height:31px"
				      src="http://jigsaw.w3.org/css-validator/images/vcss"
				      alt="Poprawny CSS!" /></a>
				</p>
			</div>
		</div>
	</body>
</html>