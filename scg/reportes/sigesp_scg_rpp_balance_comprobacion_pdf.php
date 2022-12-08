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
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_balance_comprobacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo1,$as_titulo2,$as_titulo3,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private
		//	    Arguments: as_titulo // Tï¿½tulo del Reporte
		//	    		   as_periodo_comp // Descripciï¿½n del periodo del comprobante
		//	    		   as_fecha_comp // Descripciï¿½n del perï¿½odo de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime los encabezados por pï¿½gina
		//	   Creado Por: Ing.Yozelin Barragï¿½n
		// Fecha Creaciï¿½n: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

		/*$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,9,$as_titulo); // Agregar el tï¿½tulo

		$li_tm=$io_pdf->getTextWidth(11,$as_fecha);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,715,9,$as_fecha); // Agregar el tï¿½tulo

		$io_pdf->addText(500,740,7,$_SESSION["ls_database"]); // Agregar la Base de datos
		$io_pdf->addText(500,730,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,8,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');*/
		
		
	
	$li_tm=$io_pdf->getTextWidth(9,$as_titulo);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,740,9,$as_titulo); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(9,$as_titulo1);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,730,9,$as_titulo1); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(9,$as_titulo2);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,720,9,$as_titulo2); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(9,$as_titulo3);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,710,9,$as_titulo3); // Agregar el tï¿½tulo
	

	$io_pdf->addText(500,725,7,$_SESSION["ls_database"]); // Agregar la Base de datos
	$io_pdf->addText(500,715,8,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(500,705,8,date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
		
		
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cuenta,$as_denominacion,$ad_saldo_ant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_cuenta // cuenta
		//	    		   as_denominacion // denominacion
		//	    		   io_pdf // Objeto PDF
		//    Description: funciï¿½n que imprime la cabecera de cada pï¿½gina
		//	   Creado Por: Ing.Yozelin Barragï¿½n
		// Fecha Creaciï¿½n: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$la_data=array(array('name'=>'<b>Cuenta</b> '.$as_cuenta.'  -----  '.$as_denominacion.''),
		               array('name'=>'<b>Saldo Anterior</b> '.$ad_saldo_ant.' '));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'xPos'=>305, // Orientaciï¿½n de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho Mï¿½ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($li_saldomes,$la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de informaciï¿½n
		//	   			   io_pdf // Objeto PDF
		//    Description: funciï¿½n que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragï¿½n
		// Fecha Creaciï¿½n: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($li_saldomes==0)
		{
			$la_config=array('showHeadings'=>1, // Mostrar encabezados
					'fontSize' => 7, // Tamaï¿½o de Letras
					'titleFontSize' => 7,  // Tamaï¿½o de Letras de los tï¿½tulos
					'showLines'=>1, // Mostrar Lï¿½neas
					'shaded'=>0, // Sombra entre lï¿½neas
					'colGap'=>1, // separacion entre tablas
					'width'=>550, // Ancho de la tabla
					'maxWidth'=>550, // Ancho Mï¿½ximo de la tabla
					'xPos'=>299, // Orientaciï¿½n de la tabla
					'cols'=>array('cuenta'=>array('justification'=>'center','width'=>90), // Justificaciï¿½n y ancho de la columna
						       'denominacion'=>array('justification'=>'left','width'=>140), // Justificaciï¿½n y ancho de la columna
						       'saldoanterior'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						       'debe'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						       'haber'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						       'saldo'=>array('justification'=>'right','width'=>80))); // Justificaciï¿½n y ancho de la columna

			$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
							'denominacion'=>'                        <b>Denominación</b>',
							'saldoanterior'=>'<b>Saldo Anterior</b>       ',
							'debe'=>'<b>Debe</b>              ',
							'haber'=>'<b>Haber</b>              ',
							'saldo'=>'<b>Saldo Actual</b>         ');
			$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		}
		else
		{
			$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'fontSize' => 7, // Tamaï¿½o de Letras
							 'titleFontSize' => 7,  // Tamaï¿½o de Letras de los tï¿½tulos
							 'showLines'=>1, // Mostrar Lï¿½neas
							 'shaded'=>0, // Sombra entre lï¿½neas
							 'colGap'=>1, // separacion entre tablas
							 'width'=>550, // Ancho de la tabla
							 'maxWidth'=>550, // Ancho Mï¿½ximo de la tabla
							 'xPos'=>299, // Orientaciï¿½n de la tabla
							 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificaciï¿½n y ancho de la columna
									'denominacion'=>array('justification'=>'left','width'=>120), // Justificaciï¿½n y ancho de la columna
									'saldoanterior'=>array('justification'=>'right','width'=>70), // Justificaciï¿½n y ancho de la columna
									'debe'=>array('justification'=>'right','width'=>70), // Justificaciï¿½n y ancho de la columna
									'haber'=>array('justification'=>'right','width'=>70), // Justificaciï¿½n y ancho de la columna
									'saldomes'=>array('justification'=>'right','width'=>70), // Justificaciï¿½n y ancho de la columna
									'saldo'=>array('justification'=>'right','width'=>70))); // Justificaciï¿½n y ancho de la columna

			$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
							   'denominacion'=>'                        <b>Denominación</b>',
							   'saldoanterior'=>'<b>Saldo Anterior</b>       ',
							   'debe'=>'<b>Debe</b>              ',
							   'haber'=>'<b>Haber</b>              ',
							   'saldomes'=>'<b>Saldo del Mes</b>       ',
							   'saldo'=>'<b>Saldo Actual</b>         ');
			$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		}
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($li_saldomes,$ldec_total_saldomes,$adec_totaldebe,$adec_totalhaber,$adec_total_saldo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : funciï¿½n que imprime el fin de la cabecera de cada pï¿½gina
		//	   Creado Por: Ing.Yozelin Barragï¿½n
		// Fecha Creaciï¿½n: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		if ($li_saldomes==0)
		{
			$la_data=array(array('total'=>'<b>Total '.$ls_bolivares.'</b>','debe'=>$adec_totaldebe,'haber'=>$adec_totalhaber,'saldo'=>$adec_total_saldo));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 8, // Tamaï¿½o de Letras
							 'showLines'=>1, // Mostrar Lï¿½neas
							 'shaded'=>0, // Sombra entre lï¿½neas
							 'width'=>550, // Ancho de la tabla
							 'maxWidth'=>550, // Ancho Mï¿½ximo de la tabla
							 'colGap'=>1, // separacion entre tablas
							 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
							 'xPos'=>299, // Orientaciï¿½n de la tabla
					 		 'cols'=>array('total'=>array('justification'=>'right','width'=>310), // Justificaciï¿½n y ancho de la columna
							 			   'debe'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
							 			   'haber'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>80))); // Justificaciï¿½n y ancho de la columna

			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		}
		else
		{
			$la_data=array(array('total'=>'<b>Total '.$ls_bolivares.'</b>','debe'=>$adec_totaldebe,'haber'=>$adec_totalhaber,'saldomes'=>$ldec_total_saldomes,'saldo'=>$adec_total_saldo));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldomes'=>'','saldo'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 8, // Tamaï¿½o de Letras
							 'showLines'=>1, // Mostrar Lï¿½neas
							 'shaded'=>0, // Sombra entre lï¿½neas
							 'width'=>550, // Ancho de la tabla
							 'maxWidth'=>550, // Ancho Mï¿½ximo de la tabla
							 'colGap'=>1, // separacion entre tablas
							 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
							 'xPos'=>299, // Orientaciï¿½n de la tabla
					 		 'cols'=>array('total'=>array('justification'=>'right','width'=>270), // Justificaciï¿½n y ancho de la columna
							 			   'debe'=>array('justification'=>'right','width'=>70), // Justificaciï¿½n y ancho de la columna
							 			   'haber'=>array('justification'=>'right','width'=>70), // Justificaciï¿½n y ancho de la columna
							 			   'saldomes'=>array('justification'=>'right','width'=>70), // Justificaciï¿½n y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>70))); // Justificaciï¿½n y ancho de la columna

			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		}
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
	$io_fecha = new class_fecha();
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
	//--------------------------------------------------  Parï¿½metros para Filtar el Reporte  -----------------------------------------
	$li_saldocero=$_GET["saldocero"];
	$li_saldomes=$_GET["saldomes"];
	$ld_fecdesde=$_GET["fecdes"];
	$ld_fechasta=$_GET["fechas"];
	$ls_costodesde=$_GET["costodesde"];
	$ls_costohasta=$_GET["costohasta"];
	$ls_cuentadesde_min=$_GET["cuentadesde"];
	$ls_cuentahasta_max=$_GET["cuentahasta"];
	if(($ls_cuentadesde_min=="")&&($ls_cuentahasta_max==""))
	{
		if($io_report->uf_spg_reporte_select_cuenta_min_max($ls_cuentadesde_min,$ls_cuentahasta_max))
		{
			$ls_cuentadesde=$ls_cuentadesde_min;
			$ls_cuentahasta=$ls_cuentahasta_max;
		}
	}
	else
	{
		$ls_cuentadesde=$ls_cuentadesde_min;
		$ls_cuentahasta=$ls_cuentahasta_max;
	}
	$li_nivel=$_GET["nivel"];
	//----------------------------------------------------  Parï¿½metros del encabezado  -----------------------------------------------
		$ldt_fecha=" <b>Desde  ".$ld_fecdesde."  al ".$ld_fechasta."</b> ";
		$ls_titulo="<b>xxxx </b>";
		$ls_titulo1="<b>BALANCE DE COMPROBACIÓN</b>";
		$ls_titulo2=$ldt_fecha;		
		$ls_titulo3="<b>(Expresado en ".$ls_bolivares.")</b>";
	//--------------------------------------------------------------------------------------------------------------------------------
	// Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
	$lb_valido=uf_insert_seguridad("<b>Balance de Comprobación en PDF</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_scg_reporte_balance_comprobante($ls_cuentadesde,$ls_cuentahasta,$ld_fecdesde,$ld_fechasta,$li_nivel,$li_saldocero,$ls_costodesde,$ls_costohasta);
	}
	 if($lb_valido==false) // Existe algï¿½n error ï¿½ no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');");
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuraciï¿½n de los margenes en centï¿½metros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$ls_titulo2,$ls_titulo3,$ldt_fecha,$io_pdf); // Imprimimos el encabezado de la pï¿½gina
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el nï¿½mero de pï¿½gina
		$li_tot=$io_report->dts_reporte->getRowCount("sc_cuenta");
		$ldec_totaldebe=0;
		$ldec_totalhaber=0;
		$ldec_total_saldo=0;
		$ld_saldo=0;
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$ldec_total_saldomes=0;
		for($i=1;$i<=$li_tot;$i++)
		{
			$li_tmp=($i+1);
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_cuenta=rtrim($io_report->dts_reporte->getValue("sc_cuenta",$i));

			$li_totfil=0;
			$as_cuenta="";
			for($li=$li_total;$li>1;$li--)
			{
				$li_ant=$ia_niveles_scg[$li-1];
				$li_act=$ia_niveles_scg[$li];
				$li_fila=$li_act-$li_ant;
				$li_len=strlen($ls_cuenta);
				$li_totfil=$li_totfil+$li_fila;
				$li_inicio=$li_len-$li_totfil;
				if($li==$li_total)
				{
					$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila);
				}
				else
				{
					$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
				}
			}
			$li_fila=$ia_niveles_scg[1]+1;
			$as_cuenta=substr($ls_cuenta,0,$li_fila)."-".$as_cuenta;

			$ls_denominacion=rtrim($io_report->dts_reporte->getValue("denominacion",$i));

			$ldec_debe=$io_report->dts_reporte->getValue("debe_mes",$i);
			$ldec_haber=$io_report->dts_reporte->getValue("haber_mes",$i);
			$ldec_saldo_ant=($io_report->dts_reporte->getValue("debe_mes_ant",$i)-$io_report->dts_reporte->getValue("haber_mes_ant",$i));
			$ldec_saldo_act=$ldec_saldo_ant+$ldec_debe-$ldec_haber;
			$ldec_BalDebe=$io_report->dts_reporte->getValue("total_debe",$i);
			$ldec_BalHABER=$io_report->dts_reporte->getValue("total_haber",$i);
			$ldec_saldomes=$ldec_debe-$ldec_haber;

			$ldec_totaldebe=$ldec_totaldebe+$ldec_BalDebe;
			$ldec_totalhaber=$ldec_totalhaber+$ldec_BalHABER;


			//$ldec_saldo_ant=$io_report->dts_reporte->getValue("saldo_ant",$i);
			//$ldec_debe=$io_report->dts_reporte->getValue("debe",$i);
			//$ldec_haber=$io_report->dts_reporte->getValue("haber",$i);
			//$ldec_saldo_act=$io_report->dts_reporte->getValue("saldo_act",$i);
			//$ldec_BalDebe=$io_report->dts_reporte->getValue("BalDebe",$i);
			//$ldec_BalHABER=$io_report->dts_reporte->getValue("BalHABER",$i);
			//$ldec_totaldebe=$ldec_BalDebe;
			//$ldec_totalhaber=$ldec_BalHABER;

			$ldec_saldo=$ldec_saldo_act;
			if($ldec_debe<0)
			{
				$ldec_debe_aux=abs($ldec_debe);
				$ldec_debe_aux=number_format($ldec_debe_aux,2,",",".");
				$ldec_debe="(".$ldec_debe_aux.")";
			}
			else
			{
			   $ldec_debe=number_format($ldec_debe,2,",",".");
			}
			if($ldec_haber<0)
			{
				$ldec_haber_aux=abs($ldec_haber);
				$ldec_haber_aux=number_format($ldec_haber_aux,2,",",".");
				$ldec_haber="(".$ldec_haber_aux.")";
			}
			else
			{
				$ldec_haber=number_format($ldec_haber,2,",",".");
			}
			if($ldec_saldo<0)
			{
				$ldec_saldo_aux=abs($ldec_saldo);
				$ldec_saldo_aux=number_format($ldec_saldo_aux,2,",",".");
				$ldec_saldo="(".$ldec_saldo_aux.")";
			}
			else
			{
				$ldec_saldo=number_format($ldec_saldo,2,",",".");
			}
			if($ldec_saldo_ant<0)
			{
				$ldec_saldo_ant_aux=abs($ldec_saldo_ant);
				$ldec_saldo_ant_aux=number_format($ldec_saldo_ant_aux,2,",",".");
				$ldec_saldo_ant="(".$ldec_saldo_ant_aux.")";
			}
			else
			{
				$ldec_saldo_ant=number_format($ldec_saldo_ant,2,",",".");
			}

			$ldec_saldomes=number_format($ldec_saldomes,2,",",".");

			if ($li_saldomes==0)
			{
				$la_data[$i]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>$ldec_saldo_ant,
						   'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldo'=>$ldec_saldo);
			}
			else
			{
				$la_data[$i]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'saldoanterior'=>$ldec_saldo_ant,
						   'debe'=>$ldec_debe,'haber'=>$ldec_haber,'saldomes'=>$ldec_saldomes,'saldo'=>$ldec_saldo);
			}

			//$ldec_debe=str_replace('.','',$ldec_debe);
			//$ldec_debe=str_replace(',','.',$ldec_debe);
			//$ldec_haber=str_replace('.','',$ldec_haber);
			//$ldec_haber=str_replace(',','.',$ldec_haber);
			//$ldec_saldo=str_replace('.','',$ldec_saldo);
			//$ldec_saldo=str_replace(',','.',$ldec_saldo);

		}//for

		uf_print_detalle($li_saldomes,$la_data,$io_pdf); // Imprimimos el detalle
		$ldec_total_saldo=($ldec_totaldebe-$ldec_totalhaber);
		$ldec_total_saldomes=$ldec_total_saldo;
		if($ldec_totaldebe<0)
		{
			$ldec_totaldebe_aux=abs($ldec_totaldebe);
			$ldec_totaldebe_aux=number_format($ldec_totaldebe_aux,2,",",".");
			$ldec_totaldebe="(".$ldec_totaldebe_aux.")";
		}
		else
		{
		    $ldec_totaldebe=number_format($ldec_totaldebe,2,",",".");
		}
		if($ldec_totalhaber<0)
		{
			$ldec_totalhaber_aux=abs($ldec_totalhaber);
			$ldec_totalhaber_aux=number_format($ldec_totalhaber_aux,2,",",".");
			$ldec_totalhaber="(".$ldec_totalhaber_aux.")";
		}
		else
		{
		   $ldec_totalhaber=number_format($ldec_totalhaber,2,",",".");
		}

		if($ldec_total_saldo<0)
		{
			$ldec_total_saldo_aux=abs($ldec_total_saldo);
			$ldec_total_saldo_aux=number_format($ldec_total_saldo_aux,2,",",".");
			$ldec_total_saldo="(".$ldec_total_saldo_aux.")";
		}
		else
		{
		   $ldec_total_saldo=number_format($ldec_total_saldo,2,",",".");
		}

		$ldec_total_saldomes=number_format($ldec_total_saldomes,2,",",".");
		uf_print_pie_cabecera($li_saldomes,$ldec_total_saldomes,$ldec_totaldebe,$ldec_totalhaber,$ldec_total_saldo,$io_pdf);
		
		
		

	
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
	}
	unset($io_report);
	unset($io_funciones);
?>
