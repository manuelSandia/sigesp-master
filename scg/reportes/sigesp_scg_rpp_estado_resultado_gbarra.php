<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";
}

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_insert_seguridad($as_titulo)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_insert_seguridad
	//		   Access: private
	//	    Arguments: as_titulo // Título del Reporte
	//    Description: función que guarda la seguridad de quien generó el reporte
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creación: 22/09/2006
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $io_fun_scg;

	$ls_descripcion="Generó el Reporte ".$as_titulo;
	$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_estado_resultado.php",$ls_descripcion);
	return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_init_niveles()
{	///////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function: uf_init_niveles
//	     Access: public
//	    Returns: vacio
//	Description: Este método realiza una consulta a los formatos de las cuentas
//               para conocer los niveles de la escalera de las cuentas contables
//////////////////////////////////////////////////////////////////////////////////////////////////////
global $io_funciones,$ia_niveles_scg;

$ls_formato=""; $li_posicion=0; $li_indice=0;
$dat_emp=$_SESSION["la_empresa"];
//contable
$ls_formato = trim($dat_emp["formcont"])."-";
$li_posicion = 1 ;
$li_indice   = 1 ;
$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
do
{
	$ia_niveles_scg[$li_indice] = $li_posicion;
	$li_indice   = $li_indice+1;
	$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
} while ($li_posicion>=0);
}// end function uf_init_niveles
//-----------------------------------------------------------------------------------------------------------------------------------
require_once("../../shared/graficos/pChart/pData.class");
require_once("../../shared/graficos/pChart/pChart.class");
require_once("../../shared/class_folder/class_funciones.php");
$io_funciones=new class_funciones();
require_once("../../shared/class_folder/class_fecha.php");
$io_fecha=new class_fecha();
require_once("../class_funciones_scg.php");
$io_fun_scg=new class_funciones_scg();
$ls_tiporeporte="0";
$ls_bolivares="";
if (array_key_exists("tiporeporte",$_GET))
{
	$ls_tiporeporte=$_GET["tiporeporte"];
}
switch($ls_tiporeporte)
{
	case "0":
		require_once("sigesp_scg_reporte.php");
		$io_report  = new sigesp_scg_reporte();
		$ls_bolivares ="Bs.";
		break;

	case "1":
		require_once("sigesp_scg_reportebsf.php");
		$io_report  = new sigesp_scg_reportebsf();
		$ls_bolivares ="Bs.F.";
		break;
}
$ia_niveles_scg[0]="";
uf_init_niveles();
$li_total=count($ia_niveles_scg)-1;
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
$ls_hidbot=$_GET["hidbot"];
if($ls_hidbot==1)
{
	$ls_titulo="<b> ESTADO DE RESULTADOS</b>";
	$ls_cmbmesdes=$_GET["cmbmesdes"];
	$ls_cmbagnodes=$_GET["cmbagnodes"];
	if($_SESSION["ls_gestor"]=='INFORMIX')
	{
		$fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01";
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01";
	}
	else
	{
		$fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
	}
	$ls_cmbmeshas=$_GET["cmbmeshas"];
	$ls_cmbagnohas=$_GET["cmbagnohas"];
	$ls_last_day=$io_fecha->uf_last_day($ls_cmbmeshas,$ls_cmbagnohas);
	$fechas=$ls_last_day;
	$ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day);
}
elseif($ls_hidbot==2)
{
	$ls_titulo="<b> ESTADO DE RESULTADOS</b>";
	$fecdes=$_GET["txtfecdes"];
	$ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
	$fechas=$_GET["txtfechas"];
	$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
}
elseif ($ls_hidbot==3){
	$ls_cmbmesdes=$_GET["cmbmesdes"];
	$ls_cmbagnodes=$_GET["cmbagnodes"];

	switch ($ls_cmbmesdes) {
		case '01':
			$ls_titulo="<b> ESTADO DE RESULTADOS TRIMESTRAL(ENERO-MARZO)</b>";;
			break;

		case '04':
			$ls_titulo="<b> ESTADO DE RESULTADOS TRIMESTRAL(ABRIL-JUNIO)</b>";;
			break;

		case '07':
			$ls_titulo="<b> ESTADO DE RESULTADOS TRIMESTRAL(JULIO-SEPTIEMBRE)</b>";;
			break;

		case '10':
			$ls_titulo="<b> ESTADO DE RESULTADOS TRIMESTRAL(OCTUBRE-DICIEMBRE)</b>";;
			break;
	}

	if($_SESSION["ls_gestor"]=='INFORMIX')
	{
		$fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
	}
	else
	{
		$fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
	}
	$ls_cmbmeshas=$_GET["cmbmeshas"];
	$ls_cmbagnohas=$_GET["cmbagnohas"];
	$ls_last_day=$io_fecha->uf_last_day($ls_cmbmeshas,$ls_cmbagnohas);
	$fechas=$ls_last_day;
	$ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day);
}
$li_nivel=$_GET["cmbnivel"];
//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);
$ls_nombre=$_SESSION["la_empresa"]["nombre"];
$ld_fecdes=$io_funciones->uf_convertirfecmostrar($fecdes);
$ld_fechas=$io_funciones->uf_convertirfecmostrar($fechas);
$ls_titulo=" ESTADO DE RESULTADOS";
$ls_titulo1="<b> ".$ls_nombre." </b>";
$ls_titulo2=" al ".$ld_fechas;
$ls_titulo3="<b>(Expresado en ".$ls_bolivares.")</b>";
// $ls_titulo2=" del  ".$ld_fecdes."  al  ".$ld_fechas." </b>";
//--------------------------------------------------------------------------------------------------------------------------------
// Cargar datastore con los datos del reporte
error_reporting(E_ALL);
$lb_valido=uf_insert_seguridad("<b>Estado de Resultado en TORTA</b>"); // Seguridad de Reporte
if($lb_valido)
{
	$lb_valido_ing=$io_report->uf_scg_reporte_estado_de_resultado_ingreso($ldt_fecdes,$ldt_fechas,$li_nivel);
	$lb_valido_egr=$io_report->uf_scg_reporte_estado_de_resultado_egreso($ldt_fecdes,$ldt_fechas,$li_nivel);
}
if((($lb_valido_ing==false)&&($lb_valido_egr==false))||($lb_valido==false)) // Existe algún error ó no hay registros
{
	print("<script language=JavaScript>");
	print(" alert('No hay nada que Reportar');");
	print(" close();");
	print("</script>");
}
else// Imprimimos el reporte
{
	if($lb_valido_ing)
	{
		$li_tot=$io_report->dts_reporte->getRowCount("sc_cuenta");
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
			$ls_sc_cuenta=trim($io_report->dts_reporte->data["sc_cuenta"][$li_i]);
			$li_totfil=0;
			$as_cuenta="";
			for($li=$li_total;$li>1;$li--)
			{
				$li_ant=$ia_niveles_scg[$li-1];
				$li_act=$ia_niveles_scg[$li];
				$li_fila=$li_act-$li_ant;
				$li_len=strlen($ls_sc_cuenta);
				$li_totfil=$li_totfil+$li_fila;
				$li_inicio=$li_len-$li_totfil;
				if($li==$li_total)
				{
					$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
				}
				else
				{
					$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
				}
			}
			$li_fila=$ia_niveles_scg[1]+1;
			$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
			$ls_status=$io_report->dts_reporte->data["status"][$li_i];
			$ls_denominacion=$io_report->dts_reporte->data["denominacion"][$li_i];
			$ld_saldo=$io_report->dts_reporte->data["saldo"][$li_i];
			$ld_total_ingresos=$io_report->dts_reporte->data["total_ingresos"][$li_i];
			$ls_nivel=$io_report->dts_reporte->data["nivel"][$li_i];
			if($ls_nivel>3)
			{
				$ld_saldo=abs($ld_saldo);
				$ld_saldomay=number_format($ld_saldo,2,",",".");
				$ld_saldomen="";
				$ld_saldo="";
			}
			if($ls_nivel==3)
			{
				$ld_saldo=abs($ld_saldo);
				$ld_saldomay="";
				$ld_saldomen=number_format($ld_saldo,2,",",".");
				$ld_saldo="";
			}
			if(($ls_nivel==1)||($ls_nivel==2))
			{
				$ld_saldo=abs($ld_saldo);
				$ld_saldomay="";
				$ld_saldomen="";
				$ld_saldo=number_format($ld_saldo,2,",",".");
			}
			$la_data[$li_i]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldomay'=>$ld_saldomay,'saldomen'=>$ld_saldomen,'saldo'=>$ld_saldo);
		}//for
		$ld_total_ingresos=abs($ld_total_ingresos);
	}//if($lb_valido_ing)
	if($lb_valido_egr)
	{
		$li_tot=$io_report->dts_egresos->getRowCount("sc_cuenta");
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
			$ls_sc_cuenta=trim($io_report->dts_egresos->data["sc_cuenta"][$li_i]);
			$li_totfil=0;
			$as_cuenta="";
			for($li=$li_total;$li>1;$li--)
			{
				$li_ant=$ia_niveles_scg[$li-1];
				$li_act=$ia_niveles_scg[$li];
				$li_fila=$li_act-$li_ant;
				$li_len=strlen($ls_sc_cuenta);
				$li_totfil=$li_totfil+$li_fila;
				$li_inicio=$li_len-$li_totfil;
				if($li==$li_total)
				{
					$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
				}
				else
				{
					$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
				}
			}
			$li_fila=$ia_niveles_scg[1]+1;
			$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;
			$ls_status=$io_report->dts_egresos->data["status"][$li_i];
			$ls_denominacion=$io_report->dts_egresos->data["denominacion"][$li_i];
			$ld_saldo=$io_report->dts_egresos->data["saldo"][$li_i];
			$ld_total_egresos=$io_report->dts_egresos->data["total_egresos"][$li_i];
			$ls_nivel=$io_report->dts_egresos->data["nivel"][$li_i];
			if($ls_nivel>3)
			{
				//$ld_saldo=abs($ld_saldo);
				$ld_saldo=$ld_saldo*(-1);
					
				$ld_saldomay=number_format($ld_saldo,2,",",".");
				if ($ld_saldomay < 0)
				{
					$ld_saldomay='('.$ld_saldomay.')';
					$ld_saldomay=str_replace('-',"",$ld_saldomay);
				}
				$ld_saldomen="";
				$ld_saldo="";
			}
			if($ls_nivel==3)
			{
				//$ld_saldo=abs($ld_saldo);
				$ld_saldo=$ld_saldo*(-1);
				$ld_saldomay="";
				$ld_saldomen=number_format($ld_saldo,2,",",".");
				$ld_saldo="";
			}
			if(($ls_nivel==1)||($ls_nivel==2))
			{
				//$ld_saldo=abs($ld_saldo);
				$ld_saldo=$ld_saldo*(-1);
				$ld_saldomay="";
				$ld_saldomen="";
				$ld_saldo=number_format($ld_saldo,2,",",".");
			}
			$la_data_egr[$li_i]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldomay'=>$ld_saldomay,'saldomen'=>$ld_saldomen,'saldo'=>$ld_saldo);
		}//for
		if($lb_valido_ing)
		{
		}
		else
		{
			$ld_total_ingresos=0;
		}
		$ld_total_egresos=abs($ld_total_egresos);
		$ld_total=trim($ld_total_ingresos)-($ld_total_egresos);

		 $DataSet = new pData;
		 $DataSet->AddPoint(array($ld_total_ingresos),"Serie0");
		 $DataSet->AddPoint(array($ld_total_egresos),"Serie1");
		 $DataSet->AddPoint(array(""),"titulos");
		 $DataSet->AddSerie("Serie0");
		 $DataSet->AddSerie("Serie1");
		 $DataSet->SetSerieName("Ingresos","Serie0");
		 $DataSet->SetSerieName("Egresos","Serie1");
		 $DataSet->SetAbsciseLabelSerie("titulos");
		
		 // Initialise the graph
		 $Test = new pChart(700,230);
		 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",8);
		 $Test->setGraphArea(90,30,580,200);
		 $Test->drawFilledRoundedRectangle(7,7,593,223,5,240,240,240);
		 $Test->drawRoundedRectangle(5,5,595,225,5,230,230,230);
		 $Test->drawGraphArea(255,255,255,TRUE);
		 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);
		 $Test->drawGrid(4,TRUE,230,230,230,50);
		
		 // Draw the 0 line
		 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",6);
		 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);
		
		 // Draw the bar graph
		 $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE,80);
		
		
		 // Finish the graph
		 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",8);
		 $Test->drawLegend(596,50,$DataSet->GetDataDescription(),255,255,255);
		 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",10);
		 $Test->drawTitle(50,22,$ls_titulo." ".$ls_titulo2,50,50,50,585);
		
		 $Test->Render("estadoresultadobarra.png");
	}//if
	unset($la_data);
	unset($la_data_egr);
}//else
unset($io_report);
unset($io_funciones);
?>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Estado de Resultado</title>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="498" border="0" align="center">
  <tr>
    <td width="320" class="sin-borde2"><div align="center" class="titulo-celdanew"> Estado de Resultado </div></td>
  </tr>
  <tr>
    <td width="320"><img src="estadoresultadobarra.png" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">
<a href="javascript:ue_print();"><img src="../../shared/imagebank/tools20/print.gif" width="35" height="30" border="0"/></a></div>
	</td>
  </tr>
  <tr>
    <td width="320"></td>
  </tr>
</table>


</body>
<script language="JavaScript">
function ue_print()
{
	window.print();
}
</script>
</html>