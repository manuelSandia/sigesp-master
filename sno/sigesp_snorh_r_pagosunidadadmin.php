<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	require_once("sigesp_sno.php");
	require_once("class_folder/class_funciones_nomina.php");
	$io_sno=new sigesp_sno();
	$io_fun_nomina=new class_funciones_nomina();
	$ls_logusr=$_SESSION["la_logusr"];	
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_pagosunidadadmin.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_sno->uf_select_config("SNR","REPORTE","PAGO_UNIDAD","sigesp_snorh_rpp_pagounidad.php","C");
	$ls_reporte2=$io_sno->uf_select_config("SNR","REPORTE","PAGO_UNIDAD_DETALLADO","sigesp_snorh_rpp_pagounidad_detallado.php","C");
	$ls_reporte3=$io_sno->uf_select_config("SNR","REPORTE","PAGO_UNIDAD_EXCEL","sigesp_snorh_rpp_pagounidad_excel.php","C");
	$ls_reporte4=$io_sno->uf_select_config("SNR","REPORTE","PAGO_UNIDAD_DETALLADO_EXCEL","sigesp_snorh_rpp_pagounidad_detallado_excel.php","C");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Reporte Consolidado de Pagos por Unidad Administrativa</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de N�mina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" title="Excel" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="746" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="744" height="136">
      <p>&nbsp;</p>
      <table width="696" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Reporte Consolidado de Pagos por Unidad Administrativa </td>
        </tr>
        <tr style="display:none">
          <td width="116" height="22"><div align="right"></div></td>
          <td><div align="left">
          </div>
          <td>        
          <td>        </tr>
        <tr>
          <td height="22"><div align="right">N&oacute;mina Desde </div></td>
          <td width="215"><div align="left">
            <input name="txtcodnomdes" type="text" id="txtcodnomdes" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarnominadesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>          </div>
          <td width="119"><div align="right">N&oacute;mina Hasta </div>
          <td width="236"><div align="left">
            <input name="txtcodnomhas" type="text" id="txtcodnomhas" size="13" maxlength="10" readonly>
          <a href="javascript: ue_buscarnominahasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </div>        </tr>
        <tr>
          <td height="22"><div align="right">Periodo Desde </div></td>
          <td><div align="left">
            <input name="txtperdes" type="text" id="txtperdes" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarperiododesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>          
            <input name="txtfecdesper" type="hidden" id="txtfecdesper">
          </div>
          <td><div align="right">Periodo Hasta          </div>
          <td><div align="left">
            <input name="txtperhas" type="text" id="txtperhas" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarperiodohasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>
            <input name="txtfechasper" type="hidden" id="txtfechasper">
          </div>        </tr>
        <tr>
          <td height="22"><div align="right">Unidad Desde</div></td>
          <td>        <div align="left">
            <input name="txtcodunides" type="text" id="txtcodunides" size="30" readonly>
            <a href="javascript: ue_buscarunidaddes();"><img id="banco" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div>          
          <td><div align="right">Unidad Hasta          </div>          
          <td> <input name="txtcodunihas" type="text" id="txtcodunihas" size="30"  readonly>
          <a href="javascript: ue_buscarunidadhas();"><img id="banco" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>        </tr>
        <tr>
          <td height="22"><div align="right">Dedicaci&oacute;n Desde </div></td>
          <td><div align="left">
            <input name="txtcoddeddes" type="text" id="txtcoddeddes" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscardedicaciondes();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>        
          </div>
          <td><div align="right">Dedicaci&oacute;n Hasta         
          </div>
          <td><div align="left">
            <input name="txtcoddedhas" type="text" id="txtcoddedhas" size="6" maxlength="3" readonly>
          <a href="javascript: ue_buscardedicacionhas();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </div>          </tr>
        <tr>
          <td height="22"><div align="right">Tipo Personal Desde </div></td>
          <td><div align="left">
            <input name="txtcodtipperdes" type="text" id="txtcodtipperdes" size="7" maxlength="4" readonly>
            <a href="javascript: ue_buscartipopersonaldes();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>        
          </div>
          <td><div align="right">Tipo Personal Hasta         
          </div>
          <td><div align="left">
            <input name="txtcodtipperhas" type="text" id="txtcodtipperhas" size="7" maxlength="4" readonly>
          <a href="javascript: ue_buscartipopersonalhas();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </div>          </tr>
        <tr>
          <td height="22"><div align="right">Conceptos</div></td>
          <td colspan="3"><p><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0"> <a href="javascript:uf_selectconcepto();"> Buscar Conceptos por lote</a>
              <input type="text" id="txtcodconc" name="txtcodconc"  size="100" readonly> 
            </p>
          <p>(Solo para el reporte detallado)            </p>          </tr>
        <tr class="titulo-celdanew">
          <td height="22" colspan="4">Tipo de Reporte                         </td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"><select name="cmbtiprep" id="ccmbtiprep">
            <option value="1" selected>Reporte Pago de Por Unidad Administrativa</option>
            <option value="2">Reporte Pago de Por Unidad Administrativa Detallado</option>
          </select>          </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="6"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo de la N&oacute;mina </div></td>
          <td><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
          <td><div align="right">C&oacute;digo Unidad Administrativa </div></td>
          <td><input name="rdborden" type="radio" class="sin-borde" value="3"></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre de la N&oacute;mina </div></td>
          <td><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
          <td><div align="right">Nombre Unidad Administrativa </div></td>
          <td><input name="rdborden" type="radio" class="sin-borde" value="4"></td>
        </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="5"><div align="right">
            <input name="tipo" type="hidden" id="tipo" value="consolidadopagounidad">
            <input name="operacion" type="hidden" id="operacion">          		
            <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
            <input name="reporte2" type="hidden" id="reporte2" value="<?php print $ls_reporte2;?>">
            <input name="reporte3" type="hidden" id="reporte3" value="<?php print $ls_reporte3;?>">
            <input name="reporte4" type="hidden" id="reporte4" value="<?php print $ls_reporte4;?>">
			</div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		reporte=f.reporte.value;
		reporte2=f.reporte2.value;
		codunides=f.txtcodunides.value;
		codunihas=f.txtcodunihas.value;		
		codnomdes=f.txtcodnomdes.value;
		codnomhas=f.txtcodnomhas.value;
		codperdes=f.txtperdes.value;
		codperhas=f.txtperhas.value;			
		tiporeporte=f.cmbtiprep.value;	
		fecdesper=f.txtfecdesper.value;	
		fechasper=f.txtfechasper.value;	
		coddeddes=f.txtcoddeddes.value;	
		coddedhas=f.txtcoddedhas.value;	
		codtipperdes=f.txtcodtipperdes.value;	
		codtipperhas=f.txtcodtipperhas.value;	
		orden="";
		if(f.rdborden[0].checked)
		{
			orden="1";
		}
		if(f.rdborden[1].checked)
		{
			orden="2";
		}		
		if(f.rdborden[2].checked)
		{
			orden="3";
		}		
		if(f.rdborden[3].checked)
		{
			orden="4";
		}		
		switch (tiporeporte)
		{
			case "1":		
				if((codnomdes!="")&&(codnomhas!="")&&(codperdes!="")&&(codperhas!="")&&(codunides!="")&&(codunihas!=""))
				{
					pagina="reportes/"+reporte+"?codunides="+codunides+"&codunihas="+codunihas;
					pagina=pagina+"&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"&codperdes="+codperdes+"&codperhas="+codperhas;
					pagina=pagina+"&orden="+orden+"&fecdesper="+fecdesper+"&fechasper="+fechasper;
					pagina=pagina+"&coddeddes="+coddeddes+"&coddedhas="+coddedhas+"&codtipperdes="+codtipperdes+"&codtipperhas="+codtipperhas;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				else
				{
					alert("Debe seleccionar las n�minas, los per�odos y las Unidades Administrativas.");
				}
			break;
			
			case "2":
				conceptos=f.txtcodconc.value;			
				if((codnomdes!="")&&(codnomhas!="")&&(codperdes!="")&&(codperhas!="")&&(codunides!="")&&(codunihas!="")&&(conceptos!=""))
				{
					pagina="reportes/"+reporte2+"?codunides="+codunides+"&codunihas="+codunihas;
					pagina=pagina+"&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"&codperdes="+codperdes+"&codperhas="+codperhas;
					pagina=pagina+"&orden="+orden+"&conceptos="+conceptos+"&fecdesper="+fecdesper+"&fechasper="+fechasper;
					pagina=pagina+"&coddeddes="+coddeddes+"&coddedhas="+coddedhas+"&codtipperdes="+codtipperdes+"&codtipperhas="+codtipperhas;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				else
				{
					alert("Debe seleccionar las n�minas, los per�odos, las Unidades Administrativas y los conceptos.");
				}
			break;
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_openexcel()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		reporte3=f.reporte3.value;
		reporte4=f.reporte4.value;
		codunides=f.txtcodunides.value;
		codunihas=f.txtcodunihas.value;		
		codnomdes=f.txtcodnomdes.value;
		codnomhas=f.txtcodnomhas.value;
		codperdes=f.txtperdes.value;
		codperhas=f.txtperhas.value;			
		tiporeporte=f.cmbtiprep.value;	
		fecdesper=f.txtfecdesper.value;	
		fechasper=f.txtfechasper.value;	
		coddeddes=f.txtcoddeddes.value;	
		coddedhas=f.txtcoddedhas.value;	
		codtipperdes=f.txtcodtipperdes.value;	
		codtipperhas=f.txtcodtipperhas.value;	
		orden="";
		if(f.rdborden[0].checked)
		{
			orden="1";
		}
		if(f.rdborden[1].checked)
		{
			orden="2";
		}		
		if(f.rdborden[2].checked)
		{
			orden="3";
		}		
		if(f.rdborden[3].checked)
		{
			orden="4";
		}		
		switch (tiporeporte)
		{
			case "1":		
				if((codnomdes!="")&&(codnomhas!="")&&(codperdes!="")&&(codperhas!="")&&(codunides!="")&&(codunihas!=""))
				{
					pagina="reportes/"+reporte3+"?codunides="+codunides+"&codunihas="+codunihas;
					pagina=pagina+"&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"&codperdes="+codperdes+"&codperhas="+codperhas;
					pagina=pagina+"&orden="+orden+"&fecdesper="+fecdesper+"&fechasper="+fechasper;
					pagina=pagina+"&coddeddes="+coddeddes+"&coddedhas="+coddedhas+"&codtipperdes="+codtipperdes+"&codtipperhas="+codtipperhas;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				else
				{
					alert("Debe seleccionar las n�minas, los per�odos y las Unidades Administrativas.");
				}
			break;
			
			case "2":
				conceptos=f.txtcodconc.value;			
				if((codnomdes!="")&&(codnomhas!="")&&(codperdes!="")&&(codperhas!="")&&(codunides!="")&&(codunihas!="")&&(conceptos!=""))
				{
					pagina="reportes/"+reporte4+"?codunides="+codunides+"&codunihas="+codunihas;
					pagina=pagina+"&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"&codperdes="+codperdes+"&codperhas="+codperhas;
					pagina=pagina+"&orden="+orden+"&conceptos="+conceptos+"&fecdesper="+fecdesper+"&fechasper="+fechasper;
					pagina=pagina+"&coddeddes="+coddeddes+"&coddedhas="+coddedhas+"&codtipperdes="+codtipperdes+"&codtipperhas="+codtipperhas;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				else
				{
					alert("Debe seleccionar las n�minas, los per�odos, las Unidades Administrativas y los conceptos.");
				}
			break;
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}


function ue_buscarnominadesde()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=pagounides","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarnominahasta()
{
	f=document.form1;
	if(f.txtcodnomdes.value!="")
	{
		window.open("sigesp_snorh_cat_nomina.php?tipo=pagounihas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una n�mina desde.");
	}
}
function ue_buscarperiododesde()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((codnomdes!="")&&(codnomhas!=""))
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=replisbandes&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de n�minas.");
	}
}

function ue_buscarperiodohasta()
{
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((codnomdes!="")&&(codnomhas!="")&&(f.txtperdes.value!=""))
	{
		window.open("sigesp_sno_cat_hperiodo.php?tipo=replisbanhas&codnom="+codnomdes+"&codnomhas="+codnomhas+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de n�minas y aun per�odo desde.");
	}
}

function ue_buscarunidaddes()
{
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	codperides=f.txtperdes.value;
	codperihas=f.txtperhas.value;
	if((codnomdes!="")&&(codnomhas!="")&&(codperides!="")&&(codperihas!=""))
	{
		window.open("sigesp_sno_cat_huni_ad.php?tipo=pagounides&codnomde="+codnomdes+"&codnomhas="+codnomhas+"&codperides="+codperides+"&codperihas="+codperihas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de n�minas y de periodo.");
	}
}
function ue_buscarunidadhas()
{
	codunides=f.txtcodunides.value;
	
	if(codunides!="")
	{
			window.open("sigesp_sno_cat_huni_ad.php?tipo=pagounihas&codnomde="+codnomdes+"&codnomhas="+codnomhas+"&codperides="+codperides+"&codperihas="+codperihas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de Unidad desde");
	}
}
function uf_selectconcepto()
{   
	f=document.form1;
	codnomdes=f.txtcodnomdes.value;
	codnomhas=f.txtcodnomhas.value;
	if((codnomdes!="")&&(codnomhas!=""))
	{
		window.open("sigesp_snorh_sel_catconcepto.php?codnomdes="+codnomdes+"&codnomhas="+codnomhas,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de n�minas.");
	}
}

function ue_buscardedicaciondes()
{
	f=document.form1;
	window.open("sigesp_snorh_cat_dedicacion.php?tipo=reppagunides","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscardedicacionhas()
{
	f=document.form1;
	coddeddes=f.txtcoddeddes.value;
	if(coddeddes!="")
	{
		window.open("sigesp_snorh_cat_dedicacion.php?tipo=reppagunihas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un rango de Dedicaci�n.");
	}

}

function ue_buscartipopersonaldes()
{
	f=document.form1;
	coddeddes = ue_validarvacio(f.txtcoddeddes.value);
	coddedhas = ue_validarvacio(f.txtcoddedhas.value);
	if ((coddeddes!="")&&(coddedhas!=""))
	{
		window.open("sigesp_snorh_cat_tipopersonal.php?tipo=reppagunides&coddeddes="+coddeddes+"&coddedhas="+coddedhas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe Seleccionar un rango de Dedicaci�n");
	}
}

function ue_buscartipopersonalhas()
{
	f=document.form1;
	coddeddes = ue_validarvacio(f.txtcoddeddes.value);
	coddedhas = ue_validarvacio(f.txtcoddedhas.value);
	codtipperdes=f.txtcodtipperdes.value;
	if ((coddeddes!="")&&(coddedhas!="")&&(codtipperdes!=""))
	{
		window.open("sigesp_snorh_cat_tipopersonal.php?tipo=reppagunihas&coddeddes="+coddeddes+"&coddedhas="+coddedhas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe Seleccionar un rango de Dedicaci�n y de tipo de personal");
	}
}

</script> 
</html>