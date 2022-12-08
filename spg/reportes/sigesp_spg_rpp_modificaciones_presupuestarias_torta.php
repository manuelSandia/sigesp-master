<?php
    session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";
	}

		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("sigesp_spg_funciones_reportes.php");
		require_once("../../shared/graficos/pChart/pData.class");
		require_once("../../shared/graficos/pChart/pChart.class");
		$io_funciones		= new class_funciones();
		$io_fecha 			= new class_fecha();
		$io_function_report = new sigesp_spg_funciones_reportes();
		$ls_tipoformato=$_GET["tipoformato"];
	//-----------------------------------------------------------------------------------------------------------------------------
		 global $ls_tipoformato;
		 if($ls_tipoformato==1)
		 {
			require_once("sigesp_spg_reporte_bsf.php");
			$io_report = new sigesp_spg_reporte_bsf();
			//print('cargo BsF.');
		 }
		 else
		 {
			require_once("sigesp_spg_reporte.php");
		    $io_report = new sigesp_spg_reporte();
		 }

		 require_once("../../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");

		 $io_rcbsf= new sigesp_c_reconvertir_monedabsf();
		 $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
        $ls_ckbrect		= $_GET["ckbrect"];
        $ls_ckbtras		= $_GET["ckbtras"];
        $ls_ckbinsu		= $_GET["ckbinsu"];
        $ls_ckbcre		= $_GET["ckbcre"];
		$ls_comprobante = $_GET["txtcomprobante"];
		$ls_procede  	= $_GET["txtprocede"];
		$ldt_fecha  	= $_GET["txtfecha"];
		$fecdes			= $_GET["txtfecdes"];
		$ldt_fecdes		= $io_funciones->uf_convertirdatetobd($fecdes);
		$fechas			= $_GET["txtfechas"];
		$ldt_fechas		= $io_funciones->uf_convertirdatetobd($fechas);
		$ldt_fecha		= $io_funciones->uf_convertirdatetobd($ldt_fecha);
		$li_estmodest   = $_SESSION["la_empresa"]["estmodest"];
		$li_totnoapro=0;
		$li_totapro=0;
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Modificaciones Presupuestarias Aprobadas desde la fecha ".$fecdes." hasta ".$fechas.", Comprobante  ".$ls_comprobante." ,Procede  ".$ls_procede." ,Fecha del Comprobante  ".$ldt_fecha;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_modificaciones_presupuestarias_aprobadas.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
        // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
	    $lb_valido=$io_report->uf_spg_reporte_modificaciones_presupuestarias_no_aprobadas($ls_ckbrect,$ls_ckbtras,$ls_ckbinsu,$ls_ckbcre,$ldt_fecdes,
																		                  $ldt_fechas,$ls_comprobante,$ls_procede,$ldt_fecha);
		if ($lb_valido==false) // Existe algún error ó no hay registros
		{
			$li_totnoapro=0;
			print("<script language=JavaScript>");
//			print(" alert('No hay nada que Reportar');");
//			print(" close();");
			print("</script>");
		}
		else // Imprimimos el reporte
		{
			$io_report->dts_reporte->group_noorder("procomp");
			$li_totnoapro= $io_report->dts_reporte->getRowCount("spg_cuenta");
		   // unset($io_report->dts_reporte);
		}
			
		$lb_valido=$io_report->uf_spg_reporte_modificaciones_presupuestarias($ls_ckbrect,$ls_ckbtras,$ls_ckbinsu,$ls_ckbcre,
																			  $ldt_fecdes,$ldt_fechas,$ls_comprobante,$ls_procede,
																			  $ldt_fecha);
		if($lb_valido==false)
		{
			$li_totapro=0;
			print("<script language=JavaScript>");
//			print(" alert('No hay nada que Reportar');");
//			print(" close();");
			print("</script>");
		}
		else
		{
			$io_report->dts_reporte->group_noorder("procomp");
			$li_totapro=$io_report->dts_reporte->getRowCount("spg_cuenta");
		}
				 // Dataset definition 
		$DataSet = new pData;
		$DataSet->AddPoint(array($li_totapro,$li_totnoapro),"Serie1");
		$DataSet->AddPoint(array("Aprobadas","No Aprobadas"),"titulos");
		$DataSet->AddAllSeries();
		$DataSet->SetAbsciseLabelSerie("titulos");
		
		
		// Initialise the graph
		$Test = new pChart(500,400);
		$Test->drawFilledRoundedRectangle(7,7,493,393,5,240,240,240);
		$Test->drawRoundedRectangle(5,5,495,395,5,230,230,230);
		
		// This will draw a shadow under the pie chart
		$Test->drawFilledCircle(225,205,170,200,200,200);
		
		// Draw the pie chart
		$Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",8);
		$Test->AntialiasQuality = 0;
		$Test->drawBasicPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),220,200,170,PIE_PERCENTAGE,255,255,218);
		$Test->drawPieLegend(380,15,$DataSet->GetData(),$DataSet->GetDataDescription(),150,150,150);
		
		$Test->Render("modificaciones.png");
		unset($io_report);
		unset($io_funciones);
		unset($io_function_report);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Modificaciones Presupuestarias</title>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="498" border="0" align="center">
  <tr>
    <td width="320" class="sin-borde2"><div align="center" class="titulo-celdanew"> Modificaciones Presupuestarias </div></td>
  </tr>
  <tr>
    <td width="320"><img src="modificaciones.png" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">
	<a href="javascript:ue_print();"> <img src="../../shared/imagebank/tools20/print.gif" width="35" height="30" border="0"/> </a></div>
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

