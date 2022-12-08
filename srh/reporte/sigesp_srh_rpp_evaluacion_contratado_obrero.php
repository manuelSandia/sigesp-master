<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Resultados Evaluaci�n General del Aspirante
//  ORGANISMO: IPSFA
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
	function uf_insert_seguridad()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Gener� el Reporte Resultados evaluacion contratado obrero";
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_p_evaluacion_contratado_obrero.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_encabezado_pagina($io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,700,60,60);// Agregar Logo ipsfa
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo_psicologia.jpg',525,700,60,60);// Agregar Logo psicologia
		
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora

		$li_tm=$io_pdf->getTextWidth(10,'REP�BLICA BOLIVARIANA DE VENEZUELA');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,10,'REP�BLICA BOLIVARIANA DE VENEZUELA'); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(10,'INSTITUTO DE PREVISION SOCIAL');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,10,'INSTITUTO DE PREVISION SOCIAL'); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(10,'DE LA FUERZA ARMADA');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,10,'DE LA FUERZA ARMADA'); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(9,'GERENCIA DE RECURSOS HUMANOS');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,690,9,'GERENCIA DE RECURSOS HUMANOS'); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(9,'Unidad de Psicolog�a');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,9,'Unidad de Psicolog�a'); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(9,'Resultados de Evaluaci�n');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,670,9,'Resultados de Evaluaci�n'); // Agregar el t�tulo
		$io_pdf->addText(40,650,10,'Datos de Identificaci�n'); // Agregar el t�tulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------	
		
	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_cabecera($as_codper,$as_nombre,$as_edocivper,$as_nivacaper,$as_carpos,$as_fecnacper,&$io_pdf)
	{
		$la_data[0]=array('nombre'=>'<b>APELLIDOS Y NOMBRES DEL ASPIRANTE</b>','edad'=>'<b>EDAD</b>','estado'=>'<b>ESTADO CIVIL</b>','cedula'=>'<b>C�DULA DE IDENTIDAD</b>');
		$la_data[1]=array('nombre'=>$as_nombre,'edad'=>$as_fecnacper,'estado'=>$as_edocivper,'cedula'=>$as_codper);
		$la_columna=array('nombre'=>'','edad'=>'','estado'=>'','cedula'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>2, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550, // Orientaci? de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>230), // Justificaci�n y ancho de la columna
									   'edad'=>array('justification'=>'center','width'=>100),
									   'estado'=>array('justification'=>'center','width'=>100),
						 			   'cedula'=>array('justification'=>'center','width'=>120))); // Justificaci�n y ancho de la columna
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data[0]=array('grado'=>'<b>GRADO DE INSTRUCCI�N</b>','cargo'=>'<b>CARGO PARA EL CUAL SE EVALUA</b>');
		$la_data[1]=array('grado'=>$as_nivacaper,'cargo'=>$as_carpos);
		$la_columna=array('grado'=>'','cargo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>2, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550, // Orientaci? de la tabla
						 'cols'=>array('grado'=>array('justification'=>'center','width'=>230), // Justificaci�n y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>320))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}		
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_detalle($la_data,$as_obseval,$as_receval,&$io_pdf)
	{
		$io_pdf->ezSetDy(-15);
		$la_data1[0]=array('titulo'=>'<b>PARA EL MOMENTO DE LA EVALUACI�N Y CON LAS T�CNICAS APLICADAS SE OBSERVO</b>');
		$la_columna=array('titulo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550, // Orientaci? de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>550))); // Justificaci�n y ancho de la columna
 		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		$la_columna=array('denasp'=>'                           ASPECTOS EVALUADOS','deficiente'=>'DEF.','bajo'=>'BAJ.', 'promedio'=>'PROM.',
						  'optimo'=>'OPTI.','excepcional'=>'EXCE.','no'=>'NO OBSERV.');		
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>2, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550, // Orientaci? de la tabla
						 'cols'=>array('denasp'=>array('justification'=>'left','width'=>240), // Justificaci�n y ancho de la columna
						 			   'deficiente'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'bajo'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'promedio'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'optimo'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'excepcional'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'no'=>array('justification'=>'center','width'=>60))); // Justificaci�n y ancho de la columna
						 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);

		$io_pdf->ezSetDy(-10);
		$la_data1[0]=array('titulo'=>'<b>OBSERVACIONES</b>');
		$la_columna=array('titulo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550, // Orientaci? de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>550))); // Justificaci�n y ancho de la columna
 		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		$la_data1[0]=array('titulo'=>str_pad(substr($as_obseval,0,2500),2600," "));
		$la_columna=array('titulo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550, // Orientaci? de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>550))); // Justificaci�n y ancho de la columna
 		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);

		$la_puntaje[0]='';
		$la_puntaje[1]='';
		$la_puntaje[2]='';
		$la_puntaje[3]='';
		$la_puntaje[4]='';
		$la_puntaje[5]='';
		$la_puntaje[$as_receval]='X';
		$io_pdf->ezSetDy(-10);
		$la_data1[0]=array('titulo'=>'<b>RECOMENDACI�N</b>');
		$la_columna=array('titulo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550, // Orientaci? de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>550))); // Justificaci�n y ancho de la columna
 		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		$la_data1[0]=array('campo1'=>'Ingresar', 'campo2'=>$la_puntaje[0], 'campo3'=>'Archivo de Elegible','campo4'=>$la_puntaje[3]);
		$la_data1[1]=array('campo1'=>'Ingresar bajo supervisi�n directa', 'campo2'=>$la_puntaje[1], 'campo3'=>'No ingresar','campo4'=>$la_puntaje[4]);
		$la_data1[2]=array('campo1'=>'Ingresar en per�odo de prueba','campo2'=>$la_puntaje[2], 'campo3'=>'Otra','campo4'=>$la_puntaje[5]);
		$la_columna=array('campo1'=>'', 'campo2'=>'', 'campo3'=>'', 'campo4'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>2, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550, // Orientaci? de la tabla
						 'cols'=>array('campo1'=>array('justification'=>'left','width'=>225), // Justificaci�n y ancho de la columna
						 			   'campo2'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'campo3'=>array('justification'=>'left','width'=>225), // Justificaci�n y ancho de la columna
						 			   'campo4'=>array('justification'=>'center','width'=>50))); // Justificaci�n y ancho de la columna
 		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		$io_pdf->ezSetDy(-80);
		$la_data1[0]=array('titulo'=>'                                                                                                                   <b>FIRMA:_______________________________________</b>');
		$la_columna=array('titulo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla						 
						 'maxWidth'=>550, // Orientaci? de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>550))); // Justificaci�n y ancho de la columna
 		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
	}		
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//--------------------------------------------------------------------------------------------------------------------
   	function calcular_edad($fecha_nac,$fecha_hasta)
	{  	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: calcular_edad
		//	    Arguments: fecha_nac  // fecha de nacimiento
		//                 fecha_hasta	 fecha hasta 	 
		//	      Returns: anos
		//	  Description: Funcion que obtiene la edad de una persona dada una fecha de nacimiento
		//     Creado Por: Maria Beatriz Unda		
		// Fecha Creaci�n: 29/05/2008							Fecha �ltima Modificaci�n : 
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 
		$c = date("Y",$fecha_nac);	   
		$b = date("m",$fecha_nac);	  
		$a = date("d",$fecha_nac); 	
		$anos = date("Y",$fecha_hasta)-$c; 
		if(date("m",$fecha_hasta)-$b > 0)
		{
		}
		elseif(date("m",$fecha_hasta)-$b == 0)
		{
			if(date("d",$fecha_hasta)-$a <= 0)
			{		  
				$anos = $anos-1;	        
			}
		}
		else
		{		  
			$anos = $anos-1;		          
		}  
		return $anos;	 
	}// fin de function calcular_edad($fecha_nac,$fecha_hasta)
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh('../../');
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report=new sigesp_srh_class_report();
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------	
	
 	$ls_codper =$io_fun_srh->uf_obtenervalor_get("codper","");
	$ls_feceval =$io_fun_srh->uf_obtenervalor_get("feceval",""); 	
	$ls_feceval=$io_funciones->uf_convertirdatetobd($ls_feceval);

	//----------------------------------------------------------------------------------------------------------------------------------//
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_select_evaluacion_contratado_obrero($ls_codper,$ls_feceval);
		if ($lb_valido==false)
		{
		    print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
			print(" close();");
			print("</script>");
		}
		   
		else  // Imprimimos el reporte
		{     
		    error_reporting(E_ALL);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(5,3,3,3); // Configuraci�n de los margenes en cent�metros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el n�mero de p�gina			
			uf_print_encabezado_pagina(&$io_pdf);
			while (!$io_report->rs_data->EOF)
			{
				$ls_codper=$io_report->rs_data->fields["codper"];
				$ls_nombre=$io_report->rs_data->fields["apesol"].", ".$io_report->rs_data->fields["nomsol"];
				$ls_carpos=$io_report->rs_data->fields["carpos"];
				$ls_fecnacper=$io_report->rs_data->fields["fecnac"];
				$ls_edocivper=$io_report->rs_data->fields["estciv"];
				$ls_nivacaper=$io_report->rs_data->fields["nivacasol"];
				$ls_obseval=$io_report->rs_data->fields["obseval"];
				$ls_receval=$io_report->rs_data->fields["receval"];
				$ld_fecact=	date("Y-m-d");
				$ls_edad=calcular_edad(strtotime($ls_fecnacper),strtotime($ld_fecact));
				switch ($ls_edocivper)
				{
					case "S":
						$ls_edocivper = "Soltero";
					break;
					case "C":
						$ls_edocivper = "Casado";
					break;
					case "V":
						$ls_edocivper = "Viudo";
					break;
					case "D":
						$ls_edocivper = "Divorciado";
					break;
					case "C":
						$ls_edocivper = "Concubino";
					break;
				}
				switch ($ls_nivacaper)
				{
					case "1":
						$ls_nivacaper = "Primaria";
					break;
					case "2":
						$ls_nivacaper = "Bachiller";
					break;
					case "3":
						$ls_nivacaper = "T�cnico Superior";
					break;
					case "4":
						$ls_nivacaper = "Universitario";
					break;
					case "5":
						$ls_nivacaper = "Maestr�a";
					break;
					case "6":
						$ls_nivacaper = "Postgrado";
					break;
					case "7":
						$ls_nivacaper = "Doctorado";
					break;
				}
				uf_print_cabecera($ls_codper,$ls_nombre,$ls_edocivper,$ls_nivacaper,$ls_carpos,$ls_edad,&$io_pdf);
				$lb_valido=$io_report->uf_select_dt_evaluacion_contratado_obrero($ls_codper,$ls_feceval);
				if ($lb_valido)
				{
					$li_i=0;
					while (!$io_report->rs_data_dt->EOF)
					{
						$la_puntaje[0]='';
						$la_puntaje[1]='';
						$la_puntaje[2]='';
						$la_puntaje[3]='';
						$la_puntaje[4]='';
						$la_puntaje[5]='';
						$ls_denasp=$io_report->rs_data_dt->fields["denasp"];
						$ls_puntaje=$io_report->rs_data_dt->fields["puntaje"];
						$la_puntaje[$ls_puntaje]='X';
						$la_data[$li_i]=array('denasp'=>$ls_denasp,'deficiente'=>$la_puntaje[0],'bajo'=>$la_puntaje[1], 'promedio'=>$la_puntaje[2],
											  'optimo'=>$la_puntaje[3],'excepcional'=>$la_puntaje[4],'no'=>$la_puntaje[5]);
						$li_i++;
						$io_report->rs_data_dt->MoveNext();
					}
				}
				uf_print_detalle($la_data,$ls_obseval,$ls_receval,&$io_pdf);
				$io_report->rs_data->MoveNext();
			}
		}
		if($lb_valido) // Si no ocurrio ning�n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo alg�n error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");	
		}
	}
?>	
