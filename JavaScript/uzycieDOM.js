function inicjalizacjaStyl() 
{
	var tytulStylu = znajdzCiastko('wyswietlanyStyl');
    if (tytulStylu != null) 
    {
        zmienStyl(tytulStylu);
    }
}

function wyswietListeStyli()
{
	var listaStyli = document.createElement("div");
	listaStyli.id = "listaStyli";	
	var ul = document.createElement('ul');
	style = document.getElementsByTagName("link");			
	for(var i = 0; i < style.length ; i++) 
	{	
		var li = document.createElement('li');
		var a = document.createElement('a');
		a.setAttribute("onclick","zmienStyl('"+style[i].title+"');");
		a.innerHTML = style[i].title; // wyswietli tytul w pasku
		li.appendChild(a);
		ul.appendChild(li);
	}	
	listaStyli.appendChild(ul);
	document.getElementById('pozostale_cwiczenia').appendChild(listaStyli);
}

function ustawCiastko(nazwa, wartosc, sciezka) 
{   
	var aktualnaData = new Date();     
	aktualnaData.setTime(aktualnaData.getTime() + 30 * 24 * 60 * 60 * 1000); //ustawia ciastko na 30 dni     
	czasWygasniecia = aktualnaData.toGMTString();   
	czasWygasniecia = '; expires=' + czasWygasniecia;   
	sciezka = '; path=' + sciezka;
	document.cookie = nazwa + '=' + encodeURI(wartosc) + czasWygasniecia + sciezka; 
}
  
function znajdzCiastko(nazwa) 
{   
	var cookie = document.cookie;   
	if(cookie.indexOf(nazwa + '=') < 0) 
	{ 
		return null; 
	}   
	var start = cookie.indexOf(nazwa + '=') + nazwa.length + 1;   
	var koniec = cookie.substring(start, cookie.length);   
	koniec = (koniec.indexOf(';') < 0) ? cookie.length : start + koniec.indexOf(';');   
	return decodeURI(cookie.substring(start, koniec)); 
}  

function znajdzIAktywujStyl(nazwa) 
{   
	var link = document.getElementsByTagName('link');
	var tytul;	
	for(var i = 0; i < link.length; i++ ) 
	{     
		tytul = link[i].getAttribute('title');
		if (tytul === nazwa)
			link[i].disabled = false; 
		else
			link[i].disabled = true; 
	} 
}  

function zmienStyl(nazwa) 
{  
	ustawCiastko('wyswietlanyStyl', nazwa, '~/');   
	znajdzIAktywujStyl(nazwa); 
}  