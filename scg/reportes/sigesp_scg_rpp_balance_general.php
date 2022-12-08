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
	$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_balance_general.php",$ls_descripcion);
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
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,715,11,$as_titulo); // Agregar el tï¿½tulo		

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo1);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,700,11,$as_titulo1); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,685,11,$as_titulo2); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(11,$as_titulo3);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,670,11,$as_titulo3); // Agregar el tï¿½tulo

	$io_pdf->addText(510,740,7,$_SESSION["ls_database"]); // Agregar la Base de datos
	$io_pdf->addText(510,730,8,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(510,720,8,date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle($la_data,&$io_pdf)
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
						 'fontSize' => 8, // Tamaï¿½o de Letras
						 'titleFontSize' => 8,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>115), // Justificaciï¿½n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>305), // Justificaciï¿½n y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>100))); // Justificaciï¿½n y ancho de la columna
	$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'saldo'=>'<b>Saldo</b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------


//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle_cueproacu($la_data,&$io_pdf,$li_nivel)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle_cueproacu
	//		    Acess: private
	//	    Arguments: la_data // arreglo de informaciï¿½n
	//	   			   io_pdf // Objeto PDF
	//    Description: funciï¿½n que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaciï¿½n: 28/04/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	/*
	 * $li_nivel=$li_nivel+6;
	 * $divide=(620/$li_nivel);
	 $tamanocuenta=$divide*2;
	 $tamanoden=$divide*3;
	 $tamanocelda=$divide;*/

	if($li_nivel==1){
		$tamanocuenta=60;
		$tamanoden=160;
		$tamanocelda8=1;
		$tamanocelda7=1;
		$tamanocelda6=1;
		$tamanocelda5=1;
		$tamanocelda4=1;
		$tamanocelda3=1;
		$tamanocelda2=1;
		$tamanocelda1=50;
	}
	if($li_nivel==2){
		$tamanocuenta=60;
		$tamanoden=160;
		$tamanocelda8=1;
		$tamanocelda7=1;
		$tamanocelda6=1;
		$tamanocelda5=1;
		$tamanocelda4=1;
		$tamanocelda3=1;
		$tamanocelda2=50;
		$tamanocelda1=50;
	}
	if($li_nivel==3){
		$tamanocuenta=60;
		$tamanoden=160;
		$tamanocelda8=1;
		$tamanocelda7=1;
		$tamanocelda6=1;
		$tamanocelda5=1;
		$tamanocelda4=1;
		$tamanocelda3=50;
		$tamanocelda2=50;
		$tamanocelda1=50;
	}
	if($li_nivel==4){
		$tamanocuenta=60;
		$tamanoden=160;
		$tamanocelda8=1;
		$tamanocelda7=1;
		$tamanocelda6=1;
		$tamanocelda5=1;
		$tamanocelda4=50;
		$tamanocelda3=50;
		$tamanocelda2=50;
		$tamanocelda1=50;
	}
	if($li_nivel==5){
		$tamanocuenta=60;
		$tamanoden=160;
		$tamanocelda8=1;
		$tamanocelda7=1;
		$tamanocelda6=1;
		$tamanocelda5=50;
		$tamanocelda4=50;
		$tamanocelda3=50;
		$tamanocelda2=50;
		$tamanocelda1=50;
	}
	if($li_nivel==6){
		$tamanocuenta=60;
		$tamanoden=160;
		$tamanocelda8=1;
		$tamanocelda7=1;
		$tamanocelda6=50;
		$tamanocelda5=50;
		$tamanocelda4=50;
		$tamanocelda3=50;
		$tamanocelda2=50;
		$tamanocelda1=50;
	}
	if($li_nivel==7){
		$tamanocuenta=60;
		$tamanoden=160;
		$tamanocelda8=48;
		$tamanocelda7=48;
		$tamanocelda6=48;
		$tamanocelda5=48;
		$tamanocelda4=48;
		$tamanocelda3=48;
		$tamanocelda2=48;
		$tamanocelda1=48;
	}

	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tamaï¿½o de Letras
						 'titleFontSize' => 8,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'colGap'=>1, // separacion entre tablas
	//'width'=>520, // Ancho de la tabla
						 'width'=>620, // Ancho de la tabla
						 'maxWidth'=>620, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>$tamanocuenta), // Justificaciï¿½n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>$tamanoden), // Justificaciï¿½n y ancho de la columna
										'saldo_n8'=>array('justification'=>'right','width'=>$tamanocelda8), // Justificaciï¿½n y ancho de la columna
										'saldo_n7'=>array('justification'=>'right','width'=>$tamanocelda7), // Justificaciï¿½n y ancho de la columna
										'saldo_n6'=>array('justification'=>'right','width'=>$tamanocelda6), // Justificaciï¿½n y ancho de la columna
										'saldo_n5'=>array('justification'=>'right','width'=>$tamanocelda5), // Justificaciï¿½n y ancho de la columna
										'saldo_n4'=>array('justification'=>'right','width'=>$tamanocelda4), // Justificaciï¿½n y ancho de la columna
									   'saldo_n3'=>array('justification'=>'right','width'=>$tamanocelda3), // Justificaciï¿½n y ancho de la columna
						 				'saldo_cueproacu'=>array('justification'=>'right','width'=>$tamanocelda2), // Justificaciï¿½n y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>$tamanocelda1))); // Justificaciï¿½n y ancho de la columna
	$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominación</b>',
						   'saldo_n8'=>'',
						   'saldo_n7'=>'',
						   'saldo_n6'=>'',
						   'saldo_n5'=>'',	
						   'saldo_n4'=>'',
						   'saldo_n3'=>'',
						   'saldo_cueproacu'=>'',
						   'saldo'=>'<b>Saldo</b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------

function uf_print_cuentas_acreedoras($la_data_acreedoras,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cuentas_acreedoras
	//		    Acess: private
	//	    Arguments: la_data // arreglo de informaciï¿½n
	//	   			   io_pdf // Objeto PDF
	//    Description: funciï¿½n que imprime el detalle de las Cuentas acreedoras
	//	   Creado Por: Ing. Arnaldo Suï¿½rez
	// Fecha Creaciï¿½n: 14/05/2010 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaï¿½o de Letras
						 'titleFontSize' => 8,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>115), // Justificaciï¿½n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>305), // Justificaciï¿½n y ancho de la columna
									   'saldo_cueproacu'=>array('justification'=>'right','width'=>65), // Justificaciï¿½n y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>65))); // Justificaciï¿½n y ancho de la columna
	$la_columnas=array('cuenta'=>'',
						   'denominacion'=>'',
						   'saldo_cueproacu'=>'',
						   'saldo'=>'');
	$io_pdf->ezTable($la_data_acreedoras,$la_columnas,'',$la_config);
}// end function uf_print_cuentas_acreedoras

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_pie_cabecera($ld_total,&$io_pdf)
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

	$la_data=array(array('total'=>'<b>Total Pasivo + Capital + Resultado '.$ls_bolivares.'</b>','totalgen'=>$ld_total));
	$la_columna=array('total'=>'','totalgen'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'fontSize' => 9, // Tamaï¿½o de Letras
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>520, // Ancho Mï¿½ximo de la tabla
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>420), // Justificaciï¿½n y ancho de la columna
						 			   'totalgen'=>array('justification'=>'right','width'=>100))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera


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




//--------------------------------------------------------

function uf_print_totales_cabecera($titulo,$ld_total,&$io_pdf)
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

	$la_data=array(array('total'=>'<b>'.$titulo.'  '.$ls_bolivares.'</b>','totalgen'=>$ld_total));
	$la_columna=array('total'=>'','totalgen'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'fontSize' => 9, // Tamaï¿½o de Letras
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>520, // Ancho Mï¿½ximo de la tabla
						 'colGap'=>1, // separacion entre tablas
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>420), // Justificaciï¿½n y ancho de la columna
						 			   'totalgen'=>array('justification'=>'right','width'=>100))); // Justificaciï¿½n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_pie_cabecera

//-------------------------------------
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
		$ls_bolivares ="Bolívares";
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
//--------------------------------------------------  Parï¿½metros para Filtar el Reporte  -----------------------------------------
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
//----------------------------------------------------  Parï¿½metros del encabezado  -----------------------------------------------
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$ls_nombre=$_SESSION["la_empresa"]["nombre"];
$li_ano=substr($ldt_periodo,0,4);
$ls_pasivo=$_SESSION["la_empresa"]["pasivo"];
$ls_resultado=$_SESSION["la_empresa"]["resultado"];
$ls_capital=$_SESSION["la_empresa"]["capital"];
$ls_acreedora=trim($_SESSION["la_empresa"]["orden_h"]);

$ld_fechas=$io_funciones->uf_convertirdatetobdletras($fechas);
$ls_titulo="<b> xxxx  </b>";
$ls_titulo1="<b>ESTADO DE SITUACIÓN FINANCIERA</b>";
$ls_titulo2="<b> al ".$ld_fechas."</b>";
$ls_titulo3="<b>(Expresado en ".$ls_bolivares.")</b>";
//--------------------------------------------------------------------------------------------------------------------------------
// Cargar datastore con los datos del reporte
$lb_valido=uf_insert_seguridad("<b>Balance General en PDF</b>"); // Seguridad de Reporte
if($lb_valido)
{
	$lb_valido=$io_report->uf_balance_general($ldt_fechas,$li_nivel);
}
if($lb_valido==false) // Existe algï¿½n error ï¿½ no hay registros
{
	print("<script language=JavaScript>");
	print(" alert('No hay nada que Reportar');");
	//print(" close();");
	print("</script>");
}
else// Imprimimos el reporte
{
	error_reporting(E_ALL);
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(4.8,3,3,3); // Configuraciï¿½n de los margenes en centï¿½metros
	uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$ls_titulo2,$ls_titulo3,$io_pdf); // Imprimimos el encabezado de la pï¿½gina
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el nï¿½mero de pï¿½gina
	$li_tot=$io_report->ds_reporte->getRowCount("sc_cuenta");
	$cont=0;
	$ld_total=0;
	$saldo_primer_nivel=0;
	$saldo_segundo_nivel=0;
	$saldo_tercer_nivel=0;
	$saldo_cuarto_nivel=0;
	$saldo_quinto_nivel=0;
	$saldo_sexto_nivel=0;

	$titulo_primer_nivel="";
	$titulo_segundo_nivel="";
	$nivelanterior="0";
	$ctaprimernivel="0";
	$ctasegundonivel="0";
	$cont1=0;
	$cont2=0;
	$cont3=0;
	$cont4=0;
	$cont5=0;
	$cont6=1;
	for($li_i=1;$li_i<=$li_tot;$li_i++)
	{
		$io_pdf->transaction('start'); // Iniciamos la transacciï¿½n
		$thisPageNum=$io_pdf->ezPageCount;
		$ls_orden=$io_report->ds_reporte->data["orden"][$li_i];
		$li_nro_reg=$io_report->ds_reporte->data["num_reg"][$li_i];
		$ls_sc_cuenta=trim($io_report->ds_reporte->data["sc_cuenta"][$li_i]);
		$li_totfil=0;
		$as_cuenta="";
		if($ls_sc_cuenta!=''){
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
		}
		$ls_denominacion=$io_report->ds_reporte->data["denominacion"][$li_i];
		$ls_nivel=$io_report->ds_reporte->data["nivel"][$li_i];
		$ls_nivel=abs($ls_nivel);
		$ld_saldo=$io_report->ds_reporte->data["saldo"][$li_i];
		$ld_saldo_neto = 0;
		$li_cueproacu=$io_report->ds_reporte->data["cueproacu"][$li_i];
		$ls_estatus  =$io_report->ds_reporte->data["estatus"][$li_i];
		$saldodepre=$io_report->ds_reporte->data["saldodepre"][$li_i];
		$ld_saldo6=$io_report->ds_reporte->data["saldo6"][$li_i];
		$ld_saldo5=$io_report->ds_reporte->data["saldo5"][$li_i];
		$ld_saldo4=$io_report->ds_reporte->data["saldo4"][$li_i];
		$ld_saldo3=$io_report->ds_reporte->data["saldo3"][$li_i];
		$ld_saldo2=$io_report->ds_reporte->data["saldo2"][$li_i];
		$ld_saldo1=$io_report->ds_reporte->data["saldo1"][$li_i];
		$posicion  =$io_report->ds_reporte->data["posicion"][$li_i];

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

		if($ls_nivel == '2'){
			if($ctasegundonivel!='$ls_sc_cuenta'&&$ctasegundonivel!='0'){
				//imprimir total primer nivel
				//uf_print_totales_cabecera($titulo_segundo_segundo,$saldo_segundo_nivel,$io_pdf);

				if($saldo_segundo_nivel<0)
				{
					$saldo_segundo_nivel="(".number_format(abs($saldo_segundo_nivel),2,",",".").")";
				}
				else
				{
					$saldo_segundo_nivel=number_format(abs($saldo_segundo_nivel),2,",",".");
				}
				$cont=$cont+1;
				//	$la_data[$cont]=array('cuenta'=>'<b></b>','denominacion'=>'    '.'<b>'.$titulo_segundo_nivel.'</b>','saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'<b>'.$saldo_segundo_nivel.'</b>','saldo'=>'');
			}
			$titulo_segundo_nivel="Total ".$ls_denominacion;
			$ctasegundonivel=$ls_sc_cuenta;
			$saldo_segundo_nivel=0;
		}



		if($ls_nivel == '1'){

			if($ctasegundonivel!='0'){
				//imprimir total primer nivel
				//uf_print_totales_cabecera($titulo_segundo_segundo,$saldo_segundo_nivel,$io_pdf);

				if($saldo_segundo_nivel<0)
				{
					$saldo_segundo_nivel="(".number_format(abs($saldo_segundo_nivel),2,",",".").")";
				}
				else
				{
					$saldo_segundo_nivel=number_format(abs($saldo_segundo_nivel),2,",",".");
				}
				$cont=$cont+1;
				//$la_data[$cont]=array('cuenta'=>'<b></b>','denominacion'=>'    '.'<b>'.$titulo_segundo_nivel.'</b>','saldo_cueproacu'=>'','saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'<b>'.$saldo_segundo_nivel.'</b>','saldo'=>'');
			}

			if($ctaprimernivel!='$ls_sc_cuenta'&&$ctaprimernivel!='0'){
				$cont=$cont+1;
				if($saldo_primer_nivel<0)
				{
					$saldo_primer_nivel="(".number_format(abs($saldo_primer_nivel),2,",",".").")";
				}
				else
				{
					$saldo_primer_nivel=number_format(abs($saldo_primer_nivel),2,",",".");
				}
				//	$la_data[$cont]=array('cuenta'=>'<b></b>','denominacion'=>'    '.'<b>'.$titulo_primer_nivel.'</b>','saldo_cueproacu'=>'','saldo'=>'<b>'.$saldo_primer_nivel.'</b>');
			}
			$titulo_primer_nivel="Total ".$ls_denominacion;
			$ctaprimernivel=$ls_sc_cuenta;
			$titulo_segundo_nivel="";
			$ctasegundonivel=0;
			$saldo_primer_nivel=0;
			$saldo_segundo_nivel=0;
		}



		if($ls_nivel == '7')
		{

			$saldo_primer_nivel=$saldo_primer_nivel+$ld_saldo;
			$saldo_segundo_nivel=$saldo_segundo_nivel+$ld_saldo;
		}//if


		$ls_rnivel=$io_report->ds_reporte->data["rnivel"][$li_i];
		//$ld_total=$io_report->ds_reporte->data["total"][$li_i];
		if($ls_pasivo."000"==substr($ls_sc_cuenta,0,4))
		{
			$ld_total=$ld_total+$ld_saldo;
		}
		if($ls_capital."000"==substr($ls_sc_cuenta,0,4))
		{
			$ld_total=$ld_total+$ld_saldo;
		}

		if($ls_resultado."000"==substr($ls_sc_cuenta,0,4))
		{
			if(trim($ls_capital) != trim($ls_resultado))
			{
				$ld_total=$ld_total+$ld_saldo;
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
		if($ld_saldo6<0)
		{
			$ld_saldo6="(".number_format(abs($ld_saldo6),2,",",".").")";
		}
		else
		{
			$ld_saldo6=number_format(abs($ld_saldo6),2,",",".");
		}
		if($ld_saldo5<0)
		{
			$ld_saldo5="(".number_format(abs($ld_saldo5),2,",",".").")";
		}
		else
		{
			$ld_saldo5=number_format(abs($ld_saldo5),2,",",".");
		}
		if($ld_saldo4<0)
		{
			$ld_saldo4="(".number_format(abs($ld_saldo4),2,",",".").")";
		}
		else
		{
			$ld_saldo4=number_format(abs($ld_saldo4),2,",",".");
		}
		if($ld_saldo3<0)
		{
			$ld_saldo3="(".number_format(abs($ld_saldo3),2,",",".").")";
		}
		else
		{
			$ld_saldo3=number_format(abs($ld_saldo3),2,",",".");
		}
		if($ld_saldo2<0)
		{
			$ld_saldo2="(".number_format(abs($ld_saldo2),2,",",".").")";
		}
		else
		{
			$ld_saldo2=number_format(abs($ld_saldo2),2,",",".");
		}
		if($ld_saldo1<0)
		{
			$ld_saldo1="(".number_format(abs($ld_saldo1),2,",",".").")";
		}
		else
		{
			$ld_saldo1=number_format(abs($ld_saldo1),2,",",".");
		}
			
		$cont=$cont+1;
			
		switch($ls_nivel)
		{
			case 7:
				//if($ls_estatus == 'C')
				//	{
				if($posicion=='8'){
					$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'               '.$ls_denominacion,'saldo_n8'=>$ld_saldo,'saldo_n7'=>$saldodepre,'saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'','saldo'=>'');
						
				}else{
					$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'               '.$ls_denominacion,'saldo_n8'=>'','saldo_n7'=>$ld_saldo,'saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'','saldo'=>'');
						
				}

				/*}
				 else
				 {
				 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>'');
				 }*/
				break;
			case 6:
				// if($ls_estatus == 'C')
				//	{
				$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>$ld_saldo6,'saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'','saldo'=>'');
				$cont6=$cont6+1;
				/*	}
				 else
				 {
				 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);
				 }	*/
				break;
					case 5:
						//if($ls_estatus == 'C')
						//{
						$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'         '.$ls_denominacion,'saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>'','saldo_n5'=>$ld_saldo5,'saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'','saldo'=>'');
						$cont5=$cont5+1;
						/*}
						 else
						 {
						 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);
						 }*/
						break;
							case 4:
								//if($ls_estatus == 'C')
								//	{
								$la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'      '.$ls_denominacion,'saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>$ld_saldo4,'saldo_n3'=>'','saldo_cueproacu'=>'','saldo'=>'');
								$cont4=$cont4+1;
								/*}
								 else
								 {
								 $la_data[$cont]=array('cuenta'=>$as_cuenta,'denominacion'=>'            '.$ls_denominacion,'saldo_cueproacu'=>'','saldo'=>$ld_saldo);
								 }*/
								break;
									case 3:
										//  if($ls_estatus == 'C')
										//{
										$la_data[$cont]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'<b>   '.$ls_denominacion.'</b>','saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>$ld_saldo3,'saldo_cueproacu'=>'','saldo'=>'');
										$cont3=$cont3+1;
										/*}
										 else
										 {
										 $la_data[$cont]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'<b>        '.$ls_denominacion.'</b>','saldo_cueproacu'=>'','saldo'=>$ld_saldo);
										 }*/
										break;
											case 2:
												$la_data[$cont]=array('cuenta'=>'','denominacion'=>'','saldo_cueproacu'=>'','saldo'=>'');
												$cont=$cont+1;
												//if($ls_estatus == 'C')
												//{
												$la_data[$cont]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'<b><i>'.$ld_saldo2.'</b></i>','saldo'=>'<b><i></b></i>');
												$cont2=$cont2+1;
												/*}
												 else
												 {
												 $la_data[$cont]=array('cuenta'=>'<b>'.$as_cuenta.'</b>','denominacion'=>'    '.'<b>'.$ls_denominacion.'</b>','saldo_cueproacu'=>'','saldo'=>'<b>'.$ld_saldo.'</b>');
												 }*/
												break;
													case 1:
														if ($cont>1)
														{
															$la_data[$cont]=array('cuenta'=>'','denominacion'=>'','saldo_cueproacu'=>'','saldo'=>'');
															$cont=$cont+1;
															//	if($ls_estatus == 'C')
															//{
															$la_data[$cont]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'<b><i></b></i>','saldo'=>'<b><i>'.$ld_saldo1.'</b></i>');
															$cont1=$cont1+1;
															/*	}
															 else
															 {
															 $la_data[$cont]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'','saldo'=>'<b><i>'.$ld_saldo.'</b></i>');
															 }*/
															}
															else
															{
																//if($ls_estatus == 'C')
																//{
																$la_data[$cont]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'<b><i></b></i>','saldo'=>'<b><i>'.$ld_saldo1.'</b></i>');
																$cont1=$cont1+1;
																/*}
																 else
																 {
																 $la_data[$cont]=array('cuenta'=>'<b><i>'.$as_cuenta.'</b></i>','denominacion'=>'<b><i>'.$ls_denominacion.'</b></i>','saldo_cueproacu'=>'','saldo'=>'<b><i>'.$ld_saldo.'</b></i>');
																 }*/
																}
															}
															$nivelanterior=$ls_nivel;

														}//for
														//uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle

														if($saldo_segundo_nivel<0)
														{
															$saldo_segundo_nivel="(".number_format(abs($saldo_segundo_nivel),2,",",".").")";
														}
														else
														{
															$saldo_segundo_nivel=number_format(abs($saldo_segundo_nivel),2,",",".");
														}
														$cont=$cont+1;
														//			$la_data[$cont]=array('cuenta'=>'<b></b>','denominacion'=>'    '.'<b>'.$titulo_segundo_nivel.'</b>','saldo_cueproacu'=>'','saldo_n8'=>'','saldo_n7'=>'','saldo_n6'=>'','saldo_n5'=>'','saldo_n4'=>'','saldo_n3'=>'','saldo_cueproacu'=>'<b>'.$saldo_segundo_nivel.'</b>','saldo'=>'');
												}

												if($ctaprimernivel!='$ls_sc_cuenta'&&$ctaprimernivel!='0'){
													$cont=$cont+1;
													if($saldo_primer_nivel<0)
													{
														$saldo_primer_nivel="(".number_format(abs($saldo_primer_nivel),2,",",".").")";
													}
													else
													{
														$saldo_primer_nivel=number_format(abs($saldo_primer_nivel),2,",",".");
													}
				 								//		$la_data[$cont]=array('cuenta'=>'<b></b>','denominacion'=>'    '.'<b>'.$titulo_primer_nivel.'</b>','saldo_cueproacu'=>'','saldo'=>'<b>'.$saldo_primer_nivel.'</b>');


													uf_print_detalle_cueproacu($la_data,&$io_pdf,$li_nivel);
													$ld_total=$ld_total;
													if($ld_total<0)
													{
														$ld_total="(".number_format(abs($ld_total),2,",",".").")";
													}
													else
													{
														$ld_total=number_format($ld_total,2,",",".");
													}






													//uf_print_pie_cabecera($ld_total,$io_pdf); // Imprimimos pie de la cabecera

													
													
													
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
													
													
													

													///uf_print_cuentas_acreedoras($la_data_acreedoras,&$io_pdf);
													unset($la_data_acreedoras);
													unset($la_data);
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
