<?PHP
    session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();"	;
		print "close();";
		print "</script>";
	}
	//---------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_compra;
		$ls_descripcion="Generó el Reporte Análisis de Cotización";
		$lb_valido=$io_fun_compra->uf_load_seguridad_reporte("SOC","sigesp_soc_p_analisis_cotizacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_numanacot,$ad_fecha,$ds_contcol,$ls_countcot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el banner del reporte
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 17/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_nomemp = strtoupper($_SESSION["la_empresa"]["nombre"]);
		$ls_titemp = strtoupper($_SESSION["la_empresa"]["titulo"]);
		$io_encabezado=$io_pdf->openObject();
        $io_pdf->saveState();
        $io_pdf->setStrokeColor(0,0,0);
        $io_pdf->addJpegFromFile('../../shared/imagebank/banner_cnti.jpg',38,560,740,35); // Agregar Logo
        $io_pdf->addText(230,548,12,"<b>".$ls_nomemp." (".$ls_titemp.")</b>"); // Agregar el título
        $io_pdf->addText(350,535,10,"<b>ANALISIS DE COTIZACIÓN</b>");
        $io_pdf->addText(670,548,9,"<b> CARACAS, ".$ad_fecha."</b>"); // Agregar la Fecha
        $io_pdf->addText(670,540,8,"<b> N° :  </b>".$as_numanacot); // Agregar la Fecha
        
        $io_pdf->ezSetY(530); 
        
        //$ls_countcot = 6;
        
        $xPos = 37;
        $width = 590;
        $la_columnas = array('columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'');
        $sizecols 	 = array('columna1'=>array('justification'=>'center','width'=>185),
                    	   	'columna2'=>array('justification'=>'center','width'=>180),
                            'columna3'=>array('justification'=>'center','width'=>180),
                            'columna4'=>array('justification'=>'center','width'=>180));
        
        $la_data_cabezal = array(	'columna1'=>'<b>N°</b>',
					        		'columna2'=>'<b>DESCRIPCIÓN</b>',
        							'columna3'=>'<b>CANT SOL</b>',
					        		'columna4'=>'<b>CANTIDAD</b>',
					        		'columna5'=>'<b>PRECIO UNITARIO</b>',
					        		'columna6'=>'<b>TOTAL</b>',
					        		'columna7'=>'<b>CANTIDAD</b>',
					        		'columna8'=>'<b>PRECIO UNITARIO</b>',
					        		'columna9'=>'<b>TOTAL</b>',
					        		'columna10'=>'<b>CANTIDAD</b>',
					        		'columna11'=>'<b>PRECIO UNITARIO</b>',
					        		'columna12'=>'<b>TOTAL</b>');
        
        $la_cols_cabezal = array(	'columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'','columna5'=>'',
					        		'columna6'=>'','columna7'=>'','columna8'=>'','columna9'=>'','columna10'=>'','columna11'=>'','columna12'=>'');  

        $cant = 60;
        $pUnit = 60;
        $cTot = 60;
        
        $sizecols_cabelzal = array(	'columna1'	=>array('justification'=>'left','width'=>16),
					        		'columna2'	=>array('justification'=>'center','width'=>141),
        							'columna3'	=>array('justification'=>'center','width'=>28),
					        		'columna4'	=>array('justification'=>'center','width'=>$cant),
					        		'columna5'	=>array('justification'=>'center','width'=>$pUnit),
					        		'columna6'	=>array('justification'=>'center','width'=>$cTot),
					        		'columna7'	=>array('justification'=>'center','width'=>$cant),
					        		'columna8'	=>array('justification'=>'center','width'=>$pUnit),
					        		'columna9'	=>array('justification'=>'center','width'=>$cTot),
					        		'columna10'	=>array('justification'=>'center','width'=>$cant),
					        		'columna11'	=>array('justification'=>'center','width'=>$pUnit),
					        		'columna12'	=>array('justification'=>'center','width'=>$cTot));
        
        if ($ls_countcot > 3){
        	$la_columnas 	= array('columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'', 'columna5'=>'');
        	
        	$xPos = 17.4;
        	$desc = 169;
        	$nro = 16;
        	$cant = 40;
        	$pUnit = 53;
        	$cTot = 53;
        	$sumCols = $cant + $pUnit + $cTot;
        	
        	$sizecols 	 	= array('columna1'=>array('justification'=>'center','width'=>$desc + $nro),
        							'columna2'=>array('justification'=>'center','width'=>$sumCols),
        							'columna3'=>array('justification'=>'center','width'=>$sumCols),
        							'columna4'=>array('justification'=>'center','width'=>$sumCols),
        							'columna5'=>array('justification'=>'center','width'=>$sumCols-2));
        	
        	$la_data_cabezal = array(	'columna1'=>'<b>N°</b>',
		                          		'columna2'=>'<b>DESCRIPCIÓN</b>',
        								'columna3'=>'<b>CANT SOL</b>',
		                          		'columna4'=>'<b>CANTIDAD</b>',
		                          		'columna5'=>'<b>PRECIO UNITARIO</b>',
		                          		'columna6'=>'<b>TOTAL</b>',
		                          		'columna7'=>'<b>CANTIDAD</b>',
		                          		'columna8'=>'<b>PRECIO UNITARIO</b>',
		                          		'columna9'=>'<b>TOTAL</b>',
		                          		'columna10'=>'<b>CANTIDAD</b>',
		                          		'columna11'=>'<b>PRECIO UNITARIO</b>',
		                         		'columna12'=>'<b>TOTAL</b>',
						        		'columna13'=>'<b>CANTIDAD</b>',
						        		'columna14'=>'<b>PRECIO UNITARIO</b>',
						        		'columna15'=>'<b>TOTAL</b>');
        	
        	$la_cols_cabezal = array(	'columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'','columna5'=>'',
        								'columna6'=>'','columna7'=>'','columna8'=>'','columna9'=>'','columna10'=>'','columna11'=>'',
        								'columna12'=>'','columna13'=>'','columna14'=>'','columna15'=>'');
        	
        	$sizecols_cabelzal = array(	'columna1'=>array('justification'=>'left','width'=>$nro),
					        			'columna2'=>array('justification'=>'center','width'=>$desc-28),
        								'columna3'=>array('justification'=>'center','width'=>28),
					        			'columna4'=>array('justification'=>'center','width'=>$cant),
					        			'columna5'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna6'=>array('justification'=>'center','width'=>$cTot),
					        			'columna7'=>array('justification'=>'center','width'=>$cant),
					        			'columna8'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna9'=>array('justification'=>'center','width'=>$cTot),
					        			'columna10'=>array('justification'=>'center','width'=>$cant),
					        			'columna11'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna12'=>array('justification'=>'center','width'=>$cTot),
					        			'columna13'=>array('justification'=>'center','width'=>$cant),
					        			'columna14'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna15'=>array('justification'=>'center','width'=>$cTot-2));
        	
        }
        if ($ls_countcot > 4){
        	$la_columnas	= array('columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'', 'columna5'=>'', 'columna6'=>'');
        	
        	$xPos = 17.4;
        	$desc = 114;
        	$nro = 16;
        	$cant = 30.8;
        	$pUnit = 48.1;
        	$cTot = 48.1;
        	$sumCols = $cant + $pUnit + $cTot;
        	
        	$sizecols 		= array('columna1'=>array('justification'=>'center','width'=>$desc + $nro),
        							'columna2'=>array('justification'=>'center','width'=>$sumCols),
        							'columna3'=>array('justification'=>'center','width'=>$sumCols),
        							'columna4'=>array('justification'=>'center','width'=>$sumCols),
        							'columna5'=>array('justification'=>'center','width'=>$sumCols),
        							'columna6'=>array('justification'=>'center','width'=>$sumCols));
        	
        	$la_data_cabezal = array(	'columna1'=>'<b>N°</b>',
					        			'columna2'=>'<b>DESCRIPCIÓN</b>',
        								'columna3'=>'<b>CANT SOL</b>',
					        			'columna4'=>'<b>CANT</b>',
					        			'columna5'=>'<b>PRECIO UNITARIO</b>',
					        			'columna6'=>'<b>TOTAL</b>',
					        			'columna7'=>'<b>CANT</b>',
					        			'columna8'=>'<b>PRECIO UNITARIO</b>',
					        			'columna9'=>'<b>TOTAL</b>',
					        			'columna10'=>'<b>CANT</b>',
					        			'columna11'=>'<b>PRECIO UNITARIO</b>',
					        			'columna12'=>'<b>TOTAL</b>',
					        			'columna13'=>'<b>CANT</b>',
					        			'columna14'=>'<b>PRECIO UNITARIO</b>',
					        			'columna15'=>'<b>TOTAL</b>',
        								'columna16'=>'<b>CANT</b>',
					        			'columna17'=>'<b>PRECIO UNITARIO</b>',
					        			'columna18'=>'<b>TOTAL</b>');
        	
        	$la_cols_cabezal = array(	'columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'','columna5'=>'',
					        			'columna6'=>'','columna7'=>'','columna8'=>'','columna9'=>'','columna10'=>'','columna11'=>'',
					        			'columna12'=>'','columna13'=>'','columna14'=>'','columna15'=>'','columna16'=>'','columna17'=>'','columna18'=>'');
        	
        	$sizecols_cabelzal = array(	'columna1'=>array('justification'=>'left','width'=>$nro),
					        			'columna2'=>array('justification'=>'center','width'=>$desc-28),
					        			'columna3'=>array('justification'=>'center','width'=>28),
        								'columna4'=>array('justification'=>'center','width'=>$cant),
					        			'columna5'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna6'=>array('justification'=>'center','width'=>$cTot),
					        			'columna7'=>array('justification'=>'center','width'=>$cant),
					        			'columna8'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna9'=>array('justification'=>'center','width'=>$cTot),
					        			'columna10'=>array('justification'=>'center','width'=>$cant),
					        			'columna11'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna12'=>array('justification'=>'center','width'=>$cTot),
					        			'columna13'=>array('justification'=>'center','width'=>$cant),
					        			'columna14'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna15'=>array('justification'=>'center','width'=>$cTot),
					        			'columna16'=>array('justification'=>'center','width'=>$cant),
					        			'columna17'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna18'=>array('justification'=>'center','width'=>$cTot));
        }
        if ($ls_countcot > 5){  
        	$la_columnas	= array('columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'', 'columna5'=>'', 'columna6'=>'', 'columna7'=>'');
        	
        	$xPos = 17.4;
        	$desc = 64;
        	$nro = 16; 
        	$cant = 24;
        	$pUnit = 45.1;
        	$cTot = 45.4;
        	$sumCols = $cant + $pUnit + $cTot;
        	
        	$sizecols 		= array('columna1'=>array('justification'=>'center','width'=>$desc + $nro),
        							'columna2'=>array('justification'=>'center','width'=>$sumCols),
        							'columna3'=>array('justification'=>'center','width'=>$sumCols),
        							'columna4'=>array('justification'=>'center','width'=>$sumCols),
        							'columna5'=>array('justification'=>'center','width'=>$sumCols),
        							'columna6'=>array('justification'=>'center','width'=>$sumCols),
        							'columna7'=>array('justification'=>'center','width'=>$sumCols));
        	
        	$la_data_cabezal = array(	'columna1'=>'<b>N°</b>',
					        			'columna2'=>'<b>DESCRIPCIÓN</b>',
        								'columna3'=>'<b>CANT</b>',
					        			'columna4'=>'<b>PRECIO UNIT</b>',
					        			'columna5'=>'<b>TOTAL</b>',
					        			'columna6'=>'<b>CANT</b>',
					        			'columna7'=>'<b>PRECIO UNIT</b>',
					        			'columna8'=>'<b>TOTAL</b>',
					        			'columna9'=>'<b>CANT</b>',
					        			'columna10'=>'<b>PRECIO UNIT</b>',
					        			'columna11'=>'<b>TOTAL</b>',
					        			'columna12'=>'<b>CANT</b>',
					        			'columna13'=>'<b>PRECIO UNIT</b>',
					        			'columna14'=>'<b>TOTAL</b>',
					        			'columna15'=>'<b>CANT</b>',
					        			'columna16'=>'<b>PRECIO UNIT</b>',
					        			'columna17'=>'<b>TOTAL</b>',
        								'columna18'=>'<b>CANT</b>',
					        			'columna19'=>'<b>PRECIO UNIT</b>',
					        			'columna20'=>'<b>TOTAL</b>');
        	
        	$la_cols_cabezal = array(	'columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'','columna5'=>'',
					        			'columna6'=>'','columna7'=>'','columna8'=>'','columna9'=>'','columna10'=>'','columna11'=>'',
					        			'columna12'=>'','columna13'=>'','columna14'=>'','columna15'=>'','columna16'=>'','columna17'=>'',
        								'columna18'=>'','columna19'=>'','columna20'=>'');
        	
        	$sizecols_cabelzal = array(	'columna1'=>array('justification'=>'left','width'=>$nro),
					        			'columna2'=>array('justification'=>'center','width'=>$desc),
					        			'columna3'=>array('justification'=>'center','width'=>$cant),
					        			'columna4'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna5'=>array('justification'=>'center','width'=>$cTot),
					        			'columna6'=>array('justification'=>'center','width'=>$cant),
					        			'columna7'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna8'=>array('justification'=>'center','width'=>$cTot),
					        			'columna9'=>array('justification'=>'center','width'=>$cant),
					        			'columna10'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna11'=>array('justification'=>'center','width'=>$cTot),
					        			'columna12'=>array('justification'=>'center','width'=>$cant),
					        			'columna13'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna14'=>array('justification'=>'center','width'=>$cTot),
					        			'columna15'=>array('justification'=>'center','width'=>$cant),
					        			'columna16'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna17'=>array('justification'=>'center','width'=>$cTot),
					        			'columna18'=>array('justification'=>'center','width'=>$cant),
					        			'columna19'=>array('justification'=>'center','width'=>$pUnit),
					        			'columna20'=>array('justification'=>'center','width'=>$cTot));
        }
        
        $la_config=array('showHeadings'=>0, // Mostrar encabezados
        		'fontSize' => 6, // Tamaño de Letras
        		'titleFontSize' => 6,  // Tamaño de Letras de los títulos
        		'showLines'=>1, // Mostrar Líneas
        		'shaded'=>0,   // Sombra entre líneas
        		'width'=>$width, // Ancho de la tabla
        		'maxWidth'=>$width, // Ancho Máximo de la tabla
        		'xOrientation'=>'right', // Orientación de la tabla
        		'xPos'=>$xPos,
        		'cols'=>$sizecols); // Justificación y ancho de la columna
        
        $la_data[1]=array('columna1'=>'<b>EMPRESA</b>',
                          'columna2'=>'<b>PRIMERA COTIZACIÓN</b>',
                          'columna3'=>'<b>SEGUNDA COTIZACIÓN</b>',
                          'columna4'=>'<b>TERCERA COTIZACIÓN</b>',
        				  'columna5'=>'<b>CUARTA COTIZACIÓN</b>',
        				  'columna6'=>'<b>QUINTA COTIZACIÓN</b>',
        				  'columna7'=>'<b>SEXTA COTIZACIÓN</b>'	);       
        
        $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
                
        $io_pdf->ezSetY(519);  
        $la_data[1]=array('columna1'=>'NOMBRE',
                          'columna2'=>utf8_decode($ds_contcol[0]["nombre1"]),
                          'columna3'=>utf8_decode($ds_contcol[1]["nombre2"]),
                          'columna4'=>utf8_decode($ds_contcol[2]["nombre3"]),
        				  'columna5'=>utf8_decode($ds_contcol[3]["nombre4"]),
        				  'columna6'=>utf8_decode($ds_contcol[4]["nombre5"]),
        				  'columna7'=>utf8_decode($ds_contcol[5]["nombre6"]));
        
        $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
                        
        $la_data[1]=array('columna1'=>'RIF:',
                          'columna2'=>$ds_contcol[0]["rif1"],
                          'columna3'=>$ds_contcol[1]["rif2"],
                          'columna4'=>$ds_contcol[2]["rif3"],
        				  'columna5'=>$ds_contcol[3]["rif4"],
        				  'columna6'=>$ds_contcol[4]["rif5"],
        				  'columna7'=>$ds_contcol[5]["rif6"]);
        
        $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
        
        $la_data[1]=array('columna1'=>'FECHA DE COTIZACIÓN:',
                          'columna2'=>$ds_contcol[0]["feccot1"],
                          'columna3'=>$ds_contcol[1]["feccot2"],
                          'columna4'=>$ds_contcol[2]["feccot3"],
        				  'columna5'=>$ds_contcol[3]["feccot4"],
        				  'columna6'=>$ds_contcol[4]["feccot5"],
        				  'columna7'=>$ds_contcol[5]["feccot6"]);
        
        $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
                       
        $la_data[1]=array('columna1'=>'DOMICILIO FISCAL:',
                          'columna2'=>utf8_decode($ds_contcol[0]["dirpro1"]),
                          'columna3'=>utf8_decode($ds_contcol[1]["dirpro2"]),
                          'columna4'=>utf8_decode($ds_contcol[2]["dirpro3"]),
        				  'columna5'=>utf8_decode($ds_contcol[3]["dirpro4"]),
        				  'columna6'=>utf8_decode($ds_contcol[4]["dirpro5"]),
        				  'columna7'=>utf8_decode($ds_contcol[5]["dirpro6"]));

        $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
        unset($la_columnas);
        unset($la_config);
        
     
	    $la_data[1]= $la_data_cabezal;
        $la_columnas= $la_cols_cabezal;
        
        $la_config=array('showHeadings'=>0, // Mostrar encabezados
                         'fontSize' => 5, // Tamaño de Letras
                         'titleFontSize' => 5,  // Tamaño de Letras de los títulos
                         'showLines'=>1, // Mostrar Líneas
                         'shaded'=>0, // Sombra entre líneas
                         'width'=>$width, // Ancho de la tabla
                         'maxWidth'=>$width, // Ancho Máximo de la tabla
                         'xOrientation'=>'right', // Orientación de la tabla
                         'xPos'=>$xPos,
                         'cols'=>$sizecols_cabelzal); // Justificación y ancho de la columna
        $io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
        unset($la_columnas);
        unset($la_config);
        
        $io_pdf->restoreState();
        $io_pdf->closeObject();
        $io_pdf->addObject($io_encabezado,'all');        
	}// end function uf_print_encabezado_pagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalles_cotizaciones($la_cotizaciones,$io_ds_detalle,$io_ds_detallepro,$la_countcot,$ds_contcol,$li_calculado,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalles_cotizaciones
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el el listado de  proveedores participantes
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 18/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_class_report;
		global $io_funciones, $ls_bolivares;
		$io_ds_detalle1=new class_datastore();
		$io_ds_detallepro1=new class_datastore();
		$li_totalcotizaciones=count($la_cotizaciones);
		$io_ds_detalle1->data=$io_ds_detalle->data;
		$io_ds_detallepro1->data=$io_ds_detallepro->data;
		$li_totalproveedores=$io_ds_detallepro1->getRowCount($io_ds_detallepro);

		$li_a=0;
		$li_b=0;
        $ds_linea_tot1=array("subtot1"=>0,"subtot2"=>0,"subtot3"=>0,"subtot4"=>0,"subtot5"=>0,"subtot6"=>0);
        $ds_linea_tot2=array("iva1"=>0,"iva2"=>0,"iva3"=>0,"iva4"=>0, "iva5"=>0, "iva6"=>0);
        $ds_linea_tot3=array("tot1"=>0,"tot2"=>0,"tot3"=>0,"tot4"=>0, "tot5"=>0, "tot6"=>0);
        $ds_linea_tot4=array("garantia1"=>'',"garantia2"=>'',"garantia3"=>'',"garantia4"=>'',"garantia5"=>'',"garantia6"=>'');
        $ds_linea_tot5=array("condp1"=>'',"condp2"=>'',"condp3"=>'',"condp4"=>'',"condp5"=>'',"condp6"=>'');
        $ds_linea_tot6=array("dfecent1"=>'',"dfecent2"=>'',"dfecent3"=>'',"dfecent4"=>'',"dfecent5"=>'',"dfecent6"=>'');
        $ds_linea_tot7=array("cumple1"=>'',"cumple2"=>'',"cumple3"=>'',"cumple4"=>'',"cumple5"=>'',"cumple6"=>'');
        $ds_linea_tot8=array("garantia1"=>'',"garantia2"=>'',"garantia3"=>'',"garantia4"=>'',"garantia5"=>'',"garantia6"=>'');
        
        $li_totrow=count($la_cotizaciones);
        
        //----------- Impresion de denominaciones de Items
                            
        //$la_countcot = 6;
        $sizecols = array(5.9,49.4, 10, 21.2, 21.2, 21.1, 21.2, 21.2, 21.1, 21.2,21.2,21.2);
        $just = array("center","left","center","center","center","center","center","center","center","center","center","center");
        $sizetext = 6;
        $xPos = 10;
        if ($la_countcot > 3)
        {
        	$cant = 14;
        	$pUnit = 18.8;
        	$cTot = 18.8;
        	$xPos = 3;
        	$just 	= array("center","left","center",
        					"center","center","center",	"center","center","center",
        					"center","center","center",	"center","center","center");
        	$sizecols = array(5.9,49.4,10, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot-1);
        }
        if ($la_countcot > 4)
        {
        	$cant = 10.9;
        	$pUnit = 16.9;
        	$cTot = 17;
        	$xPos = 3;
        	$just 	= array("center","left", "center","center","center",
        			"center","center","center",	"center","center","center",
        			"center","center","center",	"center","center","center","center");
        	$sizecols = array(5.9,30.1,10, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot);
        }
        if ($la_countcot > 5)
        {
        	$cant = 8.4;
        	$pUnit = 16;
        	$cTot = 16;
        	$xPos = 3;
        	$just 	= array("center","left", "center","center","center","center","center",
        			"center","center","center",	"center","center","center",
        			"center","center","center",	"center","center","center","center");
        	$sizecols = array(5.9,22.4, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot, 
        						$cant, $pUnit, $cTot);
        	
        }

        $li_a = 1;
        
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
		    if (!empty($la_data))
		    {
                $la_data=array();
		    }

		    //$ls_numcot = $la_cotizaciones[$li_i]["numcot_1"];
		    $ls_tipsolcot=$_GET["tipsolcot"];
		    $ls_numanacot=$_GET["numanacot"];	    
		    
		    $codigo1 = $la_cotizaciones[$li_i]["coditem_1"];
			$codpro1 = $la_cotizaciones[$li_i]["cod_pro_1"];
			$lb_valido=$io_class_report->uf_select_analisiscotizacion_obs($ls_numanacot,$ls_tipsolcot,$codigo1, $codpro1, &$obsanacot1);
			$codigo2 = $la_cotizaciones[$li_i]["coditem_2"];
			$codpro2 = $la_cotizaciones[$li_i]["cod_pro_2"];
			$lb_valido=$io_class_report->uf_select_analisiscotizacion_obs($ls_numanacot,$ls_tipsolcot,$codigo2, $codpro2, &$obsanacot2);
			$codigo3 = $la_cotizaciones[$li_i]["coditem_3"];
			$codpro3 = $la_cotizaciones[$li_i]["cod_pro_3"];
			$lb_valido=$io_class_report->uf_select_analisiscotizacion_obs($ls_numanacot,$ls_tipsolcot,$codigo3, $codpro3, &$obsanacot3);
			$codigo4 = $la_cotizaciones[$li_i]["coditem_4"];
			$codpro4 = $la_cotizaciones[$li_i]["cod_pro_4"];
			$lb_valido=$io_class_report->uf_select_analisiscotizacion_obs($ls_numanacot,$ls_tipsolcot,$codigo4, $codpro4, &$obsanacot4);
			$codigo5 = $la_cotizaciones[$li_i]["coditem_5"];
			$codpro5 = $la_cotizaciones[$li_i]["cod_pro_5"];
			$lb_valido=$io_class_report->uf_select_analisiscotizacion_obs($ls_numanacot,$ls_tipsolcot,$codigo5, $codpro5, &$obsanacot5);
			$codigo6 = $la_cotizaciones[$li_i]["coditem_6"];
			$codpro6 = $la_cotizaciones[$li_i]["cod_pro_6"];
			$lb_valido=$io_class_report->uf_select_analisiscotizacion_obs($ls_numanacot,$ls_tipsolcot,$codigo6, $codpro6, &$obsanacot6);

			$la_data[0]["1"]=$li_i;
			$la_data[0]["2"]=utf8_decode($la_cotizaciones[$li_i]["denominacion"]);
			
			if ($la_countcot < 6)
			$la_data[0]["3"]=utf8_decode($la_cotizaciones[$li_i]["canartbase"]);

            $li_canart_1    = (!empty($la_cotizaciones[$li_i]["canart1"]) ? $la_cotizaciones[$li_i]["canart1"] : "--"); 
            $li_preuniart_1 = (!empty($la_cotizaciones[$li_i]["preuniart1"]) ? number_format($la_cotizaciones[$li_i]["preuniart1"],2,",",".") : "NO COTIZO");
            $li_monsubart_1 = (!empty($la_cotizaciones[$li_i]["monsubart1"]) ? number_format($la_cotizaciones[$li_i]["monsubart1"],2,",","."). " ". uf_check_obs(&$obsanacot1) : "--"); 
            $li_canart_2    = (!empty($la_cotizaciones[$li_i]["canart2"]) ? $la_cotizaciones[$li_i]["canart2"] : "--"); 
            $li_preuniart_2 = (!empty($la_cotizaciones[$li_i]["preuniart2"]) ? number_format($la_cotizaciones[$li_i]["preuniart2"],2,",",".") : (($la_countcot > 1 ) ? "NO COTIZO": "--")); 
            $li_monsubart_2 = (!empty($la_cotizaciones[$li_i]["monsubart2"]) ? number_format($la_cotizaciones[$li_i]["monsubart2"],2,",","."). " ". uf_check_obs(&$obsanacot2) : "--"); 
            $li_canart_3    = (!empty($la_cotizaciones[$li_i]["canart3"]) ? $la_cotizaciones[$li_i]["canart3"] : "--");
            $li_preuniart_3 = (!empty($la_cotizaciones[$li_i]["preuniart3"]) ? number_format($la_cotizaciones[$li_i]["preuniart3"],2,",",".") : (($la_countcot > 2 ) ? "NO COTIZO": "--")); 
            $li_monsubart_3 = (!empty($la_cotizaciones[$li_i]["monsubart3"]) ? number_format($la_cotizaciones[$li_i]["monsubart3"],2,",","."). " ". uf_check_obs(&$obsanacot3) : "--");
            $li_canart_4    = (!empty($la_cotizaciones[$li_i]["canart4"]) ? $la_cotizaciones[$li_i]["canart4"] : "--");
            $li_preuniart_4 = (!empty($la_cotizaciones[$li_i]["preuniart4"]) ? number_format($la_cotizaciones[$li_i]["preuniart4"],2,",",".") : (($la_countcot > 3 ) ? "NO COTIZO": "--")); 
            $li_monsubart_4 = (!empty($la_cotizaciones[$li_i]["monsubart4"]) ? number_format($la_cotizaciones[$li_i]["monsubart4"],2,",","."). " ". uf_check_obs(&$obsanacot4) : "--");
            $li_canart_5    = (!empty($la_cotizaciones[$li_i]["canart5"]) ? $la_cotizaciones[$li_i]["canart5"] : "--");
            $li_preuniart_5 = (!empty($la_cotizaciones[$li_i]["preuniart5"]) ? number_format($la_cotizaciones[$li_i]["preuniart5"],2,",",".") : (($la_countcot > 4 ) ? "NO COTIZO": "--")); 
            $li_monsubart_5 = (!empty($la_cotizaciones[$li_i]["monsubart5"]) ? number_format($la_cotizaciones[$li_i]["monsubart5"],2,",","."). " ". uf_check_obs(&$obsanacot5) : "--");
            $li_canart_6    = (!empty($la_cotizaciones[$li_i]["canart6"]) ? $la_cotizaciones[$li_i]["canart6"] : "--");
            $li_preuniart_6 = (!empty($la_cotizaciones[$li_i]["preuniart6"]) ? number_format($la_cotizaciones[$li_i]["preuniart6"],2,",",".") : (($la_countcot > 5 ) ? "NO COTIZO": "--")); 
            $li_monsubart_6 = (!empty($la_cotizaciones[$li_i]["monsubart6"]) ? number_format($la_cotizaciones[$li_i]["monsubart6"],2,",","."). " ". uf_check_obs(&$obsanacot6) : "--");
            
            $la_data[0]["4"]=$li_canart_1;
            $la_data[0]["5"]=$li_preuniart_1;
            $la_data[0]["6"]=$li_monsubart_1 ; 
            $la_data[0]["7"]=$li_canart_2;
            $la_data[0]["8"]=$li_preuniart_2;
            $la_data[0]["9"]=$li_monsubart_2;   
            $la_data[0]["10"]=$li_canart_3;
            $la_data[0]["11"]=$li_preuniart_3;
            $la_data[0]["12"]=$li_monsubart_3; 
            
            if ($la_countcot > 3){
            	$la_data[0]["13"]=$li_canart_4;
            	$la_data[0]["14"]=$li_preuniart_4;
            	$la_data[0]["15"]=$li_monsubart_4;
            }
            if ($la_countcot > 4){
            	$la_data[0]["16"]=$li_canart_5;
            	$la_data[0]["17"]=$li_preuniart_5;
            	$la_data[0]["18"]=$li_monsubart_5;
            }
            if ($la_countcot > 5){
            	$la_data[0]["19"]=$li_canart_6;
            	$la_data[0]["20"]=$li_preuniart_6;
            	$la_data[0]["21"]=$li_monsubart_6;
            }

			$la_justificaciones=array();
			$la_justificaciones = $just;
			$la_opciones = array("color_texto"     => array(0,0,0),
								   "anchos_col"    => $sizecols,
								   "tamano_texto"  => $sizetext,
								   "lineas"        => 2,
								   "alineacion_col"=> $la_justificaciones,
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
			$io_pdf->add_tabla($xPos,$la_data,$la_opciones);
		
			if ($li_a == 15) {
				//break;
				$li_a = 0;
				$io_pdf->ezNewPage();
				$io_pdf->ezSetDy(-11);
			}
			$li_a++;
		}//for
	}//fin de uf_print_proveedores
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_subtotales($ds_contcol,$la_cotizaciones,$la_countcot,$io_ds_detalle,$as_numanacot,$as_tipsolcot,$aa_ganadores,$ds_linea_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los ganadores del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Modificado Por: David Briceño
		// Fecha Creación: 26/08/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if ($io_pdf->y < 110){
			$io_pdf->ezNewPage();
			$io_pdf->ezSetDy(-11);
		}
		
		global $io_class_report;
		global $io_funciones, $ls_bolivares;
		$li_totalcotizaciones=count($la_cotizaciones);
		$li_a=0;
		$A50E50 = "50% AL APROBAR 50% A LA ENTREGA";
		
		$la_data_diaentcom[0][0]="TIEMPO DE ENTREGA";
		$la_data_forpagcom[0][0]="CONDICIONES DE PAGO";	
		$la_data_diavalofe[0][0]="VIGENCIA DE COTIZACION";
		$la_data_garanacot[0][0]="GARANTIA";
		//$la_countcot = 6;
		
		for ($i = 0; $i < 6; $i++){
		
			$j = $i + 1;
			
			$ds_linea_tot	= "ds_linea_tot$j";
			$ls_codp		= "ls_codp$j";
			$ls_estasitec	= "ls_estasitec$j";
			$ls_estesp		= "ls_estesp$j";
			$subt 			= "subt$j";
			$subiva 		= "subiva$j";
			$subtot 		= "subtot$j";
			
			$$ds_linea_tot	= array();
			$$ls_codp		= $ds_contcol[$i]['codpro'.$j];	
			$$ls_estasitec	= $ds_contcol[$i]['estasitec'.$j];
			$$ls_estesp 	= $ds_contcol[$i]['estesp'.$j];
			$$subt=0;
			$$subiva=0;
			$$subtot=0;

		}
		

        $li_totrow=count($la_cotizaciones);

		for($li_i=0;$li_i<$li_totrow;$li_i++)
		{
			if ($la_cotizaciones[$li_i+1]["cod_pro_1"]==$ls_codp1)
			{                
				$subt1=$subt1+$la_cotizaciones[$li_i+1]["monsubart1"];
				$subiva1=$subiva1+$la_cotizaciones[$li_i+1]["moniva1"];
				$subtot1=$subtot1+$la_cotizaciones[$li_i+1]["montotart1"];
			}
			if ($la_cotizaciones[$li_i+1]["cod_pro_2"]==$ls_codp2)
			{
				$subt2=$subt2+$la_cotizaciones[$li_i+1]["monsubart2"];
				$subiva2=$subiva2+$la_cotizaciones[$li_i+1]["moniva2"];
				$subtot2=$subtot2+$la_cotizaciones[$li_i+1]["montotart2"];
			}
			if ($la_cotizaciones[$li_i+1]["cod_pro_3"]==$ls_codp3)
			{
				$subt3=$subt3+$la_cotizaciones[$li_i+1]["monsubart3"];
				$subiva3=$subiva3+$la_cotizaciones[$li_i+1]["moniva3"];
				$subtot3=$subtot3+$la_cotizaciones[$li_i+1]["montotart3"];
			}
			if ($la_cotizaciones[$li_i+1]["cod_pro_4"]==$ls_codp4)
			{
				$subt4=$subt4+$la_cotizaciones[$li_i+1]["monsubart4"];
				$subiva4=$subiva4+$la_cotizaciones[$li_i+1]["moniva4"];
				$subtot4=$subtot4+$la_cotizaciones[$li_i+1]["montotart4"];
			}
			if ($la_cotizaciones[$li_i+1]["cod_pro_5"]==$ls_codp5)
			{
				$subt5=$subt5+$la_cotizaciones[$li_i+1]["monsubart5"];
				$subiva5=$subiva5+$la_cotizaciones[$li_i+1]["moniva5"];
				$subtot5=$subtot5+$la_cotizaciones[$li_i+1]["montotart5"];
			}
			if ($la_cotizaciones[$li_i+1]["cod_pro_6"]==$ls_codp6)
			{
				$subt6=$subt6+$la_cotizaciones[$li_i+1]["monsubart6"];
				$subiva6=$subiva6+$la_cotizaciones[$li_i+1]["moniva6"];
				$subtot6=$subtot6+$la_cotizaciones[$li_i+1]["montotart6"];
			}
		}

		$ds_linea_tot1=array("subtot1"=>$subt1,"subtot2"=>$subt2,"subtot3"=>$subt3,"subtot4"=>$subt4,"subtot5"=>$subt5,"subtot6"=>$subt6);
		$ds_linea_tot2=array("iva1"=>$subiva1,"iva2"=>$subiva2,"iva3"=>$subiva3,"iva4"=>$subiva4,"iva5"=>$subiva5,"iva6"=>$subiva6);
		$ds_linea_tot3=array("tot1"=>$subtot1,"tot2"=>$subtot2,"tot3"=>$subtot3,"tot4"=>$subtot4,"tot5"=>$subtot5,"tot6"=>$subtot6);
		$ds_linea_tot4=array("garantia1"=>'',"garantia2"=>'',"garantia3"=>'',"garantia4"=>'',"garantia5"=>'',"garantia6"=>'');
		$ds_linea_tot5=array("condp1"=>'',"condp2"=>'',"condp3"=>'',"condp4"=>'',"condp5"=>'',"condp6"=>'');
		$ds_linea_tot6=array("dfecent1"=>'',"dfecent2"=>'',"dfecent3"=>'',"dfecent4"=>'',"dfecent5"=>'',"dfecent6"=>'');
		$ds_linea_tot7=array("cumple1"=>'',"cumple2"=>'',"cumple3"=>'',"cumple4"=>'',"cumple5"=>'',"cumple6"=>'');
		$ds_linea_tot8=array("asist1"=>'',"asist2"=>'',"asist3"=>'',"asist4"=>'',"asist5"=>'',"asist6"=>'');
		$ds_linea_tot9=array("poriva1"=>'',"poriva2"=>'',"poriva3"=>'',"poriva4"=>'',"poriva5"=>'',"poriva6"=>'');

		$ds_linea_tot=array($ds_linea_tot1,$ds_linea_tot2,$ds_linea_tot3,$ds_linea_tot4,$ds_linea_tot5,$ds_linea_tot6,$ds_linea_tot7,$ds_linea_tot8,$ds_linea_tot9);

        $li_y=$io_pdf->y;

		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		
		$j = 1;
		
		for ($i=1; $i <= 6; $i++)
		{			
			if (($i <= 3) || ($la_countcot > 3 && $i <= $la_countcot))
			{ 
				$la_data_subtot[0][$j]="<b>SUB-TOTAL</b>";
				$la_data_subtot[0][$j+1]=number_format($ds_linea_tot[0]["subtot".$i],2,",",".");
				$la_data_iva[0][$j]="<b>IVA ".$ds_contcol[$i-1]['poriva'.$i]."%</b>";
				$la_data_iva[0][$j+1]=number_format($ds_linea_tot[1]["iva".$i],2,",",".");
				$la_data_tot[0][$j]="<b>TOTAL</b>";
				$la_data_tot[0][$j+1]=number_format($ds_linea_tot[2]["tot".$i],2,",",".");
				
				$la_data_diaentcom[0][$i]=$ds_contcol[$i-1]['diaentcom'.$i] != "" ? $ds_contcol[$i-1]['diaentcom'.$i]." Días" : "";
				$la_data_diavalofe[0][$i]=$ds_contcol[$i-1]['diavalofe'.$i] != "" ? $ds_contcol[$i-1]['diavalofe'.$i]." Días" : "";
				$la_data_forpagcom[0][$i]=$ds_contcol[$i-1]['forpagcom'.$i] == "A50E50" ? $A50E50 : $ds_contcol[$i-1]['forpagcom'.$i];
				$la_data_garanacot[0][$i]=$ds_contcol[$i-1]['garanacot'.$i];				
			}
			$j = $j + 2;
		}
		

		$la_anchos_col_totales 	= array(42.4,21.1,42.4,21.1,42.4,21.2);
		$la_justificaciones_tot = array("right","center","right","center","right","center");
		
		$la_anchos_col_condiciones = array(65.3,63.5,63.5,63.6);
		$la_justificaciones_cond = array("left","center","center","center");
		$pos = 75.3;
		$texttot = 7;
		$textcond = 7;
		$posCond = 10;
		if ($la_countcot == 4)
		{
			$sub = 32.8;
			$Tot = 18.8;
			$desc = 65.3;
			$cond = 51.6;
			
			$la_anchos_col_totales 	= array($sub,$Tot,$sub,$Tot,$sub,$Tot,$sub,$Tot-1);
			$la_justificaciones_tot = array("right","center","right","center","right","center","right","center");
			$texttot = 6;
			
			$la_anchos_col_condiciones = array($desc,$cond,$cond,$cond,$cond-1);
			$la_justificaciones_cond = array("left","center","center","center","center");
			$pos = 68.3;
			$posCond = 3;
		}
		if ($la_countcot == 5)
		{
			$sub = 27.8;
			$Tot = 17;
			$desc = 46;
			$cond = 44.8;
			
			$la_anchos_col_totales 	= array($sub,$Tot,$sub,$Tot,$sub,$Tot,$sub,$Tot,$sub,$Tot);
			$la_justificaciones_tot = array("right","center","right","center","right","center","right","center","right","center");
			$texttot = 6;
			$la_anchos_col_condiciones = array($desc,$cond,$cond,$cond,$cond,$cond);
			$la_justificaciones_cond = array("left","center","center","center","center","center");
			$pos = 49;
			$posCond = 3;
			$textcond = 6;
		}
		if ($la_countcot == 6)
		{
			$sub = 24.4;
			$Tot = 16;
			$desc = 28.3;
			$cond = 40.4;
			
			$la_anchos_col_totales 	= array($sub,$Tot,$sub,$Tot,$sub,$Tot,$sub,$Tot,$sub,$Tot,$sub,$Tot);
			$la_justificaciones_tot = array("right","center","right","center","right","center","right","center","right","center","right","center");
			$texttot = 6;
			$la_anchos_col_condiciones = array($desc,$cond,$cond,$cond,$cond,$cond,$cond);
			$la_justificaciones_cond = array("left","center","center","center","center","center","center");
			$textcond = 6;
			$pos = 31.3;
			$posCond = 3;
		}
		
		$la_opciones_totales = array("color_texto"     => array(0,0,0),
				"anchos_col"    => $la_anchos_col_totales,
				"tamano_texto"  => $texttot,
				"lineas"        => 1,
				"alineacion_col"=> $la_justificaciones_tot,
				"grosor_lineas_externas"=>0.5,
				"grosor_lineas_internas"=>0.5);
		
		$la_opciones_condiciones = array("color_texto"     => array(0,0,0),
				"anchos_col"    => $la_anchos_col_condiciones,
				"tamano_texto"  => $textcond,
				"lineas"        => 1,
				"alineacion_col"=> $la_justificaciones_cond,
				"grosor_lineas_externas"=>0.5,
				"grosor_lineas_internas"=>0.5);
		
		$io_pdf->add_tabla($pos,$la_data_subtot,$la_opciones_totales);
		$io_pdf->add_tabla($pos,$la_data_iva,$la_opciones_totales);
		$io_pdf->add_tabla($pos,$la_data_tot,$la_opciones_totales);
		$io_pdf->add_tabla($posCond,$la_data_diaentcom,$la_opciones_condiciones);
		$io_pdf->add_tabla($posCond,$la_data_forpagcom,$la_opciones_condiciones);
		$io_pdf->add_tabla($posCond,$la_data_diavalofe,$la_opciones_condiciones);
		$io_pdf->add_tabla($posCond,$la_data_garanacot,$la_opciones_condiciones);

	}//fin de uf_print_subtotales
	//------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------

	function uf_print_ganadores($as_numanacot,$as_tipsolcot,$aa_ganadores,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_items
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los ganadores del analisis de cotizacion
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 26/08/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		global $io_class_report;
		global $io_funciones, $ls_bolivares;

		if ($io_pdf->y < 140){
			$io_pdf->ezNewPage();
			$io_pdf->ezSetDy(-10);
		}
		//Imprimiendo primer titulo
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>PROVEEDORES ADJUDICADOS</b>";
		$la_anchos_col = array(260);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 8,
							   "lineas"        => 1,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(200,200,200),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->ezSetDy(-10);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris

		//Imprimiendo titulos columnas
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Código</b>";
		$la_data[0]["2"]="<b>Nombre</b>";
		$la_data[0]["3"]="<b>Subtotal ".$ls_bolivares."</b>";
		$la_data[0]["4"]="<b>Total Cargos ".$ls_bolivares."</b>";
		$la_data[0]["5"]="<b>Monto Total ".$ls_bolivares."</b>";

		$la_anchos_col = array(25,100,40,43,52);
		$la_justificaciones = array("center","center","center","center","center");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 8,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232),
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);

		$la_data=array();
		$li_totalganadores=count($aa_ganadores);
		$li_totalsubtotal=0;
		$li_totaliva=0;
		$li_totalmonto=0;
		for($li_i=0;$li_i<$li_totalganadores;$li_i++)
		{
			$ls_proveedor		= $aa_ganadores[$li_i]["cod_pro"];
			$ls_cotizacion		= $aa_ganadores[$li_i]["numcot"];
			$ls_tipo_proveedor	= $aa_ganadores[$li_i]["tipconpro"];
			$io_class_report->uf_select_items_proveedor($ls_cotizacion,$ls_proveedor,$as_numanacot,$as_tipsolcot,$la_items,$li_totrow);
			$io_class_report->uf_calcular_montos($li_totrow,$la_items,$la_totales,$ls_tipo_proveedor);
			$la_data[$li_i]["1"]=$ls_proveedor;
			$la_data[$li_i]["2"]=$aa_ganadores[$li_i]["nompro"];
			$la_data[$li_i]["4"]=number_format($la_totales["subtotal"],2,",",".");
			$la_data[$li_i]["5"]=number_format($la_totales["totaliva"],2,",",".");
			$la_data[$li_i]["6"]=number_format($la_totales["total"],2,",",".");
			$li_totalsubtotal+=$la_totales["subtotal"];
			$li_totaliva+=$la_totales["totaliva"];
			$li_totalmonto+=$la_totales["total"];
		}

		//Imprimiendo columnas
		$la_justificaciones=array();
		$la_justificaciones = array("center","left","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 8,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "grosor_lineas_externas"=>0.5,
								   "grosor_lineas_internas"=>0.5);
		$io_pdf->add_tabla(10,$la_data,$la_opciones);

		//imprimiendo totales
		$la_data=array();
		$la_anchos=array();
		$la_justificaciones=array();
		$la_data[0]["1"]="<b>Totales ".$ls_bolivares."</b>";
		$la_data[0]["2"]="<b>".number_format($li_totalsubtotal,2,",",".")."</b>";
		$la_data[0]["3"]="<b>".number_format($li_totaliva,2,",",".")."</b>";
		$la_data[0]["4"]="<b>".number_format($li_totalmonto,2,",",".")."</b>";

		$la_anchos_col = array(25,40,43,52);
		$la_justificaciones = array("center","right","right","right");
		$la_opciones = array("color_texto"     => array(0,0,0),
							   "anchos_col"    => $la_anchos_col,
							   "tamano_texto"  => 8,
							   "lineas"        => 2,
							   "alineacion_col"=> $la_justificaciones,
								   "color_fondo"=>array(232,232,232));
		$io_pdf->add_tabla(110,$la_data,$la_opciones);
	}//fin de uf_print_detalle

    //------------------------------------------------------------------------------------------------------------------------------------
    function uf_print_observaciones($observacion, $recomendacion, &$io_pdf)
    {   	
    	    	
    	if ($io_pdf->y < 160 && (!empty($recomendacion))){
    		$io_pdf->ezNewPage();
    		$io_pdf->ezSetDy(-10);
    	}
    	
    	$la_data=array();
    	$la_justificaciones=array();
    	$la_data[0]["1"]="<b>OBSERVACIONES</b>";
    	$la_anchos_col = array(260);
    	$la_justificaciones = array("center");
    	$la_opciones = array("color_texto"     => array(0,0,0),
    			"anchos_col"    => $la_anchos_col,
    			"tamano_texto"  => 8,
    			"lineas"        => 1,
    			"alineacion_col"=> $la_justificaciones,
    			"color_fondo"=>array(200,200,200),
    			"grosor_lineas_externas"=>0.5,
    			"grosor_lineas_internas"=>0.5);
    	$io_pdf->ezSetDy(-15);
    	$io_pdf->add_tabla(10,$la_data,$la_opciones);	//primera fila del item, color gris
    	
    	$la_data=array();
    	$la_justificaciones=array();
    	$la_data[0]["1"]=$observacion . " " . $recomendacion;
    	$la_anchos_col = array(260);
    	$la_justificaciones = array("left");
    	$la_opciones = array("color_texto"     => array(0,0,0),
    			"anchos_col"    => $la_anchos_col,
    			"tamano_texto"  => 8,
    			"lineas"        => 0,
    			"alineacion_col"=> $la_justificaciones,
    			
    			"grosor_lineas_externas"=>0.5,
    			"grosor_lineas_internas"=>0.5);
    	$io_pdf->add_tabla(10,$la_data,$la_opciones);
    	         
        
    }
	//------------------------------------------------------------------------------------------------------------------------------------
   	//------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_pagina($ls_usuario,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_pagina
		//		    Acess: private
		//	    Arguments: $io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime el pie del reporte
		//	   Creado Por: Ing. Laura Cabré                  Modificado Por: Ing. Gloriely Fréitez
		// Fecha Creación: 17/06/2007                 Fecha de Modificación: 01/04/2008
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(30,30,750,60);
		$io_pdf->line(30,42,780,42);	//VERTICAL
		$io_pdf->line(30,78,780,78);	//VERTICAL
		$io_pdf->addText(160,80,8,"<b>REALIZADO  POR</b>");
		$io_pdf->addText(160,45,8,"<b>".strtoupper($ls_usuario)."</b>");
		$io_pdf->addText(145,32,8,"<b>Cargo: Analista de Compras</b>");
		$io_pdf->line(400,30,400,90);	//VERTICAL

		$io_pdf->addText(550,80,8,"<b>APROBADO POR</b>");
		$io_pdf->addText(550,45,8,"<b>SOLANGE GARCIA</b>");
		$io_pdf->addText(520,32,8,"<b>Cargo: Jefe de la Unidad de Compras</b>");
	}// end function uf_print_pie_pagina
    
    //--------------------------------------------------------------------------------------------------------------------------------
    function evalua_campos($ai_tipo,$as_valor_e)
    {
        $as_valor_r = '';
        if ($ai_tipo==1)    //estasitec
        {
            if (empty($as_valor_e)) 
            {
               $as_valor_r = '';
            }
            if($as_valor_e=="1")
            {
                $as_valor_r = "Sí";
            }
            else
            {
                $as_valor_r = "No";
            } 
        }
        if ($ai_tipo==2)    //estesp        
        {
            if (empty($as_valor_e)) 
            {
               $as_valor_r = '';
            }
            if($as_valor_e=="1")
            {
                $as_valor_r = "Sí Cumple";
            }
            else
            {
                $as_valor_r = "No Cumple";
            }
        }
        return $as_valor_r;        
    }

	//--------------------------------------------------------------------------------------------------------------------------------
    function uf_check_obs(&$obsanacot)
    {
        
    	switch ($obsanacot){
    		case 'P':
    			$obsanacot = '   ***Mejor Precio***';
    			break;
    		case 'A':
    			$obsanacot = '*Acuerdo de Pago';
    			break;
    		case 'C':
    			$obsanacot = '*Cantidad';
    			break;
    		case 'F':
    			$obsanacot = '*Fecha Entrega';
    			break;
    		case 'M':
    			$obsanacot = '  **Marca Reconocida**';
    			break;
    		case 'O':
    			$obsanacot = '     **Otro Criterio';
    			break;
    		default:
    			$obsanacot = '';
    			break;
    	}
    	
    	return $obsanacot;
    
    }
	
	//--------------------------------------------------------------------------------------------------------------------------------

	require_once("sigesp_soc_class_report.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_datastore.php");
	$io_class_report = new sigesp_soc_class_report();
	$io_funciones    = new class_funciones();
	$io_fun_compra   = new class_funciones_soc();
	$ls_tiporeporte=$io_fun_compra->uf_obtenervalor_get("tiporeporte",1);
	$ls_bolivares="Bs.";
	$la_cotizaciones = array();
	$io_ds_detalle=new class_datastore();
	$io_ds_detallecot=new class_datastore();
	$io_ds_detallepro=new class_datastore();
	$io_ds_grupodetallepro=new class_datastore();

	$ds_linea_tot=array();

	$li_calculado = 0;

	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_class_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
	error_reporting(E_ALL);
	$io_pdf=new class_pdf('LETTER','landscape');                    // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');  // Seleccionamos el tipo de letra
	$io_pdf->numerar_paginas(7);
	$io_pdf->set_margenes(62,12,3,3);
	$ls_tipsolcot=$_GET["tipsolcot"];
    
	$ls_numanacot=$_GET["numanacot"];
	$ld_fecha=$_GET["fecha"];

	$lb_valido=uf_insert_seguridad();
	if($lb_valido)
	{
        $ls_codpro1 = '';
        $ls_codpro2 = '';
        $ls_codpro3 = '';
        $ls_codpro4 = '';
        $ls_codpro5 = '';
        $ls_codpro6 = '';
        $lb_valido=$io_class_report->uf_cargar_cotizaciones_v2($ls_numanacot,$la_cotizaciones,$ls_tipsolcot,&$la_proveedor,&$li_cotizaciones,&$ls_codpro1,&$ls_codpro2,&$ls_codpro3,&$ls_codpro4,&$ls_codpro5,&$ls_codpro6);
		if($lb_valido)
		{
			$li_totcot=$li_cotizaciones;

			//CNTI para sacar estos campos desde la BD y no desde el metodo _GET
			$ls_recomendacion=$la_proveedor[1]["recanacot"];
			$ls_observacion=$la_proveedor[1]["obsana"];
			$ls_usuario= $la_proveedor[1]["nomusu"] . " ". $la_proveedor[1]["apeusu"];
			//
            
			$li_cant_pro=$io_ds_detallepro->getRowCount('cod_pro');
			$cCol=1;

			for ($i = 1 ; $i <= 6; $i++)
			{
				
				$ds_col			= "ds_col$i";
                $codpro 		= "codpro$i";
                $nompro 		= "nompro$i";
                $rifpro			= "rifpro$i";
                $dirpro			= "dirpro$i";
                $feccot			= "feccot$i";
                $diaentcom		= "diaentcom$i";
                $forpagcom		= "forpagcom$i";
                $garanacot		= "garanacot$i";
                $diavalofe		= "diavalofe$i";
                $estasitec		= "estasitec$i";
                $estesp			= "estesp$i";
                $poriva			= "poriva$i";
                
                if ($li_totcot >= $i)
                {
                	$io_ds_detallepro->insertRow("cod_pro_".$i,$la_proveedor[$i]["cod_pro"]);
                	$io_ds_detallepro->insertRow("nompro_".$i,$la_proveedor[$i]["nompro"]);
                	$io_ds_detallepro->insertRow("dirpro_".$i,$la_proveedor[$i]["dirpro"]);
                	$io_ds_detallepro->insertRow("rifpro_".$i,$la_proveedor[$i]["rifpro"]);
                	$io_ds_detallepro->insertRow("feccot_".$i,$la_proveedor[$i]["feccot"]);
                	$io_ds_detallepro->insertRow("diaentcom_".$i,$la_proveedor[$i]["diaentcom"]);
                	$io_ds_detallepro->insertRow("forpagcom_".$i,$la_proveedor[$i]["forpagcom"]);
                	$io_ds_detallepro->insertRow("estasitec_".$i,$la_proveedor[$i]["estasitec"]);
                	$io_ds_detallepro->insertRow("estesp_".$i,$la_proveedor[$i]["estesp"]);
                	$io_ds_detallepro->insertRow("garanacot_".$i,$la_proveedor[$i]["garanacot"]);
                	$io_ds_detallepro->insertRow("diavalofe_".$i,$la_proveedor[$i]["diavalofe"]);
                	$io_ds_detallepro->insertRow("poriva_".$i,$la_proveedor[$i]["poriva"]);
                }
                else
                {	
                	$io_ds_detallepro->insertRow("cod_pro_".$i,'');
                	$io_ds_detallepro->insertRow("nompro_".$i,'');
                	$io_ds_detallepro->insertRow("dirpro_".$i,'');
                	$io_ds_detallepro->insertRow("rifpro_".$i,'');
                	$io_ds_detallepro->insertRow("feccot_".$i,'');
                	$io_ds_detallepro->insertRow("diaentcom_".$i,'');
                	$io_ds_detallepro->insertRow("forpagcom_".$i,'');
                	$io_ds_detallepro->insertRow("estasitec_".$i,'');
                	$io_ds_detallepro->insertRow("estesp_".$i,'');
                	$io_ds_detallepro->insertRow("garanacot_".$i,'');
                	$io_ds_detallepro->insertRow("diavalofe_".$i,'');
                	$io_ds_detallepro->insertRow("poriva_".$i,'');
                }
                
                $$codpro		= $io_ds_detallepro->getValue('cod_pro_'.$i,1 );
                $$nompro        = $io_ds_detallepro->getValue('nompro_'.$i,1 );
                $$rifpro        = $io_ds_detallepro->getValue('rifpro_'.$i,1 );
				$$dirpro        = $io_ds_detallepro->getValue('dirpro_'.$i,1 );
				$$feccot        = $io_ds_detallepro->getValue('feccot_'.$i,1 );
				$$diaentcom     = $io_ds_detallepro->getValue('diaentcom_'.$i,1 );
				$$forpagcom     = $io_ds_detallepro->getValue('forpagcom_'.$i,1 );
				$$garanacot     = $io_ds_detallepro->getValue('garanacot_'.$i,1 );
				$$diavalofe     = $io_ds_detallepro->getValue('diavalofe_'.$i,1 );
				$$poriva	    = $io_ds_detallepro->getValue('poriva_'.$i,1 );
				$$poriva	    = $io_ds_detallepro->getValue('obsanacot_'.$i,1 );
				$$estasitec     = evalua_campos(1,$io_ds_detallepro->getValue('estasitec_'.$i,1));
				$$estesp        = evalua_campos(2,$io_ds_detallepro->getValue('estesp_'.$i,1));
				$$ds_col = array("nombre$i"=>$$nompro,"rif$i"=>$$rifpro,"dirpro$i"=>$$dirpro,"feccot$i"=>$$feccot,"codpro$i"=>$$codpro,"diaentcom$i"=>$$diaentcom,"forpagcom$i"=>$$forpagcom,"estasitec$i"=>$$estasitec,"estesp$i"=>$$estesp,"garanacot$i"=>$$garanacot,"diavalofe$i"=>$$diavalofe,"poriva$i"=>$$poriva); //
			
				$io_ds_grupodetallepro->insertRow("nompro$i",$$nompro);
				$io_ds_grupodetallepro->insertRow("rifpro$i",$$rifpro);
				$io_ds_grupodetallepro->insertRow("feccot$i",$$feccot);
				$io_ds_grupodetallepro->insertRow("dirpro$i",$$dirpro);
				
			}

			$lb_valido=$io_class_report->uf_count_cotizaciones($ls_numanacot,$ls_countcot,$ls_tipsolcot);
			$ls_countcot=count($ls_countcot);

			$ds_contcol=array($ds_col1,$ds_col2,$ds_col3,$ds_col4,$ds_col5,$ds_col6);   
                
			uf_print_encabezado_pagina($ls_numanacot,$ld_fecha,$ds_contcol,$ls_countcot,$io_pdf);
            if($lb_valido)
			{
                //imprime los items
				uf_print_detalles_cotizaciones($la_cotizaciones,$io_ds_detalle,$io_ds_detallepro,$ls_countcot,$ds_contcol,$li_calculado,$io_pdf);			
            }
            
			$lb_valido=$io_class_report->uf_select_items($ls_numanacot,$ls_tipsolcot,$la_items);
			if($lb_valido)
			{
				
				$la_ganadores=$io_class_report->uf_select_cotizacion_analisis($ls_numanacot,$ls_tipsolcot);
				uf_print_subtotales($ds_contcol,$la_cotizaciones,$ls_countcot,$io_ds_detalle,$ls_numanacot,$ls_tipsolcot,$la_ganadores,$ds_linea_tot,$io_pdf);
				
				uf_print_ganadores($ls_numanacot,$ls_tipsolcot,$la_ganadores,$io_pdf);
				$io_pdf->ezSetDy(10);
				uf_print_observaciones($ls_observacion, $ls_recomendacion, &$io_pdf);
				
				uf_print_pie_pagina($ls_usuario,$io_pdf);
				$io_pdf->ezStream();
				unset($io_pdf);
			}
		}
	}
	if(!$lb_valido)
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que reportar');");
		print(" close();");
		print("</script>");
	}
?>
