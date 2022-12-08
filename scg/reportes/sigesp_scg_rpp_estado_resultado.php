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
	//	    Arguments: as_titulo // Tï¿½tulo del Reporte
	//    Description: funciï¿½n que guarda la seguridad de quien generï¿½ el reporte
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creaciï¿½n: 22/09/2006
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $io_fun_scg;

	$ls_descripcion="Generó el Reporte ".$as_titulo;
	$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_estado_resultado.php",$ls_descripcion);
	return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_encabezado_pagina($as_titulo,$as_titulo1,$as_titulo2,$as_titulo3,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		    Acess: private
	//	    Arguments: as_titulo // Tï¿½tulo del Reporte
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: funciï¿½n que imprime los encabezados por pï¿½gina
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaciï¿½n: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(20,40,578,40);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
	$tm=330-($li_tm/2);
	$io_pdf->addText($tm,705,11,$as_titulo); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo1);
	$tm=330-($li_tm/2);
	$io_pdf->addText($tm,690,11,$as_titulo1); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
	$tm=330-($li_tm/2);
	$io_pdf->addText($tm,680,11,$as_titulo2); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo3);
	$tm=330-($li_tm/2);
	$io_pdf->addText($tm,670,11,$as_titulo3); // Agregar el tï¿½tulo

	$io_pdf->addText(510,725,7,$_SESSION["ls_database"]); // Agregar la Base de datos
	$io_pdf->addText(510,715,8,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(510,705,8,date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera_ingreso(&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private
	//	    Arguments: io_pdf // Objeto PDF
	//    Description: funciï¿½n que imprime la cabecera de cada pï¿½gina
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaciï¿½n: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_data=array(array('name'=>'<b>INGRESOS</b> '));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'shadeCol2'=>array(0.7,0.7,0.7), // Color de la sombra
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'width'=>500, // Ancho de la tabla
						 'rowGap'=>2,
						 'colGap'=>3,
						 'maxWidth'=>500); // Ancho Mï¿½ximo de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->ezSetDy(-1);
}// end function uf_print_cabecera
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera_egreso(&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera_egreso
	//		   Access: private
	//	    Arguments: io_pdf // Objeto PDF
	//    Description: funciï¿½n que imprime la cabecera de cada pï¿½gina
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaciï¿½n: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$la_data=array(array('name'=>'<b>EGRESOS</b> '));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'shadeCol2'=>array(0.7,0.7,0.7), // Color de la sombra
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'width'=>500, // Ancho de la tabla
						 'rowGap'=>2,
						 'colGap'=>3,
						 'maxWidth'=>500); // Ancho Mï¿½ximo de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->ezSetDy(-1);
}// end function uf_print_cabecera
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle_ingreso($la_data,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private
	//	    Arguments: la_data // arreglo de informaciï¿½n
	//	   			   io_pdf // Objeto PDF
	//    Description: funciï¿½n que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaciï¿½n: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'titleFontSize' => 10,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'rowGap'=>2,
						 'colGap'=>3,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>90), // Justificaciï¿½n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>110), // Justificaciï¿½n y ancho de la columna
						 			   'saldomay'=>array('justification'=>'right','width'=>100), // Justificaciï¿½n y ancho de la columna
						 			   'saldomen'=>array('justification'=>'right','width'=>100), // Justificaciï¿½n y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>100))); // Justificaciï¿½n y ancho de la columna
	$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'saldomay'=>'<b>Saldo</b>',
						   'saldomen'=>'<b></b>',
						   'saldo'=>'<b></b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle_egreso($la_data_egr,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private
	//	    Arguments: la_data // arreglo de informaciï¿½n
	//	   			   io_pdf // Objeto PDF
	//    Description: funciï¿½n que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaciï¿½n: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'titleFontSize' => 10,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'rowGap'=>2,
						 'colGap'=>3,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>90), // Justificaciï¿½n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>110), // Justificaciï¿½n y ancho de la columna
						 			   'saldomay'=>array('justification'=>'right','width'=>100), // Justificaciï¿½n y ancho de la columna
						 			   'saldomen'=>array('justification'=>'right','width'=>100), // Justificaciï¿½n y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>100))); // Justificaciï¿½n y ancho de la columna
	$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'saldomay'=>'<b>Saldo</b>',
						   'saldomen'=>'<b></b>',
						   'saldo'=>'<b></b>');
	$io_pdf->ezTable($la_data_egr,$la_columnas,'',$la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_pie_cabecera_ingreso($ld_total_ingresos,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera
	//		    Acess : private
	//	    Arguments : ad_totaldebe // Total debe
	//    Description : funciï¿½n que imprime el fin de la cabecera de cada pï¿½gina
	//	   Creado Por : Ing. Yozelin Barragan
	// Fecha Creaciï¿½n : 18/02/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_bolivares;

	$la_data=array(array('total'=>'<b>Total Ingreso '.$ls_bolivares.'</b>','saldomay'=>$ld_total_ingresos));
	$la_columna=array('total'=>'','saldomay'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>500, // Ancho Mï¿½ximo de la tabla
						 'rowGap'=>2,
						 'colGap'=>3,
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>300), // Justificaciï¿½n y ancho de la columna
						 			   'saldomay'=>array('justification'=>'right','width'=>200))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------





function uf_print_pie_cabecera_firmas_fin_pagina(&$io_pdf,$firma1,$firma2,$firma3)
{
	// cuadro inferior
	
$valor=$io_pdf->y;
if($io_pdf->y<160){
$io_pdf->ezNewPage();
}
       
		$io_pdf->addText(157,122,7,"<b>________________________________________</b>"); // Agregar el título
                $io_pdf->addText(160,110,7,$firma1); // Agregar el título
		
		$io_pdf->addText(375,122,7,"<b>________________________________________</b>"); // Agregar el título
		$io_pdf->addText(430,110,7,$firma2); // Agregar el título

		$io_pdf->addText(157,75,7,"<b>________________________________________</b>");
		$io_pdf->addText(185,63,7,$firma3); // Agregar el título
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
	
}// end function uf_print_pie_cabecera





function uf_print_pie_cabecera_espaciofirmas(&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera
	//		    Acess : private
	//	    Arguments : ad_totaldebe // Total debe
	//    Description : funciï¿½n que imprime el fin de la cabecera de cada pï¿½gina
	//	   Creado Por : Ing. Yozelin Barragan
	// Fecha Creaciï¿½n : 18/02/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_bolivares;

	$la_data=array(array('espacio'=>''));
	$la_columna=array('espacio'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>400, // Ancho Mï¿½ximo de la tabla
						 'rowGap'=>20,
						 'colGap'=>30,
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('espacio'=>array('justification'=>'center','width'=>200))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera



function uf_print_pie_cabecera_firmas1eralinea(&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera
	//		    Acess : private
	//	    Arguments : ad_totaldebe // Total debe
	//    Description : funciï¿½n que imprime el fin de la cabecera de cada pï¿½gina
	//	   Creado Por : Ing. Yozelin Barragan
	// Fecha Creaciï¿½n : 18/02/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_bolivares;

	$la_data=array(array('firma1'=>'<b>________________________________________</b>','firma2'=>'<b>________________________________________</b>'));
	$la_columna=array('firma1'=>'','firma2'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>400, // Ancho Mï¿½ximo de la tabla
						 'rowGap'=>2,
						 'colGap'=>3,
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>200), // Justificaciï¿½n y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>200))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera


function uf_print_pie_cabecera_firmas1eralinea1($firma1,$firma2,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera
	//		    Acess : private
	//	    Arguments : ad_totaldebe // Total debe
	//    Description : funciï¿½n que imprime el fin de la cabecera de cada pï¿½gina
	//	   Creado Por : Ing. Yozelin Barragan
	// Fecha Creaciï¿½n : 18/02/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_bolivares;

	$la_data=array(array('firma1'=>'<b> '.$firma1.' </b>','firma2'=>'<b> '.$firma2.' </b>'));
	$la_columna=array('firma1'=>'','firma2'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>400, // Ancho Mï¿½ximo de la tabla
						 'rowGap'=>2,
						 'colGap'=>3,
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>200), // Justificaciï¿½n y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>200))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera


function uf_print_pie_cabecera_firmas2dalinea(&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera
	//		    Acess : private
	//	    Arguments : ad_totaldebe // Total debe
	//    Description : funciï¿½n que imprime el fin de la cabecera de cada pï¿½gina
	//	   Creado Por : Ing. Yozelin Barragan
	// Fecha Creaciï¿½n : 18/02/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_bolivares;

	$la_data=array(array('firma3'=>'<b>________________________________________</b>','firma4'=>'<b>                                        </b>'));
	$la_columna=array('firma3'=>'','firma4'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>400, // Ancho Mï¿½ximo de la tabla
						 'rowGap'=>2,
						 'colGap'=>3,
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('firma3'=>array('justification'=>'center','width'=>200), // Justificaciï¿½n y ancho de la columna
						 			   'firma4'=>array('justification'=>'center','width'=>200))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera


function uf_print_pie_cabecera_firmas2dalinea2($firma3,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera
	//		    Acess : private
	//	    Arguments : ad_totaldebe // Total debe
	//    Description : funciï¿½n que imprime el fin de la cabecera de cada pï¿½gina
	//	   Creado Por : Ing. Yozelin Barragan
	// Fecha Creaciï¿½n : 18/02/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_bolivares;

	$la_data=array(array('firma3'=>'<b> '.$firma3.' </b>','firma4'=>'<b>  </b>'));
	$la_columna=array('firma3'=>'','firma4'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>400, // Ancho Mï¿½ximo de la tabla
						 'rowGap'=>2,
						 'colGap'=>3,
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('firma3'=>array('justification'=>'center','width'=>200), // Justificaciï¿½n y ancho de la columna
						 			   'firma4'=>array('justification'=>'center','width'=>200))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_pie_cabecera_egreso($ld_total_egresos,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera_egreso
	//		    Acess : private
	//	    Arguments : ld_total_egresos // Total debe
	//    Description : funciï¿½n que imprime el fin de la cabecera de cada pï¿½gina
	//	   Creado Por : Ing. Yozelin Barragan
	// Fecha Creaciï¿½n : 18/02/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_bolivares;

	$la_data=array(array('total'=>'<b>Total Egreso '.$ls_bolivares.'</b>','saldomay'=>$ld_total_egresos));
	$la_columna=array('total'=>'','saldomay'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'rowGap'=>2,
						 'colGap'=>3,
						 'width'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>300), // Justificaciï¿½n y ancho de la columna
						 			   'saldomay'=>array('justification'=>'right','width'=>200))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_pie_cabecera($ld_total,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera_egreso
	//		    Acess : private
	//	    Arguments : ld_total // Total
	//    Description : funciï¿½n que imprime el fin de la cabecera de cada pï¿½gina
	//	   Creado Por : Ing. Yozelin Barragan
	// Fecha Creaciï¿½n : 18/02/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_bolivares;
	/*if($ld_total<0)
	{
		//$ls_cadena="DESAHORRO";
	}
	else
	{
		//$ls_cadena="AHORRO";
	}*/
	$ls_cadena="RESULTADO DEL EJERCICIO";
	$la_data=array(array('total'=>'<b>Total ('.$ls_cadena.') '.$ls_bolivares.'</b>','saldomay'=>$ld_total));
	$la_columna=array('total'=>'','saldomay'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaï¿½o de Letras
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'rowGap'=>2, // ancho entre lineas 
						 'colGap'=>3, //ancho entre  columnas
						 'width'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>300), // Justificaciï¿½n y ancho de la columna
						 			   'saldomay'=>array('justification'=>'right','width'=>200))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$la_data=array(array('name'=>''));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center'); // Orientaciï¿½n de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera
//--------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_init_niveles()
{	///////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function: uf_init_niveles
//	     Access: public
//	    Returns: vacio
//	Description: Este mï¿½todo realiza una consulta a los formatos de las cuentas
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

require_once("../../shared/ezpdf/class.ezpdf.php");
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
		$ls_bolivares ="Bolívares";
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
//--------------------------------------------------  Parï¿½metros para Filtar el Reporte  -----------------------------------------
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
	$ls_titulo="<b> ESTADO DE RENDIMIENTO FINANCIERO</b>";
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
			$ls_titulo="<b> ESTADO DE RENDIMIENTO FINANCIERO TRIMESTRAL(ENERO-MARZO)</b>";;
			break;

		case '04':
			$ls_titulo="<b> ESTADO DE RENDIMIENTO FINANCIERO TRIMESTRAL(ABRIL-JUNIO)</b>";;
			break;

		case '07':
			$ls_titulo="<b> ESTADO DE RENDIMIENTO FINANCIERO TRIMESTRAL(JULIO-SEPTIEMBRE)</b>";;
			break;

		case '10':
			$ls_titulo="<b> ESTADO DE RENDIMIENTO FINANCIERO TRIMESTRAL(OCTUBRE-DICIEMBRE)</b>";;
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
//----------------------------------------------------  Parï¿½metros del encabezado  -----------------------------------------------
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);
$ls_nombre=$_SESSION["la_empresa"]["nombre"];
$ld_fecdes=$io_funciones->uf_convertirfecmostrar($fecdes);
$ld_fechas=$io_funciones->uf_convertirdatetobdletras($fechas);
$ls_costodesde = $_GET['costodesde'];
$ls_costohasta = $_GET['costohasta'];
$ls_titulo="<b>xxxxxx</b>";
$ls_titulo1="<b> ESTADO DE RENDIMIENTO FINANCIERO </b>";
//$ls_titulo1="<b> ".$ls_nombre." </b>";
$ls_titulo2="<b> al ".$ld_fechas."</b>";
$ls_titulo3="<b>(Expresado en ".$ls_bolivares.")</b>";
// $ls_titulo2=" del  ".$ld_fecdes."  al  ".$ld_fechas." </b>";
//--------------------------------------------------------------------------------------------------------------------------------
// Cargar datastore con los datos del reporte
error_reporting(E_ALL);
$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
$io_pdf->ezSetCmMargins(4.5,3,3,3); // Configuraciï¿½n de los margenes en centï¿½metros
uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$ls_titulo2,$ls_titulo3,$io_pdf); // Imprimimos el encabezado de la pï¿½gina
$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el nï¿½mero de pï¿½gina
$lb_valido=uf_insert_seguridad("<b>Estado de Resultado en PDF</b>"); // Seguridad de Reporte
if($lb_valido)
{
	$lb_valido_ing=$io_report->uf_scg_reporte_estado_de_resultado_ingreso($ldt_fecdes,$ldt_fechas,$li_nivel,$ls_costodesde,$ls_costohasta);
	$lb_valido_egr=$io_report->uf_scg_reporte_estado_de_resultado_egreso($ldt_fecdes,$ldt_fechas,$li_nivel,$ls_costodesde,$ls_costohasta);
}
if((($lb_valido_ing==false)&&($lb_valido_egr==false))||($lb_valido==false)) // Existe algï¿½n error ï¿½ no hay registros
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
			$io_pdf->transaction('start'); // Iniciamos la transacciï¿½n
			$thisPageNum=$io_pdf->ezPageCount;
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
		uf_print_cabecera_ingreso($io_pdf);
		uf_print_detalle_ingreso($la_data,$io_pdf); // Imprimimos el detalle
		$ld_total_ingresos=abs($ld_total_ingresos);
		$ld_total_ingresos=number_format($ld_total_ingresos,2,",",".");
		uf_print_pie_cabecera_ingreso($ld_total_ingresos,$io_pdf); // Imprimimos pie de la cabecera
	}//if($lb_valido_ing)
	if($lb_valido_egr)
	{
		$li_tot=$io_report->dts_egresos->getRowCount("sc_cuenta");
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
			//$io_pdf->transaction('start'); // Iniciamos la transacciï¿½n
			$thisPageNum=$io_pdf->ezPageCount;
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
		uf_print_cabecera_egreso($io_pdf);
		uf_print_detalle_egreso($la_data_egr,$io_pdf); // Imprimimos el detalle
		if($lb_valido_ing)
		{
			$ld_total_ingresos=str_replace('.','',$ld_total_ingresos);
			$ld_total_ingresos=str_replace(',','.',$ld_total_ingresos);
		}
		else
		{
			$ld_total_ingresos=0;
		}
		$ld_total_egresos=abs($ld_total_egresos);
		$ld_total=trim($ld_total_ingresos)-($ld_total_egresos);
		$ld_total_egresos=number_format($ld_total_egresos,2,",",".");
		uf_print_pie_cabecera_egreso($ld_total_egresos,$io_pdf); // Imprimimos pie de la cabecera
		$ld_total=number_format($ld_total,2,",",".");
		$firma1=" GERENTE DE ADMINITRACIÓN  Y FINANZAS ";
		$firma2=" PRESIDENTE ";
		$firma3=" OFICINA DE CONTABILIDAD ";
	//	uf_print_pie_cabecera($ld_total,$io_pdf);
	//	uf_print_pie_cabecera_espaciofirmas($io_pdf);
	//	uf_print_pie_cabecera_espaciofirmas($io_pdf);
		//uf_print_pie_cabecera_firmas1eralinea($io_pdf);
//		uf_print_pie_cabecera_firmas1eralinea1($firma1,$firma2,$io_pdf);
//		uf_print_pie_cabecera_espaciofirmas($io_pdf);
//		uf_print_pie_cabecera_firmas2dalinea($io_pdf);
//		uf_print_pie_cabecera_firmas2dalinea2($firma3,$io_pdf);


uf_print_pie_cabecera_firmas_fin_pagina($io_pdf,$firma1,$firma2,$firma3);

	}//if
	unset($la_data);
	unset($la_data_egr);
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
}//else
unset($io_report);
unset($io_funciones);
?>
