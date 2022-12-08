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
	require_once("class_folder/class_funciones_mis.php");
	$io_fun_mis=new class_funciones_mis();
	$io_fun_mis->uf_load_seguridad("MIS","sigesp_mis_r_documentoscontabilizados.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte=$io_fun_mis->uf_select_config("MIS","REPORTE","REPORTE_MIS","sigesp_mis_rpp_documentoscontabilizados.php","C");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documentos Contabilizados</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="css/sep.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">

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
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">M&oacute;dulo Integrador -<em> Reportes </em></td>
			  <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
			<tr>
			<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
			<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
		</table>     </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></div></td>
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
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_mis->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_mis);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="578" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="561" colspan="2" class="titulo-ventana">Documentos Contabilizados </td>
    </tr>
  </table>
  <table width="575" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="573"></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="22" colspan="4"><strong>Filtro</strong></td>
        </tr>
        <tr>
      	<td height="24"><div align="right">Contabilizado por</div></td>
      	<td height="24" colspan="3" style="text-align:left"><div align="left">
        <input name="txtcodusu" type="text" id="txtcodusu" style="text-align:center" value="" size="24" readonly>
        <a href="javascript:ue_catusuario();"><img id="bot_provbene" src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Usuarios" width="15" height="15" border="0"></a>
        <input name="txtnomusu" type="text" id="txtnomusu" size="42" maxlength="250" class="sin-borde" value=""  readonly>
      	</div>
      	</td>
    	</tr>
		 <tr>
      	  <td height="20" style="text-align:right">Desde</td>
      	  <td width="218" height="20" align="center"><div align="left">
          <input name="txtfecdesde" type="text" id="txtfecdesde"  style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print date("d/m/Y") ?>" size="20" maxlength="10"  datepicker="true">
          </div></td>
          <td width="37" height="20" style="text-align:right">Hasta</td>
          <td width="155" height="20" align="center"><div align="left">
          <input name="txtfechasta" type="text" id="txtfechasta" style="text-align:center" onKeyPress="currencyDate(this);" value="<?php print date("d/m/Y") ?>" size="20" maxlength="10"  datepicker="true">
      </div></td>
    	</tr>
        <tr>
          <td height="26"><div align="right">Modulo origen </div></td>
          <td width="218"><select name="cmbmodulo" size="1">
            <option value="NSD">Seleccione</option>
            <option value="SEP">Solicitud Ejecucion Prespupuestaria</option>
            <option value="SOC">Ordenes de Compra</option>
            <option value="CXP">Cuentas por Pagar</option>
            <option value="SCB">Caja y Bancos</option>
            <option value="SNO">Nomina</option>
            <option value="SPG">Presupuesto Gasto</option>
            <option value="SPI">Presupuesto Ingreso</option>
            <option value="SCG">Contabilidad Patrimonial</option>
          </select></td>
          <td width="37">&nbsp;</td>
          <td width="155">&nbsp;</td>
        </tr>
        <tr>
          <td width="99" height="22"><div align="right">Concepto</div></td>
          <td colspan="3"><div align="left">
            <label></label>
            <label>
            <input name="txtcondoc" type="text" size="60" maxlength="200">
            </label>
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">
        <div align="left"></div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="22" colspan="4"><strong>Ordenar por </strong></td>
        </tr>
        <tr>
          <td width="70" height="22"><div align="right"></div></td>
          <td width="163"><div align="left">
            <label>
            <select name="cmbcamorder" size="1">
              <option value="cmp.comprobante">Numero Documento</option>
              <option value="cmp.total">Monto</option>
              <option value="cmp.fecha">Fecha</option>
              <option value="cmp.procede">Modulo</option>
            </select>
            </label>
          </div></td>
          <td width="60">&nbsp;</td>
          <td width="216">
          	<select name="cmborder" size="1">
              <option value="ASC">Ascendete</option>
              <option value="DESC">Descendiente</option>
            </select>
          </td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center"><div align="center"></div>
      <div align="center"></div></td>
    </tr>
  </table>
    <p align="center">
          <input name="formato"  type="hidden"  id="formato"  value="<?php print $ls_reporte; ?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function ue_catusuario(){
    var destino="Reporte";
	window.open("../sss/sigesp_sss_cat_usuarios.php?destino="+destino,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_mostrar_reporte(){
	var f = document.form1;
	var li_imprimir =f.imprimir.value;
	if(li_imprimir==1){
		formato  = f.formato.value;
		codusu   = f.txtcodusu.value;
		fecdes   = f.txtfecdesde.value;
		fechas   = f.txtfechasta.value;
		modulo   = f.cmbmodulo.value;
		condoc   = f.txtcondoc.value;
		camorden = f.cmbcamorder.value;
		orden    = f.cmborder.value;
		byorder  = camorden+" "+orden
		pantalla="reportes/"+formato+"?codusu="+codusu+"&fecdes="+fecdes+"&fechas="+fechas+"&modulo="+modulo+"&orden="+byorder+"&concepto="+condoc;
		window.open(pantalla,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
	}
	else{
		alert("No tiene permiso para realizar esta operación");
	}
}
	
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>