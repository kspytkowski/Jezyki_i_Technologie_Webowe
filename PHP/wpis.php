<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
   <head>
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
		<link rel="stylesheet" title="Simple_style" href="style.css" media="screen" type="text/css" />
		<title>Witaj na stronie obsługującej blogi :)</title>
	</head>
	<body>
		<div id="kontener">
			<?php
				include 'menu.php';		
			?>
			<div id="tresc">
				<?php
					if (czyFormularzWypelniony() == false)
					{
						echo "<h1> Nie podałeś/łaś wszystkich danych w formularzu! (zalączniki nie są wymagane) </h1>";	
						include 'stopka.php';
						exit();
					}
					$data = $_POST['data'];
					$godzina = $_POST['godzina'];
					$dataDoSprawdzenia = "$data $godzina";
					if (poprawnaDataIGodzina($dataDoSprawdzenia) == false)
					{
						echo "<h1> Podana data i/lub godzina mają nieprawidłowy format, ustaw te pola w następujący sposób: data: RRRR-MM-DD, godzina: GG:MM </h1>";		
						include 'stopka.php';
						exit();	
					}
					$semafor = sem_get(54321);
					sem_acquire($semafor);						
					$uzytkownik = $_POST['nazwaUzytkownika'].="\n";
					$haslo = $_POST['haslo'];
					$wpis = $_POST['wpis'];
					$folder = dir('.');
					while($szukanyFolder = $folder->read())
					{
						if($szukanyFolder == '.' || $szukanyFolder == '..' || !is_dir($szukanyFolder))
						{
							continue;	
						}
						$info = "$szukanyFolder/info";
						$infoPlik = fopen($info, "r");
						if (flock($infoPlik, LOCK_SH))
						{				
							if (czyWlasciwyUzytkownik($infoPlik, $uzytkownik) == false)
							{ 
								flock($infoPlik, LOCK_UN);
								continue;
							}
							if (czyPoprawneHaslo($infoPlik, $haslo) == false)
							{
								flock($infoPlik, LOCK_UN);
								echo "<h1> Podane hasło jest nieprawidłowe! </h1>";
								include 'stopka.php';
								exit();	
							}
						}
						else 
						{
							echo "<h1> Wystąpiły problemy z blokadą... </h1>";
							include 'stopka.php';
							exit();
						}
						fclose($infoPlik);
						list($rok, $miesiac, $dzien) = preg_split('/-/', $data);
						list($godziny, $minuty) = preg_split('/:/', $godzina);
						$unikalnyNumer = 0;
						while($unikalnyNumer < 100)
						{                              
							settype($unikalnyNumer, "string");     
							$nazwaPliku = ustawNazwePliku($rok, $miesiac, $dzien, $godziny, $minuty, $unikalnyNumer);
							$nowyWpisSciezka = "$szukanyFolder/$nazwaPliku";
							if (!file_exists($nowyWpisSciezka) && !is_dir($nowyWpisSciezka)) 
							{
								tworzNowyPlikZWpisem($nowyWpisSciezka, $wpis);
 	 							$folderDocelowy = "/$szukanyFolder";
  								przeniesZalacznikiNaSerwer($nazwaPliku, $folderDocelowy);	
								echo "<h1> Dodano nowy wpis do bloga uzytkownika: $uzytkownik o nazwie: $szukanyFolder wraz ze wszystkimi dodanymi załącznikami </h1>";
								include 'stopka.php';
								exit();
							}
							else 
							{
								settype($unikalnyNumer, "integer");
								$unikalnyNumer++;
  							}
							if ($unikalnyNumer == 99)
							{
								echo "<h1> Nie można dodać tego wpisu, spróbuj później </h1>";
								include 'stopka.php';
  								exit();
  							}							
						}	
					}
					$folder->close();
					sem_release($semafor);
					echo "<h1> Nie ma takiego użytkownika w serwisie. Zanim dodasz wpis, musisz zalożyć nowego bloga! </h1>";					

					function czyFormularzWypelniony()
					{
						return !(empty($_POST['nazwaUzytkownika']) || empty($_POST['haslo']) || empty($_POST['wpis']) || empty($_POST['data']) || empty($_POST['godzina']));	
					}
					function czyWlasciwyUzytkownik($infoPlik, $uzytkownik)
					{						
						return strcmp(fgets($infoPlik), $uzytkownik) == 0;
					}
					function czyPoprawneHaslo($infoPlik, $haslo)
					{
						$skrotHaslo = md5($haslo);
						$skrotHaslo = $skrotHaslo.="\n";
						return strcmp(fgets($infoPlik), $skrotHaslo) == 0;							
					}
					function poprawnaDataIGodzina($data, $format = 'Y-m-d H:i')
					{
   					$d = DateTime::createFromFormat($format, $data);
    					return $d && $d->format($format) == $data;
					}	
					function ustawNazwePliku($rok, $miesiac, $dzien, $godziny, $minuty, $unikalnyNumer)
					{			
						$czasSerwera = time();
						$sekundy = date("s", $czasSerwera);
						if ($unikalnyNumer < 10)
							{
								$nazwaPliku = "$rok$miesiac$dzien$godziny$minuty$sekundy";
								$nazwaPliku.= 0;
								$nazwaPliku.= $unikalnyNumer;
							}
							else
							{
								$nazwaPliku = "$rok$miesiac$dzien$godziny$minuty$sekundy$unikalnyNumer";
							}
						return $nazwaPliku;
					}
					function tworzNowyPlikZWpisem($nowyWpisSciezka, $wpis)
					{			
						touch($nowyWpisSciezka);
						chmod($nowyWpisSciezka, 0600);
						$nowyPlikWpis = fopen($nowyWpisSciezka, "a");						
						if (flock($nowyPlikWpis, LOCK_EX))
						{							 
							fwrite($nowyPlikWpis, $wpis); 
							flock($nowyPlikWpis, LOCK_UN);
						}
						else 
						{
							echo "<h1> Wystąpiły problemy z blokadą... </h1>";
							include 'stopka.php';
							exit();
						}
						fclose($nowyPlikWpis);
					}
					function tworzNowaNazweZalacznika($nazwaPlikuWBuforze, $tablicaNazwy, $nazwaPliku, $index)
					{
						if (count($tablicaNazwy) > 1)
  						{
  							$nowaNazwa = $nazwaPliku;
							$nowaNazwa.=$index;
							$nowaNazwa.=".";
							$nowaNazwa.=$tablicaNazwy[count($tablicaNazwy)-1];        	
  						}
  						else 
  						{
  							$nowaNazwa = $nazwaPliku;
							$nowaNazwa.=$index;
  						}
  						return $nowaNazwa;
  					}
  					function przeniesZalacznikiNaSerwer($nazwaPliku, $folderDocelowy)
  					{
  						$index = 1;
						foreach ($_FILES["zalacznik"]["error"] as $klucz => $error) 
						{
 							if ($error == UPLOAD_ERR_OK) 
 							{
  								$nazwaPlikuWBuforze = $_FILES["zalacznik"]["tmp_name"][$klucz];
  								$tablicaNazwy = explode(".", $_FILES["zalacznik"]["name"][$klucz]);		
  								$nowaNazwa = tworzNowaNazweZalacznika($nazwaPlikuWBuforze, $tablicaNazwy, $nazwaPliku, $index);
  								move_uploaded_file($nazwaPlikuWBuforze, "./$folderDocelowy/$nowaNazwa");
 								$index++;
 							}
						}
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