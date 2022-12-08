<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion de Impuestos Municipales
	//  ORGANISMO: 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 15/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_retencionesmunicipales.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 04/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		//$io_pdf->Rectangle(50,515,690,65);
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_sucre.JPEG',35,720,70,50); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(18,$as_titulo);
		$tm=520-($li_tm/2);
		//$io_pdf->addText($tm,540,12,$as_titulo); // Agregar el t�tulo
		$io_pdf->addText(200,765,9,"<b>REPUBLICA BOLIVARIANA DE VENEZUELA</b>"); // Agregar el t�tulo
		$io_pdf->addText(220,755,9,"<b>GOBIERNO DEL DISTRITO CAPITAL</b>"); // Agregar el t�tulo
		$io_pdf->addText(150,745,9,"<b>SERVICIOS DE ADMINISTRACION TRIBUTARIA DEL DISTRITO CAPITAL</b>"); // Agregar el t�tulo
		
		$io_pdf->addText(180,690,9,"<b>COMPROBANTE DE RETENCION DEL IMPUESTO</b>"); // Agregar el t�tulo
		$io_pdf->addText(230,680,9,"<b>DEL UNO X MIL (1 x 1000)</b>"); // Agregar el t�tulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_numcon,$ad_fecrep,$as_agenteret,$as_rifagenteret,$as_perfiscal,$as_licagenteret,$as_diragenteret,
							   $as_nomsujret,$as_rif,$as_numlic,$ai_estcmpret,$as_diragenteret,$as_telagenteret,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_numcon // N�mero de Comprobante
		//	    		   ad_fecrep // Fecha del comprobante
		//	    		   as_agenteret // agente de Retenci�n
		//	    		   as_rifagenteret // Rif del Agente de Retenci�n
		//	    		   as_perfiscal // Per�odo Fiscal
		//	    		   as_licagenteret // N�mero de licencia de agente de retenci�n
		//	    		   as_diragenteret // Direcci�n del agente de retenci�n
		//	    		   as_nomsujret // Nombre del sujeto retenido
		//	    		   as_rif // Rif del sujeto retenido
		//	    		   as_numlic // N�mero de Licencia del sujeto retenido
		//	    		   ai_estcmpret // Estatus del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 17/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(10);
	 	$ld_date=date("d/m/Y");
		$io_pdf->Rectangle(30,40,550,735);// Marco general
		/*if($ai_estcmpret==2)
		{
		    $io_pdf->Rectangle(45,495,180,30);		
			$io_pdf->addText(90,505,15,"<b> ANULADO </b>"); 
		}*/	
		$as_tipper="X";
		$io_pdf->addText(380,715,8,"<b>N� CORRELATIVO  _____________________</b>"); 
		
		$io_pdf->addText(40,650,8,"<b>AGENTE DE RETENCION:        ______________________________________________________________________</b>");
		$io_pdf->addText(160,651,8,$as_agenteret); 
		
		$io_pdf->addText(40,630,8,"<b>N� de R.I.F.:                                ______________________________________________________________________</b>");
		$io_pdf->addText(160,631,8,$as_rifagenteret); 
		
		$io_pdf->addText(40,610,8,"<b>DOMICILIO FISCAL:                  ______________________________________________________________________</b>");
		$io_pdf->addText(160,611,8,$as_diragenteret); 
		
		$io_pdf->addText(40,590,8,"<b>TELEFONOS:                             ______________________________________________________________________</b>");
		$io_pdf->addText(160,591,8,$as_telagenteret); 
		
		$io_pdf->line(30,569,580,569); //Horizontal
		$io_pdf->line(30,240,580,240); //Horizontal
		$io_pdf->line(30,190,580,190); //Horizontal
		
		$io_pdf->addText(40,540,8,"<b>CONTRIBUYENTE:                    ______________________________________________________________________</b>");
		$io_pdf->addText(160,541,8,$as_nomsujret); 
		
		$io_pdf->addText(40,510,8,"<b>PERSONA NATURAL:  _______________    PERSONA JURIDICA:  _______________  CEDULA DE IDENTIDAD O R.I.F. N� __________________</b>");
		$io_pdf->addText(320,511,8,$as_tipper); 
		
		$io_pdf->addText(40,475,8,"<b>POR CONCEPTO DE: PRESTACION DE SERVICIO ______ ADQUISICION DE BIENES O SUMINISTROS ______ EJECUCION DE OBRAS ______</b>");
		

	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------			
			
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$ai_totbasimp,$ai_totmonimp,$ai_totmoniva,$as_rifagenteret,$as_numsop,$as_desope,$as_totconiva,$ad_fecfac,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: la_data // Arreglo de datos a imprimir
		//	    		   ai_totbasimp // Total de la base imponible
		//	    		   ai_totmonimp // Total monto imponible
		//                 ai_totmoniva // Total monto iva
		//	    		   as_rifagenteret // Rif del Agente de Retenci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		//     Modificado Por: Ing. Arnaldo Su�rez
		// Fecha Creaci�n: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->addText(480,678.5,8,$as_numsop); // Agregar el t�tulo
		//print $ai_totbasimp."<br>";//monto bruto de la operacion
		//print $ai_totmonimp."<br>";
		//print $ai_totmoniva."<br>";//monto retenido
		$io_pdf->addText(220,401,8,$ai_totbasimp);
		$io_pdf->addText(420,401,8,$as_totconiva);
		$io_pdf->addText(150,361,8,$ai_totmoniva);
		$io_pdf->addText(350,361,8,$ad_fecfac);
		$io_pdf->ezSetDy(-240);
		$la_data_l[1]=array('name'=>'<b>DESCRIPCION : </b>');
		$la_data_l[2]=array('name'=>$as_desope);		
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>0, // Mostrar Lieas
						 'shaded'=>0, // Sombra entre lineas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>295, // Orientaci�n de la tabla
						 'width'=>690, // Ancho de la tabla
						 'colGap'=>1,						 
						 'maxWidth'=>690,
						 'cols'=>array('name'=>array('justification'=>'left','width'=>510))); // Ancho Minimo de la tabla
        $io_pdf->ezTable($la_data_l,$la_columna,'',$la_config);
		unset($la_data_l);
		unset($la_columna);
		unset($la_config);	
	
		$io_pdf->addText(40,400,8,"<b>MONTO BRUTO DE LA OPERACION: Bs.____________________     MONTO DEL IMPUESTO:Bs._____________________</b>");
		
		$io_pdf->addText(40,360,8,"<b>MONTO RETENIDO: Bs.____________________     FECHA DE LA RETENCION:_____________________</b>");
		
		$io_pdf->addText(40,330,8,"<b>LLENAR SOLO EN CASOS DE PAGOS EFECTUADOS DIRECTAMENTE EN LAS CUENTAS RECEPTORAS DE FONDO DE DISTRITO CAPITAL</b>");
	
		$io_pdf->addText(40,300,8,"<b>BANCO: ___________________________________________________  N� DE PLANILLA:______________________________</b>");
	
		$io_pdf->addText(40,270,8,"<b>MONTO PAGADO: ________________________________________  FECHA DE PAGO:______________________________</b>");
		
		$io_pdf->addText(40,100,8,"<b>AGENTE DE RETENCION:                                                                FIRMA:                                                            SELLO:</b>");
		
	}// end function uf_print_detalle

	function uf_print_sello(&$io_pdf)
	{
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_sello
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Jennifer Rivero
		//     Modificado Por: Ing. Arnaldo Su�rez
		// Fecha Creaci�n: 13/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $io_pdf->ezSetDy(-59);
		$io_pdf->addText(35,230,10,'<b>FIRMA:</b>'); // Agregar el t�tulo
		$io_pdf->addText(35,150,10,'<b>FECHA:</b>'); // Agregar el t�tulo
		
		$io_pdf->addText(220,230,10,'<b>FIRMA:</b>'); // Agregar el t�tulo
		$io_pdf->addText(220,190,10,'<b>SELLO:</b>'); // Agregar el t�tulo
		$io_pdf->addText(220,150,10,'<b>FECHA:</b>'); // Agregar el t�tulo
		
		$io_pdf->addText(400,230,10,'<b>FIRMA:</b>'); // Agregar el t�tulo
		$io_pdf->addText(400,190,10,'<b>SELLO:</b>'); // Agregar el t�tulo
		$io_pdf->addText(400,150,10,'<b>FECHA:</b>'); // Agregar el t�tulo
		$la_data[1]=array('name1'=>'<b>EL LIQUIDADOR</b>',
	                    'name2'=>'<b>ENTE GUBERNAMENTAL</b>',
						'name3'=>'<b>RECIBIDO POR </b>');	
        $la_columna=array('name1'=>'','name2'=>'','name3'=>'');
		$la_config= array('showHeadings'=>0, // Mostrar encabezados
						  'fontSize' => 10, // Tama�o de Letras
						  'showLines'=>0, // Mostrar L�neas
						  'shaded'=>0, // Sombra entre l�neas
						  'shadeCol'=>array(0.9,0.9,0.9),
						  'shadeCol2'=>array(0.9,0.9,0.9),
						  'xOrientation'=>'center', // Orientaci�n de la tabla
						  'colGap'=>1,
						  'width'=>690,
						  'cols'=>array('name1'=>array('justification'=>'center','width'=>185),						                
										'name2'=>array('justification'=>'center','width'=>185),
										'name3'=>array('justification'=>'center','width'=>185))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config); 		
	}
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo= "COMPROBANTE DE RETENCION DE IMPUESTO DE TIMBRE FISCAL";
    $ls_agente=$_SESSION["la_empresa"]["nombre"];
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_comprobantes=$io_fun_cxp->uf_obtenervalor_get("comprobantes","");
	$ls_mes=$io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_anio=$io_fun_cxp->uf_obtenervalor_get("anio","");
	$ls_agenteret=$_SESSION["la_empresa"]["nombre"];
	$ls_rifagenteret=$_SESSION["la_empresa"]["rifemp"];
	$ls_diragenteret=$_SESSION["la_empresa"]["direccion"];
	$ls_licagenteret=$_SESSION["la_empresa"]["numlicemp"];
	$ls_diragenteret=$_SESSION["la_empresa"]["direccion"];
	$ls_telagenteret=$_SESSION["la_empresa"]["telemp"];
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$la_comprobantes=split('-',$ls_comprobantes);
		$la_datos=array_unique($la_comprobantes);
		$li_totrow=count($la_datos);
		sort($la_datos,SORT_STRING);
		if($li_totrow<=0)
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else
		{
			error_reporting(E_ALL);
			$io_pdf = new Cezpdf("LETTER","portrait");
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
			$io_pdf->ezSetCmMargins(3.5,1.5,3,3);
			$lb_valido=true;
			$ls_numcomant = "";
			for ($li_z=0;($li_z<$li_totrow)&&($lb_valido);$li_z++)
			{
				uf_print_encabezado_pagina($ls_titulo,$io_pdf);
				$ls_numcom=$la_datos[$li_z];
				$lb_valido=$io_report->uf_retencionesunoxmil_proveedor($ls_numcom,$ls_mes,$ls_anio);
				if($lb_valido)
				{
					$li_total=$io_report->DS->getRowCount("numcom");
					for($li_i=1;$li_i<=$li_total;$li_i++)
					{
						$ls_numcon=$io_report->DS->data["numcom"][$li_i];		 								
						$ls_codret=$io_report->DS->data["codret"][$li_i];			   
						$ls_fecrep=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecrep"][$li_i]);
						$ls_perfiscal=$io_report->DS->data["perfiscal"][$li_i];						
						$ls_codsujret=$io_report->DS->data["codsujret"][$li_i];			     
						$ls_nomsujret=$io_report->DS->data["nomsujret"][$li_i];	
						$ls_rif=$io_report->DS->data["rif"][$li_i];	
						$ls_dirsujret=$io_report->DS->data["dirsujret"][$li_i];		
						$li_estcmpret=$io_report->DS->data["estcmpret"][$li_i];	
						$ls_numlic=$io_report->DS->data["numlic"][$li_i];									
						if ($ls_numcom!=$ls_numcomant)
					   {
					    if ($li_z>=1)
						   {
							 $io_pdf->ezNewPage();  
						   }
					     uf_print_cabecera($ls_numcon,$ls_fecrep,$ls_agenteret,$ls_rifagenteret,$ls_perfiscal,$ls_licagenteret,
									  $ls_diragenteret,$ls_nomsujret,$ls_rif,$ls_numlic,$li_estcmpret,$ls_diragenteret,$ls_telagenteret,&$io_pdf);
						 $ls_numcomant=$ls_numcom;
					   }
					}											
					$lb_valido=$io_report->uf_retencionesunoxmil_detalles($ls_numcom);
					if($lb_valido)
					{
						$li_totalbaseimp=0;
						$li_totalmontoimp=0;
						$li_totmontoiva=0;
						$li_totmontotdoc=0;
						$li_total=$io_report->ds_detalle->getRowCount("numfac");			   
						for($li_i=1;$li_i<=$li_total;$li_i++)
						{
							$li_montotdoc=$io_report->uf_retencionesmunicipales_monfact($ls_numcon);
							$ls_numsop=$io_report->ds_detalle->data["numsop"][$li_i];					
							$ld_fecfac=$io_funciones->uf_convertirfecmostrar($io_report->ds_detalle->data["fecfac"][$li_i]);	
							$ls_numfac=$io_report->ds_detalle->data["numfac"][$li_i];	
							$ls_numref=$io_report->ds_detalle->data["numcon"][$li_i];	              
							$li_baseimp=$io_report->ds_detalle->data["basimp"][$li_i];
							$li_iva_ret=$io_report->ds_detalle->data["iva_ret"][$li_i];	
							$li_porimp=$io_report->ds_detalle->data["porimp"][$li_i];	
							$li_totimp=$io_report->ds_detalle->data["totimp"][$li_i];
							$ls_desope=$io_report->ds_detalle->data["desope"][$li_i];
							$ls_totconiva=$io_report->ds_detalle->data["totcmp_con_iva"][$li_i];	

							$li_totalbaseimp=$li_totalbaseimp + $li_baseimp ;	
							$li_totalmontoimp=$li_totalmontoimp + $li_totimp;
							$li_totmontotdoc=$li_totmontotdoc+$li_montotdoc;
							$li_totmontoiva=$li_totmontoiva+$li_iva_ret;
							$li_iva_ret=number_format($li_iva_ret,2,",",".");	
							$li_baseimp=number_format($li_baseimp,2,",",".");			
							$li_porimp=number_format($li_porimp,4,",",".");			
							$li_totimp=number_format($li_totimp,2,",",".");							
							$li_montotdoc=number_format($li_montotdoc,2,",",".");
							$ls_totconiva=number_format($ls_totconiva,2,",",".");							
							$la_data[$li_i]=array('fecfac'=>$ld_fecfac,'numfac'=>$ls_numfac,
												  'numref'=>$ls_numref,'baseimp'=>$li_baseimp,'iva_ret'=>$li_iva_ret,'porimp'=>$li_porimp,'totimp'=>$li_montotdoc,'numsop'=>$ls_numsop, );														
						  }																		 																						  
						  $li_totalbaseimp= number_format($li_totalbaseimp,2,",","."); 
  						  $li_totalmontoimp= number_format($li_totmontotdoc,2,",","."); 
						  $li_totmontoiva= number_format($li_totmontoiva,2,",","."); 
						  uf_print_detalle($la_data,$li_totalbaseimp,$li_totalmontoimp,$li_totmontoiva,$ls_rifagenteret,$ls_numsop,$ls_desope,$ls_totconiva,$ld_fecfac,&$io_pdf);
						  //uf_print_sello($io_pdf);
						  unset($la_data);							 
						  
					}
				}
				$io_report->DS->reset_ds();
			}
			if($lb_valido) // Si no ocurrio ning�n error
			{
				$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
				$io_pdf->ezStream(); // Mostramos el reporte
			}
			else  // Si hubo alg�n error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				//print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
		}
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_cxp);
?> 