<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
   <head>
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<script type="text/javascript" src="walidacja.js"></script>
		<script type="text/javascript" src="uzycieDOM.js"></script>
		<link rel="stylesheet" title="Simple_style" href="style.css" media="screen" type="text/css" />
      <link rel="alternate stylesheet" href="pierwszy.css" title="Pierwszy styl" type="text/css" />
      <link rel="alternate stylesheet" href="drugi.css" title="Drugi styl" type="text/css" />
      <link rel="alternate stylesheet" href="trzeci.css" title="Trzeci styl" type="text/css" />
		<title>Formularz tworzenia nowego bloga</title>
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
   			<h1>Nowy blog:</h1>
				<form action="nowy.php" method="post" enctype="multipart/form-data">
					<div id="nazwaBlog">Nazwa bloga:<br/>
   					<input type="text" name="nazwaBlog" />
         		</div> 
					<div id="nazwaUzytkownik">Nazwa użytkownika:<br/>
					   <input type="text" name="nazwaUzytkownik" />
    				</div> 
					<div id="haslo">Hasło:<br/>
				   	<input type="password" name="haslo" />
	  	    		</div>
					<div id="opisBlog">Opis bloga:<br/>
			   		<textarea name="opisBlog" rows="14" cols="60" >Opis bloga...</textarea>
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