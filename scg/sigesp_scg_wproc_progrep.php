<?php 
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SCG";
	$ls_ventanas="sigesp_scg_wproc_progrep.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
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
<title>Programaci&oacute;n de Reporte Mensual</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>

<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
.Estilo1 {font-size: 15px}
-->
</style></head>

<body>
<div align="left"></div>
<table width="780" border="0" align="left" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="7" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" align="center" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="toolbar"><div align="left"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
  <table width="780" height="200" border="0" align="left">
    <tr>
      <td>
        <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
}
else
{
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
?>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$in = new sigesp_include();
$con= $in-> uf_conectar ();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con); //Instanciando  la clase sql
$ds_prorep=new class_datastore(); //Instanciando la clase datastore
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_fecha.php");
$msg=new class_mensajes();
require_once("../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$int_scg=new class_sigesp_int_scg();
require_once("class_funciones_scg.php");
$funciones_scg=new class_funciones_scg();
require_once("sigesp_scg_procesos.php");
$iscg_procesos= new sigesp_scg_procesos();
if(array_key_exists("operacion",$_POST))
{
  $ls_operacion=$_POST["operacion"];
}
else
{
  $ls_operacion="";
}
if(array_key_exists("radiobutton",$_POST))
{
  $ls_opcion=$_POST["radiobutton"];
}
else
{
  $ls_opcion="";
}

if (array_key_exists("txtAsignado",$_POST))
{
  $ld_asignado=$_POST["txtAsignado"];
}
else
{
  $ld_asignado=0;
}

if (array_key_exists("txtCuenta",$_POST))
{
  $ls_cuenta=$_POST["txtCuenta"];
}
else
{
  $ls_cuenta="";
}

if	(array_key_exists("select",$_POST))
	{
	  $ls_cod_report=$_POST["select"];
	}
else
	{
	  $ls_cod_report="0408";
	}
//Radio Button
if  (array_key_exists("radiobutton",$_POST))
	{
	  $ls_distribucion=$_POST["radiobutton"];
    }
else
	{
	  $ls_distribucion="";
	}	


	
if (array_key_exists("txtSaldoAnterior",$_POST))
{
  	$ld_saldo_real_ant=$_POST["txtSaldoAnterior"];
}
else
{
  	$ld_saldo_real_ant=0;
}	

if (array_key_exists("txtSaldoAprobado",$_POST))
{
  	$ld_saldo_aprobado=$_POST["txtSaldoAprobado"];
}
else
{
  	$ld_saldo_aprobado=0;
}	

if (array_key_exists("txtSaldoModificado",$_POST))
{
  	$ld_saldo_mod=$_POST["txtSaldoModificado"];
}
else
{
  	$ld_saldo_mod=0;
}

// SALDO EJECUTADOS A�O ANTERIOR	

$li_periodo_ant = intval(substr($_SESSION["la_empresa"]["periodo"],0,4))-1;

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
	
	
	
?>
        <p>&nbsp;</p>
        <table width="644" border="0" align="center" cellpadding="1" cellspacing="1" class="formato-blanco">
          <tr class="titulo-ventana">
            <td colspan="5" class="titulo-celda"><div align="center" class="titulo-ventana">Programaci&oacute;n de Reporte Mensual </div></td>
          </tr>
          <tr class="contorno">
            <td colspan="5"></td>
          </tr>
          <tr class="formato-blanco">
            <td>&nbsp;</td>
            <td class="fd-blanco">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="Estilo3">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td width="86"><div align="right" class="fd-blanco">Distribuci&oacute;n</div></td>
			<?php 	 
			  if(($ls_distribucion=="N")||($ls_distribucion==""))
			  {
					$ls_ninguno="checked";		
					$ls_auto="";
					$ls_manual="";
			  }
			  elseif($ls_distribucion=="A")
			  {
					$ls_ninguno="";		
					$ls_auto="checked";
					$ls_manual="";
			  }
			  elseif($ls_distribucion=="M")
			  {
					$ls_ninguno="";		
					$ls_auto="";
					$ls_manual="checked";
			  }
			  ?>
            <td width="127" class="fd-blanco">
              <input name="radiobutton" type="radio" value="N" <?php print $ls_ninguno ?>>
             Ninguno</td>
            <td width="116"><span class="Estilo3">
              <input name="radiobutton" type="radio" value="A" <?php print $ls_auto ?>>
            </span> <span class="fd-blanco">Automatico</span></td>
            <td width="104"><span class="Estilo3">
              <input name="radiobutton" type="radio" value="M" <?php print $ls_manual ?>>
            </span> <span class="fd-blanco">Manual </span></td>
            <td width="209" class="Estilo3">
              <input name="botTotalizar" type="button" class="boton" id="botTotalizar" onClick="ue_distribuir()" value="Totalizar">
              <input name="botRecargar" type="button" class="boton" id="botRecargar" onClick="ue_recargar()" value="Recargar">
            </td>
          </tr>
          <tr class="contorno">
            <td colspan="5"></td>
          </tr>
          <tr class="formato-blanco">
            <td><span class="Estilo1"></span></td>
            <td colspan="4"><span class="Estilo1"></span></td>
          </tr>
          <tr>
            <?php
		  if($ls_operacion=="SELECT")
		  {
			 $ls_cod_report=$_POST["select"];
		  }
		  else
		  {
		     if(array_key_exists("ls_cod_report",$_SESSION))
			 {
				 $ls_cod_report=$_SESSION["ls_cod_report"];
			 }
			 else
			 {
				 $ls_cod_report="0408";
			 }
		  }
		  if($ls_cod_report=="0408")
		  {
			$bg="selected";
			$est="";
			$inv="";
			$cta="";
			$resinv="";
            $resinv08="";
            $est08="";
            $bg08="";
            $pc08="";
		  }
		  if($ls_cod_report=="0406") 
		  {
			$bg="";
			$est="selected";
			$inv="";
			$cta="";
			$resinv="";	
            $resinv08="";
            $est08="";
            $bg08="";
            $pc08="";
		  }
		  if($ls_cod_report=="0405")
		  {
		  	$bg="";
			$est="";
			$inv="selected";
			$cta="";
			$resinv="";
            $resinv08="";
            $est08="";
            $bg08="";
            $pc08="";			
		  }
		  if($ls_cod_report=="0414")
		  {
		  	$bg="";
			$est="";
			$inv="";
			$cta="selected";
			$resinv="";
            $resinv08="";
            $est08="";
            $bg08="";
            $pc08="";			
		  }
		  if($ls_cod_report=="0714")
		  {
		  	$bg="";
			$est="";
			$inv="";
			$cta="";
			$resinv="selected";
            $resinv08="";
            $est08="";
            $bg08="";
            $pc08="";			
		  }
          if($ls_cod_report=="0801")
          {
            $bg="";
            $est="";
            $inv="";
            $cta="";
            $resinv="";
            $resinv08="selected";
            $est08="";
            $bg08="";
            $pc08="";
          }
          if($ls_cod_report=="0802")
          {
            $bg="";
            $est="";
            $inv="";
            $cta="";
            $resinv="";
            $resinv08="";
            $est08="selected";
            $bg08="";
            $pc08="";
          }
          if($ls_cod_report=="0803")
          {
            $bg="";
            $est="";
            $inv="";
            $cta="";
            $resinv="";
            $resinv08="";
            $est08="";
            $bg08="selected";
            $pc08="";
          }
          if($ls_cod_report=="0804")
          {
            $bg="";
            $est="";
            $inv="";
            $cta="";
            $resinv="";
            $resinv08="";
            $est08="";
            $bg08="";
            $pc08="selected";
          }
	?>
            <td>
              <div align="right">Reporte</div></td>
            <td colspan="4"><input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"]?>">
                <select name="select" id="select">
                  <option value="00">Seleccione un Reporte</option>
                  <option value="0408" <?php  print $bg ?>>Balance General</option>
                  <option value="0406" <?php  print $est ?>>Estado de Resultado</option>
                  <option value="0405" <?php  print $inv ?>>Inversiones</option>
                  <option value="0414">Cuenta de Ahorro Inversi&oacute;n</option>
                  <option value="0714" <?php print $resinv;?>>Resumen de Inversiones Forma 0714</option>
                  <option value="0801" <?php print $resinv08;?>>Resumen de Inversiones Instructivo 08</option>
                  <option value="0803" <?php print $bg08;?>>Balance General Instructivo 08</option>
    
                </select>
                <span class="cd-titulo"> <a href="javascript: selec();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Aceptar" width="15" height="15" border="0"></a> </span></td>
          </tr>
          <tr class="formato-blanco">
            <td class="fd-blanco Estilo1">&nbsp;</td>
            <td class="fd-blanco Estilo1">&nbsp;</td>
            <td class="fd-blanco Estilo1">&nbsp;</td>
            <td colspan="2" class="fd-blanco Estilo1">&nbsp;</td>
          </tr>
          <tr class="formato-blanco">
            <td class="fd-blanco"><div align="right">Asignado</div></td>
            <td class="fd-blanco"><span class="cd-titulo">
            <input name="txtAsignado" type="text" class="fd-blanco" id="txtAsignado" onBlur="uf_format(this)"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="<?php print $ld_asignado?>" size="23">
            </span></td>
            <td class="fd-blanco"><div align="right">Cuenta</div></td>
            <td colspan="2" class="fd-blanco"><input name="txtCuenta" type="text" id="txtCuenta2" value="<?php print $ls_cuenta ?>" readonly></td>
          </tr>

		  <?php
		if (($ls_cod_report=="0408")||($ls_cod_report=="0803"))
		{
echo <<<EOT
	<tr class="formato-blanco">
		<td class="fd-blanco"><div align="right">Saldo Real Anterior</div></td>
		<td class="fd-blanco"><span class="cd-titulo">
		<input name="txtSaldoAnterior" type="text" class="fd-blanco" id="txtSaldoAnterior" onBlur="uf_format(this)"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="$ld_saldo_real_ant" size="23">
		</span></td>
		 <td colspan="2" class="fd-blanco"><div align="left"><input name="btnSaldoEjecutado" type="button" class="boton" id="btnSaldoEjecutado" onClick="ue_asignar_saldos_ejecutados('$ls_cod_report')" value="Asignar Saldos Ejecutados A�o $li_periodo_ant "></div></td>            
		</tr>
		<tr class="formato-blanco">
		<td class="fd-blanco"><div align="right">Saldo Aprobado</div></td>
		<td class="fd-blanco"><span class="cd-titulo">
		<input name="txtSaldoAprobado" type="text" class="fd-blanco" id="txtSaldoAprobado" onBlur="uf_format(this)"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="$ld_saldo_aprobado" size="23">  
		</span></td>            
		</tr>
		<tr class="formato-blanco">
		<td class="fd-blanco"><div align="right">Saldo Modificado</div></td>
		<td class="fd-blanco"><span class="cd-titulo">
		<input name="txtSaldoModificado" type="text" class="fd-blanco" id="txtSaldoModificado" onBlur="uf_format(this)"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="$ld_saldo_mod" size="23">
		</span></td>
		</tr>
		<td class="fd-blanco">&nbsp;</td>
		</tr>
EOT;
/*			<tr class="formato-blanco">
            <td colspan="5" class="cd-titulo"><div align="center" class="cd-titulo">Saldos Ejecutados A&ntilde;o $li_periodo_ant </div></td>
            </tr>          
			<tr class="formato-blanco">
			<td class="fd-blanco"><div align="left">Ej. Enero </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtEnero" type="text" class="fd-blanco" id="txtEnero" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_enero" size="23" />
			</span></td>
			<td class="fd-blanco"><div align="left">Ej. Julio </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtJulio" type="text" class="fd-blanco" id="txtJulio" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_julio" size="23" />
			</span></td>
			<td class="fd-blanco">&nbsp;</td>
		  </tr>
		  <tr class="formato-blanco">
			<td class="fd-blanco"><div align="left">Ej. Febrero </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtFebrero" type="text" class="fd-blanco" id="txtFebrero" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_febrero" size="23" />
			</span></td>
			<td class="fd-blanco"><div align="left">Ej. Agosto </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtAgosto" type="text" class="fd-blanco" id="txtAgosto" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_agosto" size="23" />
			</span></td>
			<td class="fd-blanco">&nbsp;</td>
		  </tr>
		  <tr class="formato-blanco">
			<td class="fd-blanco"><div align="left">Ej. Marzo </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtMarzo" type="text" class="fd-blanco" id="txtMarzo" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_marzo" size="23" />
			</span></td>
			<td class="fd-blanco"><div align="left">Ej. Septiembre </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtSeptiembre" type="text" class="fd-blanco" id="txtSeptiembre" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_septiembre" size="23" />
			</span></td>
			<td class="fd-blanco">&nbsp;</td>
		  </tr>
		  <tr class="formato-blanco">
			<td class="fd-blanco"><div align="left">Ej. Abril </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtAbril" type="text" class="fd-blanco" id="txtAbril" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_abril" size="23" />
			</span></td>
			<td class="fd-blanco"><div align="left">Ej. Octubre </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtOctubre" type="text" class="fd-blanco" id="txtOctubre" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_octubre" size="23" />
			</span></td>
			<td class="fd-blanco">&nbsp;</td>
		  </tr>
		  <tr class="formato-blanco">
			<td class="fd-blanco"><div align="left">Ej. Mayo </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtMayo" type="text" class="fd-blanco" id="txtMayo" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_mayo" size="23" />
			</span></td>
			<td class="fd-blanco"><div align="left">Ej. Noviembre </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtNoviembre" type="text" class="fd-blanco" id="txtNoviembre" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_noviembre" size="23" />
			</span></td>
			<td class="fd-blanco">&nbsp;</td>
		  </tr>
		  <tr class="formato-blanco">
			<td class="fd-blanco"><div align="left">Ej. Junio </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtJunio" type="text" class="fd-blanco" id="txtJunio" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_junio" size="23" />
			</span></td>
			<td class="fd-blanco"><div align="left">Ej. Diciembre </div></td>
			<td class="fd-blanco"><span class="cd-titulo">
			  <input name="txtDiciembre" type="text" class="fd-blanco" id="txtDiciembre" onblur="uf_format(this)"  onkeypress="return(currencyFormat(this,'.',',',event))" value="$ld_diciembre" size="23" />
			</span></td>
			<td class="fd-blanco">&nbsp;</td>
			</tr> */
//EOT;
		}	
        elseif ($ls_cod_report=="0801")
        {
echo <<<EOT
        <tr class="formato-blanco">
        <td class="fd-blanco"><div align="right">Presupuesto Aprobado</div></td>
        <td class="fd-blanco"><span class="cd-titulo">
        <input name="txtSaldoAprobado" type="text" class="fd-blanco" id="txtSaldoAprobado" onBlur="uf_format(this)"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="$ld_saldo_aprobado" size="23">  
        </span></td>            
        </tr>
        <tr class="formato-blanco">
        <td class="fd-blanco"><div align="right">Presupuesto Modificado</div></td>
        <td class="fd-blanco"><span class="cd-titulo">
        <input name="txtSaldoModificado" type="text" class="fd-blanco" id="txtSaldoModificado" onBlur="uf_format(this)"  onKeyPress="return(currencyFormat(this,'.',',',event))" value="$ld_saldo_mod" size="23">
        </span></td>
        </tr>
EOT;
        }
        
        
        
		?>
          
          <tr class="formato-blanco">
            <td class="fd-blanco"><div align="right"><span class="Estilo1"></span> </div></td>
            <td class="fd-blanco">
              <div align="left"><span class="cd-titulo"></span></div></td>
            <td class="fd-blanco"><div align="right"><span class="Estilo1"></span></div></td>
            <td colspan="2" class="fd-blanco">
              <div align="left"><span class="Estilo1"></span> </div></td>
          </tr>
      </table></td></tr>
  </table>
  </tr>
  <p>&nbsp;</p>
  <p>
    <input name="fila" type="hidden" id="fila">
    <input name="numrow" type="hidden" id="numrow">        
    <input name="status" type="hidden" id="status2">
    <input name="dencta" type="hidden" id="dencta">
  </p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="1209" border="0" align="left" cellpadding="1" cellspacing="1" class="fondo-tabla">
    <tr class="titulo-ventana">
      <td width="68"><div align="center">Cuenta</div></td>
      <td width="37">Denominacion</td>
      <td width="62"><div align="center">Asignado</div></td>
      <td width="39"><div align="center">Enero</div></td>
      <td width="44"><div align="center">Febrero</div></td>
      <td width="43"><div align="center">Marzo</div></td>
      <td width="38"><div align="center">Abril</div></td>
      <td width="45"><div align="center">Mayo</div></td>
      <td width="38"><div align="center">Junio</div></td>
      <td width="32"><div align="center">Julio</div></td>
      <td width="46"><div align="center">Agosto</div></td>
      <td width="66"><div align="center" class="cd-titulo">Septiembre</div></td>
      <td width="62">Octubre</td>
      <td width="62"><div align="center">Noviembre</div></td>
      <td width="64"><div align="center">Diciembre</div></td>
      <td width="64"><div align="center">Saldo Real Anterior</div></td>
      <td width="64"><div align="center">Saldo Aprobado</div></td>
      <td width="64"><div align="center">Saldo Modificado</div></td>
    </tr>
    <?php
if($ls_operacion=="SELECT")
{
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $li_rtn=0 ;
   $lb_valido=true;
   $ls_cod_report=$_POST["select"];
   $_SESSION["ls_cod_report"]=$ls_cod_report;
   
    $li_rtn = $iscg_procesos->uf_select_scg_plantillacuentareporte( $ls_codemp, $ls_cod_report );
	   
    if ($li_rtn==0)   //no existen registros
    {
			if ($ls_cod_report=="0405")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_inversiones($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 for($i=1;$i<=$li_num;$i++)
					 { 
								$ls_status=$data["status"][$i];
								if($ls_status=="S")
								{
									?>
									<tr class="celdas-azules">
									  <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
									<?php
								}
								elseif($ls_status=="C")
								{
								   ?>
									<tr class="celdas-blancas">
									  <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
								   <?php
								}
								elseif($ls_status=="I")
								{
								   ?>
									<tr class="celdas-amarillas">
									  <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
								   <?php
								}
								 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
								 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
								 print"</tr>";
							 }//for
		         }//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if($ls_cod_report=="0405")
			if ($ls_cod_report=="0714")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_inversiones_0714($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 for($i=1;$i<=$li_num;$i++)
					 { 
								$ls_status=$data["status"][$i];
								if($ls_status=="S")
								{
									?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="C")
								{
								   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="I")
								{
								   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
								}
								 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
								 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
								 print"</tr>";
							 }//for
		         }//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if($ls_cod_report=="0714")

            
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------

            if ($ls_cod_report=="0801")
            {
                $lb_valido = $iscg_procesos->uf_cargar_cuentas_inversiones_ins08($ls_codemp);
                if($lb_valido)
                {
                     $msg->message("Los datos fueron cargados");
                     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
                     $data=$SQL->obtener_datos($rs_cta);
                     $ds_prorep=new class_datastore();
                     $ds_prorep->data=$data;
                     $li_num=$ds_prorep->getRowCount("cod_report");
                     for($i=1;$i<=$li_num;$i++)
                     { 
                                $ls_status=$data["status"][$i];
                                if($ls_status=="S")
                                {
                                    ?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
                                }
                                elseif($ls_status=="C")
                                {
                                   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
                                }
                                elseif($ls_status=="I")
                                {
                                   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
                                }
                                 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
                                 print"</tr>";
                             }//for
                 }//if($lb_valido)
                else
                {
                  $msg->message("Error al cargar los datos");
                }
            }//if($ls_cod_report=="0801")
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
            if ($ls_cod_report=="0802")
            {
                $lb_valido = $iscg_procesos->uf_cargar_cuentas_edoresultado_ins08($ls_codemp);
                if($lb_valido)
                {
                     $msg->message("Los datos fueron cargados");
                     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
                     $data=$SQL->obtener_datos($rs_cta);
                     $ds_prorep=new class_datastore();
                     $ds_prorep->data=$data;
                     $li_num=$ds_prorep->getRowCount("cod_report");
                     for($i=1;$i<=$li_num;$i++)
                     { 
                            $ls_status=$data["status"][$i];
                            if($ls_status=="S")
                            {
                                ?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
                            }
                            elseif($ls_status=="C")
                            {
                               ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
                            }
                            elseif($ls_status=="I")
                            {
                               ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
                            }
                                 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
                                 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
                             print"</tr>";
                    }//for
                }//if($lb_valido)
                else
                {
                  $msg->message("Error al cargar los datos");
                }
            }//if ($ls_cod_report=="0802")            
//----------------------------------------------------------------------------------------------------------------------
            if ($ls_cod_report=="0803")
            {
                $lb_valido = $iscg_procesos->uf_cargar_cuentas_balancegeneral_ins08($ls_codemp);
                if($lb_valido)
                {
                     $msg->message("Los datos fueron cargados");
                     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
                     $data=$SQL->obtener_datos($rs_cta);
                     $ds_prorep=new class_datastore();
                     $ds_prorep->data=$data;
                     $li_num=$ds_prorep->getRowCount("cod_report");
                     
                     for($i=1;$i<=$li_num;$i++)
                     { 
                            $ls_status=$data["status"][$i];
                            if($ls_status=="S")
                            {
                            ?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
                            }
                            elseif($ls_status=="C")
                            {
                               ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
                            }
                            elseif($ls_status=="I")
                            {
                               ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
                            }
                             print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
                             print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("saldo_real_ant",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("saldo_apro",$i),2,",",".")."</td>";
                             print"<td>".number_format($ds_prorep->getValue("saldo_mod",$i),2,",",".")."</td>";
                             print"</tr>";
                         }//for

                }//if($lb_valido)
                else
                {
                  $msg->message("Error al cargar los datos");
                }
                
            }//if ($ls_cod_report=="0803")
            
//----------------------------------------------------------------------------------------------------------------------            
//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------			
			if ($ls_cod_report=="0406")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_edoresultado($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 for($i=1;$i<=$li_num;$i++)
					 { 
							$ls_status=$data["status"][$i];
							if($ls_status=="S")
							{
								?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="C")
							{
							   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="I")
							{
							   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
							}
							     print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
								 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
							 print"</tr>";
					}//for
				}//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if ($ls_cod_report=="0406")
			
			if ($ls_cod_report=="0408")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_balancegeneral($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 
					 for($i=1;$i<=$li_num;$i++)
					 { 
							$ls_status=$data["status"][$i];
							if($ls_status=="S")
							{
							?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="C")
							{
							   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="I")
							{
							   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
							}
							 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
							 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_real_ant",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_apro",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_mod",$i),2,",",".")."</td>";
							
							 print"</tr>";
						 }//for

				}//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
				
			}//if ($ls_cod_report=="0408")
			
			if ($ls_cod_report=="0414")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_ctaahorroinversion($ls_codemp);	
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 
					 for($i=1;$i<=$li_num;$i++)
					 { 
							$ls_status=$data["status"][$i];
							if($ls_status=="S")
							{
								?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="C")
							{
							   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="I")
							{
							   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
							}
							 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
							 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
							 print"</tr>";
						 }//for
				}//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if ($ls_cod_report=="0414")
      }//if($li_rtn==0)
	  
	  if ($li_rtn>0) //tiene registro
	  {
	     $rs_cta=$iscg_procesos->uf_select_datastore_mensual( $ls_codemp, $ls_cod_report );
		 $data=$SQL->obtener_datos($rs_cta);
		 $ds_prorep=new class_datastore();
		 $ds_prorep->data=$data; 
		 $li_num=$ds_prorep->getRowCount("asignado");
		 for($i=1;$i<=$li_num;$i++)
		 { 
			$ls_status=$ds_prorep->getValue("status",$i);
			if($ls_status=="S")
			{
				if($ls_cod_report != "0803")
				{
				
	 ?>
	<tr class="celdas-azules">
      				<td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>','<?php print $ds_prorep->getValue("enero",$i);?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
	            }
				else
				{
				
		?>
	<tr class="celdas-azules">
					<td ><a href="javascript: aceptar_saldos('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("saldo_real_ant",$i);?>','<?php print $ds_prorep->getValue("saldo_apro",$i);?>','<?php print $ds_prorep->getValue("saldo_mod",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>','<?php print $ds_prorep->getValue("denominacion",$i); ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
		<?php		
				
				}
				
		    }
		    elseif($ls_status=="C")
		    {
			   
			   if($ls_cod_report != "0803")
			   {
	   ?>
    <tr class="celdas-blancas">
              <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>','<?php print $ds_prorep->getValue("enero",$i);?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
	            }
				else
				{
	  ?>
    <tr class="celdas-blancas">
			  <td ><a href="javascript: aceptar_saldos('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("saldo_real_ant",$i);?>','<?php print $ds_prorep->getValue("saldo_apro",$i);?>','<?php print $ds_prorep->getValue("saldo_mod",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>','<?php print $ds_prorep->getValue("denominacion",$i); ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
	  			
	  <?php					
				}
			}
			elseif($ls_status=="I")
			{
			   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
			}
			 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
			 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
			 if($ls_cod_report == "0803")
			 {
			  print"<td>".number_format($ds_prorep->getValue("saldo_real_ant",$i),2,",",".")."</td>";
              print"<td>".number_format($ds_prorep->getValue("saldo_apro",$i),2,",",".")."</td>";
              print"<td>".number_format($ds_prorep->getValue("saldo_mod",$i),2,",",".")."</td>";
			 }
		     print"</tr>";
		  }//for
	  }//if ($li_rtn>0)			
  }//select */ 
  
  if ($ls_operacion=="DISTRIBUIR")
  {	
	 $li_indice=1;
	 $la_empresa=$_SESSION["la_empresa"];
	 $ls_formcont=$la_empresa["formcont"];
	 $ls_codemp  =  $la_empresa["codemp"];
	 $ls_cod_report = $_SESSION["ls_cod_report"];
     $_SESSION["ld_asignado"]=$ld_asignado;
	 $ld_asignado=$_SESSION["ld_asignado"];


     $_SESSION["ld_saldo_real_ant"]=$ld_saldo_real_ant;
	 $ld_saldo_real_ant=$_SESSION["ld_saldo_real_ant"];
	 
	 $_SESSION["ld_saldo_aprobado"]=$ld_saldo_aprobado;
	 $ld_saldo_aprobado=$_SESSION["ld_saldo_aprobado"];
	 
	 $_SESSION["ld_saldo_mod"]=$ld_saldo_mod;
	 $ld_saldo_mod=$_SESSION["ld_saldo_mod"];
	
	 //print "$ld_saldo_real_ant <br> <br>";
	 
	 $li_lenCta  = strlen(trim($ls_cuenta));

   	 //COLOCA FORMATO A LA CUENTA  
	 $li_pos      = $fun->uf_posocurrencia( $ls_formcont, "-", $li_indice );
	 $ls_sub      = substr($ls_cuenta,0, $li_pos );
	 $ls_new_cad  = $ls_sub . "-" . substr($ls_cuenta,$li_pos, $li_lenCta ); 
	 $li_max_nivel= 1;
	for($i=1;$i<=$li_pos;$i++)
	{
   	 	 $li_indice = $li_indice + 1;
   	 	 $li_lenCta = strlen(trim($ls_new_cad));
		 $li_pos    = $fun->uf_posocurrencia( $ls_formcont, "-", $li_indice );
		    	 	    	 
	   	 if ($li_pos > 0)
	   	 {
	   	  	 $ls_sub     = substr($ls_new_cad, 0, $li_pos );
			 $len=$li_lenCta - $li_pos;
	   	  	 $ls_new_cad  = $ls_sub . "-" . substr($ls_new_cad,$li_pos,$len );
			 $li_max_nivel= $li_max_nivel + 1;
	   	 }
	
   	 }//for
    $li_nivel_cta  = $int_scg->uf_scg_obtener_nivel($ls_cuenta);	      //Obtiene nivel de la cta 
    $ls_cta_ceros  = $int_scg->uf_scg_sin_ceros($ls_cuenta );  //devuelve la cta sin ceros
    $ls_sc_cuenta = $iscg_procesos->uf_disable_cta_inferior( $ls_cta_ceros, $ls_cuenta, $ls_cod_report);
	$ls_opcion=$_POST["radiobutton"];
	if ($ls_opcion=="N")
	{
	    $ds_prorep->data=$_SESSION["objact"];//echo "AJA 6<br>";	    	   
		$i=$_POST["fila"];
		$ld_asignado=str_replace('.','',$ld_asignado);
		$ld_asignado=str_replace(',','.',$ld_asignado);
		$ds_prorep->updateRow("asignado",$ld_asignado,$i);
		
		$ld_saldo_real_ant=str_replace('.','',$ld_saldo_real_ant);
		$ld_saldo_real_ant=str_replace(',','.',$ld_saldo_real_ant); //print " $ld_saldo_real_ant <br>";
		$ds_prorep->updateRow("saldo_real_ant",$ld_saldo_real_ant,$i);

		$ld_saldo_aprobado=str_replace('.','',$ld_saldo_aprobado);
		$ld_saldo_aprobado=str_replace(',','.',$ld_saldo_aprobado);
		$ds_prorep->updateRow("saldo_apro",$ld_saldo_aprobado,$i);		
		
		$ld_saldo_mod=str_replace('.','',$ld_saldo_mod);
		$ld_saldo_mod=str_replace(',','.',$ld_saldo_mod);
		$ds_prorep->updateRow("saldo_mod",$ld_saldo_mod,$i);	
		
		//$ld_saldo_real_ant  $ld_saldo_aprobado $ld_saldo_mod
	    $ls_modrep="1"; //Modalidad Mensual
		$ls_distribuir="1";
		$total=count($ls_sc_cuenta);
		for($li=1;$li<=$total;$li++)
		{//echo "AJA 7<br>";	    	   
		  
		  $pos=$ds_prorep->find("sc_cuenta",$ls_sc_cuenta[$li]);//echo "DESPUES";
		  $ds_prorep->updateRow("status","I",$pos);
		} //echo "AJA 8<br>";	    	   
		$ds_prorep->updateRow("modrep",$ls_modrep,$i);
		$ds_prorep->updateRow("distribuir",$ls_distribuir,$i);
	}
	
	if ($ls_opcion=="M")
	{
	$ds_prorep->data=$_SESSION["objact"];
	$i=$_POST["fila"];
	$total=count($ls_sc_cuenta);
	for($li=1;$li<=$total;$li++)
	{  //echo "PLOOM A";
	  $pos=$ds_prorep->find("sc_cuenta",$ls_sc_cuenta[$li]);//echo "PLOOM B";
	  $ds_prorep->updateRow("status","I",$pos);
	} 
	
	}//fin de opcion ==M
	if($ls_opcion=="A")
	{   
		$ds_prorep->data=$_SESSION["objact"];
		$ld_saldo_real_ant  = $_POST["txtSaldoAnterior"];
		$ld_saldo_aprobado 	= $_POST["txtSaldoAprobado"];
		$ld_saldo_mod		= $_POST["txtSaldoModificado"];
		
		

		$ld_saldo_real_ant=str_replace('.','',$ld_saldo_real_ant);
		$ld_saldo_real_ant=str_replace(',','.',$ld_saldo_real_ant); 

		$ld_saldo_aprobado=str_replace('.','',$ld_saldo_aprobado);
		$ld_saldo_aprobado=str_replace(',','.',$ld_saldo_aprobado);
		
		$ld_saldo_mod=str_replace('.','',$ld_saldo_mod);
		$ld_saldo_mod=str_replace(',','.',$ld_saldo_mod);
	
		
		
		$ld_asignado=str_replace('.','',$ld_asignado);
		$ld_asignado=str_replace(',','.',$ld_asignado);
		$ld_div_asig = round(($ld_asignado/12),2);
		$ld_suma_dic=($ld_div_asig*12);
		$ld_m12=($ld_suma_dic-$ld_asignado);
		if($ld_m12>=0)
		{
	       $ld_diciembre=$ld_div_asig-$ld_m12;
		}
		else
		{
	       $ld_diciembre=$ld_div_asig+$ld_m12;
		}
	    $ld_total=($ld_div_asig*11)+$ld_diciembre;
	    $ld_resto=round(($ld_asignado-$ld_total),2);
	    $ld_diciembre=$ld_diciembre+$ld_resto;
		$ls_modrep="1"; //Modalidad Mensual
		$ls_distribuir="3";
		$i=$_POST["fila"];
		/*$ld_saldo_real_ant  = $ds_prorep->getValue("saldo_real_ant",$i);
		$ld_saldo_aprobado 	= $ds_prorep->getValue("saldo_apro",$i);
		$ld_saldo_mod		= $ds_prorep->getValue("saldo_mod",$i);*/
		$total=count($ls_sc_cuenta);
		for($li=1;$li<=$total;$li++)
		{
		  
		  $pos=$ds_prorep->find("sc_cuenta",$ls_sc_cuenta[$li]);
		  $ds_prorep->updateRow("status","I",$pos);
		  $ds_prorep->updateRow("asignado",0,$pos);
		  $ds_prorep->updateRow("enero",0,$pos);
		  $ds_prorep->updateRow("febrero",0,$pos);
		  $ds_prorep->updateRow("marzo",0,$pos);
		  $ds_prorep->updateRow("abril",0,$pos);
		  $ds_prorep->updateRow("mayo",0,$pos);
		  $ds_prorep->updateRow("junio",0,$pos);
		  $ds_prorep->updateRow("julio",0,$pos);
		  $ds_prorep->updateRow("agosto",0,$pos);
		  $ds_prorep->updateRow("septiembre",0,$pos);
		  $ds_prorep->updateRow("octubre",0,$pos);
		  $ds_prorep->updateRow("noviembre",0,$pos);
		  $ds_prorep->updateRow("diciembre",0,$pos);	
		  $ds_prorep->updateRow("saldo_real_ant",0,$pos);
		  $ds_prorep->updateRow("saldo_apro",0,$pos);
		  $ds_prorep->updateRow("saldo_mod",0,$pos);
		} 
		$ds_prorep->updateRow("asignado",$ld_asignado,$i);
		$ds_prorep->updateRow("enero",$ld_div_asig,$i);
		$ds_prorep->updateRow("febrero",$ld_div_asig,$i);
		$ds_prorep->updateRow("marzo",$ld_div_asig,$i);
		$ds_prorep->updateRow("abril",$ld_div_asig,$i);
		$ds_prorep->updateRow("mayo",$ld_div_asig,$i);
		$ds_prorep->updateRow("junio",$ld_div_asig,$i);
		$ds_prorep->updateRow("julio",$ld_div_asig,$i);
		$ds_prorep->updateRow("agosto",$ld_div_asig,$i);
		$ds_prorep->updateRow("septiembre",$ld_div_asig,$i);
		$ds_prorep->updateRow("octubre",$ld_div_asig,$i);
		$ds_prorep->updateRow("noviembre",$ld_div_asig,$i);
		$ds_prorep->updateRow("diciembre",$ld_diciembre,$i);
		$ds_prorep->updateRow("modrep",$ls_modrep,$i);		
		$ds_prorep->updateRow("distribuir",$ls_distribuir,$i);
		$ds_prorep->updateRow("saldo_real_ant",$ld_saldo_real_ant,$i);
		$ds_prorep->updateRow("saldo_apro",$ld_saldo_aprobado,$i);
		$ds_prorep->updateRow("saldo_mod",$ld_saldo_mod,$i);			
		
		$ls_next_cta = $int_scg->uf_scg_next_cuenta_nivel($ls_cuenta);
		$li_nivel_cta= $int_scg->uf_scg_obtener_nivel($ls_next_cta);//Obtiene nivel de la cta
		$li_totrows=$ds_prorep->getRowCount("sc_cuenta");
		$ls_cta_ceros  = $int_scg->uf_scg_sin_ceros($ls_cuenta);
		$ld_total_asignado=0;
		$ld_total_asig=0;
		$ld_total_diciembre=0;
		$ld_total_saldo_real_ant = 0;
		$ld_total_saldo_aprobado = 0;
		$ld_total_saldo_mod		 = 0;
		while($li_nivel_cta>1 && !empty($ls_next_cta))
		{
			
			$ld_total_asignado=0;
			$ld_total_asig=0;
			$ld_total_diciembre=0;
			$ld_total_saldo_real_ant = 0;
			$ld_total_saldo_aprobado = 0;
			$ld_total_saldo_mod	= 0;
			$ls_cta_aux=$ls_next_cta;
			$ls_cta_ceros  = $int_scg->uf_scg_sin_ceros($ls_next_cta);
			$pos=$ds_prorep->find("sc_cuenta",$ls_next_cta);
			$li_len=strlen($ls_cta_ceros);
			$la_sc_cuenta = $iscg_procesos->uf_disable_cta_inferior( $ls_cta_ceros, $ls_next_cta, $ls_cod_report);
			$li_totrows=count($la_sc_cuenta);
			for($li_s=1;$li_s<=$li_totrows;$li_s++)
			{
				$li_posicion=$ds_prorep->find("sc_cuenta",$la_sc_cuenta[$li_s]);
				if($li_posicion>0)
				{
					$li_new_nivel=$li_nivel_cta+1;
					$li_nivel_aux  = $int_scg->uf_scg_obtener_nivel($la_sc_cuenta[$li_s]);//Obtiene nivel de la cta
					if($li_nivel_aux==$li_new_nivel)
					{
						$ld_total_asignado=$ld_total_asignado+$ds_prorep->getValue("asignado",$li_posicion);
						$ld_total_asig=$ld_total_asig+$ds_prorep->getValue("enero",$li_posicion);
						$ld_total_diciembre=$ld_total_diciembre+$ds_prorep->getValue("diciembre",$li_posicion);
						$ld_total_saldo_real_ant = $ld_total_saldo_real_ant + $ds_prorep->getValue("saldo_real_ant",$li_posicion);
						$ld_total_saldo_aprobado = $ld_total_saldo_aprobado + $ds_prorep->getValue("saldo_apro",$li_posicion);
						$ld_total_saldo_mod		 = $ld_total_saldo_mod + $ds_prorep->getValue("saldo_mod",$li_posicion);
					}
				}
			}
			$ds_prorep->updateRow("asignado",$ld_total_asignado,$pos);
			$ds_prorep->updateRow("enero",$ld_total_asig,$pos);
			$ds_prorep->updateRow("febrero",$ld_total_asig,$pos);
			$ds_prorep->updateRow("marzo",$ld_total_asig,$pos);
			$ds_prorep->updateRow("abril",$ld_total_asig,$pos);
			$ds_prorep->updateRow("mayo",$ld_total_asig,$pos);
			$ds_prorep->updateRow("junio",$ld_total_asig,$pos);
			$ds_prorep->updateRow("julio",$ld_total_asig,$pos);
			$ds_prorep->updateRow("agosto",$ld_total_asig,$pos);
			$ds_prorep->updateRow("septiembre",$ld_total_asig,$pos);
			$ds_prorep->updateRow("octubre",$ld_total_asig,$pos);
			$ds_prorep->updateRow("noviembre",$ld_total_asig,$pos);
			$ds_prorep->updateRow("diciembre",$ld_total_diciembre,$pos);
			if($ls_cod_report == "0803")
			{
			 $ds_prorep->updateRow("saldo_real_ant",$ld_total_saldo_real_ant,$pos);
			 $ds_prorep->updateRow("saldo_apro",$ld_total_saldo_aprobado,$pos);
			 $ds_prorep->updateRow("saldo_mod",$ld_total_saldo_mod,$pos);
			}
			
						
			if($li_nivel_cta==1) 
			{				
				break;				
			}
			$ls_aux   = $int_scg->uf_scg_next_cuenta_nivel($ls_next_cta);
			$ls_next_cta=$ls_aux;
			$li_nivel_cta  = $int_scg->uf_scg_obtener_nivel($ls_next_cta);//Obtiene nivel de la cta			
		}
		}//fin del else $ls_opcion="A"

		$li_num=$ds_prorep->getRowCount("cod_report");
        for($i=1;$i<=$li_num;$i++)
	    { 
			$ls_status=$ds_prorep->getValue("status",$i);
			if($ls_status=="S")
			{
				if($ls_cod_report != "0803")
				{		
		?>
    <tr class="celdas-azules">
                <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
	            }
			    else
			    {
	  ?>
    <tr class="celdas-azules">
	 			 <td ><a href="javascript: aceptar_saldos('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("saldo_real_ant",$i);?>','<?php print $ds_prorep->getValue("saldo_apro",$i);?>','<?php print $ds_prorep->getValue("saldo_mod",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>','<?php print $ds_prorep->getValue("denominacion",$i); ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
	  <?php		   
			   }
		    }
		    elseif($ls_status=="C")
		    {
			   
			 if($ls_cod_report != "0803")
			 {  
	    ?>
    <tr class="celdas-blancas">
             <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
	  
	          }
			  else
			  {
	  ?>
    <tr class="celdas-blancas">
	 		   <td ><a href="javascript: aceptar_saldos('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("saldo_real_ant",$i);?>','<?php print $ds_prorep->getValue("saldo_apro",$i);?>','<?php print $ds_prorep->getValue("saldo_mod",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>','<?php print $ds_prorep->getValue("denominacion",$i); ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
	  <?php	  
			  }
			}
			elseif($ls_status=="I")
			{			   
			   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
			}
			 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
			 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("saldo_real_ant",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("saldo_apro",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("saldo_mod",$i),2,",",".")."</td>";
		     print"</tr>";
		 }//for
	}//if ($ls_operacion=="AUTODISTRIBUIR")

if($ls_operacion=="GUARDAR")
{	
	$li_rows=0; $li_count=-2; $li_acum_insert=0; $li_acum_update=0; $li_rtn_ins=0; $li_rtn_upd=0;
	$ls_cod_report=""; $ls_sc_cuenta=""; 
	$la_empresa=$_SESSION["la_empresa"];
	$ls_codemp  =  $la_empresa["codemp"];
	$ds_prorep->data=$_SESSION["objact"];
	$li_num=$ds_prorep->getRowCount("cod_report");
	for ($i=1; $i<= $li_num; $i++)
	{
		$la_datos[0] = $ds_prorep->getValue("cod_report",$i);  //cod_report
		$la_datos[1] = $ds_prorep->getValue("sc_cuenta",$i);  //sc_cuenta
		$la_datos[2] = $ds_prorep->getValue("denominacion",$i);  //denominacion
		$la_datos[3] = $ds_prorep->getValue("status",$i);  //status
		$la_datos[4] = $ds_prorep->getValue("asignado",$i);  //asignado
		$la_datos[5] = $ds_prorep->getValue("distribuir",$i); //distribuir
		//meses
		$la_datos[6]  = $ds_prorep->getValue("enero",$i);
		$la_datos[7]  = $ds_prorep->getValue("febrero",$i);
		$la_datos[8]  = $ds_prorep->getValue("marzo",$i);
		$la_datos[9]  = $ds_prorep->getValue("abril",$i);
		$la_datos[10] = $ds_prorep->getValue("mayo",$i);
		$la_datos[11] = $ds_prorep->getValue("junio",$i);
		$la_datos[12] = $ds_prorep->getValue("julio",$i);
		$la_datos[13] = $ds_prorep->getValue("agosto",$i);
		$la_datos[14] = $ds_prorep->getValue("septiembre",$i);
		$la_datos[15] = $ds_prorep->getValue("octubre",$i);
		$la_datos[16] = $ds_prorep->getValue("noviembre",$i);
		$la_datos[17] = $ds_prorep->getValue("diciembre",$i);
		
		$la_datos[18] = $ds_prorep->getValue("nivel",$i);
		$la_datos[19] = $ds_prorep->getValue("referencia",$i);
		$la_datos[20] = $ds_prorep->getValue("no_fila",$i);
		$la_datos[21] = $ds_prorep->getValue("tipo",$i);
		$la_datos[22] = $ds_prorep->getValue("cta_res",$i);
		$la_datos[23] = $ds_prorep->getValue("modrep",$i);
		$la_datos[24] = $ds_prorep->getValue("saldo_real_ant",$i);
		$la_datos[25] = $ds_prorep->getValue("saldo_apro",$i);
		$la_datos[26] = $ds_prorep->getValue("saldo_mod",$i);
		$ls_cod_report = $la_datos[0];
		$ls_sc_cuenta  = $la_datos[1];
		
		$li_count = $iscg_procesos->uf_count_scg_plantillacuentareporte( $ls_codemp, $ls_cod_report, $ls_sc_cuenta );
		if ($li_count==0) 
		{
			$li_rtn_ins = $iscg_procesos->uf_insert_scg_plantillacuentareporte( $la_datos );
			if ($li_rtn_ins == 0)
			{
				$msg->message("Error al insertar"); 
			}
			else
			{
				$li_acum_insert = $li_acum_insert + $li_rtn_ins;
			}
		}	
		if ($li_count==1)
		{
			$lb_valido = $iscg_procesos->uf_update_scg_plantillacuentareporte($la_datos);
		}
	}//for
	//si ejecuto todos los registros realiza las transacciones de commit o rollback
	if($lb_valido)
	{   
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="UPDATE";
		$ls_descripcion =" Actualizo la Programacion de Reportes ";
		$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
										$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
										$la_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
	    $lb_valido = $iscg_procesos->uf_sql_transaction(true);	
		if ($lb_valido)
		{
			$msg->message("Los datos han sido guardados con �xito"); 
		}
		else
		{
			$msg->message("Error en la transacci�n"); 	
		}
	}//if
	else
	{
		$lb_valido = $iscg_procesos->uf_sql_transaction(false);
		$msg->message("Error en la transacci�n"); 	
	} 
	$la_empresa =  $_SESSION["la_empresa"];
    $ls_codemp  =  $la_empresa["codemp"];
    $ls_cod_report=$_SESSION["ls_cod_report"];
	$rs_cta=$iscg_procesos->uf_select_datastore_mensual( $ls_codemp, $ls_cod_report );
    $data=$SQL->obtener_datos($rs_cta);
    $ds_prorep=new class_datastore();
    $ds_prorep->data=$data;
    $li_num=$ds_prorep->getRowCount("asignado");
    for($i=1;$i<=$li_num;$i++)
	{ 
			$ls_status=$ds_prorep->getValue("status",$i);
			if($ls_status=="S")
			{
				?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
		    }
		    elseif($ls_status=="C")
		    {
			    if($ls_cod_report != "0803")
			 {  
	    ?>
    <tr class="celdas-blancas">
             <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
	  
	          }
			  else
			  {
	  ?>
    <tr class="celdas-blancas">
	 		   <td ><a href="javascript: aceptar_saldos('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("saldo_real_ant",$i);?>','<?php print $ds_prorep->getValue("saldo_apro",$i);?>','<?php print $ds_prorep->getValue("saldo_mod",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>','<?php print $ds_prorep->getValue("denominacion",$i); ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
	  <?php	  
			  }
			}
			elseif($ls_status=="I")
			{
			   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
			}
			 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
		     print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
			 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
			 if($ls_cod_report == "0803")
			 {
			  print"<td>".number_format($ds_prorep->getValue("saldo_real_ant",$i),2,",",".")."</td>";
              print"<td>".number_format($ds_prorep->getValue("saldo_apro",$i),2,",",".")."</td>";
              print"<td>".number_format($ds_prorep->getValue("saldo_mod",$i),2,",",".")."</td>";
			 }
		     print"</tr>";
		 }//for
   }//fin de ue_guardar 
  
 if ($ls_operacion=="")
 {
		$fun->uf_limpiar_sesion();
		 $ls_cod_report="0408";
         $_SESSION["ls_cod_report"]=$ls_cod_report;
	     $la_empresa =  $_SESSION["la_empresa"];
         $ls_codemp  =  $la_empresa["codemp"];
		 $lb_valido=$iscg_procesos->uf_select_reporte($ls_codemp,$ai_cuantos,$ls_cod_report);
		 if(($lb_valido)&&($ai_cuantos<=0))
		 {
				$lb_valido = $iscg_procesos->uf_cargar_txt_balancegeneral($ls_codemp);
				if($lb_valido)
				{
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 
					 for($i=1;$i<=$li_num;$i++)
					 { 
							$ls_status=$data["status"][$i];
							if($ls_status=="S")
							{
								?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="C")
							{
							   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="I")
							{
							   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
							}
							 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
							 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_real_ant",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_apro",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_mod",$i),2,",",".")."</td>";
							 print"</tr>";
                      }//for
		         }
		 }		 
		 elseif(($lb_valido)&&($ai_cuantos>0))
		 {
			 $rs_cta=$iscg_procesos->uf_select_datastore_mensual( $ls_codemp, $ls_cod_report );
			 $data=$SQL->obtener_datos($rs_cta);
			 $ds_prorep=new class_datastore();
			 $ds_prorep->data=$data;
			 $li_num=$ds_prorep->getRowCount("cod_report");
						 
			 for($i=1;$i<=$li_num;$i++)
			 { 
				$ls_status=$data["status"][$i];
				if($ls_status=="S")
				{
					?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
				}
				elseif($ls_status=="C")
				{
				   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
				}
				elseif($ls_status=="I")
				{
				   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
				}
			     print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
				 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
				 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
				 print"</tr>";
		 }//for
	  }//else	 
}//if (ls_operacion==""	)

if($ls_operacion=="RECARGAR")
{
   $la_empresa =  $_SESSION["la_empresa"];
   $ls_codemp  =  $la_empresa["codemp"];
   $li_rtn=0 ;
   $ls_cod_report=""; 
   $lb_valido=true;
   $ls_cod_report=$_POST["select"];

    $lb_valido = $iscg_procesos->delete_scg_pc_reporte($ls_cod_report);
    if ($lb_valido)   
    {
			if ($ls_cod_report=="0405")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_inversiones($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 for($i=1;$i<=$li_num;$i++)
					 { 
								$ls_status=$data["status"][$i];
								if($ls_status=="S")
								{
									?>
    <tr class="celdas-azules">
		      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="C")
								{
								   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="I")
								{
								   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
								}
								 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
								 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
								 print"</tr>";
							 }//for
		         }//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if($ls_cod_report=="0405")
			if ($ls_cod_report=="0714")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_inversiones_0714($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 for($i=1;$i<=$li_num;$i++)
					 { 
								$ls_status=$data["status"][$i];
								if($ls_status=="S")
								{
									?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="C")
								{
								   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
								}
								elseif($ls_status=="I")
								{
								   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
								}
								 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
								 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
								 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
								 print"</tr>";
							 }//for
		         }//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if($ls_cod_report=="0714")
			
			if ($ls_cod_report=="0406")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_edoresultado($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 for($i=1;$i<=$li_num;$i++)
					 { 
							$ls_status=$data["status"][$i];
							if($ls_status=="S")
							{
								?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="C")
							{
							   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="I")
							{
							   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
							}
							 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
							 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
							 print"</tr>";
					}//for
				}//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if ($ls_cod_report=="0406")
			
			if ($ls_cod_report=="0408")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_balancegeneral($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 
					 for($i=1;$i<=$li_num;$i++)
					 { 
							$ls_status=$data["status"][$i];
							if($ls_status=="S")
							{
								?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="C")
							{
							   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="I")
							{
							   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
							}
							 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
							 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_real_ant",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_apro",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_mod",$i),2,",",".")."</td>";
							 
							 print"</tr>";
						 }//for
				}//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if ($ls_cod_report=="0408")
			
			
			if ($ls_cod_report=="0803")
			{
				$lb_valido = $iscg_procesos->uf_cargar_cuentas_balancegeneral_ins08($ls_codemp);
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 
					 for($i=1;$i<=$li_num;$i++)
					 { 
							$ls_status=$data["status"][$i];
							if($ls_status=="S")
							{
								?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar_saldos('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("saldo_real_ant",$i);?>','<?php print $ds_prorep->getValue("saldo_apro",$i);?>','<?php print $ds_prorep->getValue("saldo_mod",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>','<?php print $ds_prorep->getValue("denominacion",$i); ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="C")
							{
							   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar_saldos('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("saldo_real_ant",$i);?>','<?php print $ds_prorep->getValue("saldo_apro",$i);?>','<?php print $ds_prorep->getValue("saldo_mod",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>','<?php print $ds_prorep->getValue("denominacion",$i); ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="I")
							{
							   ?>
    <tr class="celdas-amarillas">
      <td><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
							}
							 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
							 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_real_ant",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_apro",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("saldo_mod",$i),2,",",".")."</td>";
							 
							 print"</tr>";
						 }//for
				}//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if ($ls_cod_report=="0408")
			
			
			if ($ls_cod_report=="0414")
			{
				$lb_valido = $iscg_procesos->uf_cargar_txt_ctaahorroinversion($ls_codemp);	
				if($lb_valido)
				{
	                 $msg->message("Los datos fueron cargados");
				     $rs_cta=$iscg_procesos->uf_select_scg_datastore( $ls_codemp, $ls_cod_report );
					 $data=$SQL->obtener_datos($rs_cta);
					 $ds_prorep=new class_datastore();
					 $ds_prorep->data=$data;
					 $li_num=$ds_prorep->getRowCount("cod_report");
					 
					 for($i=1;$i<=$li_num;$i++)
					 { 
							$ls_status=$data["status"][$i];
							if($ls_status=="S")
							{
								?>
    <tr class="celdas-azules">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="C")
							{
							   ?>
    <tr class="celdas-blancas">
      <td ><a href="javascript: aceptar('<?php print $ds_prorep->getValue("asignado",$i);?>','<?php print $ds_prorep->getValue("sc_cuenta",$i);?>','<?php print $i?>','<?php print $li_num?>','<?php print $ls_status ?>');"><?php print $ds_prorep->getValue("sc_cuenta",$i);?></a></td>
      <?php
							}
							elseif($ls_status=="I")
							{
							   ?>
    <tr class="celdas-amarillas">
      <td height="20"><?php print $ds_prorep->getValue("sc_cuenta",$i)?></td>
      <?php
							}
							 print"<td width=250>".$ds_prorep->getValue("denominacion",$i)."</td>";
							 print"<td>".number_format($ds_prorep->getValue("asignado",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("enero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("febrero",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("marzo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("abril",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("mayo",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("junio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("julio",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("agosto",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("septiembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("octubre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("noviembre",$i),2,",",".")."</td>";
							 print"<td>".number_format($ds_prorep->getValue("diciembre",$i),2,",",".")."</td>";
							 print"</tr>";
						 }//for
				}//if($lb_valido)
				else
				{
				  $msg->message("Error al cargar los datos");
				}
			}//if ($ls_cod_report=="0414")
      }//if($lb_valido)
}//fin if($ls_operacion=="RECARGAR")

?>
  </TABLE>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;  </p>
  <tr class="fd-blanco">        
  <tr class="fd-blanco">          
  <tr class="fd-blanco">    
  <tr class="fd-blanco">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
</form>
<?php
$_SESSION["objact"]=$ds_prorep->data;
$_SESSION["ls_cod_report"]=$ls_cod_report;
?>
</body>
<script language="javascript">
function uf_format(obj)
{
	if(obj.value=="")
	{
	 	obj.value="0.0000";
	} 
	else
	{
		ldec_monto=uf_convertir(obj.value);
		obj.value=ldec_monto;
	}
}
function ue_distribuir()
{
   var i; 
   f=document.form1;
   if((f.txtAsignado.value=="")||(f.txtCuenta.value==""))
   {
     alert(" Por Favor Seleccione una Cuenta....");
   }
   else
   {
    for (i=0;i<f.radiobutton.length;i++)
	{ 
       if (f.radiobutton[i].checked) 
          break; 
    } 
    document.opcion = f.radiobutton[i].value; 
     
	 if  (document.opcion=="M" ) 
     {
		 f=document.form1;
		 i=f.fila.value;
		 if((f.txtAsignado.value=="0.0000")||(f.txtAsignado.value=="0,00"))
		 {
             alert(" Monto Incorrecto....");
		     f.txtAsignado.focus();
		 }
		 else
		 {
		     pagina="sigesp_scg_wdist_manual.php?fila="+i+"&txtAsignado="+f.txtAsignado.value;
			 window.open(pagina,"Asignaci�n","menubar=no,toolbar=no,scrollbars=no,width=500,height=390,left=50,top=50,resizable=yes,location=no");
			 f.operacion.value="DISTRIBUIR";
		 }
	 }
     
   if (document.opcion=="A")
   {
	 f=document.form1;
	 if((f.txtAsignado.value=="0.0000")||(f.txtAsignado.value=="0,00"))
	 {
		 alert(" Monto Incorrecto....");
		 f.txtAsignado.focus();
	 }
	 else
	 {
	   f.operacion.value="DISTRIBUIR";
	   f.action="sigesp_scg_wproc_progrep.php";
	   f.submit();
	 } 
   }
   
   if (document.opcion=="N")
   {
	 f=document.form1;
	 if((f.txtAsignado.value=="0.0000")||(f.txtAsignado.value=="0,00"))
	 {
		 alert(" Monto Incorrecto....");
		 f.txtAsignado.focus();
	 }
	 else
	 {
	   f.operacion.value="DISTRIBUIR";
	   f.action="sigesp_scg_wproc_progrep.php";
	   f.submit();
	 } 		   
    }
  } 
}

function uf_combo()
{
	f=document.form1;
	f.operacion.value="CARGAR_CUENTAS";
	f.action="sigesp_scg_wproc_progrep.php";
	f.submit();
}

function selec()
{
	f=document.form1;
	if(f.select.value=="00")
	{
	   alert (" Por favor Seleccione un Reporte.....");
	}
	else
	{
		f.operacion.value="SELECT";
		f.txtAsignado.value=" ";
		f.txtCuenta.value=" ";
		if(f.txtSaldoAnterior != null)
		{
		 f.txtSaldoAnterior.value = "0,00";
		}
		if(f.txtSaldoAprobado != null)
		{
		 f.txtSaldoAprobado.value = "0,00";
		}
		if(f.txtSaldoModificado != null)
		{
		 f.txtSaldoModificado.value = "0,00";
		}
		f.action="sigesp_scg_wproc_progrep.php";
		f.submit();
	}
}

function ue_guardar()
{
	f=document.form1;
	f.operacion.value="GUARDAR";
	f.txtAsignado.value=" ";
	f.txtCuenta.value=" ";
	f.action="sigesp_scg_wproc_progrep.php";
	f.submit();
}

function ue_recargar()
{
	f=document.form1;
	resp=confirm("Este proceso borrara todas las cuentas y las copiara del plan original. � Esta seguro de proceder ?");
	if (resp==true)
	{
		f.operacion.value="RECARGAR";
		f.action="sigesp_scg_wproc_progrep.php";
		f.submit();
	}
	else
	{
		f.action="sigesp_scg_wproc_progrep.php";
	}
}

function aceptar(c,d,i,li_row,s,e)
{
	f=document.form1;
	f.txtAsignado.value=c;
	f.txtCuenta.value=d;
	f.fila.value=i;
	f.numrow.value=li_row;
	f.status.value=s;
	f.txtAsignado.focus(true);
}

function aceptar_saldos(asignado,saldoant,saldoapr, saldomod, cuenta,i,li_row,s,denominacion)
{
	f=document.form1;
	f.txtCuenta.value    = cuenta;
	f.txtAsignado.value  = asignado;
	f.txtSaldoAnterior.value   = saldoant;
	f.txtSaldoAprobado.value   = saldoapr;
	f.txtSaldoModificado.value = saldomod;
	f.dencta.value = denominacion;
	f.fila.value=i;
	f.numrow.value=li_row;
	f.status.value=s;
	f.txtAsignado.focus(true);
}

function ue_asignar_saldos_ejecutados(reporte)
{
    f=document.form1;
	var Xpos=((screen.width/2)-(500/2)); 
	var Ypos=((screen.height/2)-(400/2));
	var fila = f.fila.value;
	var cuenta = f.txtCuenta.value;
	var denominacion = f.dencta.value;
	if((cuenta != "")&&(reporte !="")&&(fila  != ""))
	{
	 window.open("sigesp_scg_p_asig_saldos_ejecutados.php?cuenta="+cuenta+"&denominacion="+denominacion+"&reporte="+reporte+"&fila="+fila+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=660,height=450,left="+Xpos+",top="+Ypos+",location=no,resizable=no");
	}
	else
	{
	 alert("Debe seleccionar una cuenta, verifique por favor!!");
	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

 function EvaluateText(cadena, obj){ 
	
    opc = false; 
	
    if (cadena == "%d")  
      if ((event.keyCode > 64 && event.keyCode < 91)||(event.keyCode > 96 && event.keyCode < 123)||(event.keyCode ==32))  
      opc = true; 
    if (cadena == "%f"){ 
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
 //--------------------------------------------------------
//	Funci�n que formatea un n�mero
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
</script>
</html>