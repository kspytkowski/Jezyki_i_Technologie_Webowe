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
					if (isset($_GET['nazwa']) and $_GET['nazwa'] != "")
					{
						$blog = $_GET['nazwa'];
						$info = "$blog/info";
						if (is_dir($blog))
						{
							$infoPlik = fopen($info, "r");
							if (flock($infoPlik, LOCK_SH))
							{						
								echo "<h1> Blog: $blog </h1>";
								$autor = fgets($infoPlik);
								echo "<h2> Autor: $autor </h2>";
								fgets($infoPlik);
								$opisBlog = "";
								while (!feof($infoPlik)) 
								{
			  						$line = fgets($infoPlik);
	    							$opisBlog.=$line;
  								}	
  								flock($infoPlik, LOCK_UN);
								echo "<h3> Opis bloga: $opisBlog </h3>";
							}
							else 
							{
								echo "<h1> Wystąpiły problemy z blokadą... </h1>";
								include 'stopka.php';
								exit();
							}
							fclose($infoPlik);
							$wnetrzefolderu = scandir($blog);
							foreach($wnetrzefolderu as $wpis)
							{
								if($wpis != '.' && $wpis != '..' && !is_dir($wpis) && preg_match('/^[0-9]{16}$/', $wpis))
								{
									$data = substr($wpis, 0, 4);
									$data.= "-";
									$data.= substr($wpis, 4, 2);
									$data.= "-";
									$data.= substr($wpis, 6, 2);
									$godzina = substr($wpis, 8, 2);
									$godzina.= ":";
									$godzina.= substr($wpis, 10, 2);
									echo "<br /><h4> Wpis dodany: $data, $godzina </h4>";
									$wpisTresc = "";
									$pomoc = "$blog/$wpis";
									$wpisPlik = fopen($pomoc, "r");
									if (flock($wpisPlik, LOCK_SH))
									{	
										while (!feof($wpisPlik)) 
										{
    										$line = fgets($wpisPlik);
    										$wpisTresc.=$line;
  										}
  										flock($wpisPlik, LOCK_UN);
  										echo "<h5> $wpisTresc </h5>";
 									}
 									else 
									{
										echo "<h1> Wystąpiły problemy z blokadą... </h1>";
										include 'stopka.php';
										exit();
									}
  									fclose($wpisPlik);					
		  							$wnetrzeFolderuZalaczniki = dir("$blog");
  									$flaga = 0;
									while($zalacznik = $wnetrzeFolderuZalaczniki->read())
									{
										$wpis = substr($wpis, 0, 16);
										$wyrazenieregularne = $wpis;
										$wyrazenieregularne.= "[1-99]{1}"; // moze wyswietlic do 99 zalacznikow
										if($zalacznik != '.' && $zalacznik != '..' && !is_dir($zalacznik) && preg_match("/$wyrazenieregularne/", $zalacznik))
										{	
											if ($flaga == 0)
											{
												echo "<h4> Załączniki: </h4>";	
											}		
											$flaga++;
											echo "<h5><a href=\"$blog/$zalacznik\">Załącznik $flaga</a></h5>";
										}
									}		
									$wnetrzeFolderuZalaczniki->close();	
									$pomoc1 = "$blog/$wpis";
									$pomoc = explode(".", $pomoc1);
									$pomoc2 = $pomoc[0];
									$pomoc2.=".k";
									if (is_dir($pomoc2))
									{
										echo "<h4> Komentarze: </h4>";
										$wnetrzefolderu = scandir($pomoc2);
										foreach($wnetrzefolderu as $wpis)
										{
											if($wpis != '.' && $wpis != '..' && !is_dir($wpis))
											{
												$komentarzPlik = fopen("$pomoc2/$wpis", "r");
												if (flock($komentarzPlik, LOCK_SH))
												{
													$komentarzTresc = "";
													echo "<h5>";
													echo "Rodzaj: ";
													echo fgets($komentarzPlik);
													echo "<br />Data i godzina dodania: ";
													echo fgets($komentarzPlik);
													echo "<br />Komentujący: ";
													echo fgets($komentarzPlik);
													echo "<br />Treść komentarza: ";
													while (!feof($komentarzPlik)) 
													{
		    											$line = fgets($komentarzPlik);
    													$komentarzTresc.=$line;
  													}
  													flock($komentarzPlik, LOCK_UN);
  												}
  												else 
												{
													echo "<h1> Wystąpiły problemy z blokadą... </h1>";
													include 'stopka.php';
													exit();
												}
  												echo "$komentarzTresc";
  												fclose($komentarzPlik);
												echo "</h5>";  									
  											}
  										}
									}
									echo "<h5><a href=\"nowyKomentarz.php?nazwa=$pomoc1\">Dodaj komentarz do wpisu</a></h5>";
								}
							}
						}
						else
						{ 
							echo "<h1> Blog o nazwie $blog nie istnieje w bazie danych </h1>";
						}
					}
					else 
					{
						echo "<h1>Blogi:</h1>";
						$folder = dir('.');
     					while($szukanyFolder = $folder->read())
						{
							if($szukanyFolder != '.' && $szukanyFolder != '..' && is_dir($szukanyFolder))
							{
								echo "<h2><a href=\"blog.php?nazwa=$szukanyFolder\">$szukanyFolder</a></h2>";
     						}     			
     					}
     					$folder->close();
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