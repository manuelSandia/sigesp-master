<?php 
session_start(); ?>
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
<title>Asignaci&oacute;n de Saldos Ejecutados</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="javascript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="javascript1.2" src="js/valida_tecla_grid.js"></script>
<style type="text/css">
<!--
.Estilo1 {font-size: 15px}
-->
</style>
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../sno/css/nomina.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$in = new sigesp_include();
$con= $in-> uf_conectar ();
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
$io_sql = new class_sql($con);
$msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds_prorep=new class_datastore();

if(array_key_exists("operacion",$_POST))
{
 $ls_operacion=$_POST["operacion"];
}
else
{
$ls_operacion="";
$ls_operacion=$_POST["operacion"];
}

if(array_key_exists("txtcuenta",$_POST))
{
 $ls_cuenta=$_POST["txtcuenta"];
}
else
{
 $ls_cuenta=$_GET["cuenta"];
}

if(array_key_exists("txtdenominacion",$_POST))
{
 $ls_denominacion=$_POST["txtdenominacion"];
}
else
{
 $ls_denominacion=$_GET["denominacion"];
}

if(array_key_exists("reporte",$_POST))
{
 $ls_codrep=$_POST["reporte"];
}
else
{
 $ls_codrep=$_GET["reporte"];
}

if(array_key_exists("fila",$_POST))
{
 $i=$_POST["fila"];
}
else
{
 $i=$_GET["fila"];
}

if (array_key_exists("txtEnero",$_POST))
{
  $ld_enero=$_POST["txtEnero"];
}
else
{
  $ld_enero=0;
}	

if (array_key_exists("txtFebrero",$_POST))
{
  $ld_febrero=$_POST["txtFebrero"];
}
else
{
  $ld_febrero=0;
}

if (array_key_exists("txtMarzo",$_POST))
{
  $ld_marzo=$_POST["txtMarzo"];
}
else
{
  $ld_marzo=0;
}

if (array_key_exists("txtAbril",$_POST))
{
  $ld_abril=$_POST["txtAbril"];
}
else
{
  $ld_abril=0;
}

if (array_key_exists("txtMayo",$_POST))
{
  $ld_mayo=$_POST["txtMayo"];
}
else
{
  $ld_mayo=0;
}

if (array_key_exists("txtJunio",$_POST))
{
  $ld_junio=$_POST["txtJunio"];
}
else
{
  $ld_junio=0;
}

if (array_key_exists("txtJulio",$_POST))
{
  $ld_julio=$_POST["txtJulio"];
}
else
{
  $ld_julio=0;
}

if (array_key_exists("txtAgosto",$_POST))
{
  $ld_agosto=$_POST["txtAgosto"];
}
else
{
  $ld_agosto=0;
}

if (array_key_exists("txtSeptiembre",$_POST))
{
  $ld_septiembre=$_POST["txtSeptiembre"];
}
else
{
  $ld_septiembre=0;
}

if (array_key_exists("txtOctubre",$_POST))
{
  $ld_octubre=$_POST["txtOctubre"];
}
else
{
  $ld_octubre=0;
}

if (array_key_exists("txtNoviembre",$_POST))
{
  $ld_noviembre=$_POST["txtNoviembre"];
}
else
{
  $ld_noviembre=0;
}

if (array_key_exists("txtDiciembre",$_POST))
{
  $ld_diciembre=$_POST["txtDiciembre"];
}
else
{
  $ld_diciembre=0;
}

/*$ls_cuenta=$_GET["cuenta"];
$ls_denominacion=$_GET["denominacion"];
$ls_codrep=$_GET["reporte"];
$i=$_GET["fila"];*/
$ls_readonly="";
$la_empresa = $_SESSION["la_empresa"];
$li_periodo_ant = intval(substr($la_empresa["periodo"],0,4)) - 1;
if($ls_operacion == "")
{
	$ls_sql = "SELECT saldo_real_ant_enero, saldo_real_ant_febrero, saldo_real_ant_marzo, ".
			  "		  saldo_real_ant_abril, saldo_real_ant_mayo, saldo_real_ant_junio, ".
			  "		  saldo_real_ant_julio, saldo_real_ant_agosto, saldo_real_ant_septiembre,  ".
			  "		  saldo_real_ant_octubre, saldo_real_ant_noviembre, saldo_real_ant_diciembre ".
			  " FROM scg_pc_reporte WHERE codemp = '".$la_empresa["codemp"]."' AND cod_report = '".$ls_codrep."'".
			  " AND sc_cuenta = '".$ls_cuenta."'";
	$rs_data = $io_sql->select($ls_sql);
	if($rs_data === false)
	{
	 $msg->message("Ocurrio un error al tratar de buscar los saldos anteriores asociados a las cuenta ".$ls_cuenta.", consulte a su administrador del sistema");
	}
	else
	{
	 if(!$rs_data->EOF)
	 {
		$ld_enero   = number_format($rs_data->fields["saldo_real_ant_enero"],2,',','.');
		$ld_febrero = number_format($rs_data->fields["saldo_real_ant_febrero"],2,',','.');
		$ld_marzo   = number_format($rs_data->fields["saldo_real_ant_marzo"],2,',','.');
		$ld_abril   = number_format($rs_data->fields["saldo_real_ant_abril"],2,',','.');
		$ld_mayo    = number_format($rs_data->fields["saldo_real_ant_mayo"],2,',','.');
		$ld_junio   = number_format($rs_data->fields["saldo_real_ant_junio"],2,',','.');
		$ld_julio   = number_format($rs_data->fields["saldo_real_ant_julio"],2,',','.');
		$ld_agosto  = number_format($rs_data->fields["saldo_real_ant_agosto"],2,',','.');
		$ld_septiembre = number_format($rs_data->fields["saldo_real_ant_septiembre"],2,',','.');
		$ld_octubre    = number_format($rs_data->fields["saldo_real_ant_octubre"],2,',','.');
		$ld_noviembre  = number_format($rs_data->fields["saldo_real_ant_noviembre"],2,',','.');
		$ld_diciembre  = number_format($rs_data->fields["saldo_real_ant_diciembre"],2,',','.');
	 }
	}
}
elseif($ls_operacion == "GUARDAR")
{
  	$ld_enero2   = str_replace('.','',$ld_enero);
	$ld_enero2   = str_replace(',','.',$ld_enero2);
	$ld_febrero2   = str_replace('.','',$ld_febrero);
	$ld_febrero2   = str_replace(',','.',$ld_febrero2);
	$ld_marzo2   = str_replace('.','',$ld_marzo);
	$ld_marzo2   = str_replace(',','.',$ld_marzo2);
	$ld_abril2   = str_replace('.','',$ld_abril);
	$ld_abril2   = str_replace(',','.',$ld_abril2);
	$ld_mayo2   = str_replace('.','',$ld_mayo);
	$ld_mayo2   = str_replace(',','.',$ld_mayo2);
	$ld_junio2   = str_replace('.','',$ld_junio);
	$ld_junio2   = str_replace(',','.',$ld_junio2);
	$ld_julio2   = str_replace('.','',$ld_julio);
	$ld_julio2   = str_replace(',','.',$ld_julio2);
	$ld_agosto2   = str_replace('.','',$ld_agosto);
	$ld_agosto2   = str_replace(',','.',$ld_agosto2);
	$ld_septiembre2   = str_replace('.','',$ld_septiembre);
	$ld_septiembre2   = str_replace(',','.',$ld_septiembre2);
	$ld_octubre2   = str_replace('.','',$ld_octubre);
	$ld_octubre2   = str_replace(',','.',$ld_octubre2);
	$ld_noviembre2   = str_replace('.','',$ld_noviembre);
	$ld_noviembre2   = str_replace(',','.',$ld_noviembre2);
	$ld_diciembre2   = str_replace('.','',$ld_diciembre);
	$ld_diciembre2   = str_replace(',','.',$ld_diciembre2);
	
  	$ls_sql = "UPDATE scg_pc_reporte SET saldo_real_ant_enero = ".$ld_enero2." , saldo_real_ant_febrero = ".$ld_febrero2.", saldo_real_ant_marzo = ".$ld_marzo2.", ".
			  "		  saldo_real_ant_abril = ".$ld_abril2.", saldo_real_ant_mayo = ".$ld_mayo2.", saldo_real_ant_junio = ".$ld_junio2.", ".
			  "		  saldo_real_ant_julio = ".$ld_julio2.", saldo_real_ant_agosto = ".$ld_agosto2.", saldo_real_ant_septiembre = ".$ld_septiembre2.",  ".
			  "		  saldo_real_ant_octubre = ".$ld_octubre2.", saldo_real_ant_noviembre = ".$ld_noviembre2.", saldo_real_ant_diciembre = ".$ld_diciembre2."".
			  " WHERE codemp = '".$la_empresa["codemp"]."' AND cod_report = '".$ls_codrep."'".
			  " AND sc_cuenta = '".trim($ls_cuenta)."'";
	$rs_data = $io_sql->execute($ls_sql);
	if($rs_data === false)
	{
	 $msg->message("Ocurrio un error al tratar de buscar los saldos anteriores asociados a las cuenta ".$ls_cuenta.", consulte a su administrador del sistema");
	}
	else
	{   
	 $msg->message("Saldos asociados a la cuenta ".trim($ls_cuenta)." fueron registrados exitosamente");
	}		  
}
   
?>
<form name="form1" method="post" action="">
  <p>&nbsp;</p>
  <table width="650" height="368" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="4"><div align="left" class="titulo-ventana">
        <div align="center" class="titulo">
          <div align="left" class="titulo-ventana">
            <div align="center">Saldos Ejecutados A&ntilde;o <?php print $li_periodo_ant;  ?></div>
          </div>
        </div>
      </div></td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td height="18" class="fd-blanco"><div align="right">Cuenta</div></td>
      <td class="sin-borde3"><div align="left">
        <input name="txtcuenta" type="text" class="sin-borde3" id="txtcuenta" value="    <?php print   trim($ls_cuenta) ?>">
      </div></td>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td class="fd-blanco"><div align="right">Denominaci&oacute;n</div></td>
      <td colspan="3"><div align="left"><span class="fd-blanco"><span class="sin-borde3">
        <input name="txtdenominacion" type="text" class="sin-borde3" id="txtdenominacion" value="   <?php print   $ls_denominacion  ?>" size="90" maxlength="150">
      </span></span></div></td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="67" class="fd-blanco">&nbsp;</td>
      <td width="301">&nbsp;</td>
    </tr>
    <tr>
      <td width="134" class="fd-blanco"><div align="right">Enero</div></td>
      <td width="146">
        <div align="left">
          <input name="txtEnero" type="text" class="fd-blanco" id="txtEnero"  onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php print $ld_enero ?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>>
</div></td><td class="fd-blanco"><div align="right">Julio</div></td>
      <td><input name="txtJulio" type="text" class="fd-blanco" id="txtJulio"  onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php print $ld_julio?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>>        <div align="right">
        </div></td>
    </tr>
    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td class="fd-blanco"><div align="right">Febrero</div></td>
      <td><input name="txtFebrero" type="text" class="fd-blanco" id="txtFebrero"  onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php  print $ld_febrero?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
      <td class="fd-blanco"><div align="right">Agosto</div></td>
      <td><input name="txtAgosto" type="text" class="fd-blanco" id="txtAgosto"onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php  print $ld_agosto?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
    </tr>
    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td class="fd-blanco"><div align="right">Marzo</div></td>
      <td><input name="txtMarzo" type="text" class="fd-blanco" id="txtMarzo" onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php print $ld_marzo?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
      <td class="fd-blanco"><div align="right">Septiembre</div></td>
      <td><input name="txtSeptiembre" type="text" class="fd-blanco" id="txtSeptiembre" onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php print $ld_septiembre?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
    </tr>
    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td class="fd-blanco"><div align="right">Abril</div></td>
      <td><input name="txtAbril" type="text" class="fd-blanco" id="txtAbril" onKeyPress="return(currencyFormat(this,'.',',',event))"  onKeyUp='ue_validarcomas_puntos(this)' value="<?php print $ld_abril?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
      <td class="fd-blanco"><div align="right">Octubre</div></td>
      <td><input name="txtOctubre" type="text" class="fd-blanco" id="txtOctubre" onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php print $ld_octubre?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
    </tr>
    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td class="fd-blanco"><div align="right">Mayo</div></td>
      <td><input name="txtMayo" type="text" class="fd-blanco" id="txtMayo" onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php print $ld_mayo?>" size="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
      <td class="fd-blanco"><div align="right">Noviembre</div></td>
      <td><input name="txtNoviembre" type="text" class="fd-blanco" id="txtNoviembre" onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php print $ld_noviembre?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
    </tr>
    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td class="fd-blanco"><div align="right">Junio </div></td>
      <td><input name="txtJunio" type="text" class="fd-blanco" id="txtJunio" onKeyPress="return(currencyFormat(this,'.',',',event))" onKeyUp='ue_validarcomas_puntos(this)' value="<?php print $ld_junio?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
      <td class="fd-blanco"><div align="right">Diciembre</div></td>
      <td><input name="txtDiciembre" type="text" class="fd-blanco" id="txtDiciembre" onKeyPress="return(currencyFormat(this,'.',',',event))"  onKeyUp='ue_validarcomas_puntos(this)'value="<?php print $ld_diciembre?>" size="25" maxlength="25" style="text-align:right" <?php print $ls_readonly; ?>></td>
    </tr>
    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td>&nbsp;</td>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" rowspan="4"><input name="btnGuardar" type="button" class="boton" id="btnGuardar" onClick="ue_guardar()" value="Guardar">
          <input name="botCancelar" type="button" class="boton" id="botCancelar" onClick="ue_cancelar()" value="Cancelar">
          <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion?>">
          <input name="fila" type="hidden" id="fila" value="<?php print $i?>">
          <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_codrep ?>"></td>
    </tr>

    <tr>
      <td class="fd-blanco Estilo1">&nbsp;</td>
      <td><span class="Estilo1"></span></td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="fd-blanco">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<script language="javascript">
function ue_guardar()
{
	f=document.form1;
    li=f.fila.value;
	f.operacion.value = "GUARDAR";
	f.action="sigesp_scg_p_asig_saldos_ejecutados.php?cuenta=";
	f.submit();
}
function ue_cancelar()
{
	if(confirm("El proceso será cancelado, ¿está de acuerdo?"))
	{
	 close();
	}
}
function redondear(num, dec)
{ 
    num = parseFloat(num); 
    dec = parseFloat(dec); 
    dec = (!dec ? 2 : dec); 
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
}


function valida_null(field,valor)
{
  with (field) 
  {
    if (value==null||value=="")
      {
        //alert(mensaje);
		field=valor;
        return true;
      }
  }
  
  function EvaluateText(cadena, obj)
  { 
	
    opc = false; 
	
    if (cadena == "%d")  
      if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
      opc = true; 
    if (cadena == "%f")
	{ 
     if (event.keyCode > 47 && event.keyCode < 58) 
      opc = true; 
     if (obj.value.search("[.*]") == -1 && obj.value.length != 0) 
      if (event.keyCode == 46) 
       opc = true; 
    } 
	 if (cadena == "%s") // toma numero y letras
     if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32)||(event.keyCode > 47 && event.keyCode < 58)||(event.keyCode ==46)) 
      opc = true; 
	 if (cadena == "%c") // toma numero y punto
     if ((event.keyCode > 47 && event.keyCode < 58)|| (event.keyCode ==46))
      opc = true; 
    if(opc == false) 
     event.returnValue = false; 
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

	if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Return
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
function ue_validarcomas_puntos(valor)
{
	/*val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto != ",")&&(texto != '.'))
		{
			textocompleto += texto;
		}
		else if(texto == ",")
		{
		 break;
		}
			
	}
	valor.value=textocompleto;*/
}


function uf_formato(obj)
{
 ldec_temp1=obj.value;
 if((ldec_temp1=="")||(ldec_temp1==".")||(ldec_temp1==","))
 {
  ldec_temp1="0,00";
 } 
 obj.value=uf_convertir(ldec_temp1); 
}
</script>
</html>