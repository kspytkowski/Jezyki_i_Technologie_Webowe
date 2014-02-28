function inicjalizacja() 
{
	ustawDate();
	ustawGodzine();
}

function ustawDate() 
{
	var dataUzytkownika = new Date();
	var rok = dataUzytkownika.getFullYear().toString();
   var miesiac = (dataUzytkownika.getMonth() + 1).toString();
	var dzien = dataUzytkownika.getDate().toString();
   if(miesiac.length == 1)
       miesiac = "0" + miesiac;
   if(dzien.length == 1)
       dzien = "0" + dzien;
	document.getElementsByName('data')[0].value = rok + "-" + miesiac + "-" + dzien; 
}

function ustawGodzine() 
{
	var godzinaUzytkownika = new Date();
	var godziny = godzinaUzytkownika.getHours().toString();
   var minuty = godzinaUzytkownika.getMinutes().toString();
   if(godziny.length == 1)
       godziny = "0" + godziny;
   if(minuty.length == 1)
       minuty = "0" + minuty;
	document.getElementsByName('godzina')[0].value = godziny + ":" + minuty; 
}

function sprawdzFormatGodzina() 
{
   var wzor = /^[0-9][0-9]:[0-9][0-9]$/;
	var godzina = document.getElementsByName('godzina')[0].value;
   if (wzor.test(godzina) != true) {
   	alert("Podano godzine w nieprawidłowym formacie (poprawnie GG-MM) - ustawiona zostaje aktualna godzina!");
   	ustawGodzine();
   }
}

function sprawdzFormatData() 
{
	var wzor = /^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$/;
   var data = document.getElementsByName('data')[0].value;
   if (wzor.test(data) != true) {
   	alert("Podano date w nieprawidłowym formacie (poprawnie RRRR-MM-DD) - ustawiona zostaje aktualna data!");
   	ustawDate();
   }
}

function sprawdzPoprawnoscGodzina() 
{
	sprawdzFormatGodzina();
	var godzina = document.getElementsByName('godzina')[0].value.split(':');	
	if (godzina[0] < 0 || godzina[0] > 23 || godzina[1] < 0 || godzina[1] > 59) {
		alert("Podano błędną godzine - ustawiona zostaje aktualna godzina!");
   	ustawGodzine();	
	}
}

function sprawdzPoprawnoscData() 
{
	sprawdzFormatData();
	var data = document.getElementsByName('data')[0].value.split('-');	
	var czyPoprawna = true;
	if (data[0] < 0 || data[1] < 0 || data[1] > 12 || data[2] < 0) {
		czyPoprawna = false;
	} else if (data[0] % 4 == 0 && data[0] % 100 != 0 || data[0] % 400 == 0) {
		if (data[1] == 2 && data[2] > 29)
			czyPoprawna = false;
		if ((data[1] == 1 || data[1] == 3 || data[1] == 5 || data[1] == 7 || data[1] == 8 || data[1] == 10 || data[1] == 12) && data[2] > 31)
			czyPoprawna = false;
		if ((data[1] == 4 || data[1] == 6 || data[1] == 9 || data[1] == 11) && data[2] > 30)
			czyPoprawna = false;
	} else {
		if (data[1] == 2 && data[2] > 28)
			czyPoprawna = false;
		if ((data[1] == 1 || data[1] == 3 || data[1] == 5 || data[1] == 7 || data[1] == 8 || data[1] == 10 || data[1] == 12) && data[2] > 31)
			czyPoprawna = false;
		if ((data[1] == 4 || data[1] == 6 || data[1] == 9 || data[1] == 11) && data[2] > 30)
			czyPoprawna = false;
	}
	if (czyPoprawna == false) {
		alert("Podano błędną date - ustawiona zostaje aktualna data!");
   	ustawDate();		
	}
}

function dodajKolejnyZalacznik(kontener)
{
	var znacznik = document.createElement('input');
	var br =  document.createElement('br');
	znacznik.setAttribute('type', 'file');
	znacznik.setAttribute('name', 'zalacznik[]');
	var kontener = document.getElementById(kontener);
	kontener.appendChild(znacznik);
	kontener.appendChild(br);
}