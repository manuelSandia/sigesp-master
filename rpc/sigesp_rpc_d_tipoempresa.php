<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Tipo de Organización</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 15px}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Proveedores y Beneficiarios</td>
			<td width="349" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeńas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>

      </table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php 
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("class_folder/sigesp_rpc_c_tipoorg.php");
require_once("../shared/class_folder/class_funciones_db.php"); 
require_once("../shared/class_folder/class_mensajes.php"); 

$io_conect= new sigesp_include();//Instanciando la Sigesp_Include.
$conn=$io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql= new class_sql($conn);//Instanciando la Clase Class Sql.
$io_msg= new class_mensajes();
$io_tipoorg= new sigesp_rpc_c_tipoorg($conn);//Instanciando la Clase Sigesp Definiciones.
$io_dstipoorg = new class_datastore();//Instanciando la Clase Class  DataStore.
$io_funcion = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_funciondb = new class_funciones_db($conn);
$lb_existe="";


//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="RPC";
	$ls_ventanas="sigesp_rpc_d_tipoempresa.php";

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
			$ls_permisos             =$_POST["permisos"];
			$la_accesos["leer"]     =$_POST["leer"];			
			$la_accesos["incluir"]  =$_POST["incluir"];			
			$la_accesos["cambiar"]  =$_POST["cambiar"];
			$la_accesos["eliminar"] =$_POST["eliminar"];
			$la_accesos["imprimir"] =$_POST["imprimir"];
			$la_accesos["anular"]   =$_POST["anular"];
			$la_accesos["ejecutar"] =$_POST["ejecutar"];
		}
	}
	else
	{
	    $la_accesos["leer"]="";		
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////


if(array_key_exists("operacion",$_POST))
{
  $ls_operacion=$_POST["operacion"];
 }
else
{
  $ls_operacion="NUEVO";
}
if(array_key_exists("txtcodigo",$_POST))
{
$ls_codigo=$_POST["txtcodigo"];
}
else
{
$ls_codigo="";
}
if(array_key_exists("txtdenominacion",$_POST))
{
$ls_dentipoorg=$_POST["txtdenominacion"];
}
else
{
$ls_dentipoorg="";
}
if  (array_key_exists("hidestatus",$_POST))
	{
  	  $ls_estatus=$_POST["hidestatus"];
	}
else
	{
	  $ls_estatus="NUEVO";	  
	}	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operacion  Nuevo    ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="NUEVO")
   {
     $arremp=$_SESSION["la_empresa"];
	 $ls_codemp=$arremp["codemp"];
	 $lb_empresa=false;
	 $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'rpc_tipo_organizacion','codtipoorg');
	 if(empty($ls_codigo))
	 {
	 	$io_msg->message($io_funciondb->is_msg_error);
	 }
   }  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Fin  Operacion  Nuevo     ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Operaciones de Insercion y Actualizacion          ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_guardar")
   { 
     $arremp=$_SESSION["la_empresa"];
     $ls_codemp=$arremp["codemp"];
 	 $lb_existe=$io_tipoorg->uf_select_tipo_empresa($ls_codigo);
	 if ($lb_existe)
        { 
	      if ($ls_estatus=="NUEVO")
		     {
			   $io_msg->message("Este Código de Tipo de Empresa ya existe !!!");  
			   $lb_valido=false;
			 }
		  elseif($ls_estatus=="GRABADO")
		     {
		       $lb_valido=$io_tipoorg->uf_update_tipo_empresa($ls_codigo,$ls_dentipoorg,$la_seguridad);
	           if ($lb_valido)
		          {
				    $io_sql->commit();
				    $io_msg->message("Registro Actualizado !!!");
				    $lb_empresa=false;
				    $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'rpc_tipo_organizacion','codtipoorg');
				    $ls_estatus="NUEVO";
					$ls_dentipoorg="";
			      }
		       else
		          {
		            $io_sql->rollback();
			        $io_msg->message("Error en Actualización !!!");
			      }
	         }
	   } 
	 else
	   {
          $lb_valido=$io_tipoorg->uf_insert_tipo_empresa($ls_codigo,$ls_dentipoorg,$la_seguridad);
	      if($lb_valido)
		    {
		      $io_sql->commit();
			  $io_msg->message("Registro Incluido !!!");
		      $lb_empresa=false;
	          $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'rpc_tipo_organizacion','codtipoorg');
		      $ls_estatus="NUEVO";
			  $ls_dentipoorg="";
		    }
		 else
		    {
   		      $io_sql->rollback();
			  $io_msg->message("Error en Inclusión !!!");
		    }
	   } 
} 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////      Fin de las Operaciones de Insercion y Actualización  ///////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////    Operacion de Eliminar ////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_eliminar")
   {
      $arremp=$_SESSION["la_empresa"];
	  $ls_codemp=$arremp["codemp"];
	  $lb_existe=$io_tipoorg->uf_select_tipo_empresa($ls_codigo);
	  if ($lb_existe)
	     {
	       $lb_valido=$io_tipoorg->uf_delete_tipo_empresa($ls_codemp,$ls_codigo,$la_seguridad);
	       if ($lb_valido)
	          {
			    $io_sql->commit();
			    $io_msg->message("Registro Eliminado !!!");
			    $lb_empresa=false;
			    $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'rpc_tipo_organizacion','codtipoorg');
				$ls_estatus="NUEVO";
			    $ls_dentipoorg="";
			  }
		   else
			  {
			    $io_sql->rollback();
			    $io_msg->message("Error en Eliminación !!!");
			  }	 
          }
	   else
		  {
		    $io_msg->message("Este Registro No Existe !!!");
		  }  
  }     
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////     Operacion de Eliminar     /////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
  <table width="519" height="165" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="542" height="170"><div align="center">
          <table  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr class="titulo-celdanew">
              <td height="22" colspan="2">Tipo de Empresa </td>
            </tr>
            <tr>
              <td height="22" >&nbsp;</td>
              <td height="22" ><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>"></td>
            </tr>
            <tr>
              <td width="122" height="22" align="right">C&oacute;digo</td>
              <td width="346" height="22" ><input name="txtcodigo" type="text" id="txtcodigo" value="<?php print  $ls_codigo ?>" size="2" maxlength="2" onKeyPress="return keyRestrict(event,'1234567890');"  style="text-align:center "  onBlur="javascript:rellenar_cadena(this.value,2);">
              <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion ?>">              </td>
            </tr>
            <tr>
              <td height="22" align="right">Denominaci&oacute;n</td>
              <td height="22"><input name="txtdenominacion" id="txtdenominacion3" value="<?php print $ls_dentipoorg ?>" type="text" size="60" maxlength="40"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnńopqrstuvwxyz '+'.,-'); "></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
          </table>
            </div></td>
      </tr>
  </table>
    <p align="center">&nbsp;</p>
    <p>&nbsp;</p>
</form>
</body>

<script language="JavaScript">
function ue_nuevo()
{
    f=document.form1;
    li_incluir=f.incluir.value;	
	if(li_incluir==1)
	{		  
	   f.operacion.value="NUEVO";
	   f.hidestatus.value="NUEVO";
	   f.txtdenominacion.value="";
	   f.txtdenominacion.focus(true);
	   f.action="sigesp_rpc_d_tipoempresa.php";
	   f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_guardar()
{
var resul="";
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
evento    =f.hidestatus.value;
		
if(((evento=="NUEVO")&&(li_incluir==1))||(evento=="GRABADO")&&(li_cambiar==1))
{   
       resul=rellenar_cadena(document.form1.txtcodigo.value,2);	   
	   with (document.form1)
	   {
	     if (campo_requerido(txtcodigo,"El codigo de clasificacion debe estar lleno !!")==false)
			{
			  txtcodigo.focus();
			}
		 else
			{ 
			  resul=rellenar_cadena(document.form1.txtcodigo.value,2);	   
			  if (campo_requerido(txtdenominacion,"La denominacion de la clasificacion debe estar llena !!")==false)
				 {
				   txtdenominacion.focus();
				 }
			   else
				 {
				   f=document.form1;
				   f.operacion.value="ue_guardar";
				   f.action="sigesp_rpc_d_tipoempresa.php";
				   f.submit();
				 }
			 }
		}	
	}	
	else
	{
		 alert("No tiene permiso para realizar esta operacion");
	}	
}					
					
		function ue_eliminar()
		{
		var borrar="";
		
		f=document.form1;		
		li_eliminar=f.eliminar.value;
		if(li_eliminar==1)
		  {			
			   if (f.txtcodigo.value=="")
			   {
				 alert("No ha seleccionado ningún registro para eliminar !!!");
			   }
				else
				{
					borrar=confirm("ż Esta seguro de eliminar este registro ?");
					if (borrar==true)
					   { 
						 f=document.form1;
						 f.operacion.value="ue_eliminar";
						 f.action="sigesp_rpc_d_tipoempresa.php";
						 f.submit();
					   }
					else
					   { 
						 f=document.form1;
						 f.action="sigesp_rpc_d_tipoempresa.php";
						 alert("Eliminacion Cancelada !!!");
					   }
				}   
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
		}

		function aceptar(c,d)
		{
			f=document.form1;
			f.txtcodigo.value=c;
			f.txtdenominacion.value=d;
			f.txtdenominacion.focus(true);
		}
		
function campo_requerido(field,mensaje)
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
		
function EvaluateText(cadena, obj)
{ 
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

function rellenar_cadena(cadena,longitud)
{
var mystring=new String(cadena);
cadena_ceros="";
lencad=mystring.length;

	total=longitud-lencad;
	for (i=1;i<=total;i++)
		{
		  cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		document.form1.txtcodigo.value=cadena;
}
		
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";			
		pagina="sigesp_rpc_cat_tipoempresa.php";
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
</script>
</html>