<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_seguridad.php");
$io_fun_seguridad=new class_funciones_seguridad();
$io_fun_seguridad->uf_load_seguridad("SSS","sigesp_sss_p_usuariosnivelapr.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	      Function:  uf_limpiarvariables
		//	        Access: private
		//	   Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por : Ing. Luis Anibal Lang
		// Fecha Creaci�n : 22/02/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $la_grupos,$la_disponibles,$la_asignados, $ls_codusu, $ls_aperapr;
		
		$la_grupos="";
		$la_disponibles="";
		$la_asignados="";	
		$ls_codusu="";
		$ls_aperapr="";
   }  // end function uf_limpiarvariables

   function uf_seleccionarcombobd($aa_valores,$as_seleccionado,$ai_total)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_seleccionarcombo
		//	       Access: private
		//	    Arguments: $aa_valores      // arreglo de valores que puede tomar el combo
		//  			   $as_seleccionado // item seleccionado
		//  			   $li_total        // total de elementos en el combo
		//	   Description:  Funcion que mantiene la seleccion de un combo despues de hacer un submit
		//	   Creado Por : Ing. Luis Anibal Lang
		// Fecha Creaci�n : 22/02/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////		
		print "<select name='cmbusuarios' onChange='ue_seleccionar();' style='width:220px'>";
		print"<option value='---'>-- Seleccione Uno --</option>";
		for($li_index=0;$li_index<$ai_total;++$li_index)
		{
			if($aa_valores[$li_index]["codusu"]==$as_seleccionado)
			{
				print "<option value='".$aa_valores[$li_index]["codusu"]."' selected>".$aa_valores[$li_index]["nomusu"].
					  " ".$aa_valores[$li_index]["apeusu"]." - <b>".$aa_valores[$li_index]["codusu"]."</b>"."</option>";
			}
			else
			{
				print "<option value='".$aa_valores[$li_index]["codusu"]."'>".$aa_valores[$li_index]["nomusu"].
					  " ".$aa_valores[$li_index]["apeusu"]." - <b>".$aa_valores[$li_index]["codusu"]."</b>"."</option>";
			}
		}
   }  //  end  function uf_seleccionarcombobd
   
   function uf_print_lista($as_nombre,$as_campoclave,$as_campoimprimir,$aa_lista)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_print_lista
		//		   Access : private
		//      Arguments : $as_nombre  // Nombre del Campo
		//      			$as_campoclave  // campo por medio del cual se va filtrar la lista
		//      			$as_campoimprimir  // campo que se va a mostrar
		//      			$aa_lista  // arreglo que se va a colocar en la lista
		//	  Description : Funci�n que imprime el contenido de una caja de texto multiple
		//	   Creado Por : Ing. Luis Anibal Lang
		// Fecha Creaci�n : 26/10/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////

		if(empty($aa_lista[$as_campoclave]))
		{
			$li_total=0;
		}
		else
		{
			$li_total=count($aa_lista[$as_campoclave]);
		}
		print "<select name='".$as_nombre."[]' id='".$as_nombre."' size='10' style='width:300px' multiple>";
		for($li_i=0;$li_i<$li_total;$li_i++)
		{
			print "<option value='".$aa_lista[$as_campoclave][$li_i]."'>".$aa_lista[$as_campoimprimir][$li_i];
		}
		print "</select>";
   }  // end function uf_print_lista

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Asignaci&oacute;n de Niveles de Aprobaci&oacute;n</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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

<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
    <tr>
  <td width="780" height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Seguridad</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
	</tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="11" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"><a href="javascript: ue_guardar();"></a><a href="javascript: ue_cerrar();"></a></div></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("sigesp_sss_c_usuariosnivelapr.php");
	$io_sss= new sigesp_sss_c_usuariosnivelapr();
	require_once("class_funciones_seguridad.php");
	$io_cfs=new class_funciones_seguridad();

	$ls_codemp=$_SESSION["la_empresa"]["codemp"];

	$ls_operacion=$io_cfs->uf_obteneroperacion();
	uf_limpiarvariables();
	$lb_valido=$io_sss->uf_sss_load_usuarios($ls_codemp,$la_usuarios);
	$li_total =count($la_usuarios);
	switch ($ls_operacion) 
	{
		case "BUSCAR":
			$ls_codusu=$io_cfs->uf_obtenervalor("cmbusuarios","");
			$ls_aperapr=$io_cfs->uf_obtenervalor("cmboperacion","");
			if (($ls_codusu != "---")&&($ls_aperapr != "-"))
			{
			 $lb_valido=$io_sss->uf_sss_load_nivelesdisponibles($ls_codemp,$ls_codusu,$ls_aperapr,$la_disponibles);	
			 $lb_valido=$io_sss->uf_sss_load_nivelesasignados($ls_codemp,$ls_codusu,$ls_aperapr,$la_asignados);	
			}
		break;
		
		case "GUARDAR":
			$li_nivasig=0;
			$li_nivdisp=0;
			//$lb_valido=false;
			$ls_codusu=$io_cfs->uf_obtenervalor("cmbusuarios","");
			$ls_aperapr=$io_cfs->uf_obtenervalor("cmboperacion","");
			$la_nivasig=$io_cfs->uf_obtenervalor("txtasignados","");
			$la_nivdisp=$io_cfs->uf_obtenervalor("txtdisponibles","");
			$li_nivasig=count($la_nivasig);
			$li_nivdisp=count($la_nivdisp);
			if(!empty($la_nivasig))
			{
      			$io_sql->begin_transaction();
				for($li_i=0;$li_i<$li_nivasig;$li_i++)
				{
					$ls_codasiniv=$la_nivasig[$li_i];
					$ls_codniv=$io_sss->uf_sss_select_nivel_asig($ls_codemp,$ls_codasiniv);
					$lb_existe=$io_sss->uf_sss_select_nivel_usuario($ls_codemp,$ls_codasiniv,$ls_codniv,$ls_codusu,$ls_aperapr);
					if(!$lb_existe)
					{
						$lb_valido=$io_sss->uf_sss_insert_nivel_usuario($ls_codemp,$ls_codasiniv,$ls_codniv,$ls_codusu,$ls_aperapr,$la_seguridad);
						if($lb_valido)
						{}
						if(!$lb_valido)
						{break;}
					}
				}
				if($lb_valido)
				{		
					if(!empty($la_nivdisp))
					{
						for($li_i=0;$li_i<$li_nivdisp;$li_i++)
						{
							$ls_codasiniv=$la_nivdisp[$li_i];
							$ls_codniv=$io_sss->uf_sss_select_nivel_asig($ls_codemp,$ls_codasiniv);
							$lb_existe=$io_sss->uf_sss_select_nivel_usuario($ls_codemp,$ls_codasiniv,$ls_codniv,$ls_codusu,$ls_aperapr);
							if($lb_existe)
							{
								$lb_valido=true;
								if($lb_valido)
								{
									$lb_valido=$io_sss->uf_sss_delete_nivel_usuario($ls_codemp,$ls_codasiniv,$ls_codniv,$ls_codusu,$ls_aperapr,$la_seguridad);
								}
								if(!$lb_valido)
								{break;}
							}
						}
					}
				}
			}
			else
			{
				if(!empty($la_nivdisp))
				{
					for($li_i=0;$li_i<$li_nivdisp;$li_i++)
					{
						$ls_codasiniv=$la_nivdisp[$li_i];
						$ls_codniv=$io_sss->uf_sss_select_nivel_asig($ls_codemp,$ls_codasiniv);
						$lb_existe=$io_sss->uf_sss_select_nivel_usuario($ls_codemp,$ls_codasiniv,$ls_codniv,$ls_codusu,$ls_aperapr);
						if($lb_existe)
						{
							$lb_valido=true;
							if($lb_valido)
							{
								$lb_valido=$io_sss->uf_sss_delete_nivel_usuario($ls_codemp,$ls_codasiniv,$ls_codniv,$ls_codusu,$ls_aperapr,$la_seguridad);
							}
							if(!$lb_valido)
							{break;}
						}
					}
				}
			}
			if($lb_valido)
			{
				$io_msg->message("La asignaci�n de niveles de aprobaci�n ha sido procesada.");
				$io_sql->commit();
				$io_sql->close();
			}
			else
			{
				$io_msg->message("No se pudo procesar los niveles.");
				$io_sql->rollback();		  
				$io_sql->close();
			}
			$lb_valido=$io_sss->uf_sss_load_nivelesdisponibles($ls_codemp,$ls_codusu,$ls_aperapr,$la_disponibles);	
			$lb_valido=$io_sss->uf_sss_load_nivelesasignados($ls_codemp,$ls_codusu,$ls_aperapr,$la_asignados);		
		break;
	}
?>

<p>&nbsp;</p>
<div align="center">
          <form name="form1" method="post" action="">

<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_seguridad->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_seguridad);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
            <p>&nbsp;</p>
            <table width="104" border="0" class="formato-blanco">
              <tr>
                <td><table width="680" height="247" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td height="17" colspan="4" class="titulo-ventana">Asignaci&oacute;n de Niveles de Aprobaci&oacute;n a Usuarios </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="297" height="13">&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="21" colspan="4"><div align="right"></div>
                        <div align="center"> Usuario       
                            <?php uf_seleccionarcombobd($la_usuarios,$ls_codusu,$li_total); ?>
                      </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="16" colspan="4">&nbsp;</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="22" colspan="4"><div align="center">Operaci&oacute;n
                      <select name="cmboperacion"  style="width:200px" id="cmboperacion"  onChange="ue_niveles();">
                        <option value="-">---seleccione---</option>
                        <option value="1" <?php if($ls_aperapr=="1"){ print 'selected';} ?>>Aprobaci&oacute;n Solicitud Ejecuci&oacute;n</option>
                        <option value="2" <?php if($ls_aperapr=="2"){ print 'selected';} ?>>Aprobaci&oacute;n Orden de Compras</option>
                        <option value="3"  <?php if($ls_aperapr=="3"){ print 'selected';} ?>>Aprobaci&oacute;n Orden de Pago</option>
                      </select>
                    </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="22" colspan="4"><div align="center">
                      <label></label>
                    </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td colspan="2" rowspan="6"><div align="center" class="titulo-celdanew"><span class="Estilo1">Disponibles</span>
                            <?php uf_print_lista("txtdisponibles","codasiniv","despridoc",$la_disponibles); ?>
                    </div></td>
                    <td width="88"><div align="center"> </div></td>
                    <td width="285" rowspan="6"><div align="center" class="titulo-celdanew"><span class="Estilo1">Asignados </span> <span> </span>
                            <input name="total" type="hidden" id="total3">
                            <input name="total1" type="hidden" id="total13">
                            <?php uf_print_lista("txtasignados","codasiniv","despridoc",$la_asignados); ?>
                    </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="88" height="13"><div align="center">
                        <input name="btnincluirpersonal" type="button" class="boton" id="btnincluirpersonal3" style="width: 40px" value="&gt;" onClick="javascript: ue_pasar(form1.txtdisponibles,form1.txtasignados);">
                    </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="88" height="21"><div align="center">
                        <input name="btnincluirpersonaltodos" type="button" class="boton" id="btnincluirpersonaltodos3" style="width: 40px" value="&gt;&gt;" onClick="javascript: ue_pasartodos(form1.txtdisponibles,form1.txtasignados);">
                    </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="88" height="13"><div align="center">
                        <input name="btnexcluirpersonal" type="button" class="boton" id="btnexcluirpersonal3" style="width: 40px" value="&lt;"  onClick="javascript: ue_pasar(form1.txtasignados,form1.txtdisponibles);">
                    </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="14"><div align="center">
                        <input name="btnexcluirpersonaltodos" type="button" class="boton" id="btnexcluirpersonaltodos3" style="width: 40px" value="&lt;&lt;" onClick="javascript: ue_pasartodos(form1.txtasignados,form1.txtdisponibles);">
                    </div></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td><div align="center">
                        <input name="operacion" type="hidden" id="operacion">
                    </div></td>
                  </tr>
                </table></td>
              </tr>
            </table>
          </form>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigesp_sss_p_usuariosnivelapr.php";
	f.submit();
}
function ue_guardar()
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	if(li_cambiar==1)
	{
		ls_codusu=f.cmbusuarios.value;
		ls_codsis= f.cmboperacion.value;
		if((ls_codusu!="---")&&(ls_codsis!="-"))
		{
			if(f.txtasignados!=null)
			{
				li_totasi=f.txtasignados.length;	
			}
			for(i=0;i<li_totasi;i++)
			{
				f.txtasignados[i].selected=true;
			}
	
			if(f.txtdisponibles!=null)
			{
				li_totdis=f.txtdisponibles.length;	
			}
			for(i=0;i<li_totdis;i++)
			{
				f.txtdisponibles[i].selected=true;
			}
	
			f.operacion.value="GUARDAR";
			f.action="sigesp_sss_p_usuariosnivelapr.php";
			f.submit();
	
		}
		else
		{alert ("No se ha selecionado ningun usuario y sistema");}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_seleccionar()
{
	f=document.form1;
	ls_codusu= f.cmbusuarios.value;
	ls_codsis= f.cmboperacion.value;
	if(ls_codsis!="-")
	{
		f.operacion.value="BUSCAR";
		f.action="sigesp_sss_p_usuariosnivelapr.php";
		f.submit();
	}
}

function ue_niveles()
{
	f=document.form1;
	ls_codniv= f.cmboperacion.value;
	ls_codusu= f.cmbusuarios.value;
	if(ls_codusu != "---")
	{
		f.operacion.value="BUSCAR";
		f.action="sigesp_sss_p_usuariosnivelapr.php";
		f.submit();
	}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
///////////////////////////////////////////////////////////////////////////////////
function ue_pasar(obj_desde,obj_hasta)
{
	totdes=obj_desde.length;
	tothas=obj_hasta.length;
	for(i=0;i<totdes;i++)
	{
		if(obj_desde.options[i].selected)
		{
			asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
			asignados=obj_hasta.length;
			if (asignados< 1)
			{
				obj_hasta.options[asignados] = asignar;
			}
			else
			{
				obj_hasta.options[tothas] = asignar;
			}
			tothas=asignados + 1;
		}
	
	}
	ue_borrar_listaseleccionado(obj_desde);
}

function ue_pasartodos(obj_desde,obj_hasta)
{
	totdes=obj_desde.length;
	tothas=obj_hasta.length;
	for(i=0;i<totdes;i++)
	{
		asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
		asignados=obj_hasta.length;
		if (asignados< 1)
		{
			obj_hasta.options[asignados] = asignar;
		}
		else
		{
			obj_hasta.options[tothas] = asignar;
		}
		tothas=asignados + 1;
		
	}
	ue_borrar_listacompleta(obj_desde);
}

function ue_borrar_listacompleta(obj) 
{
	var  largo= obj.length;
	for (i=largo-1;i>=0;i--) 
	{	
		obj.options[i] = null;
	}
}

function ue_borrar_listaseleccionado(obj) 
{
	var largo= obj.length;
	var x;
	var count=0;
	arrSelected = new Array();
	for(i=0;i<largo;i++) // se coloca en el arreglo los campos seleccionados
	{	
		if(obj.options[i].selected) 
		{
			arrSelected[count]=obj.options[i].value;
		}
		count++;
	}
	for(i=0;i<largo;i++) // se colocan en null los que est�n en el arreglo
	{
		for(x=0;x<arrSelected.length;x++) 
		{
			if (obj.options[i].value==arrSelected[x]) 
			{
				obj.options[i]=null;
			}
		}
		largo = obj.length;
	}
}

</script> 
</html>