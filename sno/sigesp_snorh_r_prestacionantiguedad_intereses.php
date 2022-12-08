<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_prestacionantiguedad_intereses.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_ruta="txt/general";
	@mkdir($ls_ruta,0755);
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$ls_reporte=$io_sno->uf_select_config("SNR","REPORTE","PRESTACION_ANTIGUEDAD_INTERESES","sigesp_snorh_rpp_prestacionantiguedad_intereses.php","C");
	$li_calintpreant=trim($io_sno->uf_select_config("SNO","NOMINA","CALCULO_INT_FIDEICOISO","0","I"));
	unset($io_sno);		
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
<title >Reporte Listado de Prestaci&oacute;n de Antiguedad (Intereses)</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	if($li_calintpreant=="0")
	{
		print("<script language=JavaScript>");
		print(" alert('El Sistema no est� definido para calcular los intereses de Prestaci�n Antiguedad.');");
		print(" location.href='sigespwindow_blank.php'");
		print("</script>");
	}	
?>
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
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_gendisk();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif"  title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_descargar('<?php print $ls_ruta;?>');"></a><img src="../shared/imagebank/tools20/ayuda.gif"  title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
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
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="5" class="titulo-ventana">Reporte Listado de Prestaci&oacute;n de Antiguedad (Intereses) </td>
        </tr>
        <tr style="display:none">
          <td height="22"><div align="right"></div></td>
          <td height="22" colspan="4"><div align="left"></div></td>
        </tr>
        <tr>
          <td width="134" height="22"><div align="right"> N&oacute;mina Desde </div></td>
          <td colspan="4"><div align="left">
            <input name="txtcodnomdes" type="text" id="txtcodnomdes" size="8" maxlength="4" value="" readonly>
            <a href="javascript: ue_buscarnomina('desde');"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesnomdes" type="text" class="sin-borde" id="txtdesnomdes" size="60" maxlength="50" readonly>
          </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">N&oacute;mina Hasta </div></td>
          <td colspan="4"><input name="txtcodnomhas" type="text" id="txtcodnomhas" size="8" maxlength="4" value="" readonly>
            <a href="javascript: ue_buscarnomina('hasta');"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesnomhas" type="text" class="sin-borde" id="txtdesnomhas" size="60" maxlength="50" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo Desde </div></td>
          <td colspan="4"><div align="left">
            <input name="txtanocurperdes" type="text" id="txtanocurperdes" size="7" maxlength="4" readonly>
            <input name="txtmescurperdes" type="text" id="txtmescurperdes" size="6" maxlength="3" readonly>
            <input name="txtdesmesperdes" type="text" id="txtdesmesperdes" size="20" maxlength="20" readonly>
            <a href="javascript: ue_buscarmes('desde');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a></div></td>
       		<input name="txtcodperides" type="hidden" id="txtcodperides" size="20" maxlength="20" readonly>
	    </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo Hasta </div></td>
          <td colspan="4"><div align="left">
            <input name="txtanocurperhas" type="text" id="txtanocurperhas" size="7" maxlength="4" readonly>
            <input name="txtmescurperhas" type="text" id="txtmescurperhas" size="6" maxlength="3" readonly>
            <input name="txtdesmesperhas" type="text" id="txtdesmesperhas" size="20" maxlength="20" readonly>
            <a href="javascript: ue_buscarmes('hasta');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="periodo" width="15" height="15" border="0" id="periodo"></a>
		  	<input name="txtcodperihas" type="hidden" id="txtcodperihas" size="20" maxlength="20" readonly> </div></td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="4">
		  <input name="operacion" type="hidden" id="operacion">
          <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>"></td>
		  <input name="hidcalintpreant" type="hidden" id="hidcalintpreant" value="<?php print $li_calintpreant;?>">		  
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">
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
		if(f.hidcalintpreant.value==1) 
        {     
			codnomdes=f.txtcodnomdes.value;
			desnomdes=f.txtdesnomdes.value;
			codnomhas=f.txtcodnomhas.value;
			desnomhas=f.txtdesnomhas.value;
			anocurperdes=f.txtanocurperdes.value;
			mescurperdes=f.txtmescurperdes.value;
			desmesperdes=f.txtdesmesperdes.value;
			anocurperhas=f.txtanocurperhas.value;
			mescurperhas=f.txtmescurperhas.value;
			desmesperhas=f.txtdesmesperhas.value;
			reporte=f.reporte.value;
			perides=f.txtcodperides.value;
			perihas=f.txtcodperihas.value;
			if((codnomdes!="")&&(anocurperdes!="")&&(mescurperdes!=""))
			{
				pagina="reportes/"+reporte+"?codnomdes="+codnomdes+"&codnomhas="+codnomhas+"&anocurperdes="+anocurperdes+"&mescurperdes="+mescurperdes+"&desmesperdes="+desmesperdes;
				pagina=pagina+"&anocurperhas="+anocurperhas+"&mescurperhas="+mescurperhas+"&desmesperhas="+desmesperhas;
				pagina=pagina+"&desnomdes="+desnomdes+"&desnomhas="+desnomhas;
				pagina=pagina+"&perides="+perides+"&perihas="+perihas;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("Debe selecionar una N�mina y Per�odo");
			}
 		} 
        else 
        { 
        	alert("Esta opci�n esta desactiva."); 
        } 

   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaci�n");
   	}		
}

function ue_buscarnomina(tipo)
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=replispreant"+tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarmes(rango)
{
	f=document.form1;
	if((f.txtcodnomdes.value!=""))
	{
		window.open("sigesp_sno_cat_hmes.php?codnom="+f.txtcodnomdes.value+"&tipo=replispreant"+rango,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=200,top=200,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una n�mina.");
	}
}
</script> 
</html>