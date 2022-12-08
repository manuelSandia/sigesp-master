<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
require_once("../shared/class_folder/sigesp_include.php");
require_once("class_folder/sigesp_cfg_c_nivel_aprobacion.php");   
$io_conect    = new sigesp_include();//Instanciando la Sigesp_Include.
$conn         = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_aprobacion= new sigesp_cfg_c_nivel_aprobacion($conn);//Instanciando la Clase Sigesp Definiciones.
//--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $li_totrows,$ls_titletable,$li_widthtable,$ls_nametable,$lo_title,$io_aprobacion,$li_estact;
		$ls_titletable="Niveles de Aprobación";
		$li_widthtable=550;
		$ls_nametable="grid";
		$lo_title[1]="Nivel";
		$lo_title[2]="Monto Aprobación Desde";
		$lo_title[3]="Monto Aprobación Hasta";
		$lo_title[4]=" ";
		$lo_title[5]=" ";
		$li_totrows=$io_aprobacion->uf_obtenervalor("totalfilas",1);
		$li_estact=0;
   }
 //--------------------------------------------------------------
 //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Función que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtnivapro".$ai_totrows." type=text id=txtnivapro".$ai_totrows." class=sin-borde size=6 maxlength=4 onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_rellenarcampo(this,4);'>";
		$aa_object[$ai_totrows][2]="<input name=txtmonnivdes".$ai_totrows." type=text id=txtmonnivdes".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
		$aa_object[$ai_totrows][3]="<input name=txtmonnivhas".$ai_totrows." type=text id=txtmonnivhas".$ai_totrows." class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
		$aa_object[$ai_totrows][4]="<a href=javascript:uf_agregar_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar Detalle alt=Aceptar width=15 height=15 border=0></a>";
		$aa_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar Detalle alt=Eliminar width=15 height=15 border=0></a>";			
   }
 //--------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Paises</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones_configuracion.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
   if (document.all)
	  { //ie 
	    document.onkeydown = function(){ 
		if (window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
	  	   {
		     window.event.keyCode = 505; 
		   }
		if (window.event.keyCode == 505){ return false;} 
		   } 
	}
</script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
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
    <td height="20" class="cd-menu"></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools15/actualizar.gif" title="Cargar" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_eliminar();"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_c_check_relaciones.php");

$io_sql       = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_msg       = new class_mensajes();//Instanciando la Clase Class  Mensajes.
$io_dspais    = new class_datastore();//Instanciando la Clase Class  DataStore.
$io_funcion   = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_funciondb = new class_funciones_db($conn);
$io_chkrel    = new sigesp_c_check_relaciones($conn);
$io_grid	  = new grid_param();
$lb_existe    = "";
$lb_valido    = "";
uf_limpiarvariables();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_cfg_d_nivel_apro.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
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
		$ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];
     $ls_estatus   = $_POST["hidestatus"];
   }
else
   {
     $ls_operacion = "NUEVO";
	 $ls_estatus   = "NUEVO";
   }
$lb_empresa=false;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operación  Nuevo    ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
switch ($ls_operacion) 
{
	case "NUEVO":
		 $li_totrows=1;
		 uf_agregarlineablanca($lo_object,1);
	break; 
	
	
	case "AGREGARDETALLE":
			$li_totrows=$li_totrows+1;
			$li_estact=$_POST["estact"];
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codniv=$_POST["txtnivapro".$li_i];
				$li_monnivdes=$_POST["txtmonnivdes".$li_i];
				$li_monnivhas=$_POST["txtmonnivhas".$li_i];
				$lo_object[$li_i][1]="<input name=txtnivapro".$li_i." type=text id=txtnivapro".$li_i." class=sin-borde size=6 value='".$ls_codniv."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_rellenarcampo(this,4);' readonly>";
				$lo_object[$li_i][2]="<input name=txtmonnivdes".$li_i." type=text id=txtmonnivdes".$li_i." class=sin-borde size=12 value='".$li_monnivdes."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
				$lo_object[$li_i][3]="<input name=txtmonnivhas".$li_i." type=text id=txtmonnivhas".$li_i." class=sin-borde size=12 value='".$li_monnivhas."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
				$lo_object[$li_i][4]="<a href=javascript:uf_agregar_dt(".$li_i.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar Detalle alt=Aceptar width=15 height=15 border=0></a>";
				$lo_object[$li_i][5]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar Detalle alt=Aceptar width=15 height=15 border=0></a>";
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
	break;
	
	case "GUARDAR":
			$lb_valido=true;
			$li_estact=$_POST["estact"];
			if($li_estact==1)
			{
				$ls_existe=TRUE;
				for($li_i=1;$li_i<$li_totrows&&$lb_valido;$li_i++)
				{
					$ls_codniv=$_POST["txtnivapro".$li_i];
					if ($io_aprobacion->uf_select_nivel_aprobacion_asignado($ls_codniv)===false)
					{
						$lb_pass=true;
					}
					else
					{
						$lb_pass=false;
						$io_msg->message("El codigo ".$ls_codniv."  esta asignado a un proceso de aprobación, por favor chequee");
						break;
					}
				}
				if($lb_pass==true)
				{
					$lb_elimina=$io_aprobacion->uf_elimina_nivel_aprobacion($la_seguridad);	
				}
				else
				{
					$lb_valido=false;
				}	
			}
			else
			{
				$ls_existe=FALSE;
			}
			
			for($li_i=1;$li_i<$li_totrows&&$lb_valido;$li_i++)
			{
				$ls_codniv=$_POST["txtnivapro".$li_i];
				$li_monnivdes=$_POST["txtmonnivdes".$li_i];
				$li_monnivhas=$_POST["txtmonnivhas".$li_i];
				if ($lb_valido)
				{
					$lb_valido=$io_aprobacion->uf_guardar_niveles_aprobacion($ls_codniv,$li_monnivdes,$li_monnivhas,$ls_existe,$la_seguridad);
				}
			}
			
			if($lb_valido)
			{
				$io_sql->commit();
				if($ls_existe=="TRUE")
				{
					$io_msg->message("Los niveles de aprobación fueron actualizados!");
				}
				else
				{
					$io_msg->message("Los niveles de aprobación fueron registrados");
				}
			}
			else
			{
				$io_sql->rollback();
				if($lb_pass==false)
				{
					$io_msg->message("Debe eliminar las asignaciones de nivel antes de modificar los niveles");
				}
				else
				{
					$io_msg->message("Ocurrio un error al guardar los niveles");
				}
			}
			uf_limpiarvariables();
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
	break;
 
	
	case "ELIMINARDETALLE":
			$li_totrows=$li_totrows-1;
			$li_rowdelete=$_POST["filadelete"];
			$li_temp=0;
			$li_estact=$_POST["estact"];
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if($li_i!=$li_rowdelete)
				{		
					$li_temp=$li_temp+1;			
					$ls_codniv=$_POST["txtnivapro".$li_i];
					$li_monnivdes=$_POST["txtmonnivdes".$li_i];
					$li_monnivhas=$_POST["txtmonnivhas".$li_i];
					$lo_object[$li_temp][1]="<input name=txtnivapro".$li_temp." type=text id=txtnivapro".$li_temp." class=sin-borde size=6 value='".$ls_codniv."' onKeyUp='javascript: ue_validarnumero(this);' onBlur='javascript: ue_rellenarcampo(this,4);' readonly>";
					$lo_object[$li_temp][2]="<input name=txtmonnivdes".$li_temp." type=text id=txtmonnivdes".$li_temp." class=sin-borde size=12 value='".$li_monnivdes."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
					$lo_object[$li_temp][3]="<input name=txtmonnivhas".$li_temp." type=text id=txtmonnivhas".$li_temp." class=sin-borde size=12 value='".$li_monnivhas."' onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
					$lo_object[$li_temp][4]="<a href=javascript:uf_agregar_dt(".$li_temp.");><img src=../shared/imagebank/tools15/aprobado.gif title=Agregar Detalle alt=Aceptar width=15 height=15 border=0></a>";
					$lo_object[$li_temp][5]="<a href=javascript:uf_delete_dt(".$li_temp.");><img src=../shared/imagebank/tools15/deshacer.gif title=Eliminar Detalle alt=Aceptar width=15 height=15 border=0></a>";
				}
				else
				{
					$li_rowdelete= 0;
				}					
			}
			uf_agregarlineablanca($lo_object,$li_totrows);
	break;
	
	case "CARGAR_NIVEL":
		$li_estact=1;
		$lb_valido = $io_aprobacion->uf_cargar_niveles_aprobacion($li_totrows,$lo_object);		
		if ($lb_valido==false)
		{
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,$li_totrows);
		}
		uf_agregarlineablanca($lo_object,$li_totrows);
	break;

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
    <table width="676" height="174" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="516" height="174"><div align="center">
          <table width="620"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td height="22" colspan="2" class="titulo-ventana">Definici&oacute;n de Niveles de Aprobaci&oacute;n </td>
            </tr>
            <tr>
              <td height="22" >&nbsp;</td>
              <td height="22" ><span class="style1">
                <input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
              </span></td>
            </tr>
            <tr>
              <td width="34" height="22" align="right">&nbsp;</td>
              <td width="584" height="22" ><input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">              </td></tr>
            <tr>
              <td height="22" align="center">&nbsp;</td>
              <td height="22"><?php
					$io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
					unset($io_grid);
			?></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
<input name="filadelete" type="hidden" id="filadelete">
<input name="estact" type="hidden" id="estact" value="<?php print $li_estact;?>">
</form>
</body>
<script language="JavaScript">
function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value="NUEVO";
	   f.action="sigesp_cfg_d_nivel_apro.php";
	   f.submit();
	 }
   else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
			f.operacion.value="GUARDAR";
			f.action="sigesp_cfg_d_nivel_apro.php";
			f.submit();
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
if (li_eliminar==1)
   {	
      if (f.txtcodigo.value=="")
         {
	       alert("No ha seleccionado ningún registro para eliminar !!!");
         }
	  else
	     {
		   borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		   if (borrar==true)
		      { 
			    f=document.form1;
			    f.operacion.value="ue_eliminar";
			    f.action="sigesp_rpc_d_pais.php";
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
		
function rellenar_cad(cadena,longitud)
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
	f.estact.value=1;
	f.operacion.value="CARGAR_NIVEL";
	f.action="sigesp_cfg_d_nivel_apro.php";
	f.submit();
}

function uf_agregar_dt(li_row)
{
	f=document.form1;	
	li_total=f.totalfilas.value;
	if(li_total==li_row)
	{
		ls_codnivnew=eval("f.txtnivapro"+li_row+".value");
		li_total=f.totalfilas.value;
		lb_valido=false;
		for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
		{
			ls_codniv=eval("f.txtnivapro"+li_i+".value");
			li_monnivdes=eval("f.txtmonnivdes"+li_i+".value");
			li_monnivhas=eval("f.txtmonnivhas"+li_i+".value");
			if((ls_codniv==ls_codnivnew)&&(li_i!=li_row))
			{
				alert("El Codigo de Nivel ya existe");
				lb_valido=true;
			}
			if((li_monnivdes==li_monnivhas))
			{
				alert("El monto del nivel hasta debe ser mayor que el monto de nivel desde");
				lb_valido=true;
			}
			valid=compararMontos(li_monnivdes,li_monnivhas,"El monto del nivel hasta debe ser mayor que el monto de nivel desde")
			if(!valid)
			{
				lb_valido=true;
			}
			if(li_i > 1)
			{
				li_t=li_i-1;
				li_monnivhasprev=eval("f.txtmonnivhas"+li_t+".value");
				if(li_monnivdes != li_monnivhasprev)
				{
					alert("El monto desde debe ser igual al tope anterior hasta");
					lb_valido=true;
				}
			}
		}
		ls_codniv=eval("f.txtnivapro"+li_row+".value");
		ls_codniv=ue_validarvacio(ls_codniv);
		li_monnivdes=eval("f.txtmonnivdes"+li_row+".value");
		li_monnivdes=ue_validarvacio(li_monnivdes);
		li_monnivhas=eval("f.txtmonnivhas"+li_row+".value");
		li_monnivhas=ue_validarvacio(li_monnivhas);
	
		if((ls_codniv=="")||(li_monnivdes=="")||(li_monnivhas==""))
		{
			alert("Debe llenar todos los campos");
			lb_valido=true;
		}
		
		if(!lb_valido)
		{
			f.operacion.value="AGREGARDETALLE";
			f.action="sigesp_cfg_d_nivel_apro.php";
			f.submit();
		}
	}
}

function uf_delete_dt(li_row)
{
	f=document.form1;
	li_total=f.totalfilas.value;
	if(li_total>li_row)
	{
		ls_codniv=eval("f.txtnivapro"+li_row+".value");
		ls_codniv=ue_validarvacio(ls_codniv);
		if(ls_codniv=="")
		{
			alert("La fila a eliminar no debe estar vacio");
		}
		else
		{
			if(confirm("¿Desea eliminar el Registro actual?"))
			{
				f.filadelete.value=li_row;
				f.operacion.value="ELIMINARDETALLE"
				f.action="sigesp_cfg_d_nivel_apro.php";
				f.submit();
			}
		}
	}
}

  
</script>
</html>