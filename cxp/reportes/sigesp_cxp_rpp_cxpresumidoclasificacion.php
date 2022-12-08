<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Cuentas por Pagar Resumido
//  ORGANISMO: Ninguno en particular
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
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_cxpresumido.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_fechadesde,$as_fechahasta,$as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_periodo // Descripci�n del per�odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 12/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_periodo="<b>Del :</b>".$as_fechadesde."   "."<b>Al :</b>".$as_fechahasta;	
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
        $io_pdf->Rectangle(15,700,570,60);
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$io_pdf->addText(250,730,11,$as_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText(220,716,11,$ls_periodo); // Agregar el t�tulo
		$io_pdf->addText(500,765,9,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalleproveedores($la_datapro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalleproveedores
		//		   Access: private 
		//	    Arguments: la_datapro // arreglo de informaci�n de proveedores
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 12/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]= array('codigo'=>"<b>Codigo</b>",'nombre'=>"<b>Proveedor</b>",'saldoant'=>"<b>Saldo Anterior</b>",'mondeb'=>"<b>Debitos</b>",'monhab'=>"<b>Creditos</b>",'saldo'=>"<b>Saldo Actual</b>");
		$la_config=array('showHeadings'=>0,  // Mostrar encabezados
						 'fontSize' => 7,    // Tama�o de Letras
						 'titleFontSize' =>8,// Tama�o de Letras de los t�tulos
						 'showLines'=>1,     // Mostrar L�neas
						 'shaded'=>0,        // Sombra entre l�neas
						 'shadeCol'=>array(1,1,1), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>0.5,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>190),
									   'saldoant'=>array('justification'=>'center','width'=>80),
									   'mondeb'=>array('justification'=>'center','width'=>80),
									   'monhab'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
									   'saldo'=>array('justification'=>'center','width'=>80))); // Justificaci�n y ancho de la columna
		$la_columna = array('codigo'=>'','nombre'=>'','saldoant'=>'','mondeb'=>'','monhab'=>'','saldo'=>'');
		$io_pdf->ezTable($la_data,$la_columna,'Proveedores',$la_config);

		$la_config=array('showHeadings'=>0,  // Mostrar encabezados
						 'fontSize' => 7,    // Tama�o de Letras
						 'titleFontSize' =>8,// Tama�o de Letras de los t�tulos
						 'showLines'=>0,     // Mostrar L�neas
						 'shaded'=>0,        // Sombra entre l�neas
						 'shadeCol'=>array(1,1,1), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>0.5,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>190),
									   'saldoant'=>array('justification'=>'right','width'=>80),
									   'mondeb'=>array('justification'=>'right','width'=>80),
									   'monhab'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
		$la_columna = array('codigo'=>'','nombre'=>'','saldoant'=>'','mondeb'=>'','monhab'=>'','saldo'=>'');
		$io_pdf->ezTable($la_datapro,$la_columna,'',$la_config);
	}// end function uf_print_detalleproveedores
	//--------------------------------------------------------------------------------------------------------------------------------
     
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detallebeneficiarios($la_databen,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallebeneficiarios
		//		   Access: private 
		//	    Arguments: la_databen // arreglo de informaci�n de Beneficiarios
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 12/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]= array('codigo'=>"<b>Codigo</b>",'nombre'=>"<b>Beneficiario</b>",'saldoant'=>"<b>Saldo Anterior</b>",'mondeb'=>"<b>Debitos</b>",'monhab'=>"<b>Creditos</b>",'saldo'=>"<b>Saldo Actual</b>");
		$la_config=array('showHeadings'=>0,  // Mostrar encabezados
						 'fontSize' => 7,    // Tama�o de Letras
						 'titleFontSize' =>8,// Tama�o de Letras de los t�tulos
						 'showLines'=>1,     // Mostrar L�neas
						 'shaded'=>0,        // Sombra entre l�neas
						 'shadeCol'=>array(1,1,1), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>0.5,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>190),
									   'saldoant'=>array('justification'=>'center','width'=>80),
									   'mondeb'=>array('justification'=>'center','width'=>80),
									   'monhab'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
									   'saldo'=>array('justification'=>'center','width'=>80))); // Justificaci�n y ancho de la columna
		$la_columna = array('codigo'=>'','nombre'=>'','saldoant'=>'','mondeb'=>'','monhab'=>'','saldo'=>'');
		$io_pdf->ezTable($la_data,$la_columna,'Beneficiarios',$la_config);

		$la_config=array('showHeadings'=>0,  // Mostrar encabezados
						 'fontSize' => 7,    // Tama�o de Letras
						 'titleFontSize' =>8,// Tama�o de Letras de los t�tulos
						 'showLines'=>0,     // Mostrar L�neas
						 'shaded'=>0,        // Sombra entre l�neas
						 'shadeCol'=>array(1,1,1), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>0.5,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>190),
									   'saldoant'=>array('justification'=>'right','width'=>80),
									   'mondeb'=>array('justification'=>'right','width'=>80),
									   'monhab'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'saldo'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
		$la_columna = array('codigo'=>'','nombre'=>'','saldoant'=>'','mondeb'=>'','monhab'=>'','saldo'=>'');
		$io_pdf->ezTable($la_databen,$la_columna,'',$la_config);
	}// end function uf_print_detallebeneficiarios
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totalesproveedor($ai_totsalantp,$ai_totmondebp,$ai_totmonhabp,$ai_totsalactp,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallebeneficiarios
		//		   Access: private 
		//	    Arguments: $ai_totsalantp // total saldo anterior
		//	   			   $ai_totmondebp // total monto debitos
		//	   			   $ai_totmonhabp // total monto creditos
		//	   			   $ai_totsalactp // total saldo actual
		//	   			   $io_pdf // Objeto PDF
		//    Description: Funci�n que imprime el detalle los totales por proveedor
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 14/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		if ($ai_totsalantp<0)
		{
			$ai_totsalantp= abs($ai_totsalantp);
			$ai_totsalantp= number_format($ai_totsalantp,2,',','.');
			$ai_totsalantp= '('.$ai_totsalantp.')';
		}
		else
		{
			$ai_totsalantp= number_format($ai_totsalantp,2,',','.');
		}
		if ($ai_totsalactp<0)
		{
			$ai_totsalactp= abs($ai_totsalactp);
			$ai_totsalactp= number_format($ai_totsalactp,2,',','.');
			$ai_totsalactp= '('.$ai_totsalactp.')';
		}
		else
		{
			$ai_totsalactp= number_format($ai_totsalactp,2,',','.');
		}
		$la_data  =array(array('totales'=>'<b>TOTAL '.$ls_titulo.'</b>','totsalantp'=>$ai_totsalantp,'totmondebp'=>$ai_totmondebp,'totmonhabp'=>$ai_totmonhabp,'totsalactp'=>$ai_totsalactp));
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,      // Tama�o de Letras
						 'titleFontSize' =>8,   // Tama�o de Letras de los t�tulos
						 'showLines'=>1,        // Mostrar L�neas
						 'shaded'=>2,           // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>250),
						               'totsalantp'=>array('justification'=>'right','width'=>80),
									   'totmondebp'=>array('justification'=>'right','width'=>80),
									   'totmonhabp'=>array('justification'=>'right','width'=>80),
									   'totsalactp'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
	    $la_columna=array('totales'=>'','totsalantp'=>'','totmondebp'=>'','totmonhabp'=>'','totsalactp'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totalesbeneficiario($ai_totsalantb,$ai_totmondebb,$ai_totmonhabb,$ai_totsalactb,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallebeneficiarios
		//		   Access: private 
		//	    Arguments: $ai_totsalantp // total saldo anterior
		//	   			   $ai_totmondebp // total monto debitos
		//	   			   $ai_totmonhabp // total monto creditos
		//	   			   $ai_totsalactp // total saldo actual
		//	   			   $io_pdf // Objeto PDF
		//    Description: Funci�n que imprime el detalle los totales por proveedor
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 14/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_titulo=" Bs.F.";
		}
		else
		{
			$ls_titulo=" Bs.";
		}
		if ($ai_totsalantb<0)
		{
			$ai_totsalantb= abs($ai_totsalantb);
			$ai_totsalantb= number_format($ai_totsalantb,2,',','.');
			$ai_totsalantb= '('.$ai_totsalantb.')';
		}
		else
		{
			$ai_totsalantb= number_format($ai_totsalantb,2,',','.');
		}
		if ($ai_totsalactb<0)
		{
			$ai_totsalactb= abs($ai_totsalactb);
			$ai_totsalactb= number_format($ai_totsalactb,2,',','.');
			$ai_totsalactb= '('.$ai_totsalactb.')';
		}
		else
		{
			$ai_totsalactb= number_format($ai_totsalactb,2,',','.');
		}
		$la_data  =array(array('totales'=>'<b>TOTAL '.$ls_titulo.'</b>','totsalantb'=>$ai_totsalantb,'totmondebb'=>$ai_totmondebb,'totmonhabb'=>$ai_totmonhabb,'totsalactb'=>$ai_totsalactb));
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,      // Tama�o de Letras
						 'titleFontSize' =>8,   // Tama�o de Letras de los t�tulos
						 'showLines'=>1,        // Mostrar L�neas
						 'shaded'=>2,           // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>250),
						               'totsalantb'=>array('justification'=>'right','width'=>80),
									   'totmondebb'=>array('justification'=>'right','width'=>80),
									   'totmonhabb'=>array('justification'=>'right','width'=>80),
									   'totsalactb'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
	    $la_columna=array('totales'=>'','totsalantb'=>'','totmondebb'=>'','totmonhabb'=>'','totsalactb'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totalclasificador($ai_totsalant,$ai_totmondeb,$ai_totmonhab,$ai_totsalact,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallebeneficiarios
		//		   Access: private 
		//	    Arguments: $ai_totsalantp // total saldo anterior
		//	   			   $ai_totmondebp // total monto debitos
		//	   			   $ai_totmonhabp // total monto creditos
		//	   			   $ai_totsalactp // total saldo actual
		//	   			   $io_pdf // Objeto PDF
		//    Description: Funci�n que imprime el detalle los totales por proveedor
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 14/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totmondeb= number_format($ai_totmondeb,2,',','.');
		$ai_totmonhab= number_format($ai_totmonhab,2,',','.');
		if ($ai_totsalant<0)
		{
			$ai_totsalant= abs($ai_totsalant);
			$ai_totsalant= number_format($ai_totsalant,2,',','.');
			$ai_totsalant= '('.$ai_totsalant.')';
		}
		else
		{
			$ai_totsalant= number_format($ai_totsalant,2,',','.');
		}
		if ($ai_totsalact<0)
		{
			$ai_totsalact= abs($ai_totsalact);
			$ai_totsalact= number_format($ai_totsalact,2,',','.');
			$ai_totsalact= '('.$ai_totsalact.')';
		}
		else
		{
			$ai_totsalact= number_format($ai_totsalact,2,',','.');
		}
		$la_data  =array(array('totales'=>'<b>TOTAL POR CLASIFICADOR </b>','totsalantb'=>$ai_totsalant,'totmondebb'=>$ai_totmondeb,'totmonhabb'=>$ai_totmonhab,'totsalactb'=>$ai_totsalact));
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,      // Tama�o de Letras
						 'titleFontSize' =>8,   // Tama�o de Letras de los t�tulos
						 'showLines'=>1,        // Mostrar L�neas
						 'shaded'=>2,           // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>250),
						               'totsalantb'=>array('justification'=>'right','width'=>80),
									   'totmondebb'=>array('justification'=>'right','width'=>80),
									   'totmonhabb'=>array('justification'=>'right','width'=>80),
									   'totsalactb'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
	    $la_columna=array('totales'=>'','totsalantb'=>'','totmondebb'=>'','totmonhabb'=>'','totsalactb'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totalgeneral($ai_totsalant,$ai_totmondeb,$ai_totmonhab,$ai_totsalact,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detallebeneficiarios
		//		   Access: private 
		//	    Arguments: $ai_totsalantp // total saldo anterior
		//	   			   $ai_totmondebp // total monto debitos
		//	   			   $ai_totmonhabp // total monto creditos
		//	   			   $ai_totsalactp // total saldo actual
		//	   			   $io_pdf // Objeto PDF
		//    Description: Funci�n que imprime el detalle los totales por proveedor
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 14/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$ai_totmondeb= number_format($ai_totmondeb,2,',','.');
		$ai_totmonhab= number_format($ai_totmonhab,2,',','.');
		if ($ai_totsalant<0)
		{
			$ai_totsalant= abs($ai_totsalant);
			$ai_totsalant= number_format($ai_totsalant,2,',','.');
			$ai_totsalant= '('.$ai_totsalant.')';
		}
		else
		{
			$ai_totsalant= number_format($ai_totsalant,2,',','.');
		}
		if ($ai_totsalact<0)
		{
			$ai_totsalact= abs($ai_totsalact);
			$ai_totsalact= number_format($ai_totsalact,2,',','.');
			$ai_totsalact= '('.$ai_totsalact.')';
		}
		else
		{
			$ai_totsalact= number_format($ai_totsalact,2,',','.');
		}
		$la_data  =array(array('totales'=>'<b>TOTAL GENERAL </b>','totsalantb'=>$ai_totsalant,'totmondebb'=>$ai_totmondeb,'totmonhabb'=>$ai_totmonhab,'totsalactb'=>$ai_totsalact));
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,      // Tama�o de Letras
						 'titleFontSize' =>8,   // Tama�o de Letras de los t�tulos
						 'showLines'=>1,        // Mostrar L�neas
						 'shaded'=>2,           // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>250),
						               'totsalantb'=>array('justification'=>'right','width'=>80),
									   'totmondebb'=>array('justification'=>'right','width'=>80),
									   'totmonhabb'=>array('justification'=>'right','width'=>80),
									   'totsalactb'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
	    $la_columna=array('totales'=>'','totsalantb'=>'','totmondebb'=>'','totmonhabb'=>'','totsalactb'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_clasificador($as_codcla,$as_dencla,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_clasificador
		//		   Access: private 
		//	    Arguments: $as_codcla // codigo de clasificador
		//	   			   $as_dencla // denominacion de clasificador
		//	   			   $io_pdf // Objeto PDF
		//    Description: Funci�n que imprime el detalle los totales por proveedor
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 14/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($as_codcla=="--")
		{
			$as_dencla="NINGUNO";
		}
//		$io_pdf->ezSetY(-2);
		$io_pdf->ezSetDy(-10);
		$la_data  =array(array('data'=>'<b>CLASIFICADOR: '.$as_codcla.' - '.$as_dencla.'</b>'));
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 9,      // Tama�o de Letras
						 'titleFontSize' =>8,   // Tama�o de Letras de los t�tulos
						 'showLines'=>1,        // Mostrar L�neas
						 'shaded'=>2,           // Sombra entre l�neas
						 'shadeCol'=>array(0.5,0.5,0.5), // Color de la sombra
						// 'shadeCol2'=>array(1,1,1), // Color de la sombra
 						 'colGap'=>1,
						 'width'=>530, // Ancho de la tabla
						 'maxWidth'=>530, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('data'=>array('justification'=>'left','width'=>570))); // Justificaci�n y ancho de la columna
	    $la_columna=array('data'=>'');
	    $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	 }
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_dsctasxpagar= new class_datastore();
	$io_dsctasxpagar = new class_datastore();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo= "<b>Cuentas Por Pagar</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$li_excluir=$io_fun_cxp->uf_obtenervalor_get("excluir",0);
	$li_intervalo=$io_fun_cxp->uf_obtenervalor_get("intervalo",1);
	$ld_fecemides=$io_fun_cxp->uf_obtenervalor_get("fecemides","");
	$ld_fecemihas=$io_fun_cxp->uf_obtenervalor_get("fecemihas","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	$lb_valido= $io_report->uf_obtener_clasificador();
	$ls_estretiva=$_SESSION["la_empresa"]["estretiva"];
	if ($lb_valido==false) // Existe alg�n error � no hay registros.
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar ');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{

		error_reporting(E_ALL);
		$io_pdf=new Cezpdf('LETTER','portrait');                       // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,2,3,3);                            // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ld_fecemides,$ld_fecemihas,$ls_titulo,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(750,50,8,'','',1); // Insertar el n�mero de p�gina
		$li_totgeneralant=0;
		$li_totgeneralact=0;
		$li_totgeneraldeb=0;
		$li_totgeneralhab=0;
		while(!$io_report->rs_clasificador->EOF)
		{
			$la_databen="";
			$la_datapro="";
			$ls_codcla=$io_report->rs_clasificador->fields["codcla"];
			$ls_dencla=$io_report->rs_clasificador->fields["dencla"];
			if($li_intervalo==1)
			{
				$lb_existe=$io_report->uf_select_solicitudesclasificador("","","",$ld_fecemides,$ld_fecemihas,$ls_codcla);//print $ls_codcla."<br>";
			}
			else
			{
				$lb_existe=$io_report->uf_select_solicitudesclasificador("","","","",$ld_fecemihas,$ls_codcla);//print $ls_codcla."<br>";
			}
			$li_p=0;
			$li_q=0;
			$li_totgendeb=0;
			$li_totgenhab=0;
			$li_totgensal=0;
			$li_totmondebp=0;
			$li_totmonhabp=0;
			$li_totmondebb=0;
			$li_totmonhabb=0;
			$li_totsalantp=0;
			$li_totsalactp=0;
			$li_totsalantb=0;
			$li_totsalactb=0;
			$li_totclasificadorant=0;
			$li_totclasificadoract=0;
			$li_totclasificadordeb=0;
			$li_totclasificadorhab=0;
			$lb_valido=true;
			if($lb_existe)
			{
				uf_print_clasificador($ls_codcla,$ls_dencla,&$io_pdf);
				while(!$io_report->rs_data->EOF)
				{
					$li_salsol=0;
					$li_totaldebe=0;
					$li_totalhaber=0;
					$li_totalsaldo=0;
					$ls_tipproben=$io_report->rs_data->fields['tipproben'];
					$ls_cedbene= $io_report->rs_data->fields['ced_bene'];
					$ls_codpro= $io_report->rs_data->fields['cod_pro'];
					$ls_nombre= $io_report->rs_data->fields['nombre'];
					if($ls_tipproben=="B")
					{
						$ls_codigo=$ls_cedbene;
					}
					else
					{
						$ls_codigo=$ls_codpro;
					}
					//	print "CODCLA=>".$ls_codcla."  PROBEN=>".$ls_codigo."<br><br>";
					if($lb_valido)
					{
						$li_monsolpre=0;
						//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
						$lb_valido= $io_report->uf_select_solicitudes_previasclasificador($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas,$ls_codcla);
						if($lb_valido)
						{
							$li_solcont=0;
							$li_solanul=0;
							while(!$io_report->rs_solprevias->EOF)
							{
								$ls_estatus= $io_report->rs_solprevias->fields['estatus'];
								$li_monsol= $io_report->rs_solprevias->fields['monsol'];
								$ls_numsolaux= $io_report->rs_solprevias->fields['numsol'];
								if($ls_estretiva=="B")
								{
									$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsolaux);
									$li_monsol=$li_monsol+$li_monretiva;
								}
								switch ($ls_estatus)
								{
									case "C":
										$li_solcont=($li_solcont+$li_monsol);
									break;
									case "A":
										$li_solanul=($li_solanul+$li_monsol);
									break;
								}
								$io_report->rs_solprevias->MoveNext();
							}
							$li_monsolpre=($li_solcont-$li_solanul);
						}
						$io_report->io_sql->free_result($io_report->rs_solprevias);
						$li_monpagpre=0;
						$lb_valido= $io_report->uf_select_pagospreviosclasificador($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas,$ls_codcla,
																	   $li_monpagpre,$li_monretpre);
																					   
						if($ls_estretiva=="B")
						{
							$li_monpagpre=$li_monpagpre+$li_monretpre;
						}
						$li_monsalant=($li_monsolpre-$li_monpagpre);
						if($li_monsalant>0)
						{
							$ls_saldoanterior= number_format($li_monsalant,2,',','.');
							$ls_saldoanterior="(".$ls_saldoanterior.")";
						}
						else
						{
							$ls_saldoanterior= abs($li_monsalant);
							$ls_saldoanterior= number_format($ls_saldoanterior,2,',','.');
						}
//						print "CODCLA=>".$ls_codcla."  PROBEN=>".$ls_codigo."  MONTO=>".$li_monsalant."<br><br>";
						//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
		
						//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
						$lb_valido= $io_report->uf_select_solicitudesactualesclasificacion($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas,$ls_codcla);
						if($lb_valido)
						{
							$li_salsol=$li_monsalant;
							while(!$io_report->rs_solicitudes->EOF)
							{
								$ls_numsol= $io_report->rs_solicitudes->fields['numsol'];
								$ls_estprodoc= $io_report->rs_solicitudes->fields['estprodoc'];
								$ls_consol= $io_report->rs_solicitudes->fields['consol'];
								$li_monsol= $io_report->rs_solicitudes->fields['montot']; //Monto de la Solicitudes de Pago actuales.
								$ld_fecsol= $io_report->rs_solicitudes->fields['fecha'];
								if($ls_estretiva=="B")
								{
									$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
									$li_monsol=$li_monsol+$li_monretiva;
								}
								$li_salsol= $li_salsol+$li_monsol;
								$li_totalhaber=$li_totalhaber+$li_monsol;
								$ld_fecsol=$io_funciones->uf_convertirfecmostrar($ld_fecsol);
								$ls_monto= number_format($li_monsol,2,',','.');
								$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
								$io_report->rs_solicitudes->MoveNext();
							}
						}
						$io_report->io_sql->free_result($io_report->rs_solicitudes);
						//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
							
						//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
						$lb_valido=$io_report->uf_select_informacionndncclasificador($ls_tipproben,$ls_codigo,$ld_fecemides,$ld_fecemihas,"",$ls_codcla);
						if($lb_valido)
						{
							while(!$io_report->rs_ndnc->EOF)
							{
								$ls_numdc= $io_report->rs_ndnc->fields['numdc'];
								$ls_codope= $io_report->rs_ndnc->fields['codope'];
								$ls_desope= $io_report->rs_ndnc->fields['desope'];
								$li_monto=  $io_report->rs_ndnc->fields['montot']; //Monto de la Solicitudes de Pago actuales.
								$ld_fecope= $io_report->rs_ndnc->fields['fecope'];
								if($ls_codope=="ND")
								{
									$li_salsol= $li_salsol+$li_monto;
									$li_debe=0;
									$li_haber=$li_monto;
									$ls_procedencia="Debito";
									$li_totalhaber=$li_totalhaber+$li_monto;
								}
								else
								{
									$li_salsol= $li_salsol-$li_monto;
									$li_debe=$li_monto;
									$li_haber=0;
									$ls_procedencia="Credito";
									$li_totaldebe=$li_totaldebe+$li_monto;
								}
								$ld_fecope=$io_funciones->uf_convertirfecmostrar($ld_fecope);
								$li_debe= number_format($li_debe,2,',','.');
								$li_haber= number_format($li_haber,2,',','.');
								$li_salsol=round($li_salsol,2);
								if(doubleval($li_salsol)>0)
								{
									$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
								}
								else
								{
									$ls_salsol= abs($li_salsol);
									$ls_salsol= number_format($ls_salsol,2,',','.');
								}
								$io_report->rs_ndnc->MoveNext();
							}
						}
						$io_report->io_sql->free_result($io_report->rs_ndnc);
						//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
						
						//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
						$lb_valido=$io_report->uf_select_informacionpagoscxpclasificador($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas,"",$ls_codcla);
						if($lb_valido)
						{
							while(!$io_report->rs_pagactuales->EOF)
							{
								$ls_salsol="";
								$ls_numsol= $io_report->rs_pagactuales->fields['numsol'];
								$ls_codope= $io_report->rs_pagactuales->fields['codope'];
								$ls_conmov= $io_report->rs_pagactuales->fields['conmov'];
								$li_monto= $io_report->rs_pagactuales->fields['monto']; //Monto de la Solicitudes de Pago actuales.
								$ld_fecmov= $io_report->rs_pagactuales->fields['fecmov'];
								$ls_estmov= $io_report->rs_pagactuales->fields['estmov'];
								if($ls_estretiva=="B")
								{
									$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
									$li_monto=$li_monto+$li_monretiva;
								}
								if ($ls_estmov=='O' || $ls_estmov=='C')
								{
									$li_salsol= $li_salsol-$li_monto;
									$li_totaldebe=$li_totaldebe+$li_monto;
									$ld_debe=number_format($li_monto,2,',','.');
									$ld_haber="0,00";
									$ls_anulado ="";
								}
								else 
								{
									$li_salsol= $li_salsol+$li_monto;
									$li_totalhaber=$li_totalhaber+$li_monto;
									$ld_debe="0,00";
									$ld_haber=number_format($li_monto,2,',','.');
									$ls_anulado =" Anulado";
								}
								$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
								$li_salsol=round($li_salsol,2);
								if(doubleval($li_salsol)>0)
								{
									$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
								}
								else
								{
									$ls_salsol= abs($li_salsol);
									$ls_salsol= number_format($ls_salsol,2,',','.');
								}
								switch ($ls_codope)
								{
									case "CH":
										$ls_procedencia="Cheque".$ls_anulado;
									break;
									case "ND":
										$ls_procedencia="Nota de Debito";
									break;
								}
								$ls_monto= number_format($li_monto,2,',','.');
								$io_report->rs_pagactuales->MoveNext();
							}
						}
						$io_report->io_sql->free_result($io_report->rs_pagactuales);
						//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
					}
					else
					{
						break;
					}
					$li_totalsaldo=$li_salsol;
					/*$li_totgendeb=$li_totgendeb+$li_totaldebe;
					$li_totgenhab=$li_totgenhab+$li_totalhaber;*/
					$li_totgensal=$li_totgensal+$li_totalsaldo;
					if(doubleval($li_totalsaldo)>0)
					{
						$li_totalsaldo= "(".number_format($li_totalsaldo,2,',','.').")";
					}
					else
					{
						$li_totalsaldo= abs($li_totalsaldo);
						$li_totalsaldo= number_format($li_totalsaldo,2,',','.');
					}
		/*			$li_totalhaber= number_format($li_totalhaber,2,',','.');
					$li_totaldebe= number_format($li_totaldebe,2,',','.');
		*/			if(($li_excluir==0)||($li_salsol>0))
					{
							if($ls_tipproben=="P")
							{
								$li_p++;
								//print $ls_codigo."  SALANT".$li_monsalant."   ACUMULADO".
								$li_totsalantp= $li_totsalantp + $li_monsalant;
								$li_totsalactp= $li_totsalactp + $li_salsol;
								$li_totmondebp= $li_totmondebp + $li_totaldebe;
								$li_totmonhabp= $li_totmonhabp + $li_totalhaber;
								$li_montodebe= number_format($li_totaldebe,2,',','.');
								$li_montohaber= number_format($li_totalhaber,2,',','.');
								$la_datapro[$li_p]= array('codigo'=>$ls_codigo,'nombre'=>$ls_nombre,'saldoant'=>$ls_saldoanterior,'mondeb'=>$li_montodebe,'monhab'=>$li_montohaber,'saldo'=>$li_totalsaldo);
							}
							else
							{
								$li_q++;
								$li_totsalantb= $li_totsalantb + $li_monsalant;
								$li_totsalactb= $li_totsalactb + $li_salsol;
								$li_totmondebb= $li_totmondebb + $li_totaldebe;
								$li_totmonhabb= $li_totmonhabb + $li_totalhaber;
								$li_montodebe= number_format($li_totaldebe,2,',','.');
								$li_montohaber= number_format($li_totalhaber,2,',','.');
								$la_databen[$li_q]= array('codigo'=>$ls_codigo,'nombre'=>$ls_nombre,'saldoant'=>$ls_saldoanterior,'mondeb'=>$li_montodebe,'monhab'=>$li_montohaber,'saldo'=>$li_totalsaldo);
							}
		
					}
					if(!$lb_valido)
					{break;}
					$io_report->rs_data->MoveNext();
				}// fin for uf_select_solicitudes
				$io_report->io_sql->free_result($io_report->rs_data);
				$li_totclasificadorant=$li_totsalantp+$li_totsalantb;
				$li_totclasificadoract=$li_totsalactp+$li_totsalactb;
				$li_totclasificadordeb=$li_totmondebp+$li_totmondebb;
				$li_totclasificadorhab=$li_totmonhabp+$li_totmonhabb;
				$li_totgeneralant=$li_totgeneralant+$li_totclasificadorant;
				$li_totgeneralact=$li_totgeneralact+$li_totclasificadoract;
				$li_totgeneraldeb=$li_totgeneraldeb+$li_totclasificadordeb;
				$li_totgeneralhab=$li_totgeneralhab+$li_totclasificadorhab;
				$li_totmondebp= number_format($li_totmondebp,2,',','.');
				$li_totmonhabp= number_format($li_totmonhabp,2,',','.');
				$li_totmondebb= number_format($li_totmondebb,2,',','.');
				$li_totmonhabb= number_format($li_totmonhabb,2,',','.');
				if($la_datapro!="")
				{
					uf_print_detalleproveedores($la_datapro,$io_pdf); // Imprimimos el detalle  
					uf_print_totalesproveedor($li_totsalantp,$li_totmondebp,$li_totmonhabp,$li_totsalactp,&$io_pdf);
				}
				if($la_databen!="")
				{
					uf_print_detallebeneficiarios($la_databen,$io_pdf); // Imprimimos el detalle  
					uf_print_totalesbeneficiario($li_totsalantb,$li_totmondebb,$li_totmonhabb,$li_totsalactb,&$io_pdf);
				}
				uf_print_totalclasificador($li_totclasificadorant,$li_totclasificadordeb,$li_totclasificadorhab,$li_totclasificadoract,&$io_pdf);
			}
			unset($la_datapro);
			unset($la_databen);
			$io_report->rs_clasificador->MoveNext();
		}		
		uf_print_totalgeneral($li_totgeneralant,$li_totgeneraldeb,$li_totgeneralhab,$li_totgeneralact,&$io_pdf);
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
			unset($io_pdf);
			unset($io_report);
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generarse el Reporte');"); 
		//	print(" close();");
			print("</script>");
		}
	}
?>