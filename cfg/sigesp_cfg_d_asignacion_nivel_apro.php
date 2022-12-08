<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Configuración de Nivel de Aprobación</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"></head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?php
require_once("class_folder/sigesp_cfg_c_nivel_aprobacion.php");   
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_c_check_relaciones.php");
require_once("../shared/class_folder/class_funciones_db.php");
$io_conect       = new sigesp_include();
$con             = $io_conect-> uf_conectar ();
$la_emp          = $_SESSION["la_empresa"];
$io_msg          = new class_mensajes(); //Instanciando la clase mensajes 
$io_sql          = new class_sql($con); //Instanciando  la clase sql
$io_dsest        = new class_datastore(); //Instanciando la clase datastore
$lb_valido       = "";
$io_chkrel       = new sigesp_c_check_relaciones($con);
$io_aprobacion= new sigesp_cfg_c_nivel_aprobacion($con);//Instanciando la Clase Sigesp Definiciones.
$io_funciondb = new class_funciones_db($con);



//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_cfg_d_asignacion_nivel_apro.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos            = $_POST["permisos"];
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
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////


if (array_key_exists("operacion",$_POST))
   {
     $ls_aperapr	   		   = $_POST["cmboperacion"];
	 $ls_operacion             = $_POST["operacion"];
	 $ls_codigoasi             = $_POST["txtcodigoasi"];
	 $lr_datos["codasinivel"]  = $ls_codigoasi;
	 $ls_codigonivel           = $_POST["txtcodigonivel"];
	 $lr_datos["codnivel"]     = $ls_codigonivel;
	 $ls_descripcion           = $_POST["txtdescripcion"];
	 $lr_datos["descripcion"]  = $ls_descripcion;
	 $ls_estatus               = $_POST["status"];
   }
else
   {
	 $ls_aperapr	   ="";
	 $ls_operacion     = "";
	 $ls_codigoasi     = "";
     $ls_codigonivel   = "";
	 $ls_descripcion   = "";
	 $ls_estatus       = "NUEVO";
	 $ls_status='N';
	 $lb_empresa=true;
	 $ls_codigoasi=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_empresa,"sigesp_asig_nivel","codasiniv",$ls_status);
   }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  if ($ls_operacion=="NUEVO")
	{ 
      $ls_status='N';
	  $lb_empresa=true;
	  $ls_codigoasi=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_empresa,"sigesp_asig_nivel","codasiniv",$ls_status);;
	  $ls_codigonivel="";
	  $ls_operacionsis="";
	  $ls_descripcion="";
	  $ls_aperapr="";
    }

 
 if ($ls_operacion=="GUARDAR")
	{ 
	  $lb_valido = $io_aprobacion->uf_guardar_asignaciones_nivel($lr_datos,$ls_aperapr,$la_seguridad);
		 $ls_aperapr	   ="";
		 $ls_operacion     = "";
		 $ls_codigoasi     = "";
		 $ls_codigonivel   = "";
		 $ls_descripcion   = "";
		 $ls_estatus       = "NUEVO";
		 $ls_status='N';
		 $lb_empresa=true;
		 $ls_codigoasi=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_empresa,"sigesp_asig_nivel","codasiniv",$ls_status);
    }
	   
if ($ls_operacion=="ELIMINAR")
   {
	  $lb_existe = $io_aprobacion->uf_select_asig_niveles($ls_codigoasi,$ls_codigonivel);
      if ($lb_existe)
	     {
	        $lb_valido = $io_aprobacion->uf_delete_asig_nivel($ls_codigoasi,$ls_codigonivel,$la_seguridad);
	        if ($lb_valido)
               {
		         $io_sql->commit();
		         $io_msg->message("Registro Eliminado !!!");
		         $ls_aperapr	   ="";
				 $ls_operacion     = "";
				 $ls_codigoasi     = "";
				 $ls_codigonivel   = "";
				 $ls_descripcion   = "";
				 $ls_estatus       = "NUEVO";
				 $ls_status='N';
				 $lb_empresa=true;
				 $ls_codigoasi=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_empresa,"sigesp_asig_nivel","codasiniv",$ls_status);
		       }
	        else
		       { 
		         $io_sql->rollback();
		         $io_msg->message("Ocurrio un error al eliminar el registro !!!");
		       }	 
		 }
	  else
	     {
		    $io_msg->message("Este Registro No Existe !!!");
		 }
	}
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="611" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="616" height="221"><table width="564"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="22" colspan="2" class="titulo-ventana">Configuración de Nivel de Aprobación</td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>"></td>
        <tr>
          <td width="177" height="22" align="right">C&oacute;digo Asignaci&oacute;n</td>
          <td width="385" height="22"><input name="txtcodigoasi" type="text" id="txtcodigoasi" size="10" maxlength="6" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz');" value="<?php print $ls_codigoasi ?>"  style="text-align:center ">
            <input name="operacion" type="hidden" id="operacion"></td>
        <td height="22" style="text-align:right">&nbsp;</td>
      	<tr>
	  	<td><div align="left">
	  	  <label></label>
      	  <div align="right"><span style="text-align:right">Operaci&oacute;n</span></div>
	  	</div></td>
      	<td><select name="cmboperacion"  style="width:200px" id="cmboperacion">
                <option value="-">---seleccione---</option>
                <option value="1" <?php if($ls_aperapr=="1"){ print 'selected';} ?>>Aprobaci&oacute;n Solicitud Ejecución</option>
                <option value="2" <?php if($ls_aperapr=="2"){ print 'selected';} ?>>Aprobaci&oacute;n Orden de Compras</option>
                <option value="3"  <?php if($ls_aperapr=="3"){ print 'selected';} ?>>Aprobaci&oacute;n Orden de Pago</option>
			  </select></td>
		</tr>
        <tr>
          <td height="22" align="right">Descripci&oacute;n</td>
          <td height="22" colspan="2"><input name="txtdescripcion" type="text" id="txtdescripcion" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-');" value="<?php print $ls_descripcion ?>" size="60" maxlength="100"></td>
        </tr>
        <tr>
          <td height="22" align="right">Nivel</td>
          <td height="22" colspan="2"><input name="txtcodigonivel" type="text" id="txtcodigonivel" size="10" maxlength="10"  value="<?php print $ls_codigonivel ?>"  style="text-align:center" readonly  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz');">
            <a href="javascript:catalogo_nivel();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> </td>
        </tr>
      </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>

<script language="javascript">

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.txtcodigoasi.readOnly=false;
	   f.txtcodigoasi.value="";
	   f.cmboperacion.value="-";
	   f.txtdescripcion.value="";
	   f.txtcodigonivel.value="";
	   f.txtdescripcion.focus();
	   f.operacion.value="NUEVO";
	   f.action="sigesp_cfg_d_asignacion_nivel_apro.php";
	   f.submit(); 
	}
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}

function ue_guardar()
{
	 f          = document.form1;
	 li_incluir = f.incluir.value;
	 ls_nivel	= f.txtcodigonivel.value;
	 ls_descripcion = f.txtdescripcion.value;
	 if ((li_incluir==1))
	 { 
		if ((ls_nivel=="")||(ls_descripcion==""))
		  {
			alert("Debe llenar todos los campos!");
			f.txtdescripcion.focus();
		  }
		else
		  {
			f=document.form1;
			f.operacion.value="GUARDAR";
			f.action="sigesp_cfg_d_asignacion_nivel_apro.php";
			f.submit();
		  }
	 }
	 else
	 {
	   alert("No tiene permiso para realizar esta operación");
	 }
}

function valida_null(field,mensaje)
{
  with (field) 
  {
    if (value==null||value=="")
      {
        alert(mensaje);
        return false;
      }
    else
      {
   	    return true;
      }
  }
}	

function ue_eliminar()
{
var borrar="";

f=document.form1;
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (f.txtcodigoasi.value=="")
        {
	      alert("No ha seleccionado ningún registro para eliminar !!!");
        }
	 else
	    {
		  borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_cfg_d_asignacion_nivel_apro.php";
			   f.submit();
		     }
		  else
		     { 
			   alert("Eliminación Cancelada !!!");
			 }
	    }
   }
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}

function ue_buscar()
{
	f=document.form1;
    li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
	     pagina="sigesp_cfg_cat_asig_niveles.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
       }
	else
	   {
	     alert("No tiene permiso para realizar esta operación");
	   }   
}

function catalogo_nivel()
{
	pagina="sigesp_cfg_cat_niveles.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

</script>
</html>