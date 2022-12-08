<?php
session_start();
	$li_act=$_SESSION["la_empresa"]["cencosact"];
	if ($li_act==1)
	{
		$ls_cencosact= "checked";
	}
	else
	{
		$ls_cencosact= "";
	}
	$li_pas=$_SESSION["la_empresa"]["cencospas"];
	if ($li_pas==1)
	{
		$ls_cencospas= "checked";
	}
	else
	{
		$ls_cencospas= "";
	}
	$li_ing=$_SESSION["la_empresa"]["cencosing"];
	if ($li_ing==1)
	{
		$ls_cencosing= "checked";
	}
	else
	{
		$ls_cencosing= "";
	}
	$li_gas=$_SESSION["la_empresa"]["cencosgas"];
	if ($li_gas==1)
	{
		$ls_cencosgas= "checked";
	}
	else
	{
		$ls_cencosgas= "";
	}
	$li_res=$_SESSION["la_empresa"]["cencosres"];
	if ($li_res==1)
	{
		$ls_cencosres= "checked";
	}
	else
	{
		$ls_cencosres= "";
	}
	$li_cap=$_SESSION["la_empresa"]["cencoscap"];
	if ($li_cap==1)
	{
		$ls_cencoscap= "checked";
	}
	else
	{
		$ls_cencoscap= "";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Configuración Centro de Costos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo2 {font-size: 12px}
.Estilo3 {color: #000000}
-->
</style>
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Configuraci&oacute;n Centro de Costos</td>
    </tr>
  </table>
<br>
    <table width="500" height="249" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="formato-azul">
        <td width="231" height="58"><div align="center" class="sin-borde2 Estilo2 Estilo3"><em>D&iacute;gitos</em></div></td>
        <td width="189"><div align="center" class="sin-borde2 Estilo2 Estilo3"><em>Maneja Centro de Costo </em></div>
        <td width="9"><div align="center"></div></td>
        <td width="10">
        
        <td width="59"></td>
      </tr>
	  <tr>
        <td width="231" height="26"><div align="center">Activos</div></td>
        <td width="189"><div align="left">
          <label>
          <div align="center">
            <input name="chkcencosact" type="checkbox" class="sin-borde" id="chkcencosact" value="1" <?php print $ls_cencosact ?>>
		  </div>
          </label>
        </div><div align="center"></div></td>
      </tr>
      <tr>
        <td height="26"><div align="center">Pasivos</div></td>
        <td><div align="left">
          <label>
          <div align="center">
            <input name="chkcencospas" type="checkbox" class="sin-borde" id="chkcencospas" value="1" <?php print $ls_cencospas ?>>
          </div>
          </label>
        </div></td>
      </tr>
	   <tr>
        <td height="26"><div align="center">Ingreso</div></td>
        <td><div align="left">
          <label>
          <div align="center">
            <input name="chkcencosing" type="checkbox" class="sin-borde" id="chkcencosing" value="1" <?php print $ls_cencosing ?>>
          </div>
          </label>
        </div></td>
      </tr>
	   <tr>
        <td height="26"><div align="center">Gasto</div></td>
        <td><div align="left">
          <label>
          <div align="center">
            <input name="chkcencosgas" type="checkbox" class="sin-borde" id="chkcencosgas" value="1" <?php print $ls_cencosgas ?>>
          </div>
          </label>
        </div></td>
      </tr>
	   <tr>
        <td height="26"><div align="center">Resultado</div></td>
        <td><div align="left">
          <label>
          <div align="center">
            <input name="chkcencosres" type="checkbox" class="sin-borde" id="chkcencosres" value="1" <?php print $ls_cencosres ?>>
          </div>
          </label>
        </div></td>
      </tr>
	   <tr>
        <td height="24"><div align="center">Capital</div></td>
        <td><div align="left">
          <label>
          <div align="center">
            <input name="chkcencoscap" type="checkbox" class="sin-borde" id="chkcencoscap" value="1" <?php print $ls_cencoscap ?>>
          </div>
          </label>
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Buscar" width="20" height="20" border="0"> Procesar</a></div></td>
      </tr>
  </table>
  <br>
  <table width="500" border="0" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td width="510" colspan="2" class="titulo-celda"></td>
    </tr>
  </table>

<?php
include("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_nombre="%".$_POST["nombre"]."%";
}
else
{
	$ls_operacion="";
}
/*print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cedula </td>";
print "<td>Nombre del Beneficiario</td>";
print "<td>Cuenta Contable</td>";
print "</tr>";
print "</table>";*/
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function ue_search()
  {
	f = document.form1;
	fop = opener.document.form1;
	if (f.chkcencosact.checked==true)
    { 
	   fop.hidcencosact.value="1";
	}
	else
	{
		fop.hidcencosact.value="0";
	}
	if (f.chkcencospas.checked==true)
    { 
	   fop.hidcencospas.value="1";
	}
	else
	{
		fop.hidcencospas.value="0";
	}
	if (f.chkcencosing.checked==true)
    { 
	   fop.hidcencosing.value="1";
	}
	else
	{
		fop.hidcencosing.value="0";
	}
	if (f.chkcencosgas.checked==true)
    { 
	   fop.hidcencosgas.value="1";
	}
	else
	{
		fop.hidcencosgas.value="0";
	}
	if (f.chkcencosres.checked==true)
    { 
	   fop.hidcencosres.value="1";
	}
	else
	{
		fop.hidcencosres.value="0";
	}
	if (f.chkcencoscap.checked==true)
    { 
	   fop.hidcencoscap.value="1";
	}
	else
	{
		fop.hidcencoscap.value="0";
	}
	close();
  }
</script>
</html>