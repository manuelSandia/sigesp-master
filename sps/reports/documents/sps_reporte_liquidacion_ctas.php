<?Php
session_start();

require_once("../../../sps/class_folder/dao/sps_pro_liquidacion_dao.php");
require_once("../../../sps/class_folder/utilidades/class_function.php");
require_once("../../../shared/ezpdf/class.ezpdf.php");

$lo_liq_dao  = new sps_pro_liquidacion_dao();
$lo_function = new class_function();

   //-----------------------------------------------------------------------------------------------------------------------------------
	function uf_formato_estructura($as_codestpro, &$as_codestpro1, &$as_codestpro2, &$as_codestpro3,
	                               &$as_codestpro4,&$as_codestpro5)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_formato_estructura
		//		   Access: public
		//	    Arguments: $as_codestpro   // La estructura Presupuestaria completa
		//				   $as_codestpro1  // Codigo de Estrutura Presupuestaria 1
		//				   $as_codestpro2  // Codigo de Estrutura Presupuestaria 2
		//				   $as_codestpro3  // Codigo de Estrutura Presupuestaria 3
		//				   $as_codestpro4  // Codigo de Estrutura Presupuestaria 4
		//				   $as_codestpro5  // Codigo de Estrutura Presupuestaria 5
		//	  Description: Función que convierte la estructura presupuestaria completa y le da formato por nivel
		//	   Creado Por: Ing. Luiser Blanco
		// Fecha Creación: 04/01/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_empresa=$_SESSION["la_empresa"];
		$ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
		$li_longestpro1= (25-$ls_loncodestpro1)+1;
		$ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
		$li_longestpro2= (25-$ls_loncodestpro2)+1;
		$ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
		$li_longestpro3= (25-$ls_loncodestpro3)+1;
		$ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
		$li_longestpro4= (25-$ls_loncodestpro4)+1;
		$ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
		$li_longestpro5= (25-$ls_loncodestpro5)+1;
		$as_codestpro1= substr($as_codestpro,0,25);
		$as_codestpro2= substr($as_codestpro,25,25);
		$as_codestpro3= substr($as_codestpro,50,25);
		$as_codestpro4= substr($as_codestpro,75,25);
		$as_codestpro5= substr($as_codestpro,100,25);
		$as_codestpro1= substr($as_codestpro1,$li_longestpro1-1,$ls_loncodestpro1);
		$as_codestpro2= substr($as_codestpro2,$li_longestpro2-1,$ls_loncodestpro2);
		$as_codestpro3= substr($as_codestpro3,$li_longestpro3-1,$ls_loncodestpro3);
		$as_codestpro4= substr($as_codestpro4,$li_longestpro4-1,$ls_loncodestpro4);
		$as_codestpro5= substr($as_codestpro5,$li_longestpro5-1,$ls_loncodestpro5);
	}// end function uf_formato_estructura	

	//-----------------------------------------------------------------------------------------------------------------------------------

function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		    Acess: private
	//	    Arguments: as_titulo // Título del Reporte
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: función que imprime los encabezados por página
	//	   Creado Por: Ing. Maria Alejandra Roa
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(20,40,578,40);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
	$tm=330-($li_tm/2);
	$io_pdf->addText($tm,705,11,$as_titulo); // Agregar el título
	
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina  

//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_1($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_1
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>500,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,				  
				  		 'cols'=>array('numliq'=>array('justification'=>'left','width'=>400), // Justificación y ancho de la columna
						               'fecha'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
            $la_columna= array('numliq'=>'<b>    Liquidación Nº </b>',
			         	       'fecliq'=>'<b>  Fecha  </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_1
//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera_2($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_2
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>500,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,				  
				  		 'cols'=>array('nomper'=>array('justification'=>'left','width'=>400), // Justificación y ancho de la columna
						               'cedper'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
            $la_columna= array('nomper'=>'<b>  Apellidos y Nombres </b>',
			         	       'cedper'=>'<b> Cédula </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_2
//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_3($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_3
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>500,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,				  
				  		 'cols'=>array('descargo'=>array('justification'=>'left','width'=>250), // Justificación y ancho de la columna
						               'desuniadm'=>array('justification'=>'left','width'=>250))); // Justificación y ancho de la columna
            $la_columna= array('descargo'=>'<b>  Cargo </b>',
			         	       'desuniadm'=>'<b>  Dependencia </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_3
//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_4($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_4
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>500,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,				  
				  		 'cols'=>array('fecing'=>array('justification'=>'center','width'=>125),  // Justificación y ancho de la columna
						               'fecegr'=>array('justification'=>'center','width'=>125),  // Justificación y ancho de la columna
									   'anoser'=>array('justification'=>'center','width'=>84),   // Justificación y ancho de la columna
									   'messer'=>array('justification'=>'center','width'=>83),   // Justificación y ancho de la columna
						               'diaser'=>array('justification'=>'center','width'=>83))); // Justificación y ancho de la columna
            $la_columna= array('fecing'=>'<b> Fecha Ingreso </b>',
			         	       'fecegr'=>'<b> Fecha Egreso </b>',
							   'anoser'=>'<b> Años </b>',
							   'messer'=>'<b> Meses </b>',
							   'diaser'=>'<b> Días </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_4
//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_5($la_data,&$io_pdf)
{
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //       Function: uf_print_cabecera_5
 //		   Access: private
 //	    Arguments: io_pdf // Objeto PDF
 //    Description: función que imprime la cabecera de la página
 //	   Creado Por: Ing. Maria Alejandra Roa
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
         $la_config=array('showHeadings'=>1,      // Mostrar encabezados
						 'fontSize' => 6,         // Tamaño de Letras
						 'titleFontSize' => 9,    // Tamaño de Letras de los títulos
						 'showLines'=>1,          // Mostrar Líneas
						 'shaded'=>0,             // Sombra entre líneas
						 'width'=>500,            // Ancho de la tabla
						 'maxWidth'=>500,         // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,				  
				  		 'cols'=>array('dencauret'=>array('justification'=>'center','width'=>125),   // Justificación y ancho de la columna
						               'sueproper'=>array('justification'=>'center','width'=>125),   // Justificación y ancho de la columna
									   'salint'=>array('justification'=>'center','width'=>125),     // Justificación y ancho de la columna
						               'suediaper'=>array('justification'=>'center','width'=>125))); // Justificación y ancho de la columna
            $la_columna= array('dencauret'=>'<b> Motivo de Pago </b>',
							   'sueproper'=>'<b> Sueldo Promedio </b>',
			         	       'salint'=>'<b> Sueldo Integral </b>',
							   'suediaper'=>'<b> Sueldo Diario </b>');
              $io_pdf->ezTable($la_data,$la_columna,'',$la_config);

}// end function uf_print_cabecera_5
//-----------------------------------------------------------------------------------------------------------------------------------//
function uf_print_cabecera_6(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_6
		//		   Access: private
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función que imprime parte de la cabecera
		//	   Creado Por: Ing. Maria Alejandra Roa
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'                                                                                                            <b>ESPECIFICACIONES</b> '));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1,    // Mostrar Líneas
						 'fontSize' => 7,   // Tamaño de Letras
						 'shaded'=>2,       // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500,     // Ancho de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,  
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezSetDy(-1);
	}// end function uf_print_cabecera_6
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data         // arreglo de información
		//	   			   io_pdf          // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Maria Alejandra Roa
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_config=array('showHeadings'=>1,             // Mostrar encabezados
						 'fontSize' => 7,               // Tamaño de Letras
						 'titleFontSize' => 10,         // Tamaño de Letras de los títulos
						 'showLines'=>1,                // Mostrar Líneas
						 'shaded'=>0,                   // Sombra entre líneas
						 'width'=>500,                  // Ancho de la tabla
						 'maxWidth'=>500,               // Ancho Máximo de la tabla
						 'xOrientation'=>'center',      // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,
						 'cols'=>array('numespliq'=>array('justification'=>'center','width'=>50),        // Justificación y ancho de la columna
						 			   'desespliq'=>array('justification'=>'left','width'=>300),   // Justificación y ancho de la columna
						 			   'diapag'=>array('justification'=>'center','width'=>60),      // Justificación y ancho de la columna
									   'subtotal'=>array('justification'=>'right','width'=>90)));       // Justificación y ancho de la columna
		$la_columnas=array('numespliq'=>'<b> Nº </b>',
						   'desespliq'=>'<b>  Descripción</b>',
						   'diapag'=>'<b>Días</b>',
						   'subtotal'=>'<b>Monto                </b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle   
//-----------------------------------------------------------------------------------------------------------------------------------//	
function uf_print_total($ld_total,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_total
	//		    Acess : private
	//	    Arguments : $ld_total
	//    Description : función que imprime el total de asignaciones
	//	   Creado Por : Ing. Maria Alejandra Roa
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_data=array(array('total'=>'<b> TOTAL MONTO A PAGAR   </b>','totalasig'=>$ld_total));
	$la_columna=array('total'=>'','totalasig'=>'');
	$la_config=array('showHeadings'=>0,      // Mostrar encabezados
					 'fontSize' => 7,        // Tamaño de Letras
					 'showLines'=>1,         // Mostrar Líneas
					 'shaded'=>0,            // Sombra entre líneas
					 'width'=>500,           // Ancho Máximo de la tabla
					 'rowGap'=>2,
					 'colGap'=>2,
					 'xOrientation'=>'center', // Orientación de la tabla
			 		 'cols'=>array('total'=>array('justification'=>'right','width'=>410), // Justificación y ancho de la columna
					 			   'totalasig'=>array('justification'=>'right','width'=>90))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
    //$io_pdf->set_margenes($superior,$inferior,$izquierdo,$derecho);
    //$io_pdf->set_margenes(80,25,25,15);
}// end function uf_print_total

function uf_print_texto(&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_texto
	//		    Acess : private
	//	    Arguments : 
	//    Description : función que imprime el texto del reporte
	//	   Creado Por : Ing. Maria Alejandra Roa
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
	$io_pdf->addText(60,350,8,"");
	$io_pdf->addText(60,340,8,"Yo, ________________________________ portador(a) de la cédula de identidad Nº_________________, por medio de la presente ");
    $io_pdf->addText(60,320,8,"declaro que he recibido a mi entera satisfacción de '".$_SESSION["la_empresa"]["nombre"]."' las cantidades arriba  descritas y nada tengo ");
	$io_pdf->addText(60,300,8,"que reclamar por estos o cualquier otro concepto derivado de la terminación  de la relación laboral, firmando conforme.");
	$io_pdf->addText(60,280,8,"  ");
	$io_pdf->addText(60,260,7,"                                                                 firma _________________________ ");
	$io_pdf->addText(60,150,8, "");
	$io_pdf->addText(60,130,8,"Revisado por: _________________________"."           "." Aprobado Por: _________________________");
	
}// end function uf_print_texto
function uf_print_detalle_cuentas($la_data,$ai_total,&$io_pdf )
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_texto
	//		    Acess : private
	//	    Arguments : 
	//    Description : función que imprime el texto del reporte
	//	   Creado Por : Ing. Maria Alejandra Roa
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_pdf->ezSetDy(-5);
	$la_data1=array(array('name'=>'                                                                                                    <b>AFECTACIÓN PRESUPUESTARIA</b> '));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'showLines'=>1,    // Mostrar Líneas
					 'fontSize' => 7,   // Tamaño de Letras
					 'shaded'=>2,       // Sombra entre líneas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'xOrientation'=>'center', // Orientación de la tabla
					 'width'=>500,     // Ancho de la tabla
					 'rowGap'=>2,
					 'colGap'=>2,  
					 'maxWidth'=>500); // Ancho Máximo de la tabla
	$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
	$io_pdf->ezSetDy(-1);
  	unset($la_data1);
  	unset($la_columna);
  	unset($la_config);
	$la_columnas=array('codprouniadm'=>'<b>Estructura Presupuestaria</b>',
					   'cueprefid'=>'<b>Partida</b>',
					   'totpagliq'=>'<b>Monto</b>                  ');
	$la_config=array('showHeadings'=>1,             // Mostrar encabezados
						 'fontSize' => 7,               // Tamaño de Letras
						 'titleFontSize' => 10,         // Tamaño de Letras de los títulos
						 'showLines'=>1,                // Mostrar Líneas
						 'shaded'=>0,                   // Sombra entre líneas
						 'width'=>500,                  // Ancho de la tabla
						 'maxWidth'=>500,               // Ancho Máximo de la tabla
						 'xOrientation'=>'center',      // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,
						 'cols'=>array('codprouniadm'=>array('justification'=>'center','width'=>350),        // Justificación y ancho de la columna
						 			   'cueprefid'=>array('justification'=>'center','width'=>60),   // Justificación y ancho de la columna
									   'totpagliq'=>array('justification'=>'right','width'=>90)));       // Justificación y ancho de la columna
	
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
  	unset($la_data);
  	unset($la_columnas);
  	unset($la_config);
	$la_data1=array(array('name'=>'<b>TOTAL</b> ','monto'=>'<b>'.$ai_total.'</b> '));
	$la_columna=array('name'=>'','monto'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'showLines'=>1,    // Mostrar Líneas
					 'fontSize' => 7,   // Tamaño de Letras
					 'shaded'=>2,       // Sombra entre líneas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'xOrientation'=>'center', // Orientación de la tabla
					 'width'=>500,     // Ancho de la tabla
					 'rowGap'=>2,
					 'colGap'=>2,  
					 'maxWidth'=>500, // Ancho Máximo de la tabla
					 'cols'=>array('name'=>array('justification'=>'right','width'=>410),   // Justificación y ancho de la columna
								   'monto'=>array('justification'=>'right','width'=>90)));       // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
	$io_pdf->ezSetDy(-1);
  	unset($la_data1);
  	unset($la_columna);
  	unset($la_config);
	
}// end function uf_print_texto
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas_liq($la_data,$ai_totald,$ai_totalh,&$io_pdf)
	{ 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas_liq
		//		    Acess: private
		//	    Arguments: la_data         // arreglo de información
		//	   			   io_pdf          // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Maria Alejandra Roa
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_data1=array(array('name'=>'                                                                                                    <b>AFECTACIÓN CONTABLE</b> '));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1,    // Mostrar Líneas
						 'fontSize' => 7,   // Tamaño de Letras
						 'shaded'=>2,       // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500,     // Ancho de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,  
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		$io_pdf->ezSetDy(-1);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);

		$la_config=array('showHeadings'=>1,             // Mostrar encabezados
						 'fontSize' => 7,               // Tamaño de Letras
						 'titleFontSize' => 10,         // Tamaño de Letras de los títulos
						 'showLines'=>1,                // Mostrar Líneas
						 'shaded'=>0,                   // Sombra entre líneas
						 'width'=>500,                  // Ancho de la tabla
						 'maxWidth'=>500,               // Ancho Máximo de la tabla
						 'xOrientation'=>'center',      // Orientación de la tabla
						 'rowGap'=>2,
						 'colGap'=>2,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>320),        // Justificación y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>90),   // Justificación y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>90)));       // Justificación y ancho de la columna
		$la_columnas=array('cuenta'=>'<b> Cuenta </b>',
						   'debe'=>'<b>  Debe </b>',
						   'haber'=>'<b>  Haber  </b>');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
  	unset($la_data);
  	unset($la_columnas);
  	unset($la_config);
	$la_data1=array(array('name'=>'<b>TOTAL</b> ','montod'=>'<b>'.$ai_totald.'</b> ','montoh'=>'<b>'.$ai_totalh.'</b> '));
	$la_columna=array('name'=>'','montod'=>'','montoh'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'showLines'=>1,    // Mostrar Líneas
					 'fontSize' => 7,   // Tamaño de Letras
					 'shaded'=>2,       // Sombra entre líneas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'xOrientation'=>'center', // Orientación de la tabla
					 'width'=>500,     // Ancho de la tabla
					 'rowGap'=>2,
					 'colGap'=>2,  
					 'maxWidth'=>500, // Ancho Máximo de la tabla
					 'cols'=>array('name'=>array('justification'=>'right','width'=>320),   // Justificación y ancho de la columna
								   'montod'=>array('justification'=>'right','width'=>90),       // Justificación y ancho de la columna
								   'montoh'=>array('justification'=>'right','width'=>90)));       // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
	$io_pdf->ezSetDy(-1);
  	unset($la_data1);
  	unset($la_columna);
  	unset($la_config);
	}// end function uf_print_detalle   

//-----------------------------------------------------------------------------------------------------------------------------------//
//                                                      Datos del reporte
//-----------------------------------------------------------------------------------------------------------------------------------//
$ls_empresa = $_SESSION["la_empresa"]["nombre"];
$ls_codper  = $_GET["codper"];
$ls_nomper  = $_GET["nomper"];
$ls_codnom  = $_GET["codnom"];
$ls_numliq  = $_GET["numliq"];
$ls_titulo  = "<b> Liquidación de Prestaciones Sociales </b>";
//-----------------------------------------------------------------------------------------------------------------------------------// 

//-----------------------------------------------------------------------------------------------------------------------------------//
  error_reporting(E_ALL);
  $io_pdf = new Cezpdf('LETTER','portrait');                         // Instancia de la clase PDF
  $io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');     // Seleccionamos el tipo de letra
  $io_pdf->ezSetCmMargins(4.5,3,3,3);                                // Configuración de los margenes en centímetros
  uf_print_encabezado_pagina($ls_titulo,$io_pdf);                    // Imprimimos el encabezado de la página
  $io_pdf->ezStartPageNumbers(550,50,10,'','',1);                    // Insertar el número de página
  
  $lb_valido=$lo_liq_dao->getCabeceraLiquidacion($ls_codper,$ls_codnom,$ls_numliq,$la_array);
 
  if ($lb_valido==false) // Existe algún error ó no hay registros
  {
		print("<script language=JavaScript>");
		print(" alert('No existen datos a Reportar.');");
		print(" close();");
		print("</script>");
  }
  else
  {
  	   $io_pdf->transaction('start'); // Iniciamos la transacción
	   $thisPageNum=$io_pdf->ezPageCount; 
	  	  
	   $ls_cedper = $la_array["cedper"][0];
	   $ls_fecliq = $lo_function->uf_dtoc($la_array["fecliq"][0]);
	   $ls_fecing = $lo_function->uf_dtoc($la_array["fecing"][0]);
	   $ls_fecegr = $lo_function->uf_dtoc($la_array["fecegr"][0]);
	   $ls_cargo  = $la_array["descargo"][0];
	   $li_anoser = $la_array["anoser"][0];
	   $li_messer = $la_array["messer"][0];
	   $li_diaser = $la_array["diaser"][0];
	   $ls_dencauret = $la_array["dencauret"][0];
	   $ld_salint    = $lo_function->uf_ntoc($la_array["salint"][0], 2);
	   $ld_sueproper = $lo_function->uf_ntoc($la_array["sueproper"][0], 2);
	   $ld_suediaper = $la_array["salint"][0]/30;
	   $ld_suediaper = $lo_function->uf_ntoc($ld_suediaper, 2);
	   $ls_desuniadm = $la_array["desuniadm"][0];
	   
	    $la_data[0]=array('numliq'=>$ls_numliq,'fecliq'=>$ls_fecliq);
		uf_print_cabecera_1($la_data,$io_pdf);
		$la_data2[0]=array('nomper'=>$ls_nomper,'cedper'=>$ls_cedper);
		uf_print_cabecera_2($la_data2,$io_pdf);
		$la_data3[0]=array('descargo'=>$ls_cargo,'desuniadm'=>$ls_desuniadm);
		uf_print_cabecera_3($la_data3,$io_pdf);
        $la_data4[0]=array('fecing'=>$ls_fecing,'fecegr'=>$ls_fecegr,'anoser'=>$li_anoser,'messer'=>$li_messer,'diaser'=>$li_diaser);
		uf_print_cabecera_4($la_data4,$io_pdf);
		$la_data5[0]=array('dencauret'=>$ls_dencauret,'sueproper'=>$ld_sueproper,'salint'=>$ld_salint,'suediaper'=>$ld_suediaper);
		uf_print_cabecera_5($la_data5,$io_pdf);
		uf_print_cabecera_6($io_pdf);
		
		$lb_valido=$lo_liq_dao->getDetalleLiquidacionReporte($ls_codper,$ls_codnom,$ls_numliq,$la_detalle);
        if ($lb_valido)
		{   $ld_total=0;
			$li_totrow_det=count($la_detalle["numespliq"]);
			for($li_d=0;$li_d<$li_totrow_det;$li_d++)
			{
			    $li_numespliq = $la_detalle["numespliq"][$li_d];
				$ls_desespliq = $la_detalle["desespliq"][$li_d];
				$ld_diapag    = $lo_function->uf_ntoc($la_detalle["diapag"][$li_d], 2);
				$ld_subtotal  = $lo_function->uf_ntoc($la_detalle["subtotal"][$li_d], 2);
				$ld_total     = $ld_total+($la_detalle["subtotal"][$li_d]);
				$la_data[$li_d]=array('numespliq'=>$li_numespliq,'desespliq'=>$ls_desespliq,'diapag'=>$ld_diapag,'subtotal'=>$ld_subtotal);
			}
			uf_print_detalle($la_data,$io_pdf);            // Imprimimos el detalle 
			$ld_total = $lo_function->uf_ntoc($ld_total, 2);
			uf_print_total($ld_total,$io_pdf);             // Imprimimos el total
		}
		$li_totaldebe=0;
		$li_totalhaber=0;
		$lb_valido=$lo_liq_dao->getEstructuraPresupuestaria($ls_codper,$ls_codnom,$ls_numliq,$ls_fecliq,$la_ctaspg);
		if ($lb_valido)
		{
			$li_totrow_det=count($la_ctaspg["codestpro1"]);
			for($li_d=0;$li_d<$li_totrow_det;$li_d++)
			{
				$ls_programatica = $la_ctaspg["codestpro1"][$li_d].$la_ctaspg["codestpro2"][$li_d].$la_ctaspg["codestpro3"][$li_d].$la_ctaspg["codestpro4"][$li_d].$la_ctaspg["codestpro5"][$li_d];
				uf_formato_estructura($ls_programatica,$ls_codest1,$ls_codest2,$ls_codest3,$ls_codest4,$ls_codest5);
				$ls_programatica=$ls_codest1.'-'.$ls_codest2.'-'.$ls_codest3;
				switch($_SESSION["la_empresa"]["estmodest"])
				{
					case "2": // Modalidad por Programa
						
						$ls_programatica=$ls_codest5.'-'.$ls_codest2.'-'.$ls_codest3.'-'.$ls_codest4.'-'.$ls_codest5;
						break;
				}
				$ls_cueprefid = trim($la_ctaspg["spg_cuenta"][$li_d]);
				$ls_scg_cuenta = trim($la_ctaspg["sc_cuenta"][$li_d]);
				$ld_totpagliq = $lo_function->uf_ntoc($la_ctaspg["monto"][$li_d],2);
				$la_cta_spg[$li_d]=array('codprouniadm'=>$ls_programatica,'cueprefid'=>$ls_cueprefid,'totpagliq'=>$ld_totpagliq);
				$la_cta_sc[$li_d]=array('cuenta'=>$ls_scg_cuenta,'debe'=>$ld_totpagliq,'haber'=>'0,00');
				$li_totaldebe=$li_totaldebe+$la_ctaspg["monto"][$li_d];
			}
			uf_print_detalle_cuentas($la_cta_spg,$lo_function->uf_ntoc($li_totaldebe,2),$io_pdf);
			$lb_valido=$lo_liq_dao->getDetalleContableLiq($ls_codper,$ls_codnom,$ls_numliq,$ls_fecliq,$la_ctasc);
			if ($lb_valido)
			{
				$li_totrow_det=count($la_ctasc["sc_cuenta"]);
				for($li_j=0;$li_j<$li_totrow_det;$li_j++)
				{
					$ls_scg_cuenta = trim($la_ctasc["sc_cuenta"][$li_j]);
					$ls_scg_beneficiario = trim($la_ctasc["cuentabene"][$li_j]);
					if ($ls_scg_cuenta!="")
					{
						$ld_totpagliq = $lo_function->uf_ntoc(abs($la_ctasc["monto"][$li_j]),2);
						$la_cta_sc[$li_d]=array('cuenta'=>$ls_scg_cuenta,'debe'=>'0,00','haber'=>$ld_totpagliq);
						$li_d++;
						$li_totalhaber=$li_totalhaber+abs($la_ctasc["monto"][$li_j]);
					}
				}
				$ld_saldo=$li_totaldebe-$li_totalhaber;
				$la_cta_sc[$li_d]=array('cuenta'=>$ls_scg_beneficiario,'debe'=>'0,00','haber'=>$lo_function->uf_ntoc($ld_saldo,2));
				uf_print_detalle_cuentas_liq($la_cta_sc,$lo_function->uf_ntoc($li_totaldebe,2),$lo_function->uf_ntoc($li_totalhaber+$ld_saldo,2),$io_pdf);
			}
		}	
	    
  } //end else
  if ($lb_valido) // Si no ocurrio ningún error
  {
	 $io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
	 $io_pdf->ezStream();             // Mostramos el reporte
   }
   else  // Si hubo algún error
   {
		 print("<script language=JavaScript>");
		 print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
		 print(" close();");
		 print("</script>");		
	}
   unset($io_pdf);

//unset($lo_reporte_base);
unset($lo_function);			
  

?>
