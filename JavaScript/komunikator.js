var xmlhttp = null;
var idSluchacz = -1;
var przerwa = 500; 

function zablokujKomunikator()
{
	var elementyKomunikatora = document.getElementsByClassName('komunikator');
	for(var i = 0; i < elementyKomunikatora.length; i++)
	{
		elementyKomunikatora[i].disabled = true;	
	}
	if (idSluchacz != -1)
		clearInterval(idSluchacz);
	if (xmlhttp != null)
		xmlhttp.abort();
	xmlhttp = null;
}

function odblokujKomunikator()
{
	var elementyKomunikatora = document.getElementsByClassName('komunikator');
	for(var i = 0; i < elementyKomunikatora.length; i++)
	{
		elementyKomunikatora[i].disabled = false;	
	}
	xmlhttp = new getXMLObject();
	xmlhttp.open("POST","serwer2.php",true);	
	xmlhttp.onreadystatechange = aktualizujKomunikator;
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.send("czas=0");
	idSluchacz = setInterval(function() { sluchacz(); }, przerwa);
}

function zmienStan()
{
	var stanKomunikatora = document.getElementsByClassName('komunikator')[0].disabled;
	
	if (stanKomunikatora == true)
		odblokujKomunikator();
	else
		zablokujKomunikator();	
}

function getXMLObject() // adaptacja kodu z wikipedii
{ 
	var xmlHttp = false;
	try 
	{
		xmlHttp = new ActiveXObject("Msxml2.XMLHTTP")
	}
	catch (e) 
	{
		try 
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP")
		}
		catch (e2) 
		{
			xmlHttp = false 
		}
	}
	if (!xmlHttp && typeof XMLHttpRequest != 'undefined') 
	{
		xmlHttp = new XMLHttpRequest();
	}
	return xmlHttp;
}

function mojAJAX() 
{
	if(xmlhttp) 
	{
		var wiadomosc = document.getElementsByName('trescKomunikatu')[0].value;
		var podpis = document.getElementsByName('podpis')[0].value;
		if (podpis != "" && wiadomosc != "") 
		{ 
			xmlhttp.open("POST","serwer1.php",true);
			xmlhttp.onreadystatechange = aktualizujKomunikator;
			xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xmlhttp.send("wiadomosc=" + wiadomosc + "&podpis=" + podpis);
			czysc();
		}
		else 
		{
			alert("Nie wprowadzono wiadomości lub podpisu - post nie wysłany");
		}
	}
}

function sluchacz() 
{
	if(xmlhttp) 
	{
		if (xmlhttp.readyState == 4) 
		{
			if(xmlhttp.status == 200) 
			{
				xmlhttp.open("POST","serwer2.php",true);
				xmlhttp.onreadystatechange = aktualizujKomunikator;
				xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xmlhttp.send();
			}
		}
	}
}

function aktualizujKomunikator() 
{
	if (xmlhttp.readyState == 4) 
	{
		if(xmlhttp.status == 200) 
		{
			document.getElementsByName('komunikator')[0].value = xmlhttp.responseText;
		}
	}
}

function czysc()
{
	document.getElementsByName('trescKomunikatu')[0].value = "";
	document.getElementsByName('podpis')[0].value = "";
}