<?php

if (isset($_POST['czas']) && $_POST['czas'] == 0)
	$czasWyslaniaZapytania = date("Y-m-d h:i:s", $_POST['czas']);
else
	$czasWyslaniaZapytania = date("Y-m-d h:i:s");
while(1) {
	clearstatcache();
	$semafor = sem_get(321);
	sem_acquire($semafor);	
	$czasModyfikacjiPlikuCzat = filemtime("czat.txt");
	$czasModyfikacjiPlikuCzat = date("Y-m-d h:i:s", $czasModyfikacjiPlikuCzat);
	if ($czasModyfikacjiPlikuCzat > $czasWyslaniaZapytania)
	{
		$doWyslania = "";
		$PlikOdczyt = fopen("czat.txt","r");
		flock($PlikOdczyt, LOCK_EX);
		while ($linijka = fgets($PlikOdczyt))
		{
			$doWyslania = $doWyslania.$linijka;
		};
		flock($PlikOdczyt,LOCK_UN);
		fclose($PlikOdczyt);
		sem_release($semafor);
		echo "$doWyslania";
		exit();
	}
	sem_release($semafor);
	sleep(1);
}

?>