// JavaScript Document
//--------------------------------------------------------
//	Función que Instancia el objeto ajax
//--------------------------------------------------------
function objetoAjax()
{
	var xmlhttp=false;
	try
	{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E)
		{
			xmlhttp = false;
  		}
	}
	if(!xmlhttp && typeof XMLHttpRequest!='undefined')
	{
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}


//--------------------------------------------------------
//	Función que actualiza el total de las filas de un campo de un pagina opener
//--------------------------------------------------------
function ue_calcular_total_fila_opener(campo)
{
	existe=true;
	li_i=1;
	while(existe)
	{
		existe=opener.document.getElementById(campo+li_i);
		if(existe!=null)
		{
			li_i=li_i+1;
		}
		else
		{
			existe=false;
			li_i=li_i-1;
		}
	}
	return li_i
}

//--------------------------------------------------------
//	Función que valida el correo electrónico
//--------------------------------------------------------
function ue_validarcorreo(theElement)
{
	var s = theElement.value;
	var filter=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
	if (s.length == 0 ) return true;
	if (filter.test(s))
	{
		return true;
	}
	else
	{
		alert("Ingrese una direccion de correo valida");
		theElement.focus();
		return false;
	}
}

//--------------------------------------------------------
//	Función que actualiza el total de las filas de un campo local
//--------------------------------------------------------
function ue_calcular_total_fila_local(campo)
{
	existe=true;
	li_i=1;
	while(existe)
	{
		existe=document.getElementById(campo+li_i);
		if(existe!=null)
		{
			li_i=li_i+1;
		}
		else
		{
			existe=false;
			li_i=li_i-1;
		}
	}
	return li_i
}

//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

//--------------------------------------------------------
//	Función que rellena un campo con ceros a la izquierda
//--------------------------------------------------------
function ue_rellenarcampo(valor,maxlon)
{
	var total;
	var auxiliar;
	var longitud;
	var index;
	
	total=0;
    auxiliar=valor.value;
	longitud=valor.value.length;
	total=maxlon-longitud;
	if (total < maxlon)
	{
		for (index=0;index<total;index++)
		{
		   auxiliar="0"+auxiliar;      
		}
		valor.value = auxiliar;
	}
}

//--------------------------------------------------------
//	Función que formatea un número
//--------------------------------------------------------
function ue_formatonumero(fld, milSep, decSep, e)
{ 
	var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
	
	if (fld.readOnly==true) return false; 
	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
	if (whichCode == 127) return true; // Suprimir
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
    	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
    	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
      } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
     	fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
}

//--------------------------------------------------------
//	Función que valida que el texto no esté vacio
//--------------------------------------------------------
function ue_validarvacio(valor)
{
	var texto;
	while(''+valor.charAt(0)==' ')
	{
		valor=valor.substring(1,valor.length)
	}
	texto = valor;
	return texto;
}

//--------------------------------------------------------
//	Función que convierte los montos para poder hacer calculos
//--------------------------------------------------------
function ue_formato_calculo(monto)
{
	while(monto.indexOf('.')>0)
	{//Elimino todos los puntos o separadores de miles
		monto=monto.replace(".","");
	}
	monto=monto.replace(",",".");	
	return monto;
}

/*****************************************************************
  Funcion que compara dos cajas de textos con montos o dos montos 
  y devuelve si la segunda es mayor o igual que la primera.
  En caso contrario muestra un mensaje
******************************************************************/
function compararMontos(cajaMonto1,cajaMonto2,mensaje) 
{
	f = document.form1;	
	var lb_caja1null = true;
	var lb_caja2null = true;
	if (document.getElementById(cajaMonto1) == null)
	{monto1 = parseFloat(ue_formato_calculo(cajaMonto1));}
	else
	{monto1 = parseFloat(ue_formato_calculo(eval("f."+cajaMonto1+".value;"))); lb_caja1null= false;}
	if (document.getElementById(cajaMonto2) == null)
	{monto2 = parseFloat(ue_formato_calculo(cajaMonto2));}
	else
	{monto2 = parseFloat(ue_formato_calculo(eval("f."+cajaMonto2+".value;"))); lb_caja2null= false;}

	var booleano;
	if (monto2 >= monto1)
	{
		booleano = true;
	}
	else
	{
		booleano = false;
	}
	if ((booleano == false) && (mensaje != ""))
	{
		alert(mensaje);		
		if (lb_caja1null == false)
		{
		  eval("f."+cajaMonto1+".focus();");
		  if (eval("f."+cajaMonto1+".type") == "text")
		  {eval("f."+cajaMonto1+".select();");}
		}
		else if (lb_caja2null == false)
		{
		  eval("f."+cajaMonto2+".focus();");
		  if (eval("f."+cajaMonto2+".type") == "text")
		  {eval("f."+cajaMonto2+".select();");}
		}
	}
	return booleano;
}

