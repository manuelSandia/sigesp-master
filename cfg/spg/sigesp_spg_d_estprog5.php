<?php
session_start();
$la_data       = $_SESSION["la_empresa"];
$ls_denestpro1 = $la_data["nomestpro1"];
$ls_denestpro2 = $la_data["nomestpro2"];
$ls_denestpro3 = $la_data["nomestpro3"];
$ls_denestpro4 = $la_data["nomestpro4"];
$ls_nomniv5    = $la_data["nomestpro5"];
$ls_estcencos=$_SESSION["la_empresa"]["estcencos"];

if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
   $ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
   $ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
   $ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
   $ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
   $ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
   $ls_clasificacion = $_POST["txtclasificacion"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de <?php print $ls_nomniv5 ?>  </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
.Estilo5 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo6 {
	color: #006699;
	font-size: 12px;
}
.Estilo8 {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo10 {font-size: 10px}
.Estilo11 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo13 {font-size: 12px}
.Estilo14 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
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
a:hover {
	color: #006699;
}
-->
</style></head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div>      </td>
  </tr>
</table>
<p>
  <?php
	require_once("class_folder/sigesp_spg_c_estprog4.php");
	require_once("class_folder/sigesp_spg_c_estprog5.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	$io_conect  = new sigesp_include();
    $conn       = $io_conect->uf_conectar();
	$io_sql     = new class_sql($conn);
	$io_msg     = new class_mensajes();
	$io_funcion = new class_funciones(); 
 	$io_estpro4 = new sigesp_spg_c_estprog4($conn);
	$io_estpro5 = new sigesp_spg_c_estprog5($conn);
	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre                     = $_SESSION["la_empresa"];
	$ls_empresa               = $arre["codemp"];
	$ls_codemp                = $ls_empresa;
	$ls_logusr                = $_SESSION["la_logusr"];
	$ls_sistema               = "CFG";
	$ls_ventanas              = "sigesp_spg_d_estprog5.php";
	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;
    $li_estmodest             = $arre["estmodest"];
    /*if ($li_estmodest=='1')
	   {
	     $li_maxlenght_1 = '20';
	     $li_maxlenght_2 = '6';
	     $li_maxlenght_3 = '3';
	     $li_size        = '25';
	     $ls_ancho       = '65';
	   }
	else
	   {
	     $li_maxlenght_1 = '2';
	     $li_maxlenght_2 = '2';
	     $li_maxlenght_3 = '2';
	     $li_size        = '5';
	     $ls_ancho       = '85';
	   }
	*/
	if (array_key_exists("permisos5",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos            = $_POST["permisos5"];
			$la_accesos["leer"]     = $_POST["leer"];			
			$la_accesos["incluir"]  = $_POST["incluir"];			
			$la_accesos["cambiar"]  = $_POST["cambiar"];
			$la_accesos["eliminar"] = $_POST["eliminar"];
			$la_accesos["imprimir"] = $_POST["imprimir"];
			$la_accesos["anular"]   = $_POST["anular"];
			$la_accesos["ejecutar"] = $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]     = "";	 	
		$la_accesos["incluir"]  = "";
		$la_accesos["cambiar"]  = "";
		$la_accesos["eliminar"] = "";
		$la_accesos["imprimir"] = "";
		$la_accesos["anular"]   = "";
		$la_accesos["ejecutar"] = "";
		$ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	//Inclusión de la clase de seguridad.
	
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	$ds=null;
	if  (array_key_exists("statusprog5",$_POST))
	{
  	  $ls_status = $_POST["statusprog5"];
	}
	else
	{
	  $ls_status="NUEVO";	  
	}	
	if( array_key_exists("opeestpro5",$_POST))
	{
		$ls_operacion  = $_POST["opeestpro5"];
		$ls_codestpro1 = $_POST["txtcodestpro1"];
		$ls_codestpro2 = $_POST["txtcodestpro2"];
		$ls_codestpro3 = $_POST["txtcodestpro3"];
		$ls_codestpro4 = $_POST["txtcodestpro4"];
		$ls_codestpro5 = $_POST["txtcodestpro5"];
		$ls_denestpro1 = $_POST["txtdenestpro1"];
		$ls_denestpro2 = $_POST["txtdenestpro2"];
		$ls_denestpro3 = $_POST["txtdenestpro3"];
		$ls_denestpro4 = $_POST["txtdenestpro4"];
		$ls_denestpro5 = $_POST["txtdenestpro5"];
		$ls_codcencos = $_POST["txtcodcencos"];
		$ls_dencencos = $_POST["txtdencencos"];
		if (!array_key_exists('session_activa',$_SESSION))
		{
			$ls_codfuefin  = $_POST["txtcodigo"];
			$ls_denfuefin  = $_POST["txtdenominacion"];
		}
		else
		{
			$ls_codfuefin  ="";
			$ls_denfuefin  = "";
		}		
		$readonly      = "";
		$disabled      = "";
	}
	else
	{
		$ls_operacion  = "";
		$ls_codestpro1 = $_POST["txtcodestpro1"];
		$ls_denestpro1 = $_POST["txtdenestpro1"];
	    $ls_codestpro2 = $_POST["txtcodestpro2"];
		$ls_denestpro2 = $_POST["txtdenestpro2"];
		$ls_codestpro3 = $_POST["txtcodestpro3"];
		$ls_denestpro3 = $_POST["txtdenestpro3"];
		$ls_codestpro4 = $_POST["txtcodestpro4"];
		$ls_denestpro4 = $_POST["txtdenestpro4"];
		if (array_key_exists("txtcodestpro5",$_POST))
		   {
			 $ls_codestpro5  = $_POST["txtcodestpro5"];
			 $ls_denestpro5  = $_POST["txtdenestpro5"];
			 if (!array_key_exists('session_activa',$_SESSION))
			 {
				$ls_codfuefin  = $_POST["txtcodigo"];
				$ls_denfuefin  = $_POST["txtdenominacion"];
		 	 }
			 else
			 {
				$ls_codfuefin  ="";
				$ls_denfuefin  = "";
			 }		
		   }
		else
		   { 
			 $ls_codestpro5 = "";
			 $ls_denestpro5 = "";
			 $ls_codfuefin  = "";
			 $ls_denfuefin  = "";
		   }
		$disabled="disabled";
		$readonly="";
	}
 
 if ($ls_operacion == "NUEVO")
	{
	  $ls_codestpro1 = $_POST["txtcodestpro1"];
	  $ls_denestpro1 = $_POST["txtdenestpro1"];
      $ls_codestpro2 = $_POST["txtcodestpro2"];
	  $ls_denestpro2 = $_POST["txtdenestpro2"];
	  $ls_codestpro3 = $_POST["txtcodestpro3"];
	  $ls_denestpro3 = $_POST["txtdenestpro3"];
	  $ls_codestpro4 = $_POST["txtcodestpro4"];
	  $ls_denestpro4 = $_POST["txtdenestpro4"];
	  $ls_codestpro5 = "";
	  $ls_denestpro5 = "";
	  $ls_codfuefin  = "";
	  $ls_denfuefin  = "";
	  $readonly      = "";
	  $disabled      = "disabled";
	  $ls_status     = "NUEVO";
	}
 
 if ($ls_operacion == "GUARDAR")
	{
		$disabled      = "";
	
		$ls_codestp1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
	    $ls_codestp2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,25);
		$ls_codestp3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,25);
		$ls_codestp4= $io_funcion->uf_cerosizquierda($ls_codestpro4,25);
		$ls_codestp5= $io_funcion->uf_cerosizquierda($ls_codestpro5,25);
		 $ls_clasificacion = $_POST["txtclasificacion"];
		$lb_encontrado = $io_estpro4->uf_spg_select_estprog4($ls_codemp,$ls_codestp1,$ls_codestp2,$ls_codestp3,$ls_codestp4,$ls_clasificacion);
	    if ($lb_encontrado)
	       {
			 $lb_existe = $io_estpro5->uf_spg_select_estprog5($ls_codemp,$ls_codestp1,$ls_codestp2,$ls_codestp3,$ls_codestp4,$ls_codestp5,$ls_clasificacion); 
			 if (!$lb_existe)
			    {
				  $lb_valido = $io_estpro5->uf_spg_insert_estprog5($ls_codemp,$ls_codestp1,$ls_codestp2,$ls_codestp3,$ls_codestp4,$ls_codestp5,$ls_denestpro5,$ls_codfuefin,$li_estmodest,$ls_clasificacion,$ls_codcencos,$la_seguridad);		
				  if ($lb_valido)
					 {
					   $io_sql->commit();
					   $io_msg->message("Registro Incluido !!!");
					   $ls_codestpro5 = "";
					   $ls_denestpro5 = "";
					   $ls_codfuefin  = "";
					   $ls_denfuefin  = "";
					   $ls_status     = "NUEVO";
					 }
				  else
					 { 
					   $io_sql->rollback();
					   $io_msg->message($io_estpro5->is_msg_error);
					 }
			    }
			 else
			    {
				  $lb_valido = $io_estpro5->uf_spg_update_estprog5($ls_codemp,$ls_codestp1,$ls_codestp2,$ls_codestp3,$ls_codestp4,$ls_codestp5,$ls_denestpro5,$ls_codfuefin,$ls_codcencos,$la_seguridad);		
				  if ($lb_valido)
					 { 
					   $io_sql->commit();
					   $io_msg->message("Registro Actualizado !!!");
					   $ls_codestpro5 = "";
					   $ls_denestpro5 = "";
					   $ls_codfuefin  = "";
					   $ls_denfuefin  = "";
					   $ls_status     = "NUEVO";
					 }
				  else
					 {
					   $io_sql->rollback();
					   $io_msg->message($io_estpro5->is_msg_error);
					 }
				}
	       }
		else
		   {
		     $io_msg->message("Debe registrar la Estructura de Nivel 4 previamente !!!");
		   }
		 $readonly = "";
	   }	 

   if ($ls_operacion == "ELIMINAR")
	  { 
	    $ls_clasificacion = $_POST["txtclasificacion"];
		$ls_codestp1 = $io_funcion->uf_cerosizquierda($ls_codestpro1,25);
	    $ls_codestp2 = $io_funcion->uf_cerosizquierda($ls_codestpro2,25);
		$ls_codestp3 = $io_funcion->uf_cerosizquierda($ls_codestpro3,25);
		$ls_codestp4 = $io_funcion->uf_cerosizquierda($ls_codestpro4,25);
		$ls_codestp5 = $io_funcion->uf_cerosizquierda($ls_codestpro5,25);
		$lb_valido     = $io_estpro5->uf_spg_delete_estpro5($ls_codemp,$ls_codestp1,$ls_codestp2,$ls_codestp3,$ls_codestp4,$ls_codestp5,$ls_denestpro5,$ls_clasificacion,$la_seguridad);
		if ($lb_valido)
		   {
		     $io_sql->commit();
			 $io_msg->message("Registro Eliminado !!!");
		   }
		else
		   {
		     $io_sql->rollback();
			 $io_msg->message($io_estpro5->is_msg_error);
		   }
		$ls_codestpro5 = "";
		$ls_denestpro5 = "";
		$ls_codfuefin  = "";
	    $ls_denfuefin  = "";
		$ls_status     = "NUEVO";
		$readonly      = "";
	  }
		

 if ($ls_operacion == "BUSCAR")
	{
		$ls_codestprog1 = $_POST["txtcodestprog1"];
		$ls_denestprog1 = $_POST["txtdenestprog1"];
		$ls_codestprog2 = $_POST["txtcodestpro2"];
		$ls_denestprog2 = $_POST["txtdenestprog2"];
		$readonly       = "readonly";
	}
	
?>
</p>
<p>&nbsp;</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos5 id=permisos5 value='$ls_permisos'>");
	print("<input type=hidden name=leer      id=leer      value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir   id=incluir   value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar   id=cambiar   value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar  id=eliminar  value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir  id=imprimir  value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular    id=anular    value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar  id=ejecutar  value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
?>
  <table width="695" height="310" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="693" height="308"><div align="center">
        <table width="610" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-ventana">
            <td height="22" colspan="3"><input name="hidmaestro" type="hidden" id="hidmaestro" value="Y">
            <input name="opeestpro5" type="hidden" id="opeestpro5">
 <?php print $ls_nomniv5 ?></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td width="493" height="22" colspan="2"><input name="statusprog5" type="hidden" id="statusprog5" value="<?php print $ls_status ?>">
              <label></label></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22" align="right"><?php print $la_data["nomestpro1"]?></td>
            <td height="22" colspan="2" align="left"><input name="txtcodestpro1" type="text" id="txtcodestpro1" size="<?php print $ls_loncodestpro1+10 ?>" maxlength="<?php print $ls_loncodestpro1 ?>" value="<?php print  $ls_codestpro1 ?>" readonly="" style="text-align:center">
                <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" value="<?php print $ls_denestpro1 ?>" size="<?php print $ls_ancho ?>" maxlength="80" readonly=""></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right"><?php print $la_data["nomestpro2"] ?></div></td>
            <td height="22" colspan="2"><label>
              <input name="txtcodestpro2" type="text" id="txtcodestpro2" style="text-align:center " value="<?php print $ls_codestpro2 ?>" size="<?php print $ls_loncodestpro2+10 ?>" maxlength="<?php print $ls_loncodestpro2 ?>" readonly>
              <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" value="<?php print $ls_denestpro2 ?>" size="<?php print $ls_ancho ?>" maxlength="80" readonly>
            </label></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right"><?php print $la_data["nomestpro3"]?></div></td>
            <td height="22" colspan="2"><label>
              <input name="txtcodestpro3"  id="txtcodestpro3" type="text" value="<?php print  $ls_codestpro3 ?>" size="<?php print $ls_loncodestpro3+10 ?>" maxlength="<?php print $ls_loncodestpro3 ?>" style="text-align:center" readonly>
              <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" value="<?php print $ls_denestpro3 ?>" size="<?php print $ls_ancho ?>" maxlength="80" readonly="">
            </label></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22"><div align="right"><?php print $la_data["nomestpro4"]?></div></td>
            <td height="22" colspan="2"><label>
              <input name="txtcodestpro4" type="text" id="txtcodestpro4" value="<?php print $ls_codestpro4 ?>" size="<?php print $ls_loncodestpro4+10 ?>" maxlength="<?php print $ls_loncodestpro4 ?>" style="text-align:center">
              <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" style="text-align:left" value="<?php print $ls_denestpro4 ?>" size="<?php print $ls_ancho ?>" readonly>
            </label></td>
          </tr>
          <tr class="formato-blanco">
            <td width="94" height="22"><div align="right" >
                <p>C&oacute;digo</p>
            </div></td>
            <td height="22" colspan="2"><div align="left" >
              <label>
              <input name="txtcodestpro5" type="text" id="txtcodestpro5" value="<?php print $ls_codestpro5 ?>" size="<?php print $ls_loncodestpro5+10 ?>" maxlength="<?php print $ls_loncodestpro5 ?>" <?php print $readonly?> style="text-align:center" onBlur="javascript:rellenar_cad(this.value,<?php print $ls_loncodestpro5 ?>,'cod')" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-');">
               <input name="txtclasificacion" type="hidden" id="txtclasificacion" value="<?php print $ls_clasificacion?>" >
              </label>
            </div></td>
          </tr>
          
          
          <tr class="formato-blanco">
            <td height="22"><div align="right">Denominaci&oacute;n</div></td>
            <td height="22" colspan="2"><div align="left">
                <input name="txtdenestpro5" type="text" id="txtdenestpro5" style="text-align:left" value="<?php print $ls_denestpro5 ?>" size="91" maxlength="254" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnńopqrstuvwxyz '+',-.;*&?ż!ˇ+()[]{}%@/'+'´áéíóú');">
            </div></td>
          </tr>
<?php
	if (!array_key_exists('session_activa',$_SESSION))
	{

?>
            <tr> 
            <td height="22"><div align="right">Fuente de Financiamiento</div></td>
            <td height="22" colspan="3"><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center" value="<?php print $ls_codfuefin;?>" size="5" maxlength="2" readonly> 
              <a href="javascript: ue_catalogo('sigesp_spg_cat_fuentefinan.php');"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> 
              <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" value="<?php print $ls_denfuefin;?>" size="50" readonly></td>
          </tr>
            <tr>
              <td height="22"><div align="right">Centro de Costos </div></td>
              <td height="22" colspan="3"><input name="txtcodcencos" type="text" id="txtcodcencos" maxlength="2" size="4"onKeyPress="return keyRestrict(event,'1234567890');"  style="text-align:center " >
                <a href="javascript: ue_centrocostos();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                <input name="txtdencencos" type="text" class="sin-borde" id="txtdencencos" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-')" size="50" maxlength="250"  readonly></td>
            </tr>
<?php
	}
?>
          <input name="estcencos" type="hidden" id="estcencos" value="<?php print $ls_estcencos; ?>">
          <tr class="formato-blanco">
            <td height="22" colspan="3">&nbsp;
                <table width="543" height="21" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td width="271"><div align="center"><a href="javascript: uf_estprog4();"><?php print "Ir a ".$la_data["nomestpro4"]?></a></div></td>
                    <td width="270"><div align="center"><a href="javascript: uf_estprog1();"><?php print "Ir a ".$la_data["nomestpro1"]?></a></div></td>
                  </tr>
              </table></td>
          </tr>
          <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td height="22" colspan="2" align="left">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
f   = document.formulario;
fop = opener.document.formulario;

function ue_nuevo()
{
	f.statusprog5.value = "NUEVO";
	f.txtcodigo.value ="";
	f.txtdenestpro5.value ="";
	f.txtcodestpro5.value="";
	f.action           = "sigesp_spg_d_estprog5.php";
	f.submit();
}

function ue_guardar()
{
li_incluir    = f.incluir.value;
li_cambiar    = f.cambiar.value;
lb_status     = f.statusprog5.value;
ls_codestpro1 = f.txtcodestpro1.value;
ls_codestpro2 = f.txtcodestpro2.value;
ls_codestpro3 = f.txtcodestpro3.value;
ls_codestpro4 = f.txtcodestpro4.value;
ls_codestpro5 = f.txtcodestpro5.value;
ls_denestpro5 = f.txtdenestpro5.value;

if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
   {
	 if ((ls_codestpro1!="") && (ls_codestpro2!="") && (ls_codestpro3!="") && (ls_codestpro4!="") && (ls_codestpro5!="") && (ls_denestpro5!=""))
	    {
		  f.opeestpro5.value ="GUARDAR";
		  f.action="sigesp_spg_d_estprog5.php";
		  f.submit();
		}
     else
	    {
		  alert("Debe completar todos los campos !!!");
		}
  }
 else
  {
    alert("No tiene permiso para realizar esta operación !!!");
  }  
}

function ue_eliminar()
{
var borrar="";

li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (f.txtcodestpro5.value=="")
        {
	      alert("No ha seleccionado ningún registro para eliminar !!!");
        }
	 else
	    {
		  borrar=confirm("ż Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f.opeestpro5.value = "ELIMINAR";
			   f.action          = "sigesp_spg_d_estprog5.php";
			   f.submit();
		     }
		  else
		     { 
			   f.action="sigesp_spg_d_estprog5.php";
		 	   alert("Eliminación Cancelada !!!");
		     }
	    }
   }
 else
   {
     alert("No tiene permiso para realizar esta operacion");
   }
}


function ue_buscar()
{
	ls_codestpro1 = f.txtcodestpro1.value;
	ls_codestpro2 = f.txtcodestpro2.value;
	ls_codestpro3 = f.txtcodestpro3.value;
	ls_codestpro4 = f.txtcodestpro4.value;
	ls_codestpro5 = f.txtcodestpro5.value;
	ls_denestpro1 = f.txtdenestpro1.value;
	ls_denestpro2 = f.txtdenestpro2.value;
	ls_denestpro3 = f.txtdenestpro3.value;
	ls_denestpro4 = f.txtdenestpro4.value;
	ls_denestpro5 = f.txtdenestpro5.value;
	ls_estcla     = f.txtclasificacion.value;
	window.open("sigesp_spg_cat_estpro5.php?txtcodestpro1="+ls_codestpro1+"&txtdenestpro1="+ls_denestpro1+"&txtcodestpro2="+ls_codestpro2+"&txtdenestpro2="+ls_denestpro2+"&txtcodestpro3="+ls_codestpro3+"&txtdenestpro3="+ls_denestpro3+"&txtcodestpro4="+ls_codestpro4+"&txtdenestpro4="+ls_denestpro4+"&txtpantalla=d_estprog5"+"&txtclasificacion="+ls_estcla,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=50,top=50,location=no,resizable=yes");
}

function ue_cerrar()
{
	f.action="sigespwindow_blank.php";
	f.submit();
}

function uf_estprog4()
{
	f.action="sigesp_spg_d_estprog4.php";
	f.submit();
}

function uf_estprog1()
{
	f.action="sigesp_spg_d_estprog1.php";
	f.submit();
}

function rellenar_cad(cadena,longitud,campo)
{
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
	total        = longitud-lencad;
	if (cadena!="")
	   {
		 for (i=1;i<=total;i++)
			 {
			   cadena_ceros=cadena_ceros+"0";
			 }
		cadena=cadena_ceros+cadena;
		if (campo=="cod")
		   {
			 f.txtcodestpro5.value=cadena;
		   }
	   }
}



function ue_catalogo(ls_catalogo)
{
	if(ls_catalogo=='sigesp_spg_cat_fuentefinan.php')
	{
		//f=document.formulario;
		//lb_existe=f.existe.value;
		window.open("sigesp_spg_cat_fuentefinan.php?txtpantalla=d_estprog5","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
		/*if(lb_existe=="TRUE")
		{
			alert("La Unidad Ejecutora no se puede modificar.");		
		}
		else
		{
			if((f.totrowbienes.value>1)||(f.totrowservicios.value>1)||(f.totrowconceptos.value>1)||
			   (f.totrowcargos.value>0)||(f.totrowcuentas.value>1)||(f.totrowcuentascargo.value>1))
			{
				alert("La Unidad Ejecutora no se puede modificar, ya existen movimientos en la solicitud.");		
			}
			else
			{
				// abre el catalogo que se paso por parametros
				window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
			}
		}*/
	}
	else
	{
		// abre el catalogo que se paso por parametros
		//window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
	}
}
function ue_centrocostos()
{
	f=document.formulario;
	li_estcencos=f.estcencos.value;
	if (li_estcencos==1)
	{
		pagina="sigesp_spg_cat_centro_costo.php";
		window.open(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=450,resizable=yes,location=no")
	}
	else
	{
		alert("El sistema no esta configurado para trabajar con Centro de Costos");
	}
}


</script>
</html>