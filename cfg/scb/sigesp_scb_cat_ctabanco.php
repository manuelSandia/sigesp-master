<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Bancarias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Bancarias</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">Cuenta</div></td>
        <td width="431"><div align="left">
          <input name="cuenta" type="text" id="cuenta">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="60">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Banco</div></td>
        <td><input name="codigo" type="text" id="codigo"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?php
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
require_once("../../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
$ls_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_ctaban="%".$_POST["cuenta"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_ctaban="%%";
	$ls_denominacion="%%";

}
print "<table width=600 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>C?digo </td>";
print "<td>Denominaci?n</td>";
print "<td>Banco</td>";
print "<td>Tipo de Cuenta</td>";
print "<td>Cuenta Contable</td>";
print "<td>Denominaci?n Cta. Contable</td>";
print "<td>Apertura</td>";
print "<td>Cierre</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT a.ctaban as ctaban,a.dencta as dencta,a.sc_cuenta as sc_cuenta,d.denominacion as denominacion,a.codban as codban,c.nomban as nomban,a.codtipcta as codtipcta,b.nomtipcta as nomtipcta,a.fecapr as fecapr,a.feccie as feccie,a.estact as estact, a.ctabanext as ctabanext".
			" FROM  scb_ctabanco a,scb_tipocuenta b,scb_banco c,scg_cuentas d ".
			" WHERE a.codemp='".$ls_codemp."' AND a.codtipcta=b.codtipcta AND a.codban=c.codban AND a.codban like '".$ls_codigo."'  AND a.ctaban like '".$ls_ctaban."' ".
			" AND   (a.sc_cuenta=d.sc_cuenta AND a.codemp=d.codemp)";

			$rs_cta=$SQL->select($ls_sql);
			$data=$rs_cta;
			if($rs_cta===false)
			{
				$io_msg->message($fun->uf_convertirmsg($SQL->message));
			}
			else
			{
				if($row=$SQL->fetch_row($rs_cta))
				{
						$data=$SQL->obtener_datos($rs_cta);
						$arrcols=array_keys($data);
						$totcol=count($arrcols);
						$ds->data=$data;
						$totrow=$ds->getRowCount("ctaban");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codban=$data["codban"][$z];
						$nomban=$data["nomban"][$z];
						$ctaban=$data["ctaban"][$z];
						$dencta=$data["dencta"][$z];
						$codtipcta=$data["codtipcta"][$z];
						$nomtipcta=$data["nomtipcta"][$z];
						$ctabanext=$data["ctabanext"][$z];
						$ctascg=$data["sc_cuenta"][$z];
						$denctascg=$data["denominacion"][$z];
						$fecapertura=$fun->uf_convertirfecmostrar($data["fecapr"][$z]);
						$feccierre=$fun->uf_convertirfecmostrar($data["feccie"][$z]);
						$statuscta=$data["estact"][$z];
						print "<td><a href=\"javascript: aceptar('$codban','$nomban','$ctaban','$dencta','$ctascg','$denctascg','$fecapertura','$feccierre','$statuscta','$codtipcta','$nomtipcta','$ctabanext');\">".$ctaban."</a></td>";
						print "<td>".$dencta."</td>";
						print "<td>".$nomban."</td>";
						print "<td>".$nomtipcta."</td>";
						print "<td>".$ctascg."</td>";
						print "<td>".$denctascg."</td>";																			
						print "<td>".$fecapertura."</td>";					
						print "<td>".$feccierre."</td>";					
						print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("No se han definido cuentas para el banco seleccionado");
				}
		}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codban,nomban,ctaban,dencta,ctascg,denctascg,fecapertura,feccierre,statuscta,codtipcta,nomtipcta,ctabanext)
  {
    opener.document.form1.txtcodigo.value=ctaban;
    opener.document.form1.txtdencta.value=dencta;
	opener.document.form1.txttipocuenta.value=codtipcta;
	opener.document.form1.txtdentipocuenta.value=nomtipcta;
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtdenban.value=nomban;
	opener.document.form1.txtcuentacontable.value=ctascg;
	opener.document.form1.txtdencuenta.value=denctascg;
	opener.document.form1.txtfechaapertura.value=fecapertura;
	opener.document.form1.txtfechacierre.value=feccierre;
	opener.document.form1.txtctaext.value=ctabanext;
	opener.document.form1.status.value='C';
	if(statuscta==1)
	{
		opener.document.form1.statuscta.checked=true;
	}
	else
	{
		opener.document.form1.statuscta.checked=false;
	}
	
	opener.document.form1.txtcodigo.readOnly=true;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_scb_cat_ctabanco.php";
  f.submit();
  }
</script>
</html>
