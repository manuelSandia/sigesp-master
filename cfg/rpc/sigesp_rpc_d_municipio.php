<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Municipios</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuraci�n</td>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("class_folder/sigesp_rpc_c_municipio.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");

$io_conect    = new sigesp_include();//Instanciando la Sigesp_Include.
$conn         = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql       = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_municipio = new sigesp_rpc_c_municipio($conn);//Instanciando la Clase Sigesp Definiciones.
$io_dsmun     = new class_datastore();//Instanciando la Clase Class  DataStore.
$io_funcion   = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_funciondb = new class_funciones_db($conn);
$io_msg       = new class_mensajes();
$io_chkrel    = new sigesp_c_check_relaciones($conn);
$lb_existe    = "";


//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_codemp   = $ls_empresa;
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_rpc_d_municipio.php";

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
     $ls_operacion       = $_POST["operacion"];
	 $ls_pais            = $_POST["cmbpais"];
	 $lr_datos["pais"]   = $ls_pais;
	 $ls_estado          = $_POST["cmbestado"];
	 $lr_datos["estado"] = $ls_estado;
	 $ls_codmun          = $_POST["txtcodigo"];
     $ls_denmun          = $_POST["txtdenominacion"];
     $ls_estatus         = $_POST["hidestatus"];
  }
else
  {
    $ls_operacion       = "";
    $ls_pais            = "---";
  	$lr_datos["pais"]   = $ls_pais;
    $ls_estado          = "---";
    $lr_datos["estado"] = $ls_estado;
	$ls_codmun          = "";
    $ls_denmun          = "";
	$ls_estatus         = "NUEVO";	  
  }	
$lb_empresa = false;

////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operaci�n  Nuevo    ///////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="NUEVO") 
   {
	 $ls_codmun  = $io_municipio->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_municipio','codmun',$ls_pais,$ls_estado);
	 if(empty($ls_codmun))
	   {
	 	$io_msg->message($io_funciondb->is_msg_error);
	   }
   }    

if ($ls_operacion=="ue_guardar")
   { 
     $lb_existe=$io_municipio->uf_load_municipio($ls_pais,$ls_estado,$ls_codmun);
	 if ($lb_existe)
        { 
	      if ($ls_estatus=="NUEVO")
		     {
			   $io_msg->message("Este C�digo de Municipio ya existe !!!");  
			   $lb_valido=false;
			 }
		  elseif($ls_estatus=="GRABADO")
		     {
		       $lb_valido=$io_municipio->uf_update_municipio($ls_pais,$ls_estado,$ls_codmun,$ls_denmun,$la_seguridad);
	     	   if ($lb_valido)
		          {
	    	        $io_sql->commit();
			        $io_msg->message("Registro Actualizado !!!");
					$ls_pais  ="";
					$ls_estado="";
					$ls_codmun="";
					$ls_denmun="";
			      }
		       else
		          {
		            $io_sql->rollback();
			        $io_msg->message("Error en Actualizaci�n !!!");
			      }
	         }
	    }
     else
	    {	 
		  $lb_valido=$io_municipio->uf_insert_municipio($ls_pais,$ls_estado,$ls_codmun,$ls_denmun,$la_seguridad);
	      if ($lb_valido)
		     {
		       $io_sql->commit();
			   $io_msg->message("Registro Incluido !!!");
			   $ls_pais   = "";
			   $ls_estado = "";
			   $ls_codmun = "";
			   $ls_denmun = "";
		     }
	      else
			 {
			   $io_sql->rollback();
			   $io_msg->message("Error en Inclusi�n !!!");
		     }
 		}
} 


if ($ls_operacion=="ue_eliminar")
   {
	  $lb_existe = $io_municipio->uf_load_municipio($ls_pais,$ls_estado,$ls_codmun);
      if ($lb_existe)
	     {
	       $ls_condicion = " AND (column_name='codmun')";//Nombre del o los campos que deseamos buscar.
	       $ls_mensaje   = "";                           //Mensaje que ser� enviado al usuario si se encuentran relaciones a asociadas al campo.
	       $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_empresa,$ls_condicion,'sigesp_municipio',$ls_codmun,$ls_mensaje);//Verifica los movimientos asociados a la cuenta
           if (!$lb_tiene)
		      {
		        $lb_valido = $io_municipio->uf_delete_municipio($ls_codemp,$ls_pais,$ls_estado,$ls_codmun,$la_seguridad);
	            if ($lb_valido)
	               {
					 $io_sql->commit();
					 $io_msg->message("Registro Eliminado !!!");
					 $ls_pais   = "";
					 $ls_estado = "";
					 $ls_codmun = "";
					 $ls_denmun = "";
				   }
			    else
				   {
					 $io_sql->rollback();
					 $io_msg->message("Error en Eliminaci�n !!!");
				   }	 
		      }
		   else
			  {
		        $io_msg->message($io_chkrel->is_msg_error);
			  }
		 }
	  else
	     {
		   $io_msg->message("Este Registro No Existe !!!");
		 }  
   }     
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////             Fin Operacion de Eliminar                /////////////////////////////////////////////
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

    <table width="524" height="208" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="522" height="206"><div align="center">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td height="22" colspan="2" class="titulo-ventana">Registro de Municipios</td>
            </tr>
            <tr>
              <td width="134" height="22">&nbsp;</td>
              <td width="334" height="22"><span class="style1">
                <input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
              </span></td>
            </tr>
            <tr>
              <td height="22" align="right"><span class="style2">Pais</span></td>
              <td height="22"><?php
            //Llenar Combo Pais
            $rs_mun=$io_municipio->uf_llenarcombo_pais();
          ?>
                <select name="cmbpais" id="cmbpais" onChange="javascript:uf_cambiopais();"  style="width:150px ">
                  <?php
		  while($row=$io_sql->fetch_row($rs_mun))
		  {
		   $ls_codpai=$row["codpai"];
		   $ls_denpai=$row["despai"];
		   if ($ls_codpai==$ls_pais)
			   {
				 print "<option value='$ls_codpai' selected>$ls_denpai</option>";
			   }
		   else
		       {
				 print "<option value='$ls_codpai'>$ls_denpai</option>";
			   }
		 } 
	     ?>
                </select>
              <input name="hidpais" type="hidden" id="hidpais" value="<?php print $ls_pais ?>"></td>
            </tr>
            <tr>
              <td height="22" Align="right"><span class="style2">Estado</span></td>
              <td height="22"><?php
          //Llenar Combo Estado
		  $rs_mun=$io_municipio->uf_load_estados($ls_pais);
		 ?>
                <select name="cmbestado" id="cmbestado" onChange="javascript:uf_cambioestado();"  style="width:150px ">
                  <option value="---">---seleccione---</option>
                  <?php
		 while($row=$io_sql->fetch_row($rs_mun))
		 {
		   $ls_codest=$row["codest"];
		   $ls_denest=$row["desest"];
		   if ($ls_codest==$ls_estado)
			   {
				 print "<option value='$ls_codest' selected>$ls_denest</option>";
			   }
		   else
			   {
				 print "<option value='$ls_codest'>$ls_denest</option>";
			   }
		 } 
	     ?>
                </select>
              <input name="hidestado" type="hidden" id="hidestado" value="<?php print $ls_estado ?>"></td>
            </tr>
            <tr>
              <td height="22" align="right"><span class="style2">C&oacute;digo</span></td>
              <td height="22"><input name="txtcodigo" type="text" id="txtcodigo" value="<?php print  $ls_codmun ?>" size="3" maxlength="3" onKeyPress="return keyRestrict(event,'1234567890');"  onBlur="javascript:rellenar_cad(this.value,3)" style="text-align:center ">
                  <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
              </td>
            </tr>
            <tr>
              <td height="22" align="right"><span class="style2">Denominaci&oacute;n</span></td>
              <td height="22"><input name="txtdenominacion" id="txtdenominacion" value="<?php print $ls_denmun ?>" type="text" size="60" maxlength="60" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz '+',.-');"></td>
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
</form>
  
<script language="JavaScript">
function ue_nuevo()
{
  f=document.form1;
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
       f.operacion.value="";
	   f.cmbpais[0].selected=true;
	   f.cmbestado[0].selected=true;
	   f.txtcodigo.value="";
	   f.txtdenominacion.value="";
	   f.txtcodigo.focus(true);
	   f.action="sigesp_rpc_d_municipio.php";
	   f.submit();
	 }
  else
	 {
 	   alert("No tiene permiso para realizar esta operaci�n");
	 }   
}


function ue_guardar()
{
var resul="";
			   
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
ls_codpais=f.cmbpais.value;
ls_codest=f.cmbestado.value;
if (ls_codpais!='---' && ls_codest!='---')
   {
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
   {
     with (document.form1)
	      {
	        if (campo_requerido(txtcodigo,"El C�digo del Municipio debe estar lleno !!")==false)
			   {
			     txtcodigo.focus();
			   }
			else
			   { 
			     resul=rellenar_cad(document.form1.txtcodigo.value,3);
				 if (campo_requerido(txtdenominacion,"La Denominaci�n del Municipio debe estar llena !!")==false)
					{
					  txtdenominacion.focus();
					}
				 else
					{
					  f=document.form1;
					  f.operacion.value="ue_guardar";
					  f.action="sigesp_rpc_d_municipio.php";
					  f.submit();
					}
			   }
		  }			
   }
  else
   {
     alert("No tiene permiso para realizar esta operaci�n");
   }
   }
else
  {
    alert('Debe seleccionar una Ubicaci�n Geogr�fica v�lida !!!');
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
	      alert("No ha seleccionado ning�n registro para eliminar !!!");
        }
	 else
	    {
		  borrar=confirm("� Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f=document.form1;
			   f.operacion.value="ue_eliminar";
			   f.action="sigesp_rpc_d_municipio.php";
			   f.submit();
		     }
		  else
		     { 
			   alert("Eliminaci�n Cancelada !!!");
		     }
	    }	   
   }
 else
   {
     alert("No tiene permiso para realizar esta operaci�n");
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
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
 	     if (f.cmbpais.value=="s1")
	        {
		      alert("Debe seleccionar un Pais y un Estado para ver el Cat�logo de Municipios");
	        }
	     else
	        {
			  if (f.cmbestado.value=="s1")
		         {
			       alert("Debe seleccionar un Pais y un Estado para ver el Cat�logo de Municipios");
		         }
	          else 
		         {   
				   f.operacion.value="";
				   ls_pais=f.hidpais.value;			 
				   ls_estado=f.hidestado.value;			
				   pagina="sigesp_cfg_cat_municipios.php?hidestado="+ls_estado+"&hidpais="+ls_pais;
				   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
		         }
		    }
       }
}	   
		
function uf_cambiopais()
{
	f=document.form1;
	if (f.cmbpais.value=="s1")
	   {
		  f.operacion.value="";
		  f.txtcodigo.value="";
		  f.txtdenominacion.value=""; 
	   }
	else
	   {   
		 f.operacion.value="pais";
	   }
	f.action="sigesp_rpc_d_municipio.php";
	f.submit();
}
		
function uf_cambioestado()
{
	f=document.form1;
	if (f.cmbestado.value=="s1")
	   {
		  f.operacion.value="";
		  f.txtcodigo.value="";
		  f.txtdenominacion.value=""; 
	   }
	else
	   {   
		  f.operacion.value="NUEVO";
	   }
  f.action="sigesp_rpc_d_municipio.php";
  f.submit();  
}		
</script>
</html>