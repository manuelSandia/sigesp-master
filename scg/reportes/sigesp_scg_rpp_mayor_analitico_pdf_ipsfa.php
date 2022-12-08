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
		//	    Arguments: as_titulo // T�tulo del Reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 22/09/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_scg;

		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_mayor_analitico.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_periodo_comp // Descripci�n del periodo del comprobante
		//	    		   as_fecha_comp // Descripci�n del per�odo de la fecha del comprobante
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing.Yozelin Barrag�n
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,10,$as_titulo); // Agregar el t�tulo

		$io_pdf->addText(500,730,7,$_SESSION["ls_database"]); // Agregar la Base de datos
		$io_pdf->addText(500,720,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,710,8,date("h:i a")); // Agregar la hora
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
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing.Yozelin Barrag�n
		// Fecha Creaci�n: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;

		$la_data=array(array('name'=>'<c:covensol_color:000077><b>Cuenta</b> '.$as_cuenta.'  -----  '.$as_denominacion.'</c:covensol_color>'),
		               array('name'=>'<c:covensol_color:000077><b>Saldo Anterior '.$ls_bolivares.'</b> '.$ad_saldo_ant.' </c:covensol_color>'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar L�neas
						 'fontSize' => 7, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9),
						 'shadeCo2'=>array(0.9,0.9,0.9),
						 'rowGap' => 2,
					     'colGap' => 3,
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>299, // Orientaci�n de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf,$li_ocultar)
	{
		
		global $datos_reporte;
		
		if($datos_reporte['color']){$color2=array(1,1,0.8);}else{$color2=array(1,1,1);}
	
		if($li_ocultar==1)
		{
			

					$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'fontSize' => 6, // Tama�o de Letras
							 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
							 'showLines'=>0, // Mostrar L�neas
							 'lineCol' => array(0.2,0.2,0.2),
							 'shaded'=>2, // Sombra entre l�neas
							 'shadeCol' => array(1,1,1),
							 'shadeCol2' => $color2,
							 'rowGap' => 2,
							 'colGap' => 2,
							 'width'=>550, // Ancho de la tabla
							 'maxWidth'=>550, // Ancho M�ximo de la tabla
							 'xPos'=>299, // Orientaci�n de la tabla
							 'Titulo_Color'  => 'si', // Para poner color de fondo y de letra al titulo
							 'TituloCol' => array(0.1,0.4,0.6), //Color de fondo del titulo
							 'Letra_Titulo' => array(1,1,1),  //Color de letra del titulo
							 'cols'=>array('procede'=>array('justification'=>'center','width'=>30), // Justificaci�n y ancho de la columna
										   'comprobante'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
										   'nombre'=>array('justification'=>'left','width'=>75), // Justificaci�n y ancho de la columna
										   'documento'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
										   'fecha'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75)),
							'cabecera_cols'=>array('procede'=>array('justification'=>'center','width'=>30), // Justificaci�n y ancho de la columna
										   'comprobante'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
										   'nombre'=>array('justification'=>'center','width'=>75), // Justificaci�n y ancho de la columna
										   'documento'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
										   'fecha'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
										   'debe'=>array('justification'=>'center','width'=>75), // Justificaci�n y ancho de la columna
										   'haber'=>array('justification'=>'center','width'=>75), // Justificaci�n y ancho de la columna
										   'saldo'=>array('justification'=>'center','width'=>75))
										   
								); // Justificaci�n y ancho de la columna

			$la_columnas=array('procede'=>'<b>Procede</b>',
							   'comprobante'=>'<b>Comprobante</b>',
							   'nombre'=>'<b>Beneficiario</b>',
							   'documento'=>'<b>Documento</b>',
							   'fecha'=>'<b>Fecha</b>',
							   'debe'=>'<b>Debe</b>',
							   'haber'=>'<b>Haber</b>',
							   'saldo'=>'<b>Saldo Actual</b>');
		}
		else
		{
			$la_config=array('showHeadings'=>1, // Mostrar encabezados
							 'fontSize' => 6, // Tama�o de Letras
							 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
							 'showLines'=>0, // Mostrar L�neas
							 'lineCol' => array(0.2,0.2,0.2),
							 'shaded'=>2, // Sombra entre l�neas
							 'shadeCol' => array(1,1,1),
							 'shadeCol2' => $color2,
							 'rowGap' => 2,
							 'colGap' => 2,
							 'width'=>550, // Ancho de la tabla
							 'maxWidth'=>550, // Ancho M�ximo de la tabla
							 'xPos'=>299, // Orientaci�n de la tabla
							 'Titulo_Color'  => 'si', // Para poner color de fondo y de letra al titulo
							 'TituloCol' => array(0.1,0.4,0.6), //Color de fondo del titulo
							 'Letra_Titulo' => array(1,1,1),  //Color de letra del titulo
							 'cols'=>array('procede'=>array('justification'=>'center','width'=>30), // Justificaci�n y ancho de la columna
										   'comprobante'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
										   'nombre'=>array('justification'=>'left','width'=>75), // Justificaci�n y ancho de la columna
										   'documento'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
										   'fecha'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75)),
							'cabecera_cols'=>array('procede'=>array('justification'=>'center','width'=>30), // Justificaci�n y ancho de la columna
										   'comprobante'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
										   'nombre'=>array('justification'=>'center','width'=>75), // Justificaci�n y ancho de la columna
										   'documento'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
										   'fecha'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
										   'debe'=>array('justification'=>'center','width'=>75), // Justificaci�n y ancho de la columna
										   'haber'=>array('justification'=>'center','width'=>75), // Justificaci�n y ancho de la columna
										   'saldo'=>array('justification'=>'center','width'=>75))
										   
								); // Justificaci�n y ancho de la columna
			$la_columnas=array('procede'=>'<b>Procede</b>',
							   'comprobante'=>'<b>Comprobante</b>',
							   'concepto'=>'<b>Concepto</b>',
							   'nombre'=>'<b>Beneficiario</b>',
							   'documento'=>'<b>Documento</b>',
							   'fecha'=>'<b>Fecha</b>',
							   'debe'=>'<b>Debe</b>',
							   'haber'=>'<b>Haber</b>',
							   'saldo'=>'<b>Saldo Actual</b>');
		}
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_totaldebe,$ad_totalhaber,$ad_totalsaldo,&$io_pdf,$li_ocultar)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private
		//	    Arguments : ad_total // Total General
		//    Description : funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing.Yozelin Barrag�n
		// Fecha Creaci�n: 18/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares,$datos_reporte;
		
		if($datos_reporte['color']){$color2=array(1,1,0.8);}else{$color2=array(1,1,1);}
		
		if($li_ocultar==1)
		{
			$la_data=array(array('total'=>'<b><i>Total '.$ls_bolivares.' </i></b>','debe'=>$ad_totaldebe,'haber'=>$ad_totalhaber,'saldo'=>$ad_totalsaldo));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tama�o de Letras
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>2, // Sombra entre l�neas
							 'shadeCol' => array(0.9,0.9,1),
							 'shadeCol2' => array(0.9,0.9,1),
							 'rowGap' => 2,
							 'colGap' => 2,
							 'width'=>299, // Ancho M�ximo de la tabla
							 'xOrientation'=>'center', // Orientaci�n de la tabla
							 'xPos'=>299, // Orientaci�n de la tabla
							 'cols'=>array('total'=>array('justification'=>'right','width'=>250), // Justificaci�n y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75))); // Justificaci�n y ancho de la columna

			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			$la_data=array(array('name'=>''));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>0, // Sombra entre l�neas
							 'width'=>530, // Ancho M�ximo de la tabla
							 'xOrientation'=>'center'); // Orientaci�n de la tabla
		}
		else
		{
			$la_data=array(array('total'=>'<b><i>Total '.$ls_bolivares.' </i></b>','debe'=>$ad_totaldebe,'haber'=>$ad_totalhaber,'saldo'=>$ad_totalsaldo));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tama�o de Letras
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>2, // Sombra entre l�neas
							 'shadeCol' => array(0.9,0.9,1),
							 'shadeCol2' => array(0.9,0.9,1),
							 'rowGap' => 2,
							 'colGap' => 2,
							 'width'=>299, // Ancho M�ximo de la tabla
							 'xOrientation'=>'center', // Orientaci�n de la tabla
							 'xPos'=>299, // Orientaci�n de la tabla
							 'cols'=>array('total'=>array('justification'=>'right','width'=>325), // Justificaci�n y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75))); // Justificaci�n y ancho de la columna

			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			$la_data=array(array('name'=>''));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>0, // Sombra entre l�neas
							 'width'=>530, // Ancho M�ximo de la tabla
							 'xOrientation'=>'center'); // Orientaci�n de la tabla
		}
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_pie_cabecera($ad_montototaldebe,$ad_montototalhaber,$ad_fechasta,&$io_pdf,$li_ocultar)
	{
				
		global $ls_bolivares,$datos_reporte,$ld_saldo_tot;
		
		if($datos_reporte['color']){$color2=array(1,1,0.8);}else{$color2=array(1,1,1);}


		if($li_ocultar==1)
		{

			$la_data=array(array('total'=>'<b><i>Total General '.$ls_bolivares.' </i></b>','debe'=>$ad_montototaldebe,'haber'=>$ad_montototalhaber,'saldo'=>$ld_saldo_tot));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tama�o de Letras
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>2, // Sombra entre l�neas
							 'shadeCol' => array(0.9,0.9,1),
							 'shadeCol2' => array(0.9,0.9,1),
							 'rowGap' => 2,
							 'colGap' => 2,
							 'width'=>550, // Ancho M�ximo de la tabla
							 'xOrientation'=>'center', // Orientaci�n de la tabla
							 'xPos'=>299, // Orientaci�n de la tabla
							 'cols'=>array('total'=>array('justification'=>'right','width'=>325), // Justificaci�n y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75))); // Justificaci�n y ancho de la columna

			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

			$la_data=array(array('name'=>''));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>0, // Sombra entre l�neas
							 'width'=>530, // Ancho M�ximo de la tabla
							 'xOrientation'=>'center'); // Orientaci�n de la tabla
		}
		else
		{


			
			$la_data=array(array('total'=>'<b><i>Total General '.$ls_bolivares.' </i></b>','debe'=>$ad_montototaldebe,'haber'=>$ad_montototalhaber,'saldo'=>$ld_saldo_tot));
			$la_columna=array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'fontSize' => 7, // Tama�o de Letras
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>2, // Sombra entre l�neas
							 'shadeCol' => array(0.9,0.9,1),
							 'shadeCol2' => array(0.9,0.9,1),
							 'rowGap' => 2,
							 'colGap' => 2,
							 'width'=>550, // Ancho M�ximo de la tabla
							 'xOrientation'=>'center', // Orientaci�n de la tabla
							 'xPos'=>299, // Orientaci�n de la tabla
							 'cols'=>array('total'=>array('justification'=>'right','width'=>325), // Justificaci�n y ancho de la columna
										   'debe'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'haber'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
										   'saldo'=>array('justification'=>'right','width'=>75))); // Justificaci�n y ancho de la columna

			$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
			
			$la_data=array(array('name'=>''));
			$la_columna=array('name'=>'');
			$la_config=array('showHeadings'=>0, // Mostrar encabezados
							 'showLines'=>0, // Mostrar L�neas
							 'shaded'=>0, // Sombra entre l�neas
							 'width'=>530, // Ancho M�ximo de la tabla
							 'xOrientation'=>'center'); // Orientaci�n de la tabla
		}
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio
		//	Description: Este m�todo realiza una consulta a los formatos de las cuentas
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
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
		$ld_fecdesde=$_GET["fecdes"];
		$ld_fechasta=$_GET["fechas"];
		$ls_cuentadesde_min=$_GET["cuentadesde"];
		$ls_cuentahasta_max=$_GET["cuentahasta"];
		$li_recortar=$_GET["recortar"];
		$li_lenconcepto=$_GET["lenconcepto"];
		
		$datos_reporte['sc_cuenta_d'] = $_GET["cuentadesde"];
		$datos_reporte['sc_cuenta_h'] = $_GET["cuentahasta"];
		$datos_reporte['fecdes'] = $_GET["fecdes"];
		$datos_reporte['fechas'] = $_GET["fechas"];
		$datos_reporte['recortar'] = $_GET["recortar"];
		$datos_reporte['lenconcepto'] = $_GET["lenconcepto"];
		$datos_reporte['orden'] = $_GET["orden"];
		$datos_reporte['color'] = $_GET["color"];
		
		
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
		$ldt_fecha="<b> Desde   ".$ld_fecdesde."   al   ".$ld_fechasta." </b>" ;
		$ls_titulo="<b> Mayor  Analitico</b>  ".$ldt_fecha;
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
	$lb_valido=uf_insert_seguridad("<b>Mayor Anal�tico en PDF</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		 $cuentas=$io_report->cuentas_mayor($datos_reporte);
    }
	 if($cuentas===false) // Existe alg�n error � no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');");
		//print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    //error_reporting(E_ALL);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la p�gina

		$ld_saldo_tot = 0;
		$ld_montototaldebe=0;
		$ld_montototalhaber=0;			
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1);
				
		foreach($cuentas as $fila_sc){
					
					//echo 'Cuenta: '.$fila_sc['sc_cuenta'].'<br>';
					$datos = $datos_reporte;
					$datos['sc_cuenta_d'] = $fila_sc['sc_cuenta'];
					$datos['sc_cuenta_h'] = $fila_sc['sc_cuenta'];
					$fila_mayor=$io_report->mayor_analitico($datos);
					
					$total = $io_report->rs_analitico->RecordCount();
					$ld_totaldebe=0;
					$ld_totalhaber=0;								
					$ld_totalsaldo=0;
					$ld_saldo=0;
					$ldec_mondeb=0;
					$ldec_monhab=0;
					//echo 'Total: '.$total.'<br>';
									
					if($total>0){
							
							
							$thisPageNum=$io_pdf->ezPageCount;
							
							$ls_cuenta=trim($io_report->rs_analitico->fields["sc_cuenta"]);							
							$ls_denominacion=$io_report->rs_analitico->fields["denominacion"];
							$ld_saldo_ant=$io_report->rs_analitico->fields["saldo_ant"];
							$contador = 0;	
														
							foreach($io_report->rs_analitico as $fila_mayor){								
									
									$contador++;					
									$ls_comprobante=$io_report->rs_analitico->fields["comprobante"];
									$ls_codpro=$fila_mayor["cod_pro"];
									$ls_cedbene=$fila_mayor["ced_bene"];
									$ls_nompro=$fila_mayor["nompro"];
									$ls_nombene=$fila_mayor["apebene"].", ".$fila_mayor["nombene"];
									$ls_nombre="";
									$ls_codban=$fila_mayor["codban"];
									$ls_ctaban=$fila_mayor["ctaban"];
									if($ls_codpro!="----------")
									{
										$ls_nombre=$ls_nompro;
									}
									if($ls_cedbene!="----------")
									{
										$ls_nombre=$ls_nombene;
									}
									$ls_documento=$fila_mayor["documento"];
									$ls_procede=$fila_mayor["procede"];
									if ($ls_procede=='SCBBCH')
									{
										$io_report->uf_scg_mayor_analitico_info_cheques($ls_comprobante,$ls_codban,$ls_ctaban);
										$ls_cheque=$io_report->rs_info_cheques->fields["numdoc"];
										$ls_nombanco=$io_report->rs_info_cheques->fields["nomban"];
										$ld_fecmov=$io_report->rs_info_cheques->fields["fecmov"];
						
										if ($ls_cheque=='')
										{
											$ls_cheque='';
										}
										if ($ls_nombanco=='')
										{
											$ls_nombanco='';
										}				
										if ($ld_fecmov=='')
										{
											$ld_fecmov='';
										}	
										$ls_infobanco="   <b>Cheque:</b> $ls_cheque, <b>Fecha:</b> $ld_fecmov, <b>Banco:</b> $ls_nombanco ";				
									}
									else
									{
										$ls_infobanco='';
									}			
									if ($li_recortar==1)
									{
										$ls_concepto=substr($fila_mayor["descripcion"],0,$li_lenconcepto).$ls_infobanco;
									}
									else
									{
										$ls_concepto=$fila_mayor["descripcion"].$ls_infobanco;
									}
									$ldec_monto=$fila_mayor["monto"];
									$fecmov=$fila_mayor["fecha"];
									$ld_fecmov=$io_funciones->uf_convertirfecmostrar($fecmov);
									$ls_debhab=$fila_mayor["debhab"];									
																
									
									if($ls_debhab=='D')
									{
										$ldec_mondeb=$ldec_monto;
										$ldec_monhab=0;
										$ld_totaldebe=$ld_totaldebe+$ldec_mondeb;
						
									}
									elseif($ls_debhab=='H')
									{
										$ldec_monhab=$ldec_monto;
										$ldec_mondeb=0;
										$ld_totalhaber=$ld_totalhaber+$ldec_monhab;
						
									}
									if ($contador==1)
									{
									  $ld_saldo=$ld_saldo_ant+$ldec_mondeb-$ldec_monhab;
									}
									else
									{
										if($ls_debhab=='D')
										{
											$ld_saldo=$ld_saldo+$ldec_monto;
										}
										elseif($ls_debhab=='H')
										{
											$ld_saldo=$ld_saldo-$ldec_monto;
										}
									}					
									
									$pdf_mondeb=number_format(abs($ldec_mondeb),2,",",".");
									$pdf_monhab=number_format(abs($ldec_monhab),2,",",".");
									if($ld_saldo<0)
									{									
									  $ld_saldox=$ld_saldo;
									  $ld_saldo_aux=number_format(abs($ld_saldo),2,",",".");
									  $ld_saldo_final="(".$ld_saldo_aux.")";
									  
									}
									else
									{
									  $ld_saldox=$ld_saldo;
									  $ld_saldo_aux=number_format($ld_saldo,2,",",".");
									  $ld_saldo_final=$ld_saldo_aux;
 									  
									}
									$la_data[$contador]=array('procede'=>$ls_procede,'comprobante'=>$ls_comprobante,'concepto'=>$ls_concepto,
													   'nombre'=>$ls_nombre,'documento'=>$ls_documento,'fecha'=>$ld_fecmov,'debe'=>$pdf_mondeb,
													   'haber'=>$pdf_monhab,'saldo'=>$ld_saldo_final);																
							}//fin del foreach
							
							$ld_saldo_ant_pdf=number_format($ld_saldo_ant,2,",",".");
							
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
								if($li==$li_total){$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila);}
								else{$as_cuenta=substr($ls_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;}
							}
							$li_fila=$ia_niveles_scg[1]+1;
							$as_cuenta=substr($ls_cuenta,0,$li_fila)."-".$as_cuenta;
										
							$ld_totalsaldo_final=$ld_saldo_final;
							
							if($ld_totaldebe<0){$ld_totaldebe_pdf="(".number_format(abs($ld_totaldebe),2,",",".").")";}
							else{$ld_totaldebe_pdf=number_format($ld_totaldebe,2,",",".");}
							
							if($ld_totalhaber<0){$ld_totalhaber_pdf="(".number_format(abs($ld_totalhaber),2,",",".").")";}
							else{$ld_totalhaber_pdf=number_format($ld_totalhaber,2,",",".");}
						
							$ld_montototaldebe=$ld_montototaldebe+$ld_totaldebe;
							$ld_montototalhaber=$ld_montototalhaber+$ld_totalhaber;						
							$ld_saldo_tot = $ld_saldo_tot + $ld_saldox;
							
							uf_print_cabecera($as_cuenta,$ls_denominacion,$ld_saldo_ant_pdf,$io_pdf);
							$io_pdf->ezSetDy(-4);
							uf_print_detalle($la_data,$io_pdf,$li_ocultar); // Imprimimos el detalle
							uf_print_pie_cabecera($ld_totaldebe_pdf,$ld_totalhaber_pdf,$ld_totalsaldo_final,$io_pdf,$li_ocultar);
							
							unset($la_data);
							
					}//fin del if
		
		}//fin del foreach
		
		
		$ld_saldo_tot=number_format($ld_saldo_tot,2,",",".");
		$ld_montototalhaber=number_format($ld_montototalhaber,2,",",".");
		$ld_montototaldebe=number_format($ld_montototaldebe,2,",",".");
		

		uf_print_total_pie_cabecera($ld_montototaldebe,$ld_montototalhaber,$ld_fechasta,$io_pdf,$li_ocultar);

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
