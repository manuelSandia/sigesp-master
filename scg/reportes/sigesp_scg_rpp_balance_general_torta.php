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
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_balance_general.php",$ls_descripcion);
		return $lb_valido;
	}
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

	//-----------------------------------------------------------------------------------------------------------------------------------

	 require_once("../../shared/ezpdf/class.ezpdf.php");
	 require_once("../../shared/class_folder/class_funciones.php");
	 $io_funciones=new class_funciones();
	 require_once("../../shared/class_folder/class_fecha.php");
	 require_once("../../shared/class_folder/class_sql.php");
	 require_once("../../shared/class_folder/sigesp_include.php");
	 require_once("../../shared/class_folder/class_sigesp_int.php");
	 require_once("../../shared/class_folder/class_sigesp_int_scg.php");
	$ls_tiporeporte="0";
	$ls_bolivares="";
	if (array_key_exists("tiporeporte",$_GET))
	{
		$ls_tiporeporte=$_GET["tiporeporte"];
	}
	switch($ls_tiporeporte)
	{
		case "0":
			require_once("sigesp_scg_class_bal_general.php");
			$io_report  = new sigesp_scg_class_bal_general();
			$ls_bolivares ="Bs.";
			break;
	
		case "1":
			require_once("sigesp_scg_class_bal_generalbsf.php");
			$io_report  = new sigesp_scg_class_bal_generalbsf();
			$ls_bolivares ="Bs.F.";
			break;
	}	 
	 require_once("../../shared/class_folder/class_fecha.php");
	 $io_fecha=new class_fecha();
	 require_once("../class_funciones_scg.php");
	 $io_fun_scg=new class_funciones_scg();
	 $ia_niveles_scg[0]="";			
	 uf_init_niveles();
	 $li_total=count($ia_niveles_scg)-1;
	require_once("../../shared/graficos/pChart/pData.class");
	require_once("../../shared/graficos/pChart/pChart.class");
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	   $ls_cmbmes=$_GET["cmbmes"];
	   $ls_cmbagno=$_GET["cmbagno"];
	   $ls_last_day=$io_fecha->uf_last_day($ls_cmbmes,$ls_cmbagno);
	   $fechas=$ls_last_day;
	   if($_SESSION["ls_gestor"]=='INFORMIX')
	   {
	     $ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day);
	   }
	   else 
	   {
	     $ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day)." 00:00:00";
	   }
  	   $li_nivel=$_GET["cmbnivel"];
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$li_ano=substr($ldt_periodo,0,4);
		$ls_pasivo=$_SESSION["la_empresa"]["pasivo"];
		$ls_resultado=$_SESSION["la_empresa"]["resultado"];
		$ls_capital=$_SESSION["la_empresa"]["capital"];
		$ls_acreedora=trim($_SESSION["la_empresa"]["orden_h"]);

		$ld_fechas=$io_funciones->uf_convertirfecmostrar($fechas);
		$ls_titulo="<b>BALANCE GENERAL</b>";
		$ls_titulo1="<b> ".$ls_nombre." </b>"; 
		$ls_titulo2="<b> al ".$ld_fechas."</b>";
		$ls_titulo3="<b>(Expresado en ".$ls_bolivares.")</b>";  
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar datastore con los datos del reporte
	$lb_valido=uf_insert_seguridad("<b>Balance General en PDF</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_balance_general($ldt_fechas,$li_nivel); 
	}
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}	
		else// Imprimimos el reporte
		{
			error_reporting(E_ALL);
			$li_tot=$io_report->ds_reporte->getRowCount("sc_cuenta");
           	$cont=0;	
			$ld_total=0;
			$ld_totalcapital=0;
			$ld_totalpasivo=0;
			$ld_totalresultado=0;
			for($li_i=1;$li_i<=$li_tot;$li_i++)
			{
				$ls_orden=$io_report->ds_reporte->data["orden"][$li_i];
				$li_nro_reg=$io_report->ds_reporte->data["num_reg"][$li_i];
				$ls_sc_cuenta=trim($io_report->ds_reporte->data["sc_cuenta"][$li_i]);

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
				
				$ls_denominacion=$io_report->ds_reporte->data["denominacion"][$li_i];
				$ls_nivel=$io_report->ds_reporte->data["nivel"][$li_i];
				$ls_nivel=abs($ls_nivel);
				$ld_saldo=$io_report->ds_reporte->data["saldo"][$li_i];
				$ld_saldo_neto = 0;
				$li_cueproacu=$io_report->ds_reporte->data["cueproacu"][$li_i];
				$ls_estatus  =$io_report->ds_reporte->data["estatus"][$li_i];
				if($li_i>1)
				{
					$li_cueproacu_last=$io_report->ds_reporte->data["cueproacu"][$li_i-1];
					if(($li_cueproacu==1)&&($li_cueproacu_last == 1))
					{
					 $ld_saldo_last =$io_report->ds_reporte->data["saldo"][$li_i-1];				 
					 $ld_saldo_neto = $ld_saldo_last + $ld_saldo;
					 if($ld_saldo_neto<0)
					 { 
						$ld_saldo_neto="(".number_format(abs($ld_saldo_neto),2,",",".").")";
					 }
					 else
					 {
						$ld_saldo_neto=number_format(abs($ld_saldo_neto),2,",",".");	
					 }
					}
					else
					{
					 $ld_saldo_neto = "";
					}
				}
				
				$ls_rnivel=$io_report->ds_reporte->data["rnivel"][$li_i];
				if($ls_pasivo."000"==substr($ls_sc_cuenta,0,4))
				{
					$ld_total=$ld_total+$ld_saldo;
					$ld_totalpasivo=$ld_totalpasivo+$ld_saldo;
				}
				if($ls_capital."000"==substr($ls_sc_cuenta,0,4))
				{
					$ld_total=$ld_total+$ld_saldo;
					$ld_totalcapital=$ld_totalcapital+$ld_saldo;
				}
				
				if($ls_resultado."000"==substr($ls_sc_cuenta,0,4))
				{
					if(trim($ls_capital) != trim($ls_resultado))
				    {
					 $ld_total=$ld_total+$ld_saldo;
					 $ld_totalresultado=$ld_totalresultado+$ld_saldo;
					}
				}
				
				if($ld_saldo<0)
				 { 
					$ld_saldo="(".number_format(abs($ld_saldo),2,",",".").")";
				 }
				 else
				 {
					$ld_saldo=number_format(abs($ld_saldo),2,",",".");	
				 }
				 
				$cont=$cont+1;
				switch($ls_nivel)
				{
				 case 7:
						if($ls_estatus == 'C')
						{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>$ld_saldo,'saldo'=>'');					  
						}
						else
						{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);
						}
				 break;
				 case 6:
					    if($ls_estatus == 'C')
						{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>$ld_saldo,'saldo'=>'');					  
						}
						else
						{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);
						}	
				 break;
				 case 5:
					    if($ls_estatus == 'C')
						{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>$ld_saldo,'saldo'=>'');					  
						}
						else
						{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);
						}
				 break;
				 case 4:
				        if($ls_estatus == 'C')
						{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>$ld_saldo,'saldo'=>'');					  
						}
						else
						{
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);
						}
				 break;
				 case 3:
					    if($ls_estatus == 'C')
						{
						 $la_data[$cont]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'<b>        '.$ls_denominacion.'</b>','saldo_cueproacu'=>$ld_saldo,'saldo'=>'');					  
						}
						else
						{
						 $la_data[$cont]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'<b>        '.$ls_denominacion.'</b>','saldo_cueproacu'=>'','saldo'=>$ld_saldo);
						}
				 break;
				 case 2:
				        $la_data[$cont]=array('cuenta'=>'','denominacion'=>'','saldo_cueproacu'=>'','saldo'=>'');
						$cont=$cont+1;
						if($ls_estatus == 'C')
						{
						   $la_data[$cont]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'<b>        '.$ls_denominacion.'</b>','saldo_cueproacu'=>$ld_saldo,'saldo'=>'');					  
						}
						else
						{
						   $la_data[$cont]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'    '.'<b>'.$ls_denominacion.'</b>','saldo_cueproacu'=>'','saldo'=>'<b>'.$ld_saldo.'</b>');
						}
				 break;
				 case 1:
						 if ($cont>1)
							 {
								$la_data[$cont]=array('cuenta'=>'','denominacion'=>'','saldo_cueproacu'=>'','saldo'=>'');
								$cont=$cont+1;
								if($ls_estatus == 'C')
								{
								 $la_data[$cont]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'<b><i>'.$ld_saldo.'</b></i>','saldo'=>'<b><i></b></i>');					  
								}
								else
								{
								 $la_data[$cont]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'','saldo'=>'<b><i>'.$ld_saldo.'</b></i>');
								}
							 }
							 else
							 {
								if($ls_estatus == 'C')
								{
								 $la_data[$cont]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'<b><i>'.$ld_saldo.'</b></i>','saldo'=>'<b><i></b></i>');					  
								}
								else
								{
								 $la_data[$cont]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'','saldo'=>'<b><i>'.$ld_saldo.'</b></i>');
								}
							 }
				}
				
			}//for
			$ld_total=$ld_total;
			if($ld_total<0)
			{
			   $ld_total="(".number_format(abs($ld_total),2,",",".").")";
			}
			else
			{
				$ld_total=number_format($ld_total,2,",",".");
			}
			$lb_ctas_acreedoras = $io_report->uf_obtener_cuentas_acreedoras($ldt_fechas,$li_nivel);
			$la_data_acreedoras = NULL; 
			$ld_totalcapital=abs($ld_totalcapital);
			$ld_totalpasivo=abs($ld_totalpasivo);
			$ld_totalresultado=abs($ld_totalresultado);
			print $ld_totalcapital."<br>".$ld_totalpasivo."<br>".$ld_totalresultado;
			 // Dataset definition 
			 $DataSet = new pData;
			 $DataSet->AddPoint(array($ld_totalcapital,$ld_totalpasivo,$ld_totalresultado),"Serie1");
			 $DataSet->AddPoint(array("Patrimonio","Pasivo","Activo"),"titulos");
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
			
			 $Test->Render("balance.png");
		/*	if($lb_ctas_acreedoras)
			{
				$li_tot_acree=$io_report->ds_cuentas_acreedoras->getRowCount("sc_cuenta");
				$pos=0;	
				$ld_total=0;
				for($li_i=1;$li_i<=$li_tot_acree;$li_i++)
				{
				 $ls_sc_cuenta=trim($io_report->ds_cuentas_acreedoras->data["sc_cuenta"][$li_i]);
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
				 $ls_denominacion=$io_report->ds_cuentas_acreedoras->data["denominacion"][$li_i];
				 $ls_nivel=$io_report->ds_cuentas_acreedoras->data["nivel"][$li_i];
				 $ls_nivel=abs($ls_nivel);
				 $ld_saldo=$io_report->ds_cuentas_acreedoras->data["saldo"][$li_i];
				 $ls_rnivel=$io_report->ds_cuentas_acreedoras->data["rnivel"][$li_i];
				 $ls_estatus  =$io_report->ds_cuentas_acreedoras->data["estatus"][$li_i];
				 if($ld_saldo<0)
				 { 
					$ld_saldo="(".number_format(abs($ld_saldo),2,",",".").")";
				 }
				 else
				 {
					$ld_saldo=number_format(abs($ld_saldo),2,",",".");	
				 }
				 	$pos++;
					switch($ls_nivel)
				    {
					 case 7:
					        if($ls_estatus == 'C')
						    {
							 $la_data_acreedoras[$pos]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>$ld_saldo,'saldo'=>'');
							}
							else
							{
							 $la_data_acreedoras[$pos]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);
							}
					 break;
					 case 6:
					        if($ls_estatus == 'C')
						    {
							 $la_data_acreedoras[$pos]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>$ld_saldo,'saldo'=>'');					  
							}
							else
							{
							 $la_data_acreedoras[$pos]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);					  
							}
					 break;
					 case 5:
					        if($ls_estatus == 'C')
						    {
							 $la_data_acreedoras[$pos]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>$ld_saldo,'saldo'=>'');					  
							}
							else
							{
							 $la_data_acreedoras[$pos]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);					  
							}
					 break;
					 case 4:
					        if($ls_estatus == 'C')
						    {
					         $la_data_acreedoras[$pos]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>$ld_saldo,'saldo'=>'');					  
							}
							{
							 $la_data_acreedoras[$pos]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);					  
							}
					 break;
					 case 3:
					        if($ls_estatus == 'C')
							{
							 $la_data_acreedoras[$pos]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'<b>        '.$ls_denominacion.'</b>','saldo_cueproacu'=>$ld_saldo,'saldo'=>'');
							}
							else
							{
							 $la_data_acreedoras[$pos]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'<b>        '.$ls_denominacion.'</b>','saldo_cueproacu'=>'','saldo'=>$ld_saldo);
							}
					 break;
					 case 2:
					        if($ls_estatus == 'C')
							{
							 $la_data_acreedoras[$pos]=array('cuenta'=>'','denominacion'=>'','saldo_cueproacu'=>'','saldo'=>'');
						     $pos++;
						     $la_data_acreedoras[$pos]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'    '.'<b>'.$ls_denominacion.'</b>','saldo_cueproacu'=>'<b>'.$ld_saldo.'</b>','saldo'=>'');
							}
							else
							{
							 $la_data_acreedoras[$pos]=array('cuenta'=>'','denominacion'=>'','saldo_cueproacu'=>'','saldo'=>'');
						     $pos++;
						     $la_data_acreedoras[$pos]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'    '.'<b>'.$ls_denominacion.'</b>','saldo_cueproacu'=>'','saldo'=>'<b>'.$ld_saldo.'</b>');
							}
					 break;
					 case 1:
					        if($ls_estatus == 'C')
							{
							 if ($pos>1)
							 {
								$la_data_acreedoras[$pos]=array('cuenta'=>'','denominacion'=>'','saldo_cueproacu'=>'','saldo'=>'');
								$pos++;
								$la_data_acreedoras[$pos]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'<b><i>'.$ld_saldo.'</i></b>','saldo'=>'');
							 }
							 else
							 {
								$la_data_acreedoras[$pos]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'<b><i>'.$ld_saldo.'</i></b>','saldo'=>'');
							 }
							}
							else
							{
							 if ($pos>1)
							 {
								$la_data_acreedoras[$pos]=array('cuenta'=>'','denominacion'=>'','saldo_cueproacu'=>'','saldo'=>'');
								$pos++;
								$la_data_acreedoras[$pos]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'','saldo'=>'<b><i>'.$ld_saldo.'</i></b>');
							 }
							 else
							 {
								$la_data_acreedoras[$pos]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'','saldo'=>'<b><i>'.$ld_saldo.'</i></b>');
							 }
							 
							}
					 break;
					}
				}
			}
			unset($la_data_acreedoras);*/
			unset($la_data);		
		 }//else
		unset($io_report);
	    unset($io_funciones);			
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Balance General</title>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="498" border="0" align="center">
  <tr>
    <td width="320" class="sin-borde2"><div align="center" class="titulo-celdanew"> Balance General </div></td>
  </tr>
  <tr>
    <td width="320"><img src="balance.png" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">
<a href="javascript:ue_print();"><img src="../../shared/imagebank/tools20/print.gif" width="35" height="30" border="0"/></div></a>
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

