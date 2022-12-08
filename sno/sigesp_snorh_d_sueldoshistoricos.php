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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_sueldoshistoricos.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ld_fecsue,$li_suebas,$li_sueint,$li_sueprodia,$ls_codded,$ls_desded,$ls_codtipper,$ls_destipper,$io_fun_nomina;
		global $ls_operacion,$ls_existe;
		
		$ld_fecsue="dd/mm/aaaa";
		$li_suebas="0,00";
		$li_sueint="0,00";
		$li_sueprodia="0,00";
		$ls_codded="";
		$ls_desded="";
		$ls_codtipper="";
		$ls_destipper="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funci�n que carga todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 15/11/2010 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper, $ls_nomper, $ld_fecsue, $li_suebas, $li_sueint, $li_sueprodia;
		global $ls_codded, $ls_desded, $ls_codtipper, $ls_destipper, $ld_fecnacper, $ld_fecingper, $ld_fecingadmpubper; 
		
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ld_fecnacper=$_POST["txtfecnacper"];
		$ld_fecingper=$_POST["txtfecingper"];
		$ld_fecingadmpubper=$_POST["txtfecingadmpubper"];
		$ld_fecsue=$_POST["txtfecsue"];
		$li_suebas=$_POST["txtsuebas"];
		$li_sueint=$_POST["txtsueint"];
		$li_sueprodia=$_POST["txtsueprodia"];
		$ls_codded=$_POST["txtcodded"];
		$ls_desded=$_POST["txtdesded"];
		$ls_codtipper=$_POST["txtcodtipper"];
		$ls_destipper=$_POST["txtdestipper"];
   }
   //--------------------------------------------------------------
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
<title >Definici&oacute;n de Sueldos Hist&oacute;ricos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_sueldoshistoricos.php");
	$io_sueldoshistoricos=new sigesp_snorh_c_sueldoshistoricos();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codper=$_GET["codper"];
			$ls_nomper=$_GET["nomper"];
			$ld_fecnacper=$_GET["fecnacper"];
			$ld_fecingper=$_GET["fecingper"];
			$ld_fecingadmpubper=$_GET["fecingadmpubper"];
			break;

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_sueldoshistoricos->uf_guardar($ls_codper,$ld_fecsue,$li_suebas,$li_sueint,$li_sueprodia,
													     $ls_codded,$ls_codtipper,$ls_existe,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			break;

		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_sueldoshistoricos->uf_delete_sueldoshistorios($ls_codper,$ld_fecsue,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_codper=$_POST["txtcodper"];
				$ls_nomper=$_POST["txtnomper"];
			}
			break;
	}
	$io_sueldoshistoricos->uf_destructor();
	unset($io_sueldoshistoricos);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_nomina">Sistema de N�mina</td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque�as"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	</table>	 </td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif"  title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_snorh_d_personal.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="623" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="621">	<p>&nbsp;</p>      <table width="527" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td colspan="3"><div align="center">
            <input name="txtnomper" type="text" class="sin-borde2" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" readonly>
            <input name="txtcodper" type="hidden" id="txtcodper" value="<?php print $ls_codper;?>">
        </div></td>
      </tr>
      <tr class="titulo-ventana">
        <td height="20" colspan="3" class="titulo-ventana">Definici&oacute;n de Sueldos Hist&oacute;ricos </td>
      </tr>
      <tr>
        <td width="150" height="22">&nbsp;</td>
        <td width="371" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Fecha de Sueldo </div></td>
        <td colspan="2"><div align="left">
          <select name="cmbmes" id="cmbmes">
            <option value="01" selected>Enero</option>
            <option value="02">Febrero</option>
            <option value="03">Marzo</option>
            <option value="04">Abril</option>
            <option value="05">Mayo</option>
            <option value="06">Junio</option>
            <option value="07">Julio</option>
            <option value="08">Agosto</option>
            <option value="09">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
          </select>
          <select name="cmbano" id="cmbano" onChange="javascript: ue_cargar_fecha();">
          </select>
          <input name="txtfecsue" type="text" id="txtfecsue" value="<?php print $ld_fecsue;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Dedicaci&oacute;n </div></td>
        <td colspan="2"><input name="txtcodded" type="text" id="txtcodded" value="<?php print $ls_codded;?>" size="6" maxlength="3" readonly>
          <a href="javascript: ue_buscardedicacion();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
          <input name="txtdesded" type="text" class="sin-borde" id="txtdesded" value="<?php print $ls_desded;?>" size="50" maxlength="100" readonly></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Tipo de Personal </div></td>
        <td colspan="2"><input name="txtcodtipper" type="text" id="txtcodtipper" size="7" maxlength="4" value="<?php print $ls_codtipper;?>" readonly>
          <a href="javascript: ue_buscartipopersonal();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0">
          <input name="txtdestipper" type="text" class="sin-borde" id="txtdestipper" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_destipper;?>" size="50" maxlength="100" readonly>
          </a></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Sueldo Base </div></td>
        <td colspan="2"><div align="left">
          <input name="txtsuebas" type="text" id="txtsuebas" value="<?php print $li_suebas;?>" size="23" maxlength="20" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
        </div></td>
        </tr>
      <tr>
        <td height="22"><div align="right">Sueldo Integral </div></td>
        <td colspan="2"><div align="left">
          <input name="txtsueint" type="text" id="txtsueint" value="<?php print $li_sueint;?>" size="23" maxlength="20" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Sueldo Promedio diario </div></td>
        <td colspan="2"><div align="left">
          <input name="txtsueprodia" type="text" id="txtsueprodia" value="<?php print $li_sueprodia;?>" size="23" maxlength="20" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event))">
        </div></td>
      </tr>
      <tr>
        <td><div align="right"></div></td>
        <td colspan="2"><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
            <input name="txtfecingper" type="hidden" id="txtfecingper" value="<?php print $ld_fecingper;?>">			
			<input name="txtfecingadmpubper" type="hidden" id="txtfecingadmpubper" value="<?php print $ld_fecingadmpubper;?>">			
            <input name="txtfecnacper" type="hidden" id="txtfecnacper" value="<?php print $ld_fecnacper;?>"></td>
      </tr>
	  
	  
    </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
f=document.form1;
f.cmbano.length=0;
var fecha = new Date();
actual = fecha.getFullYear();
i=0;
for(inicio=1970;inicio<=actual;inicio++)
{
	f.cmbano.options[i]= new Option(inicio,inicio);
	i++;
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";	
		codper=ue_validarvacio(f.txtcodper.value);
		nomper=ue_validarvacio(f.txtnomper.value);	
		fecnacper=ue_validarvacio(f.txtfecnacper.value);	
		fecingper=ue_validarvacio(f.txtfecingper.value);
		fecingadmpubper=ue_validarvacio(f.txtfecingadmpubper.value);
		f.action="sigesp_snorh_d_sueldoshistoricos.php?codper="+codper+"&nomper="+nomper+"&fecnacper="+fecnacper+"&fecingper="+fecingper+"&fecingadmpubper="+fecingadmpubper+"";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	codper=ue_validarvacio(f.txtcodper.value);
	f.action="sigesp_snorh_d_personal.php?codper="+codper;
	f.submit();
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;
		codper = ue_validarvacio(f.txtcodper.value);
		codded = ue_validarvacio(f.txtcodded.value);
		codtipper = ue_validarvacio(f.txtcodtipper.value);
		f.txtfecsue.value=ue_validarfecha(f.txtfecsue.value);	
		fecsue = ue_validarvacio(f.txtfecsue.value);
		suebas = ue_validarvacio(f.txtsuebas.value);
		sueint = ue_validarvacio(f.txtsueint.value);
		sueprodia = ue_validarvacio(f.txtsueprodia.value);
		fecnacper=ue_validarvacio(f.txtfecnacper.value);	
		fecingper=ue_validarvacio(f.txtfecingper.value);
		fecingadmpubper=ue_validarvacio(f.txtfecingadmpubper.value);
		if(!ue_comparar_fechas(fecnacper,fecsue))
		{
			alert("La fecha del Sueldo Hist�rico es menor que la de Nacimiento del personal.");
			valido=false;
		}
		else if(!ue_comparar_fechas(fecingadmpubper,fecsue))
		{
			alert("La fecha del Sueldo Hist�rico es menor que la de Ingreso a la Administraci�n P�blica.");
			valido=false;
		}
		if(valido)
		{
			if ((codper!="")&&(fecsue!="")&&(codded!="")&&(codtipper!=""))
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_snorh_d_sueldoshistoricos.php";
				f.submit();
			}
			else
			{
				alert("Debe llenar todos los datos.");
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
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		codper = ue_validarvacio(f.txtcodper.value);
		window.open("sigesp_snorh_cat_sueldoshistoricos.php?codper="+codper+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			codper = ue_validarvacio(f.txtcodper.value);
			fecsue = ue_validarvacio(f.txtfecsue.value);
			if ((codper!="")&&(fecsue!=""))
			{
				if(confirm("�Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_snorh_d_sueldoshistoricos.php";
					f.submit();
				}
			}
			else
			{
				alert("Debe buscar el registro a eliminar.");
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cargar_fecha()
{
	if(f.existe.value=="FALSE")
	{
		f=document.form1;
		f.txtfecsue.value="01/"+f.cmbmes.value+"/"+f.cmbano.value;
	}
}

function ue_buscardedicacion()
{
	f=document.form1;
	window.open("sigesp_snorh_cat_dedicacion.php?tipo=asignacion","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscartipopersonal()
{
	f=document.form1;
	codded = ue_validarvacio(f.txtcodded.value);
	if (codded!="")
	{
		window.open("sigesp_snorh_cat_tipopersonal.php?tipo=asignacion&codded="+codded+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe Seleccionar la dedicaci�n");
	}
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
//	window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_personal.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script>
</html>