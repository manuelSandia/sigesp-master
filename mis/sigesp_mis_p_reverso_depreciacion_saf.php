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
	require_once("class_folder/class_funciones_mis.php");
	$oi_fun_integrador=new class_funciones_mis();
	$oi_fun_integrador->uf_load_seguridad("MIS","sigesp_mis_p_reverso_depreciacion_saf.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Funci�n que limpia todas las variables necesarias en la p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/10/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_operacion,$lo_title,$li_totrows,$oi_fun_integrador,$li_widthtable,$ls_titletable,$ls_nametable;
		global $ls_mes,$ls_ano,$la_mes;
		
		$ls_mes="";
		$la_mes[0]="";
		$la_mes[1]="";
		$la_mes[2]="";
		$la_mes[3]="";
		$la_mes[4]="";
		$la_mes[5]="";
		$la_mes[6]="";
		$la_mes[7]="";
		$la_mes[8]="";
		$la_mes[9]="";
		$la_mes[10]="";
		$la_mes[11]="";
		$la_mes[12]="";
		$ls_ano=date("Y");
        $lo_title[1]="";
		$lo_title[2]="Comprobante";
		$lo_title[3]="Descripci�n";
		$lo_title[4]="Monto";
		$lo_title[5]="Detalle";
		$li_widthtable=700;
		$ls_titletable="Depreciaciones por Reversar";
		$ls_nametable="grid";
		$ls_operacion =$oi_fun_integrador->uf_obteneroperacion();
		$li_totrows=$oi_fun_integrador->uf_obtenervalor("totalfilas",0);
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
		// Fecha Creaci�n: 18/03/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_mes,$ls_ano;
		
		$ls_mes=$_POST["cmbmes"];
		$ls_ano=$_POST["txtano"];
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_agregarlineablanca(&$aa_object,$ai_totrows)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function: uf_agregarlineablanca
		//	Arguments: aa_object  // arreglo de Objetos
		//			   ai_totrows  // total de Filas
		//	Description:  Funci�n que agrega una linea mas en el grid
		//////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input type=checkbox name=chksel".$ai_totrows." id=chksel".$ai_totrows." value=1 style=width:15px;height:15px onClick='return false;'>";		
		$aa_object[$ai_totrows][2]="<input type=text name=txtcomprobante".$ai_totrows." class=sin-borde readonly style=text-align:center size=15 maxlength=18>";
		$aa_object[$ai_totrows][3]="<input type=text name=txtdescripcion".$ai_totrows." class=sin-borde readonly style=text-align:center size=80 maxlength=254>";
		$aa_object[$ai_totrows][4]="<input type=text name=txtmonto".$ai_totrows." class=sin-borde readonly style=text-align:left size=20 maxlength=30>";
		$aa_object[$ai_totrows][5]="<div align='center'><img src=../shared/imagebank/mas.gif alt=Detalle width=12 height=24 border=0></div>";
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reversar  Depreciaci&oacute;n de Activos Fijos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo2 {font-size: 36px}
-->

</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/report.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion.js"></script>
</head>
<body>
<?php
	require_once("../shared/class_folder/ddlb_generic.php");
	require_once("../shared/class_folder/grid_param.php");
	$io_grid = new grid_param();
	require_once("sigesp_mis_c_contabiliza.php");  
	$in_class_contabiliza = new sigesp_mis_c_contabiliza();
	require_once("class_folder/class_sigesp_saf_integracion.php");  	
	$in_class = new class_sigesp_saf_integracion();  
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,1);
			break;

		case "PROCESAR":
			uf_load_variables();
			for($li_i=1;$li_i<=$li_totrows;$li_i++)
			{
				if(array_key_exists("chksel".$li_i,$_POST))
				{
					$ls_comprobante = $_POST["txtcomprobante".$li_i];
					$ls_mes         = $_POST["txtmes".$li_i];
					$ls_anio	    = $_POST["txtano".$li_i];
					$ld_fechaconta  = $_POST["txtfechaconta".$li_i];
					$lb_valido      = $in_class->uf_load_depreciacion_int($ls_comprobante);
					if ($lb_valido)
					   {
					     $lb_valido=$in_class->uf_reversar_contabilizacion_depreciacion($ls_comprobante,$ls_mes,$ls_anio,$ld_fechaconta,$la_seguridad);
					   }
					if ($lb_valido)
					   {
						 $in_class->io_msg->message("La Depreciaci�n del Comprobante ".$ls_comprobante." fu� Reversada.");
					   }
					else
					   {
						 $in_class->io_msg->message("No se pudo Reversar la Depreciaci�n del Comprobante ".$ls_comprobante);
					   }		
				}
			}
			$li_totrows=1;
			uf_agregarlineablanca($lo_object,$li_totrows);
			break;
		
		case "BUSCAR":
			uf_load_variables();
	    	$in_class_contabiliza->uf_select_activos_contabilizar($ls_mes,$ls_ano,"1",$lo_object,$li_totrows);
			$oi_fun_integrador->uf_seleccionarcombo("00-01-02-03-04-05-06-07-08-09-10-11-12",$ls_mes,$la_mes,13);
			if($li_totrows==0)
			{
				$li_totrows=1;
				uf_agregarlineablanca($lo_object,$li_totrows);
			}
			break;
	}
	$in_class->uf_destroy_objects();
	unset($in_class);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="762" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
      <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">M&oacute;dulo Integrador - <i>Activos fijos</i></td>
            <td width="346" bgcolor="#E7E7E7"><div align="right" class="letras-peque&ntilde;as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
      <tr>
        <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
        <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"></a><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$oi_fun_integrador->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($oi_fun_integrador);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <p>&nbsp;</p>
  <table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td colspan="2">Reversar Depreciaci&oacute;n de Activos Fijos </td>
    </tr>
    <tr>
      <td  height="23"><div align="right">Mes </div></td>
      <td><div align="left">
        <label>
        <select name="cmbmes" id="cmbmes">
          <option value="00" selected>--Seleccione--</option>
          <option value="01" <?php print $la_mes[1]; ?>>Enero</option>
          <option value="02" <?php print $la_mes[2]; ?>>Febrero</option>
          <option value="03" <?php print $la_mes[3]; ?>>Marzo</option>
          <option value="04" <?php print $la_mes[4]; ?>>Abril</option>
          <option value="05" <?php print $la_mes[5]; ?>>Mayo</option>
          <option value="06" <?php print $la_mes[6]; ?>>Junio</option>
          <option value="07" <?php print $la_mes[7]; ?>>Julio</option>
          <option value="08" <?php print $la_mes[8]; ?>>Agosto</option>
          <option value="09" <?php print $la_mes[9]; ?>>Septiembre</option>
          <option value="10" <?php print $la_mes[10]; ?>>Octubre</option>
          <option value="11" <?php print $la_mes[11]; ?>>Noviembre</option>
          <option value="12" <?php print $la_mes[12]; ?>>Diciembre</option>
        </select>
        </label>
      </div></td>
    </tr>
    <tr>
      <td width="120"  height="23"><div align="right">A&ntilde;o</div></td>
      <td width="380" ><div align="left"><a href="javascript: ue_buscardestino();"></a>
        <label>
        <input name="txtano" type="text" id="txtano" size="6" maxlength="4" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $ls_ano;?>">
        </label>
      </div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
	  <?php
		 $io_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
	  ?>
	  </div></td>
    </tr>
    <tr>
      <td><input name="operacion" type="hidden" id="operacion">
          <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>"></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		if(f.txtano.value!="")
		{
			// Para verificar que se selecciono algun comprobante
			lb_valido=false;
			li_total=f.totalfilas.value;
			for(li_i=1;((li_i<=li_total)&&(lb_valido==false));li_i++)
			{
				lb_valido=eval("f.chksel"+li_i+".checked");
			}
			if(lb_valido)
			{
				f.operacion.value ="PROCESAR";
				f.action="sigesp_mis_p_reverso_depreciacion_saf.php";
				f.submit();
			}
			else
			{
				alert("No hay nada que contabilizar.");
			}
		}
		else
		{
			alert("debe Seleccionar un A�o.");
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
		if(f.txtano.value!="")
		{
			f.operacion.value = "BUSCAR";
			f.action="sigesp_mis_p_reverso_depreciacion_saf.php";
			f.submit();
		}
		else
		{
			alert("debe Seleccionar un A�o.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function uf_verdetalle(mes,ano)
{
	Xpos=((screen.width/2)-(500/2)); 
	Ypos=((screen.height/2)-(400/2));
	window.open("sigesp_mis_pdt_saf.php?mes="+mes+"&ano="+ano+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=500,height=400,left="+Xpos+",top="+Ypos+",location=no,resizable=no");
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>