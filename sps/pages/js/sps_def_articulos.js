// JavaScript Document
var url    = '../php/sps_def_articulos.php';
var params = 'operacion=';
var metodo = 'get';
var catalogo;

Event.observe(window, 'load', ue_inicializar , false);

function ue_inicializar()
{
  params = "operacion=ue_inicializar";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onInicializar});
  function onInicializar(respuesta)
  {  
	if (trim(respuesta.responseText) != "")
	{	
		var respuestas = respuesta.responseText.split('&');
	    num_respuesta = -1;
		// DEDICACIÓN
		num_respuesta++;
		if (trim(respuestas[num_respuesta]) != "")
		{	  
			var dedicacion = JSON.parse(respuestas[num_respuesta]);
			for (i=0; i<dedicacion.codded.length; i++)
			     $('cmbdedicacion').options[$('cmbdedicacion').options.length] = new Option(dedicacion.desded[i],dedicacion.codded[i]);
			ue_buscartipopersonal();
		}                                                                    
	 }			 
  }	// end onInicializar
  ue_cancelar();
  ue_nuevo();
} /* end function*/

function ue_buscartipopersonal()
{
  params = "operacion=ue_tipopersonal&codded="+$F('cmbdedicacion');
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onRespuesta});
  function onRespuesta(respuesta)
  {  
	if (trim(respuesta.responseText) != "")
	{	
		var respuestas = respuesta.responseText.split('&');
	    num_respuesta = -1;
		// TIPO PERSONAL
		num_respuesta++;
		if (trim(respuestas[num_respuesta]) != "")
		{	  
			$('cmbtipopersonal').options.length=0;
			var tipopersonal = JSON.parse(respuestas[num_respuesta]);
			for (i=0; i<tipopersonal.codtipper.length; i++)
			     $('cmbtipopersonal').options[$('cmbtipopersonal').options.length] = new Option(tipopersonal.destipper[i],tipopersonal.codtipper[i]);
		}                                                                    
	 }			 
  }	// end onInicializar
} /* end function*/


function ue_cancelar()
{
   document.form1.reset();
   limpiar_datos_detalle();
   limpiar_tabla_detalle();
   limpiar_datos_detalle_cuenta();
   limpiar_tabla_detalle_cuenta();
   habilitar("txtid_art,txtnumart,txtfecvig,btnbusart");
   deshabilitar("txtconart,txtnumlitart,cmboperador,txtcanmes,txtdiasal,cmbtiempo,cmbcondicion,cmbestacu,txtdiaacu");
   $('txtdiaacu').value = "0,00";
   scrollTo(0,0);
}

function ue_nuevo()
{	
  function onNuevo(respuesta)
  {    
        ue_cancelar();
	$('hidguardar').value = "insertar";
	$('txtid_art').value  = trim(respuesta.responseText);
	deshabilitar("txtid_art");
    habilitar("txtnumart,txtfecvig,txtconart,txtnumlitart,cmboperador,txtcanmes,txtdiasal,cmbtiempo,cmbcondicion,cmbestacu,txtdiaacu");
	$('txtnumart').focus();
  }	
  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onNuevo});
}

function ue_guardar()
{
	lb_valido=true;
	
	var la_objetos=new Array("txtid_art","txtnumart","txtfecvig","txtconart");
	var la_mensajes=new Array("el código", "el número","la fecha vigente","el concepto" );
	lb_valido = valida_datos_llenos(la_objetos, la_mensajes);
	if(lb_valido)
	{
		if(($F('hidguardar')== "modificar")&&($F('hidpermisos').indexOf('m',0)<0 ))
		{
			alert("No tiene permiso para actualizar información.");
		}
		else
		{
			function onGuardar(respuesta)
			{
				ue_cancelar();
				if(trim(respuesta.responseText) !="" )
				{alert(respuesta.responseText);}
			}
			//Arreglo de detalles 
			var art_detalle = new Array();
			var filas = $('dt_art').getElementsByTagName("tr");
			for (f=1; f<filas.length; f++)
			{
				var IdFila   = filas[f].getAttribute("id");
				var columnas = filas[f].getElementsByTagName("td");
				var dtart = 
				{
				  "id_art"      : $F('txtid_art'),
				  "numart"      : $F('txtnumart'),
				  "fecvig"      : $F('txtfecvig'),	
				  "conart"      : $F('txtconart'),
				  "estpro"      : $F('chkestpro'),
				  "valmaxpro"   : $F('txtvalmaxpro'),
				  "numlitart"   : columnas[0].innerHTML,
				  "operador"    : columnas[1].innerHTML,
				  "canmes"      : columnas[2].innerHTML,
				  "diasal"      : columnas[3].innerHTML,
				  "tiempo"      : columnas[4].innerHTML,
				  "condicion"   : columnas[5].innerHTML,
				  "estacu"      : columnas[6].innerHTML,
				  "diaacu"      : columnas[7].innerHTML
				}				
				art_detalle[f-1] = dtart;	
			}
			//Arreglo de cuentas
			var art_cuentas = new Array();
			var filas = $('dt_cuenta').getElementsByTagName("tr");
			for (f=1; f<filas.length; f++)
			{
				var IdFila   = filas[f].getAttribute("id");
				var columnas = filas[f].getElementsByTagName("td");
				var dtcuentas = 
				{
				  "id_art"      : $F('txtid_art'),
				  "numart"      : $F('txtnumart'),
				  "fecvig"      : $F('txtfecvig'),	
				  "codded"      : columnas[0].innerHTML,
				  "codtipper"   : columnas[1].innerHTML,
				  "spg_cuenta"      : columnas[2].innerHTML
				}				
				art_cuentas[f-1] = dtcuentas;	
			}
			var art =
			{
				"id_art":$F('txtid_art'),
				"numart":$F('txtnumart'),
				"fecvig":$F('txtfecvig'),
				"conart":$F('txtconart'),
				"dt_art":art_detalle,
				"dt_cuenta":art_cuentas
			};
			
			var objeto = JSON.stringify(art);
			params = "operacion=ue_guardar&objeto="+objeto+"&insmod="+$F('hidguardar');
			new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onGuardar});
		}
	}	
}
function ue_buscar()
{
	pagina="sps_cat_articulos.html.php";
	catalogo = popupWin(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=620,height=300,resizable=yes,location=no,top=0,left=0");
}

function ue_cargarCatalogo(arr_datos)
{
  ue_cancelar();
  $('hidguardar').value = "modificar";
  var cajas = new Array('txtid_art','txtnumart','txtfecvig','txtconart','chkestpro','txtvalmaxpro');
  for (i=0; i<cajas.length; i++)
  {
	  if(cajas[i]=='chkestpro')
	  {
		  if (trim(arr_datos[i])==1)
		  {
		  	$(cajas[i]).checked = true;
		  }
	  }
	  else
	  {
	  	$(cajas[i]).value = trim(arr_datos[i]);
	  }
  }
  ue_chequear_articulo();
  ue_chequear_cuentas();
  if ((navigator.appName == "Netscape"))
  {	 
	  eval("$error_provocado;"); //Esta linea de abajo es un error provocado intencionalmente
  }
}

function ue_agregar_detalle()  
{
  lb_valido=true;
  var la_objetos =new Array ("txtnumlitart","cmboperador","txtcanmes","txtdiasal","cmbtiempo","cmbcondicion","cmbestacu");  
  var la_mensajes=new Array ("el literal ","Operador","cantidad en meses", "dias de salario", "el tiempo", "la condición ", "el estatus ");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes );
  if ((lb_valido) && ($F('cmbestacu') == "S"))
  {
	var la_objetos =new Array ("txtdiaacu");  
    var la_mensajes=new Array ("los dias acumulados");
    lb_valido = valida_datos_llenos(la_objetos,la_mensajes );
  }
  else
  {
	  $('txtdiaacu').value = "0,00";  
  }
  if(lb_valido)
  {
      var nuevaFila = clonarFila("dt_art","fila0");
      copiarDatosDetalle(nuevaFila);
      limpiar_datos_detalle();
      try{$('txtnumlitart').focus();}
	  catch(e){}
  }
}

function limpiar_datos_detalle()
{
  $('txtnumlitart').value = "";
  $('txtcanmes').value = "";
  $('txtdiasal').value = "";
  $('txtdiaacu').value = "";
  $('cmbestacu').value = "";
}

function limpiar_tabla_detalle()
{  
  var FILAS = $("dt_art").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{eliminarFila("dt_art",FILAS[f].id)}
  }
}

function copiarDatosDetalle(Fila)
{
  var boton =  '<div class="menuBar">';
      boton += '<a class="menuButton" href="javascript:eliminarFila(\'dt_art\','+Fila.id+');">';
	  boton += '<img src="../../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" width="15" height="15" border="0" align="absmiddle">';
	  boton += '</a>';
	  boton += '</div>';  
  var ultima_columna = boton ;

  valores = new Array($F("txtnumlitart"),$F('cmboperador'),$F("txtcanmes"),$F("txtdiasal"),$F('cmbtiempo'),$F("cmbcondicion"),$F("cmbestacu"),$F("txtdiaacu"),ultima_columna);
  alineaciones = new Array("center","center","center","center","center","center","center","center","center");
  
  for (cnt=0; cnt < valores.length; cnt++)
  {
	  agregarColumna(Fila,valores[cnt],alineaciones[cnt]);
  }
}

function ue_chequear_articulo()
{
  if ( (ue_valida_null($('txtid_art')))&&(ue_valida_null($('txtnumart')))&&(ue_valida_null($('txtfecvig'))) )
  {
	params = "operacion=ue_chequear_articulo&id_art="+trim($F('txtid_art'))+"&numart="+$F('txtnumart')+"&fecvig="+$F('txtfecvig');  
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequear});		
  }
  
  function onChequear(respuesta)
  {            	  	
  	  deshabilitar("txtid_art,txtnumart,txtfecvig,btnbusart");
	  habilitar("txtconart,txtnumlitart,cmboperador,txtcanmes,txtdiasal,cmbtiempo,cmbcondicion,cmbestacu,txtdiaacu");
	  $('txtnumlitart').focus();		  
	  $('hidguardar').value = "modificar";
	  if (trim(respuesta.responseText) != "")
	  {       
		  //Mostramos los Datos del Detalle
		  var det_articulos = JSON.parse(respuesta.responseText);
		  campos = "numlitart,operador,canmes,diasal,tiempo,condicion,estacu,diaacu";
		  campos = campos.split(',');
		  var cajas = new Array("txtnumlitart","cmboperador","txtcanmes","txtdiasal","cmbtiempo","cmbcondicion","cmbestacu","txtdiaacu");
		  //Limpiamos los Datos del Detalle
		  limpiar_detalle_articulos(true);
		  for (f=0; f<det_articulos.numlitart.length; f++)
		  { 
		    for (c=0; c<campos.length; c++)
		    {
		  	  eval(cajas[c]+".value = trim(det_articulos."+campos[c]+"["+f+"]);");
			  if((campos[c]=='diasal')||(campos[c]=='diaacu'))
			  {
				  ue_getformat(eval(cajas[c]));
			  }
		    }
			ue_agregar_detalle();
		  }
	   }
	   try
	   {catalogo.close();}
	   catch(e)
	   {}
	} //end of onChequear(respuesta)
}

function limpiar_detalle_articulos()
{  
  var FILAS = $("dt_art").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{eliminarFila("dt_art",FILAS[f].id)}
  }
  if ((arguments.length <= 0))
  {deshabilitar("txtid_art,txtnumart,txtfecvig");}
}

function ue_eliminar()
{
  lb_valido=true;
  var la_objetos=new Array ("txtid_art", "txtnumart", "txtfecvig");
  var la_mensajes=new Array ("el Código del Articulo", "el número de Articulo","la fecha vigente del Articulo");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes);
  if(lb_valido)
  {
	if (confirm("¿ Esta seguro de Eliminar este Registro ?"))
	{
	  function onEliminar(respuesta)
	  {
		ue_cancelar();
		alert(respuesta.responseText);
	  }
	  params = "operacion=ue_eliminar&id_art="+$F('txtid_art')+"&numart="+$F('txtnumart')+"&fecvig="+$F('txtfecvig');
	  new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onEliminar});
	}
	else
	{
	  ue_cancelar();
	  alert("Eliminación Cancelada !!!");	  
	}
  };
}

function ue_buscar_spg_cuenta()
{
	pagina="sps_cat_spg_cuenta.html.php";
    catalogo = popupWin(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=440,height=250,resizable=yes,location=no,top=0,left=0");  
}

function ue_cargar_registro_spg_cuenta(arr_datos)
{   
	  var cajas = new Array('txtcuepre','txtdencuepre');
	  for (i=0; i<cajas.length; i++)
	  {
		  $(cajas[i]).value = arr_datos[i];
	  }
	  deshabilitar("txtcuepre, txtdencuepre");
}


function ue_agregar_detallecuenta()  
{
  lb_valido=true;
  var la_objetos =new Array ("cmbdedicacion","cmbtipopersonal","txtcuepre");  
  var la_mensajes=new Array ("La dedicación ","El tipo de personal","La cuenta presupuestaria");
  lb_valido = valida_datos_llenos(la_objetos,la_mensajes );
  if(lb_valido)
  {
      var nuevaFila = clonarFila("dt_cuenta","filacuenta0");
      copiarDatosDetalleCuenta(nuevaFila);
      limpiar_datos_detalle_cuenta();
      try{$('cmbdedicacion').focus();}
	  catch(e){}
  }
}

function copiarDatosDetalleCuenta(Fila)
{
  var boton =  '<div class="menuBar">';
      boton += '<a class="menuButton" href="javascript:eliminarFila(\'dt_cuenta\','+Fila.id+');">';
	  boton += '<img src="../../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" width="15" height="15" border="0" align="absmiddle">';
	  boton += '</a>';
	  boton += '</div>';  
  var ultima_columna = boton ;

  valores = new Array($F("cmbdedicacion"),$F('cmbtipopersonal'),$F("txtcuepre"),ultima_columna);
  alineaciones = new Array("center","center","center","center");
  
  for (cnt=0; cnt < valores.length; cnt++)
  {
	  agregarColumna(Fila,valores[cnt],alineaciones[cnt]);
  }
}

function limpiar_datos_detalle_cuenta()
{
  $('txtcuepre').value = "";
  $('txtdencuepre').value = "";
}

function limpiar_tabla_detalle_cuenta()
{  
  var FILAS = $("dt_cuenta").rows;
  if(FILAS.length > 1)
  {
	for (f=(FILAS.length-1); f>0; f--)
	{eliminarFila("dt_cuenta",FILAS[f].id)}
  }
}


function ue_chequear_cuentas()
{
  if ( (ue_valida_null($('txtid_art')))&&(ue_valida_null($('txtnumart')))&&(ue_valida_null($('txtfecvig'))) )
  {
	params = "operacion=ue_chequear_cuentas&id_art="+trim($F('txtid_art'))+"&numart="+$F('txtnumart')+"&fecvig="+$F('txtfecvig');  
	new Ajax.Request(url,{method:metodo,parameters:params,onComplete:onChequear});		
  }
  
  function onChequear(respuesta)
  {            	  	
	 if (trim(respuesta.responseText) != "")
	  {       
		  //Mostramos los Datos del Detalle
		  var det_cuenta = JSON.parse(respuesta.responseText);
		  campos = "codded,codtipper,spg_cuenta";
		  campos = campos.split(',');
		  var cajas = new Array("cmbdedicacion","cmbtipopersonal","txtcuepre");
		  //Limpiamos los Datos del Detalle
		  limpiar_tabla_detalle_cuenta();
		  for (f=0; f<det_cuenta.codded.length; f++)
		  { 
		    for (c=0; c<campos.length; c++)
		    {
		  	  eval(cajas[c]+".value = trim(det_cuenta."+campos[c]+"["+f+"]);");
		    }
			ue_agregar_detallecuenta();
		  }
	   }
	   try
	   {}
	   catch(e)
	   {}
	} //end of onChequear(respuesta)
}
