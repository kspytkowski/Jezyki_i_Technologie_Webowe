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
				<?php
					if (czyFormularzWypelniony() == false)
					{
						echo "<h1>Komentarz nie został dodany, ponieważ pozostawiłeś/łaś nieuzupełnione pola. Zanim wyślesz ponownie komentarz upewnij się, że wypełniłeś wszystkie pola.</h1>"; 
						include 'stopka.php';
						exit();					
					}
					$semafor = sem_get(4321);
					sem_acquire($semafor);	
					$folderKomentarz = tworzFolderKomentarzy();
					$wnetrzefolderu = scandir($folderKomentarz);	
					$numerKomentarza = count($wnetrzefolderu) - 2;	
					$nowyKomentarz = "./$folderKomentarz/$numerKomentarza";
					touch($nowyKomentarz);
					chmod($nowyKomentarz, 0600);					
					$rodzajKomentarza = $_POST['rodzajKomentarza'];	
					$dataICzas = $_POST['dataIGodzina']; 		
					$podpis = $_POST['podpis'];
					$komentarz = $_POST['komentarz'];
					dodajNowyKomentarz($nowyKomentarz, $rodzajKomentarza, $dataICzas, $podpis, $komentarz);
					sem_release($semafor);					
					echo "<h1> Dodano komentarz do wpisu </h1>";
					
					function czyFormularzWypelniony()
					{
						return !(empty($_POST['komentarz']) || empty($_POST['podpis']));	
					}
					function tworzFolderKomentarzy()
					{
						$folderKomentarz = $_POST['nazwa'];
						$folderKomentarz.=".k";
						if (!file_exists($folderKomentarz))
						{
							mkdir ("./$folderKomentarz", 0700);
						}
						return $folderKomentarz;
					}
					function dodajNowyKomentarz($nowyKomentarz, $rodzajKomentarza, $dataICzas, $podpis, $komentarz)
					{
						$nowyPlikKomentarz = fopen($nowyKomentarz, "a"); 
						if (flock($nowyPlikKomentarz, LOCK_EX))
						{						
							fwrite($nowyPlikKomentarz, $rodzajKomentarza);
							fwrite($nowyPlikKomentarz, "\n");
							fwrite($nowyPlikKomentarz, $dataICzas);
							fwrite($nowyPlikKomentarz, "\n");
							fwrite($nowyPlikKomentarz, $podpis);
							fwrite($nowyPlikKomentarz, "\n");
							fwrite($nowyPlikKomentarz, $komentarz); 
							flock($nowyPlikKomentarz, LOCK_UN);
						}
						else 
						{
							echo "<h1> Wystąpiły problemy z blokadą... </h1>";
							include 'stopka.php';
							exit();
						}
						fclose($nowyPlikKomentarz);						
					}
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