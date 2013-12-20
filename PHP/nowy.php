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
						echo "<h1> Pozostawiłeś/łaś nieuzupełnione pola. Zanim wyślesz ponowną rejestrację upewnij się, że wypełniłeś wszystkie pola (załączniki nie są wymagane) </h1>"; 
						include 'stopka.php';						
						exit();					
					}
					$nazwaBlog = basename($_POST['nazwaBlog']);
					$nazwaUzytkownik = $_POST['nazwaUzytkownik'];
					$haslo = $_POST['haslo'];
					$opisBlog = $_POST['opisBlog'];
					$semafor = sem_get(321);
					sem_acquire($semafor);				
					if (czyJestJuzTakiUzytkownik($nazwaUzytkownik) == true)
					{
						echo "<h1> Użytkownik o podanym nicku posiada już blog w serwisie (nie można posiadać więcej) </h1>"; 
						include 'stopka.php';
						exit();						
					}
					if (file_exists($nazwaBlog) || is_dir($nazwaBlog))
					{
						echo "<h1> Nie udało się stworzyć bloga o nazwie $nazwaBlog, blog ten już istnieje </h1>";
						include 'stopka.php';
						exit();					
					}
					mkdir ("./$nazwaBlog", 0705);
					$info = "$nazwaBlog/info";
					touch($info);
					chmod($info, 0600);
					wypelnijInfoPlik($info, $nazwaUzytkownik, $haslo, $opisBlog);
					sem_release($semafor);
					echo "<h1> Stworzono folder: $nazwaBlog z plikiem: $info zawierającym nazwę użytkownika: $nazwaUzytkownik , zakodowane hasło i opis bloga </h1>";
					
					function czyFormularzWypelniony()
					{
						return !(empty($_POST['nazwaBlog']) || empty($_POST['nazwaUzytkownik']) || empty($_POST['haslo']) || empty($_POST['opisBlog']));
					}
					function czyJestJuzTakiUzytkownik($nazwaUzytkownik)
					{
						$znaleziony = false;
						$wnetrzefolderu = scandir('.');
						$nazwaUzytkownik.="\n";
						foreach($wnetrzefolderu as $folder)
						{
							if($folder != '.' && $folder != '..' && is_dir($folder))
							{
								$infoPlik = fopen("$folder/info", "r");
								if (flock($infoPlik, LOCK_SH))
								{
									$tymczasowyUzytkownik = fgets($infoPlik);
									if (strcmp($tymczasowyUzytkownik, $nazwaUzytkownik) == 0)
									{
										$znaleziony = true;	
										flock($infoPlik, LOCK_UN);	
										fclose($infoPlik);
										break;
									}	
									flock($infoPlik, LOCK_UN);	
								}				
  								else 
								{
									echo "<h1> Wystąpiły problemy z blokadą... </h1>";
									include 'stopka.php';
									exit();
								}				
  								fclose($infoPlik);
							}
						}
						return $znaleziony;								
					}
					function wypelnijInfoPlik($info, $nazwaUzytkownik, $haslo, $opisBlog)
					{
						$infoPlik = fopen($info, "a"); 
						if (flock($infoPlik, LOCK_EX))
						{ 
							fwrite($infoPlik, $nazwaUzytkownik); 
							fwrite($infoPlik, "\n");
							fwrite($infoPlik, md5($haslo)); 
							fwrite($infoPlik, "\n");
							fwrite($infoPlik, $opisBlog); 
							flock($infoPlik, LOCK_UN);
						}
						else 
						{
							echo "<h1> Wystąpiły problemy z blokadą... </h1>";
							include 'stopka.php';
							exit();					
						}
						fclose($infoPlik); 
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