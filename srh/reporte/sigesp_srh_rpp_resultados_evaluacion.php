<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Resultados Evaluaci�n General del Aspirante
//  ORGANISMO: IPSFA
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-----------------------------------------------------------------------------------------------------------------------------------
///Elaborado por: Ing. Gusmary Balza
//-----------------------------------------------------------------------------------------------------------------------------------
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
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_p_resultados_evaluacion_aspirante.php",$ls_descripcion);
		return $lb_valido;
	}
	
//-----------------------------------------------------------------------------------------------------------------------------------	
	function uf_print_encabezado_pagina($as_titulo,$io_pdf)
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
       
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,725,50,50); // Agregar Logo
		
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
		 $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$io_pdf->ezSetY(760);
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'titleFontSize' => 10,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina	
	
//-------------------------------------------------------------------------------------------------------------------------------//

 function uf_print_encabezado_detalle($la_dataper,&$io_pdf)
	{
		
		 $io_pdf->ezSetY(700);
		$la_datap[1]=array('codper'=>'<b>C�digo del Aspirante</b>',
							 'nombre'=>'<b>Nombre del Aspirante</b>',
							 'codcon'=>'<b>Concurso</b>',
							 'fecha'=>'<b>Fecha Registro</b>');
		$la_columnas=array('codper'=>'',
						   'nombre'=>'',
						   'codcon'=>'',
						   'fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
									   'nombre'=>array('justification'=>'left','width'=>310),
									   'codcon'=>array('justification'=>'center','width'=>70),
						 			   'fecha'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datap,$la_columnas,'',$la_config);
	
	
		$la_columnas=array('codper'=>'',
						   'nombre'=>'',
						   'codcon'=>'',
						   'fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						'cols'=>array('codper'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
									   'nombre'=>array('justification'=>'left','width'=>310),
									   'codcon'=>array('justification'=>'center','width'=>70),
						 			   'fecha'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_dataper,$la_columnas,'',$la_config);	
		
	    unset($la_dataper);
		unset($la_columnas);
		unset($la_config);	
				
  }

//---------------------------------------------------------------------------------------------------------------------------------//
function uf_print_requisitos_minimos($la_data,$ai_i,$ls_punreqmin,&$io_pdf){
	$io_pdf->ezSetY(660);
	//Titulo encabezado de la tabla
	$la_datatit[1]=array('titulo'=>'<b>Evaluaci�n de Requisitos M�nimos</b>');
	$la_columnas=array('titulo'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize'=>7, // Tama�o de Letras
						 'titleFontSize'=>9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
	$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
	unset($la_datatit);
	unset($la_columnas);
	unset($la_config);
		
	//Titulo cuerpo de la tabla
	$la_columnas=array('codite'=>'<b>C�digo del Requisito</b>',
							 'denite'=>'<b>Denominaci�n</b>',
							 'valormax'=>'<b>Puntaje Req</b>',
							 'puntos'=>'<b>Puntaje Obt</b>');
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codite'=>array('justification'=>'center','width'=>120), // Justificaci�n y ancho de la columna
									   'denite'=>array('justification'=>'left','width'=>310),
									   'valormax'=>array('justification'=>'right','width'=>70),
						 			   'puntos'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	unset($la_columnas);
	unset($la_config);

	$la_data=array(array('name'=>'<b>Resultado Requisitos m�nimos:</b>'.'  '.$ls_punreqmin.' puntos'),
				   array('name'=>''), 	
	               array('name'=>'<b>Firma:</b>__________________________________________'),
	               array('name'=>'<b>Jefe de Secci�n Reclutamiento y Selecci�n</b>       '));
				
	$la_columnas=array('name'=>'');		
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>750, // Ancho de la tabla						 
						 'maxWidth'=>750, // Orientaci? de la tabla
						 'cols'=>array('name'=>array('justification'=>'right','width'=>550)));
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	unset($la_data);
	unset($la_columnas);
	unset($la_config);	
}		


function uf_print_evaluacion_psicologica($la_data2,$ar_r, $as_punevapsi,&$io_pdf){
	$la_datatit[1]=array('titulo'=>'<b>Evaluaci�n Psicol�gica</b>');
	$la_columnas=array('titulo'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize'=>7, // Tama�o de Letras
					 'showLines'=>0, // Mostrar L�neas
					 'shaded'=>0, // Sombra entre l�neas
					 'width'=>570, // Ancho de la tabla
					 'maxWidth'=>570, // Ancho M�ximo de la tabla
					 'xOrientation'=>'center', // Orientaci�n de la tabla
			         'outerLineThickness'=>0.5,
					 'innerLineThickness' =>0.5,
					 'cols'=>array('titulo'=>array('justification'=>'center','width'=>550))); // Justificaci�n y ancho de la columna
	$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
	unset($la_datatit);
	unset($la_columnas);
	unset($la_config);
	
	$la_columnas=array('codite2'=>'<b>C�digo Psicol�gico</b>',
						 'denite2'=>'<b>Denominaci�n</b>',
						 'valormax2'=>'<b>Puntaje Req</b>',
						 'puntos2'=>'<b>Puntaje Obt</b>');
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 7, // Tama�o de Letras
					 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
					 'showLines'=>1, // Mostrar L�neas
					 'shaded'=>0, // Sombra entre l�neas
					 'width'=>570, // Ancho de la tabla
					 'maxWidth'=>570, // Ancho M�ximo de la tabla
					 'xOrientation'=>'center', // Orientaci�n de la tabla
			         'outerLineThickness'=>0.5,
					 'innerLineThickness' =>0.5,
					 'cols'=>array('codite2'=>array('justification'=>'center','width'=>120), // Justificaci�n y ancho de la columna
								   'denite2'=>array('justification'=>'left','width'=>310),
								   'valormax2'=>array('justification'=>'right','width'=>70),
					 			   'puntos2'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna
	$io_pdf->ezTable($la_data2,$la_columnas,'',$la_config);
	unset($la_columnas);
	unset($la_config);
	
	$la_data=array(array('name'=>'<b>Resultado Psicol�gico:</b>'.' '.$as_punevapsi.' puntos'),
				   array('name'=>''), 	
	               array('name'=>'<b>Firma:</b>__________________________________________'),
	               array('name'=>'<b>Jefe de Secci�n Psicolog�a</b>        '));		                  		
	$la_columnas=array('name'=>'');		
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 7, // Tama�o de Letras
					 'showLines'=>0, // Mostrar L�neas
					 'shaded'=>0, // Sombra entre l�neas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'xPos'=>320, // Orientaci�n de la tabla
					 'width'=>570, // Ancho de la tabla						 
					 'maxWidth'=>570, // Orientaci? de la tabla
					 'cols'=>array('name'=>array('justification'=>'right','width'=>540)));
					        
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	unset($la_data);
	unset($la_columnas);
	unset($la_config);
}	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function uf_print_entrevista_tecnica($la_data3,$as_s,$ls_punenttec,&$io_pdf){
	$la_datatit[1]=array('titulo'=>'<b>Evaluaci�n de Entrevista T�cnica</b>');
	$la_columnas=array('titulo'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize'=>7, // Tama�o de Letras
					 'showLines'=>0, // Mostrar L�neas
					 'shaded'=>0, // Sombra entre l�neas
					 'width'=>570, // Ancho de la tabla
					 'maxWidth'=>570, // Ancho M�ximo de la tabla
					 'xOrientation'=>'center', // Orientaci�n de la tabla
			         'outerLineThickness'=>0.5,
					 'innerLineThickness' =>0.5,
					 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
	$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
	unset($la_datatit);
	unset($la_columnas);
	unset($la_config);	
     
	$la_columnas=array('codite3'=>'<b>C�digo de Entrevista</b>',
					   'denite3'=>'<b>Denominaci�n</b>',
                       'valormax3'=>'<b>Puntaje Req</b>',
					   'puntos3'=>'<b>Puntaje Obt</b>');
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 7, // Tama�o de Letras
					 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
					 'showLines'=>1, // Mostrar L�neas
					 'shaded'=>0, // Sombra entre l�neas
					 'width'=>570, // Ancho de la tabla
					 'maxWidth'=>570, // Ancho M�ximo de la tabla
					 'xOrientation'=>'center', // Orientaci�n de la tabla
			         'outerLineThickness'=>0.5,
					 'innerLineThickness' =>0.5,
					 'cols'=>array('codite3'=>array('justification'=>'center','width'=>120), // Justificaci�n y ancho de la columna
								   'denite3'=>array('justification'=>'left','width'=>310),
								   'valormax3'=>array('justification'=>'right','width'=>70),
					 			   'puntos3'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna
	$io_pdf->ezTable($la_data3,$la_columnas,'',$la_config);
	unset($la_columnas);
	unset($la_config);
	
	$la_data=array(array('name'=>'<b>Resultado Entrevista T�cnica:</b>'.' '.$ls_punenttec.' puntos'),
				   array('name'=>''), 	
	               array('name'=>'<b>Firma:</b>__________________________________________'),
	               array('name'=>'<b>Gerente de</b>______________________________________'));
			
					                  		
	$la_columnas=array('name'=>'');		
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' => 7, // Tama�o de Letras
					 'showLines'=>0, // Mostrar L�neas
					 'shaded'=>0, // Sombra entre l�neas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'xPos'=>320, // Orientaci�n de la tabla
					 'width'=>570, // Ancho de la tabla						 
					 'maxWidth'=>570, // Orientaci? de la tabla
					 'cols'=>array('name'=>array('justification'=>'right','width'=>550)));
	
				        
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);

             

	unset($la_data);
	unset($la_columnas);
	unset($la_config);
}// end function uf_print_detalle		

//-----------------------------------------------------------------------------------------------------------------------------------

function uf_print_pie_cabecera($as_total,$as_conclusion,&$io_pdf){
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private 
	//	    Arguments: as_codper // total de registros que va a tener el reporte
	//	    		   as_nomper // total de registros que va a tener el reporte
	//	    		   io_pdf // total de registros que va a tener el reporte
	//    Description: funci�n que imprime la cabecera de cada p�gina
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creaci�n: 21/04/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
         //YOLE////////////////////////////////	
		$io_pdf->ezSetDy(-8);
		$la_data_2[1]=array('name1'=>'100 pts','name2'=> $as_total.' pts');
				
		$la_columnas=array('name1'=>'','name2'=>'');
		                  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>7, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>45, // Ancho de la tabla
						 'maxWidth'=>45, // Ancho Máximo de la tabla
						 'xPos'=>500, // Orientación de la tabla
						 'xOrientation'=>'right', // Orientación de la tablA
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>40),
						 			   'name2'=>array('justification'=>'center','width'=>40))); // Justificaci�n y ancho de la columna
		
		$io_pdf->ezTable($la_data_2,$la_columnas,'',$la_config);
         /////////////////////////////////////////////////////////
	
	/*$la_data=array(array('name'=>'<b>Resultado Total:</b>'.'  '.$as_total),
		           (array('name'=>'<b>Conclusi�n:</b>'.'  '.$as_conclusion)),
	$la_data=array(array('name'=>'<b>Conclusi�n:</b>'.'  '.$as_conclusion),*/
	$la_data=array(array('name'=>'<b>Conclusi�n:</b>'),	
	        (array('name'=>'<b>_________________________________________________________________________________________________________________________________________</b>'))); //yole
	$la_columna=array('name'=>'');		
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 7, // Tama�o de Letras
					 'showLines'=>0, // Mostrar L�neas
					 'shaded'=>0, // Sombra entre l�neas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'xPos'=>320, // Orientaci�n de la tabla
					 'width'=>570, // Ancho de la tabla						 
					 'maxWidth'=>570, // Orientaci? de la tabla
					 'cols'=>array('name'=>array('justification'=>'left','width'=>550)));
					               
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	unset($la_data);
	unset($la_columna);
	unset($la_config);
	$la_data[1]=array('name'=>'',
	                  'name2'=>'');
	$la_data[2]=array('name'=>'<b>Firma</b>__________________________',
	                  'name2'=>'<b>Firma</b>__________________________');
	$la_data[3]=array('name'=>'<b>Jefe Dpto. T�cnico de Personal</b>',
	                  'name2'=>'<b>Gerente de Recursos Humanos</b>');                    			
	$la_columna=array('name'=>'',
	                  'name2'=>'');		
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
					 'fontSize' => 7, // Tama�o de Letras
					 'showLines'=>0, // Mostrar L�neas
					 'shaded'=>0, // Sombra entre l�neas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'xOrientation'=>'center', // Orientaci�n de la tabla
					 'width'=>520, // Ancho de la tabla						 
					 'maxWidth'=>520, // Orientaci? de la tabla
					 'cols'=>array('name'=>array('justification'=>'center','width'=>250),
					 			   'name2'=>array('justification'=>'center','width'=>250)));
					               
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	unset($la_data);
	unset($la_columna);
	unset($la_config);
}// end function uf_print_cabecera
	
//-------------------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_funciones=new class_funciones();
	$io_fun_srh=new class_funciones_srh('../../');
	$io_report=new sigesp_srh_class_report();
//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo="<b>RESULTADO DE LA EVALUACION GENERAL DEL ASPIRANTE</b>";
	
//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------	
	
 	$ls_codper =$io_fun_srh->uf_obtenervalor_get("codper","");
	$ls_codcon =$io_fun_srh->uf_obtenervalor_get("codcon",""); 	
	$ls_fecha =$io_fun_srh->uf_obtenervalor_get("fecha","");
	
	$ls_punreqmin =$io_fun_srh->uf_obtenervalor_get("punreqmin","");
	$ls_punevapsi =$io_fun_srh->uf_obtenervalor_get("punevapsi","");
	$ls_punenttec =$io_fun_srh->uf_obtenervalor_get("punenttec","");
	
	$ls_total =$io_fun_srh->uf_obtenervalor_get("total","");
	//$ls_conclusion =$io_fun_srh->uf_obtenervalor_get("conclusion","");
	$ls_conclusion =$io_fun_srh->uf_obtenervalor_get(" ",""); //yole
	
//----------------------------------------------------------------------------------------------------------------------------------//

 	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido){
		$lb_valido=$io_report->uf_select_aspirante_total($ls_codper,$ls_codcon,$ls_fecha);
		if ($lb_valido==false){
		    print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
			print(" close();");
			print("</script>");
		}
		else{// Imprimimos el reporte
			error_reporting(E_ALL);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3,3,3,3); // Configuraci�n de los margenes en cent�metros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el n�mero de p�gina			
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
		    $lp_totrow=$io_report->ds_detalle->getRowCount("codper"); 
			$li_aux=0;
			for($lp_p=1;$lp_p<=$lp_totrow;$lp_p++){
				$li_aux++;
				$li_totrow=0;
				$ls_codper=$io_report->ds_detalle->data["codper"][$lp_p];
				$ls_nombre=$io_report->ds_detalle->data["nombre"][$lp_p];
				$ls_fecha=$io_report->ds_detalle->data["fecreg"][$lp_p];
				$ls_codcon=$io_report->ds_detalle->data["codcon"][$lp_p];
			 
			 	$ls_fecha_f=$io_funciones->uf_convertirfecmostrar($ls_fecha);
			 	$la_dataper[$lp_p]=array('codper'=>$ls_codper,'nombre'=>$ls_nombre,'fecha'=>$ls_fecha_f,
									  'codcon'=>$ls_codcon); 
				uf_print_encabezado_detalle($la_dataper,&$io_pdf);
				unset($la_dataper);

				//obteniendo requisitos
		     	$io_report->uf_select_requisitosxaspirante($ls_codper,$ls_codcon);
			 	$li_totrow=$io_report->DS->getRowCount("codite"); 
			 	for($li_i=1;$li_i<=$li_totrow;$li_i++){      
					$ls_codigo=$io_report->DS->data["codite"][$li_i];
					$ls_denite=trim ($io_report->DS->data["denite"][$li_i]);
					$ls_valormax=$io_report->DS->data["valormax"][$li_i];
					$ls_puntos=$io_report->DS->data["puntos"][$li_i];
					
					$la_data[$li_i]=array('codite'=>$ls_codigo,'denite'=>$ls_denite,
				                      'valormax'=>$ls_valormax.' pts',
									  'puntos'=>$ls_puntos.' pts');
			  	}
			  	//imprimiendo requisitos
			  	if ($li_totrow>0){
		   			uf_print_requisitos_minimos($la_data,$li_totrow, $ls_punreqmin,&$io_pdf);
			 	}
		   		unset($la_data);
		   
		   		//obteniendo evaluacion psicologica
	         	$io_report->uf_select_evalpsicologicaxaspirante($ls_codper,$ls_codcon);
			 	$lr_totrow=$io_report->det_item_psi->getRowCount("codite"); 	 
			 	for($lr_r=1;$lr_r<=$lr_totrow;$lr_r++){      
					$ls_codigo2=$io_report->det_item_psi->data["codite"][$lr_r];
					$ls_denite2=trim ($io_report->det_item_psi->data["denite"][$lr_r]);
					$ls_valormax2=$io_report->det_item_psi->data["valormax"][$lr_r];
					$ls_puntos2=$io_report->det_item_psi->data["puntos"][$lr_r];
													
					$la_data2[$lr_r]=array('codite2'=>$ls_codigo2,'denite2'=>$ls_denite2,
					                      'valormax2'=>$ls_valormax2.' pts',
										  'puntos2'=>$ls_puntos2.' pts');
			  	}
			  	//imprimiendo evalueacion psicologica
			  	if ($lr_totrow>0){
	       			uf_print_evaluacion_psicologica($la_data2,$lr_totrow,$ls_punevapsi,&$io_pdf);
				}
		   		unset($la_data2);	
	  			
		   		//obteniendo entrevista tecnica
        		$io_report->uf_select_entrevistasxaspirante($ls_codper,$ls_codcon);
        		$ls_totrow=$io_report->det_item_ent->getRowCount("codite");
        		for($ls_s=1;$ls_s<=$ls_totrow;$ls_s++){      
					$ls_codigo3=$io_report->det_item_ent->data["codite"][$ls_s];
					$ls_denite3=trim ($io_report->det_item_ent->data["denite"][$ls_s]);
					$ls_valormax3=$io_report->det_item_ent->data["valormax"][$ls_s];
					$ls_puntos3=$io_report->det_item_ent->data["puntos"][$ls_s];
													
					$la_data3[$ls_s]=array('codite3'=>$ls_codigo3,'denite3'=>$ls_denite3,
					                      'valormax3'=>$ls_valormax3.' pts',
										  'puntos3'=>$ls_puntos3.' pts');
				}
			  	//imprimiendo entrevista tecnica
				if ($ls_totrow>0){
		   			uf_print_entrevista_tecnica($la_data3,$ls_totrow, $ls_punenttec,&$io_pdf);
				}
		   		unset($la_data3);
				uf_print_pie_cabecera($ls_total,$ls_conclusion,&$io_pdf);	 
			}
		}
	
		if($lb_valido){// Si no ocurrio ning�n error
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		    else{// Si hubo alg�n error
		    print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");	
		}
	}	
?>	
