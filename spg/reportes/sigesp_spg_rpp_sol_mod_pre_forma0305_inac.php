<?php
session_start();
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
if(!array_key_exists("la_logusr",$_SESSION)){
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";
}

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_encabezado_pagina($as_titulo,$as_procede,$ad_fecha,$as_fuentefin="",&$io_pdf){
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezado_pagina
	//		    Acess: private
	//	    Arguments: as_titulo // T�tulo del Reporte
	//	    		   io_pdf    // Instancia de objeto pdf
	//    Description: Funci�n que imprime los encabezados por p�gina
	//	   Creado Por: Ing. N�stor Falcon
	// Fecha Creaci�n: 18/05/2007.
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
	$tm=306-($li_tm/2);
	$io_pdf->rectangle(20,690,570,80);
	$io_pdf->addText(30,760,8,"ENTE DE ADSCRIPCI�N:{$_SESSION["la_empresa"]["nomorgads"]}");
	$io_pdf->addText(30,750,8,"ORGANO:{$_SESSION['la_empresa']['nombre']}");
	$io_pdf->addText(383,750,8,"<b>P�GINA N�.</b>");
	$io_pdf->addText(510,750,8,"COD. A0939");
	$io_pdf->addText(25,710,7,"FECHA:".$ad_fecha);
	$io_pdf->addText(25,695,7,"FUENTE DE FINANCIAMIENTO: ".$as_fuentefin);
	$io_pdf->addText(250,730,10,"FORMATO 0305");
	$io_pdf->addText($tm,720,10,$as_titulo);
	$io_pdf->rectangle(20,625,570,60);
	$io_pdf->addText(30,660,7,"INSUBSISTENCIA");$io_pdf->rectangle(95,658,10,10);
	$io_pdf->addText(130,660,7,"REDUCCI�N");$io_pdf->rectangle(180,658,10,10);
	$io_pdf->addText(220,675,7,"<b>RECURSOS ADICIONALES</b>");
	$io_pdf->addText(223,660,7,"CR�DITO ADICIONAL");$io_pdf->rectangle(300,658,10,10);
	$io_pdf->addText(238,638,7,"RECTIFICACI�N");$io_pdf->rectangle(300,635,10,10);
	$io_pdf->addText(470,675,7,"<b>TRASPASO</b>");
	$io_pdf->addText(450,660,7,"GASTOS CORRIENTES");$io_pdf->rectangle(540,658,10,10);
	$io_pdf->addText(460,638,7,"GASTOS DE CAPITAL");$io_pdf->rectangle(540,635,10,10);
	//Impresi�n de las X para el Marcado de Operacion.

	switch ($as_procede){
		case 'SPGINS':
			$io_pdf->addText(97.5,660.5,7,"<b>X</b>");//Insubsistencia
			break;
		case 'SPGCRA':
			$io_pdf->addText(302.5,660.5,7,"<b>X</b>");//Cr�dito Adicional.
			break;
		case 'SPGREC':
			$io_pdf->addText(302.5,637.5,7,"<b>X</b>");//Rectificacion.
			break;
		case 'SPGTRA':
			$io_pdf->addText(542.5,660.5,7,"<b>X</b>");//Traspaso.
			break;
	}
	
	$io_pdf->Rectangle(19,80,570,30);

	$io_pdf->Rectangle(19,40,570,60);
	$io_pdf->line(19,80,590,80);
	$io_pdf->line(95,40,95,100);
	$io_pdf->line(170,40,170,100);
	$io_pdf->line(275,40,275,100);
	$io_pdf->line(400,40,400,110);
	$io_pdf->line(480,40,480,100);

	$io_pdf->addText(200,102,8,"<b>INSTITUCION</b>"); // Agregar el t�tulo
	$io_pdf->addText(410,102,8,"<b>OFICINA NACIONAL DE PRESUPUESTO</b>"); // Agregar el t�tulo

		
	$io_pdf->addText(25,90,7,"ELABORADO POR:"); // Agregar el t�tulo
	$io_pdf->addText(110,90,7,"REVISADO POR:"); // Agregar el t�tulo
	$io_pdf->addText(190,90,7,"JEFE DE OFICINA DE"); // Agregar el t�tulo
	$io_pdf->addText(200,83,7,"PLANIFICACION"); // Agregar el t�tulo
	$io_pdf->addText(175,45,9,"Lic. In�s Maria Martinez"); // Agregar el t�tulo
	$io_pdf->addText(298,90,7,"APROBADO POR GERENTE"); // Agregar el t�tulo
	$io_pdf->addText(300,83,7,"GENERAL O PRESIDENTE"); // Agregar el t�tulo
	$io_pdf->addText(280,45,9,"Lic. Jos� Luis Martinez Bravo"); // Agregar el t�tulo
	$io_pdf->addText(415,90,7,"JEFE SECTOR"); // Agregar el t�tulo
	$io_pdf->addText(490,90,7,"DIRECTOR G. SECTORIAL"); // Agregar el t�tulo

	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezado_pagina
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle($la_data,$ls_titulo1,$ls_titulo2,$ld_monto,&$io_pdf){
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private
	//	    Arguments: la_data // arreglo de informaci�n
	//	   			   io_pdf // Objeto PDF
	//    Description: funci�n que imprime el detalle
	//	   Creado Por: Ing.Yozelin Barrag�n
	// Fecha Creaci�n: 13/09/2006
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_pdf->setStrokeColor(1,1,1);
	$io_pdf->ezSetDy(-15);
	
	
	$la_datatit[1]=array('titulo'=>"<b>".$ls_titulo1."</b>");
	$la_columnas=array('titulo'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
	$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
	$io_pdf->ezSetDy(-5);
	
	$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>1, // Sombra entre l�neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho M�ximo de la tabla
						  'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('proyecto'=>array('justification'=>'center','width'=>65), // Justificaci�n y ancho de la 
						 			   'accion'=>array('justification'=>'center','width'=>45),
									   'ejecutora'=>array('justification'=>'center','width'=>25),
									   'partida'=>array('justification'=>'center','width'=>25),
									   'generica'=>array('justification'=>'center','width'=>25),
									   'especifica'=>array('justification'=>'center','width'=>25), 
									   'subespecifica'=>array('justification'=>'center','width'=>25),
									   'denominacion'=>array('justification'=>'left','width'=>255),
									   'monto'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho 
	$la_columnas = array('proyecto'=>'<b>PROYECTO O ACCION CENTRALIZADA</b>',
		                     'accion'=>'<b>ACCI�N ESPEC�FICA</b>',
							 'ejecutora'=>'<b>SUB-ACC</b>',
							 'partida'=>'<b>PART</b>',
							 'generica'=>'<b>GEN</b>',
			                 'especifica'=>'<b>ESP</b>',
							 'subespecifica'=>'<b>SUB</b>',
							 'denominacion'=>'<b>DENOMINACI�N</b>',
							 'monto'=>'<b>BOL�VARES</b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	
	unset($la_data);
	unset($la_columnas);
	unset($la_config);
	
	//IMPRIMIENDO EL TOTAL
	$la_datatit[1]=array('proyecto'=>'','accion'=>'','ejecutora'=>'','partida'=>'',
						 'generica'=>'','especifica'=>'','subespecifica'=>'',
						 'denominacion'=>'<b>'.$ls_titulo2.'</b>','monto'=>'<b>'.number_format($ld_monto,2,",",".").'</b>');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>1, // Sombra entre l�neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('proyecto'=>array('justification'=>'center','width'=>65), // Justificaci�n y ancho de la 
						 			   'accion'=>array('justification'=>'center','width'=>45),
									   'ejecutora'=>array('justification'=>'center','width'=>25),
									   'partida'=>array('justification'=>'center','width'=>25),
									   'generica'=>array('justification'=>'center','width'=>25),
									   'especifica'=>array('justification'=>'center','width'=>25), 
									   'subespecifica'=>array('justification'=>'center','width'=>25),
									   'denominacion'=>array('justification'=>'left','width'=>255),
									   'monto'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho 

	$la_columnas = array('proyecto'=>'<b>PROYECTO O ACCION CENTRALIZADA</b>',
		                     'accion'=>'<b>ACCI�N ESPEC�FICA</b>',
							 'ejecutora'=>'<b>UEL</b>',
							 'partida'=>'<b>PART</b>',
							 'generica'=>'<b>GEN</b>',
			                 'especifica'=>'<b>ESP</b>',
							 'subespecifica'=>'<b>SUB</b>',
							 'denominacion'=>'<b>DENOMINACI�N</b>',
							 'monto'=>'<b>BOL�VARES</b>');

	$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
	unset($la_data);
	unset($la_columnas);
	unset($la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------------------------

$ls_procede     = $_GET["procede"];
$ls_comprobante = $_GET["comprobante"];
$ld_fecha       = $_GET["fecha"];


require_once("../../shared/ezpdf/class.ezpdf.php");
require_once("sigesp_spg_reportes_class.php");
$io_report = new sigesp_spg_reportes_class();
$io_pdf    = new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
$io_pdf->ezSetCmMargins(5.8,6,3,3); // Configuraci�n de los margenes en cent�metros
$ls_titulo = "<b>SOLICITUD DE MODIFICACI�N PRESUPUESTARIA N�. ".$ls_comprobante."</b>";
$ls_fuentefin = $io_report->uf_obtener_fuente_financiamiento_comprobante($ls_comprobante,$ls_procede,$ld_fecha);
uf_print_encabezado_pagina($ls_titulo,$ls_procede,$ld_fecha,$ls_fuentefin,$io_pdf); // Imprimimos el encabezado de la p�gina
$la_result_dis= $io_report->uf_buscar_detalle_comprobaten($_SESSION["la_empresa"]["codemp"],$ls_procede,$ls_comprobante,$ld_fecha,"D");
$la_result_aum= $io_report->uf_buscar_detalle_comprobaten($_SESSION["la_empresa"]["codemp"],$ls_procede,$ls_comprobante,$ld_fecha,"A");

if ($la_result_dis->EOF && $la_result_aum->EOF){
	 print("<script language=JavaScript>");
	 print(" alert('No hay nada que Reportar');");
	 print(" close();");
	 print("</script>");
}
else{
	$la_data_detalle_dis = $io_report->uf_obtener_datadetalle($la_result_dis,$ld_tot_dis,true);
	uf_print_detalle($la_data_detalle_dis,"Partidas Cedentes","TOTAL PARTIDAS CEDENTES",$ld_tot_dis,&$io_pdf);
	$la_data_detalle_aum = $io_report->uf_obtener_datadetalle($la_result_aum,$ld_tot_aum,true);
	uf_print_detalle($la_data_detalle_aum,'Partidas Receptoras',"TOTAL PARTIDAS RECEPTORAS",$ld_tot_aum,&$io_pdf);
}

$io_pdf->ezStopPageNumbers(1,1);
if (isset($d) && $d)
{
	$ls_pdfcode = $io_pdf->ezOutput(1);
	$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
	echo '<html><body>';
	echo trim($ls_pdfcode);
	echo '</body></html>';
}
else
{
	$io_pdf->ezStream();
}
		 
unset($io_pdf);
unset($io_report);
?>