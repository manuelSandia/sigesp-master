<?php
session_start();
require_once("class_folder/class_funciones_viaticos.php");
$io_fun_viaticos=new class_funciones_viaticos();
if(array_key_exists("hiddestino",$_POST))
{
	$ls_destino=$io_fun_viaticos->uf_obtenervalor("hiddestino","");
}
else
{
	$ls_destino=$io_fun_viaticos->uf_obtenervalor_get("destino","");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Misiones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
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
<form name="form1" method="post" action="">
    <input name="operacion" type="hidden" id="operacion">
    <input name="hiddestino" type="hidden" id="hiddestino" value="<?php print $ls_destino ?>">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Misiones</td>
  </tr>
</table>
  <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
    <tr>
      <td width="76"><div align="right">C&oacute;digo</div></td>
      <td width="422" height="22"><div align="left">
          <input name="txtcodmis" type="text" id="txtnombre2">
      </div></td>
    </tr>
    <tr>
      <td><div align="right">Descripci&oacute;n</div></td>
      <td height="22"><div align="left">
          <input name="txtdesmis" type="text" id="txtdesmis">
      </div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
    </tr>
  </table>
  <br>
  <div align="center">
    <?php
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
$ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codmis="%".$_POST["txtcodmis"]."%";
	$ls_denmis="%".$_POST["txtdesmis"]."%";
}
else
{
	$ls_operacion="";

}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='80' align='center'>Código</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql=" SELECT codmis,denmis".
			" FROM  scv_misiones".
			" WHERE codmis LIKE '".$ls_codmis."'".
			" AND   denmis LIKE '".$ls_denmis."'".
			" ORDER BY codmis ";
    $rs_cta=$io_sql->select($ls_sql);
    $data=$rs_cta;
	if($row=$io_sql->fetch_row($rs_cta))
	{
		$data=$io_sql->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;

		$totrow=$ds->getRowCount("codmis");
		for($z=1;$z<=$totrow;$z++)
		{
			switch($ls_destino)
			{
				case"SOLICITUD":
					print "<tr class=celdas-blancas>";
					$ls_codmis= $data["codmis"][$z];
					$ls_denmis= $data["denmis"][$z];
					print "<td><a href=\"javascript: aceptar('$ls_codmis','$ls_denmis');\">".$ls_codmis."</a></td>";
					print "<td>".$ls_denmis."</td>";
					print "</tr>";			
				break;
	
				case"DEFINICION":
					print "<tr class=celdas-blancas>";
					$ls_codmis= $data["codmis"][$z];
					$ls_denmis= $data["denmis"][$z];
					print "<td><a href=\"javascript: aceptar_definicion('$ls_codmis','$ls_denmis');\">".$ls_codmis."</a></td>";
					print "<td>".$ls_denmis."</td>";
					print "</tr>";			
				break;
			}
		}
	}
	else
	{
		$io_msg->message("No hay registros");
	}

}
print "</table>";
?>
  </div>
</form>
</body>
<script language="JavaScript">
function aceptar(ls_codmis,ls_denmis)
{
	opener.document.form1.txtcodmis.value= ls_codmis;
	opener.document.form1.txtdenmis.value= ls_denmis;
	ls_obssolvia=opener.document.form1.txtobssolvia.value;
	if(ls_obssolvia=="")
	{
		opener.document.form1.txtobssolvia.value= ls_denmis;
	}
	else
	{
		opener.document.form1.txtobssolvia.value= ls_obssolvia+", "+ls_denmis;
	}
	close();
}

function aceptar_definicion(ls_codmis,ls_denmis)
{
	opener.document.form1.txtcodmis.value= ls_codmis;
	opener.document.form1.txtdenmis.value= ls_denmis;
	opener.document.form1.existe.value= "TRUE";
	opener.document.form1.hidestatus.value= "C";
	close();
}
  function ue_search()
  {
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_scv_cat_misiones.php";
	f.submit();
  }

</script>
</html>