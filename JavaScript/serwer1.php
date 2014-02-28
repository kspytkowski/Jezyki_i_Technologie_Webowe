<?php

$wiadomosc = $_POST['wiadomosc'];
$uzytkownik = $_POST['podpis'];
$doWyslania = "";
$semafor = sem_get(321);
sem_acquire($semafor);	
$czatPlik = file("czat.txt"); // zwraca plik w tablicy w postaci linijek	 
$czatPlik[count($czatPlik)]=$uzytkownik.": ".$wiadomosc."\n";
if (count($czatPlik) > 25)
	$czatPlik = array_slice($czatPlik, 1); // zwraca tablice od elementu o indeksie 1
$PlikZapis = fopen("czat.txt","w+");
flock($PlikZapis, LOCK_EX);
for($linijka = 0; $linijka < count($czatPlik); $linijka++)
{	
	$doWyslania = $doWyslania.$czatPlik[$linijka];
	fputs($PlikZapis,$czatPlik[$linijka]);
};
flock($PlikZapis, LOCK_UN);
fclose($PlikZapis);
sem_release($semafor);
echo "$doWyslania";
exit();

?>