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
	require_once("class_folder/class_funciones_sob.php");
	$io_fun_cxp=new class_funciones_sob();
	$io_fun_cxp->uf_load_seguridad("SOB","sigesp_sob_r_seguimientoobras.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Seguimiento de Obras </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sob.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="807" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="785" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Obras </td>
			  <td width="369" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table>    </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: uf_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
</div> 
<p>&nbsp;	</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="575" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="573"></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center" class="titulo-ventana">Seguimiento  de Obras </td>
  </tr>
  <tr style="visibility:hidden">
    <td height="22" colspan="3" align="center"><div align="left"></div></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="199" height="22"><div align="right">Desde
          <input name="txtcoddes" type="text" id="txtcoddes" size="20" readonly>
              <a href="javascript: ue_catalogo_obras('REPDES');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></div></td>
        <td width="89"><div align="right">Hasta</div></td>
        <td width="215"><input name="txtcodhas" type="text" id="txtcodhas" size="20" readonly>
            <a href="javascript: ue_catalogo_obras('REPHAS');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td height="33" colspan="3" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22"><strong>Fecha de Registro </strong> </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="199" height="22"><div align="right">Desde
          <input name="txtfecregdes" type="text" id="txtnumsoldes" size="15" maxlength="10"  datepicker="true" readonly>
            </div></td>
        <td width="89"><div align="right">Hasta</div></td>
        <td width="215"><input name="txtfecreghas" type="text" id="txtnumsolhas" size="15" maxlength="10"  datepicker="true" readonly>
          </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22"><strong>Organismo Ejecutor </strong> </td>
        <td width="340">&nbsp;</td>
        <td width="19">&nbsp;</td>
      </tr>
      <tr>
        <td width="144" height="22"><div align="right"></div></td>
        <td colspan="2"><input name="txtcodpro" type="text" id="txtcodpro"  style="text-align: center" size="4" maxlength="4" readonly="true">
          <a href="javascript:ue_catpropietario();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
          <input name="txtnompro" type="text" id="txtnompro3"  style="text-align:left" class="sin-borde" size="50" maxlength="100" readonly="true" >          <a href="javascript: ue_catalogo_solicitudes('REPHAS');"></a></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="center">&nbsp;</td>
  </tr>
</table>
<p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
<input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
	function ue_limpiarproben()
	{
		f=document.formulario;
		f.txtcoddes.value="";
		f.txtcodhas.value="";
	}

	function ue_catalogo(ls_catalogo)
	{
		// abre el catalogo que se paso por parametros
		window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
	function ue_catalogo_obras(ls_tipo)
	{
		ls_catalogo="sigesp_cat_obra.php?estado=''&origen="+ls_tipo+"";
		window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=yes");
	}

	function ue_catalogo_proben(ls_tipo)
	{
		f=document.formulario;
		valido=true;		
		if(f.rdproben[0].checked)
		{
			valido=false;
		}
		if(f.rdproben[1].checked)
		{
			ls_catalogo="sigesp_cxp_cat_proveedor.php?tipo="+ls_tipo+"";
		}
		if(f.rdproben[2].checked)
		{
			ls_catalogo="sigesp_cxp_cat_beneficiario.php?tipo="+ls_tipo+"";
		}
		if(valido)
		{		
			window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("Debe indicar si es Proveedor ó Beneficiario");
		}
	}
	
	function uf_imprimir()
	{
			f=document.formulario;
			li_imprimir=f.imprimir.value;
			if(li_imprimir==1)
			{
				coddes=f.txtcoddes.value;
				codhas=f.txtcodhas.value;
				fecregdes=f.txtfecregdes.value;
				fecreghas=f.txtfecreghas.value;
				codorgeje=f.txtcodpro.value;
				pantalla="reportes/sigesp_sob_rpp_seguimientoobras.php?coddes="+coddes+"&codhas="+codhas+"&fecregdes="+fecregdes+"&fecreghas="+fecreghas+"&codorgeje="+codorgeje;
				window.open(pantalla,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{alert("No tiene permiso para realizar esta operación");}
	}
	function ue_catpropietario()
	{
		pagina="sigesp_cat_organismo.php";
		popupWin(pagina,"catalogo",520,200);
	}

	function ue_cargarpropietario(codigo,nombre,descripcion)
	{
		f=document.formulario;
		f.txtcodpro.value=codigo;
		f.txtnompro.value=nombre;
	}
	function ue_cerrar()
	{
		window.location.href="sigespwindow_blank.php";
	}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
</html>