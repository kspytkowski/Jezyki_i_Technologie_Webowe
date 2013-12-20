<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
   <head>
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
		<link rel="stylesheet" title="Simple_style" href="style.css" media="screen" type="text/css" />
		<title>Formularz tworzenia nowego komentarza do wpisu</title>
	</head>
	<body>
	   <div id="kontener">
		  	<?php
				include 'menu.php';		
			?>
			<div id="tresc">
      	  	<h1>Nowy komentarz do wpisu:</h1>
    	  	   <form action="koment.php" method="post" enctype="multipart/form-data">
					<div id="rodzajKomentarza">
  						<select name="rodzajKomentarza">
					      <option>pozytywny</option>
				   	   <option>negatywny</option>
			   	   	<option>neutralny</option>
				      </select><br/>
					</div>
 				   	   <div id="komentarz">Komentarz:<br/>
	  		  		   <textarea name="komentarz" rows="10" cols="50" >Komentarz...</textarea>
		         </div>	
   				<div id="podpis">Podpis:<br/>
					   <input type="text" name="podpis" />
					</div>
			      <div id="dodatkowe">
	 					<input type="hidden" name="nazwa" value="<?php echo $_GET["nazwa"]; ?>" />    
	 					<input type="hidden" name="dataIGodzina" value="<?php echo date("Y-m-d H:i:s", time()); ?>" />
   				</div> 
   				<div>
	   				<input type="submit" value="Wyślij" /> 
   					<input type="reset" value="Wyczyść" />
					</div>
				</form>
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