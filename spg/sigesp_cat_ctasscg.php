<?php
//session_id('8675309');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Contables</title>
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" align="right"><div align="center">Cat&aacute;logo de Cuentas Contables</div></td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="122" height="22" align="right">Cuenta</td>
        <td width="238" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="30" maxlength="25" style="text-align:center">        
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="60" maxlength="254">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
$in		   = new sigesp_include();
$con	   = $in->uf_conectar();
$dat	   = $_SESSION["la_empresa"];
$io_sql    = new class_sql($con);
$arr 	   = $_SESSION["la_empresa"];
$as_codemp = $arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";

}
else
{
	$ls_operacion="";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center  width=120>Cuenta Contable</td>";
print "<td style=text-align:center  width=380>Denominaci?n</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql = "SELECT sc_cuenta, denominacion, status ".
	          "  FROM scg_cuentas ".
		      " WHERE codemp = '".$as_codemp."' AND sc_cuenta like '".$ls_codigo."' AND denominacion like '".$ls_denominacion."'".
			  " ORDER BY sc_cuenta";
	$rs_data    = $io_sql->select($ls_sql);
	$li_numrows = $io_sql->num_rows($rs_data);
	if ($li_numrows>0)
	   {
	      while ($row=$io_sql->fetch_row($rs_data))
		        {
				  $ls_cuenta = $row["sc_cuenta"];
				  $ls_dencue = $row["denominacion"];
				  $status    = $row["status"];
			      if ($status=="S")
					 {
					   print "<tr class=celdas-blancas>";
					   print "<td style=text-align:center width=120>".$ls_cuenta."</td>";
					   print "<td style=text-align:left   width=380>".$ls_dencue."</td>";
					 }
				  else
					 {
					   print "<tr class=celdas-azules>";
					   print "<td style=text-align:center width=120><a href=\"javascript: aceptar('$ls_cuenta','$ls_dencue');\">".$ls_cuenta."</a></td>";
					   print "<td style=text-align:left width=380>".$ls_dencue."</td>";
					 }
			      print "</tr>";			
				}  
	   }
	else
	   {?>
	     <script language="javascript">
		   alert("No se han creado Cuentas Contables !!!");
		   close();
		 </script>
	   <?php
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

  function aceptar(ls_cuenta,ls_dencue)
  {
    opener.document.form1.txtcuenta.value		= ls_cuenta;
	opener.document.form1.txtdenominacion.value = ls_dencue;
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_cat_ctasscg.php";
	  f.submit();
  }

</script>
</html>
