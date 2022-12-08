<?php
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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_periodo // Descripci�n del per�odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$io_encabezado=$io_pdf->openObject();		
		$io_pdf->saveState();		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=280-($li_tm/2);
		$io_pdf->addText($tm,730,12,"<b>".$as_titulo."</b>"); // Agregar el t�tulo
		$io_pdf->addText(507,735,9,"Fecha: ".date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(514,725,9,"Hora: ".date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_listado($la_data,&$io_pdf)
	{	 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
                global $ls_bolivares;
                //$la_data[$li_rec]= array('orden'=>$ls_orden,'codartser'=>$ls_codas,
                //                          'denartser'=>$ls_denas,'codestpro'=>$ls_codestpro,
                //                          'spg_cuenta'=>$ls_spg_cuenta,'montotartser'=>$ls_montotartser,'total'=>0);
		//orden_salida
		$la_columna=array('orden_salida'=>'<b>Orden</b>','codartser'=>'<b>C�digo</b>',
						  'denartser'=>'<b>Denominacion</b>',
						  'codestpro'=>'<b>Estructura Prespuestaria</b>',
						  'spg_cuenta'=>'<b>Cuenta</b>',
						  'montotartser'=>'<b>Monto</b>',
						  'total'=>'<b>Total</b>');
						  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 10,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas						 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xPos'=>310, // Orientaci�n de la tabla
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('orden_salida'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
                                                               'codartser'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
                                                                'denartser'=>array('justification'=>'left','width'=>110), // Justificaci�n y ancho de la columna
                                                                'codestpro'=>array('justification'=>'center','width'=>140), // Justificaci�n y ancho de la columna
                                                                'spg_cuenta'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
                                                                'montotartser'=>array('justification'=>'right','width'=>60),
                                                                'total'=>array('justification'=>'right','width'=>70))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ad_numreg,$ad_totmon,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
                $io_pdf->ezSetDy(-10);
		$la_data=array(array('name'=>'<b>N� de Registros:</b>'.$ad_numreg,
		                     'name1'=>'<b>Total '.$ls_bolivares.':</b> '.$ad_totmon));				
		$la_columna=array('name'=>'','name1'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>310, // Orientaci�n de la tabla
						 'width'=>750, // Ancho de la tabla						 
						 'maxWidth'=>750, // Orientaci? de la tabla
						 'cols'=>array('name'=>array('justification'=>'left','width'=>250),      // Justificaci? y ancho de la columna
						 			   'name1'=>array('justification'=>'right','width'=>335))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("sigesp_soc_class_report.php");	
	require_once("../class_folder/class_funciones_soc.php");
        require_once("../../shared/class_folder/class_datastore.php");
        
	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_soc   = new class_funciones_soc();
	$io_report    = new sigesp_soc_class_report($con);
	$ls_tiporeporte=$io_fun_soc->uf_obtenervalor_get("tiporeporte",0);
        
        $obj          = new class_datastore();
        //$rs_dataoc    = $obj;
        
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
		
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido=false;
	//----------------------------------------------------  Par�metros del encabezado    -----------------------------------------------
	$ls_titulo ="IMPUTACION PRESUPUESTARIA DE LAS ORDENES DE COMPRA";	
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	
	$ls_numordcomdes=$io_fun_soc->uf_obtenervalor_get("txtnumordcomdes","");
	$ls_numordcomhas=$io_fun_soc->uf_obtenervalor_get("txtnumordcomhas","");
	$ls_codprodes=$io_fun_soc->uf_obtenervalor_get("txtcodprodes","");
	$ls_codprohas=$io_fun_soc->uf_obtenervalor_get("txtcodprohas","");
	$ls_fecordcomdes=$io_fun_soc->uf_obtenervalor_get("txtfecordcomdes","");
	$ls_fecordcomhas=$io_fun_soc->uf_obtenervalor_get("txtfecordcomhas","");
	$ls_coduniadmdes=$io_fun_soc->uf_obtenervalor_get("txtcoduniejedes","");
	$ls_coduniadmhas=$io_fun_soc->uf_obtenervalor_get("txtcoduniejehas","");
	$ls_codartdes=$io_fun_soc->uf_obtenervalor_get("txtcodartdes","");
	$ls_codarthas=$io_fun_soc->uf_obtenervalor_get("txtcodarthas","");
	$ls_codserdes=$io_fun_soc->uf_obtenervalor_get("txtcodserdes","");
	$ls_codserhas=$io_fun_soc->uf_obtenervalor_get("txtcodserhas","");
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];
	$ls_rdanucom="0";
	$ld_registros=0;
	$ls_rdemi=$io_fun_soc->uf_obtenervalor_get("rdemi","");
	$ls_rdpre=$io_fun_soc->uf_obtenervalor_get("rdpre","");	
	$ls_rdcon=$io_fun_soc->uf_obtenervalor_get("rdcon","");
	$ls_rdanu=$io_fun_soc->uf_obtenervalor_get("rdanu","");
	$ls_rdinv=$io_fun_soc->uf_obtenervalor_get("rdinv","");
	
	$ls_estcondat=$io_fun_soc->uf_obtenervalor_get("rdtipo","");
	$ls_tipo=$io_fun_soc->uf_obtenervalor_get("esttip","");

	//--------------------------------------------------------------------------------------------------------------------------------
        
        $rs_dataoc = new class_datastore();
        
	$rs_data = $io_report->uf_select_imputacion_spg_orden_compra($ls_numordcomdes,$ls_numordcomhas,
									$ls_fecordcomdes,$ls_fecordcomhas,                                                            
									$ls_estcondat,$ls_tipo,&$lb_valido);
	$li_row=$io_sql->num_rows($rs_data);
	
        $rs_dataoc->data = $io_sql->obtener_datos($rs_data);
        
        $rs_data_spg = $io_report->uf_select_imputacion_cuentas_spg_orden_compra($ls_numordcomdes,$ls_numordcomhas,
									$ls_fecordcomdes,$ls_fecordcomhas,                                                            
									$ls_estcondat,$ls_tipo,&$lb_valido);
	$li_row_spg=$io_sql->num_rows($rs_data_spg);
	
	//--------------------------------------------------------------------------------------------------------------------------------
	//		Recorre los arreglos y construye otro con lo que se va a imprimir
	//--------------------------------------------------------------------------------------------------------------------------------        
        $li_total_row=$rs_dataoc->getRowCount("numordcom");

	if ($li_row>0)
	{
                $li_registro = 0;
                $la_data=array();
		while($row_spg=$io_sql->fetch_row($rs_data_spg))
		{
			$ls_orden_buscar        = "";
			$ls_spg_cuenta          = "";
			//$li_i                 = $li_i+1;
			$ls_orden_buscar        = trim($row_spg["numordcom"]);
			$ls_spg_cuenta_buscar   = $row_spg["spg_cuenta"];			
						$ls_codestpro1	 = $row_spg['codestpro1'];
						$ls_codestpro2	 = $row_spg['codestpro2'];
						$ls_codestpro3	 = $row_spg['codestpro3'];
						$ls_codestpro4	 = $row_spg['codestpro4'];
						$ls_codestpro5	 = $row_spg['codestpro5'];
						$ls_codpro1=trim($ls_codestpro1);
						$ls_codpro2=trim($ls_codestpro2);
						$ls_codpro3=trim($ls_codestpro3);
						$ls_codpro4=trim($ls_codestpro4);
						$ls_codpro5=trim($ls_codestpro5);
						$ls_codpro1 = substr($ls_codpro1,-$_SESSION["la_empresa"]["loncodestpro1"]);
						$ls_codpro2 = substr($ls_codpro2,-$_SESSION["la_empresa"]["loncodestpro2"]);
						$ls_codpro3 = substr($ls_codpro3,-$_SESSION["la_empresa"]["loncodestpro3"]);
						$ls_codpro4 = substr($ls_codpro4,-$_SESSION["la_empresa"]["loncodestpro4"]);
						$ls_codpro5 = substr($ls_codpro5,-$_SESSION["la_empresa"]["loncodestpro5"]);
						if($ls_estmodest==1)
						{
							$ls_codestpro  = $ls_codpro1.'-'.$ls_codpro2.'-'.$ls_codpro3;
						}
						else
						{
							$ls_codestpro = $ls_codpro1.'-'.$ls_codpro2.'-'.$ls_codpro3.'-'.$ls_codpro4.'-'.$ls_codpro5;
						}
						//$ls_codestpro           = $row_spg["codestpro1"].'-'.$row_spg["codestpro2"].'-'.$row_spg["codestpro3"].'-'.$row_spg["codestpro4"].'-'.$row_spg["codestpro5"];
                        $ld_total_orden         = 0;
                        $ld_total_cuenta        = 0;
                        
                        
                        for ($li_i=1;$li_i<=$li_total_row;$li_i++)
                        {
                            $ls_orden               = trim($rs_dataoc->getValue('numordcom',$li_i));
                            if ($ls_orden==$ls_orden_buscar)
                            {
                                $ls_orden_next      = $rs_dataoc->getValue('numordcom',$li_i+1);
                                $ls_denas           = $rs_dataoc->getValue('denartser',$li_i); 
                                $ls_codas           = trim($rs_dataoc->getValue('codartser',$li_i)); 
                                $ls_spg_cuenta      = $rs_dataoc->getValue('spg_cuenta',$li_i);
                                $ls_montotartser    = $rs_dataoc->getValue('monttotartser',$li_i);
                                $ls_montsubartser   = $rs_dataoc->getValue('montsubartser',$li_i);
                                $ld_total_orden     = $ld_total_orden+$ls_montotartser;
                                
                                if (($ls_orden==$ls_orden_buscar)and($ls_spg_cuenta==$ls_spg_cuenta_buscar))
                                {
									$li_registro++;
                                    $la_data[$li_registro]= array('orden'=>$ls_orden,'codartser'=>$ls_codas,
                                                                  'denartser'=>$ls_denas,'codestpro'=>$ls_codestpro,
                                                                  'spg_cuenta'=>$ls_spg_cuenta,'montotartser'=>$ls_montotartser,
                                                                  'montsubartser'=>$ls_montsubartser);
                                }

                            }
                        }//for
		}//while
                
                $ld_registros = $li_registro;                
	}
	else
	{
		print("<script language=JavaScript>");
		print("alert('No hay nada que reportar');"); 
		//print("close();");
		print("</script>");		
	}

	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
	//	print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$ls_descripcion="Gener� el Reporte de Imputaci�n Presupuestaria Orden de Compra";
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_r_orden_compra.php",$ls_descripcion);
		if($lb_valido)	
		{
			error_reporting(E_ALL);
			$io_pdf=new Cezpdf('LETTER','portrait');                            // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');      // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.5,3,3,3);                                 // Configuraci�n de los margenes en cent�metros
			uf_print_encabezado_pagina($ls_titulo,$io_pdf);                     // Imprimimos el encabezado de la p�gina
			$io_pdf->ezStartPageNumbers(578,47,9,'','',1);                      // Insertar el n�mero de p�gina
			$ldec_monto=0;
                        $ldec_monto_ep = 0;
                        $ls_montocuenta = 0;
                        $ls_montotcuenta = 0;
			$li_rec   = 0;
			
                        $li_row = $ld_registros;
			//var_dump($la_data);
                        
                        if ($ld_registros>0)
			{     
				$ld_registrado=0;
                                for ($li_i=1;$li_i<=$ld_registros;$li_i++)
				{
                                    $li_rec=$li_rec+1;                                    
                                    $ls_orden     = $la_data[$li_i]['orden'];
                                    $ls_cuenta    = $la_data[$li_i]['spg_cuenta'];
                                    $ls_est_grupo = $ls_orden.$la_data[$li_i]['codestpro'];
                                    
                                    if ($li_i<$ld_registros)
                                    {
                                        $ls_cuenta_next     = $la_data[$li_i+1]['spg_cuenta'];
                                        $ls_orden_next      = $la_data[$li_i+1]['orden'];
                                        $ls_est_grupo_next  = $ls_orden.$la_data[$li_i+1]['codestpro'];
                                    }
                                    else
                                    {
                                        $ls_cuenta_next     = '';
                                        $ls_orden_next      = '';
                                        $ls_est_grupo_next  = '';
                                    }
                                    
                                    $ls_codas        = $la_data[$li_i]['codartser'];
                                    $ls_denas        = $la_data[$li_i]['denartser'];
                                    $ls_codestpro    = $la_data[$li_i]['codestpro'];
									$ls_spg_cuenta   = $la_data[$li_i]['spg_cuenta'];
                                    $ls_montotartser = $la_data[$li_i]['montotartser'];
                                    $ls_montocuenta  = $la_data[$li_i]['montsubartser'];
                                    
                                    $ldec_monto_ep   = $ldec_monto_ep + $ls_montocuenta;    //$ls_montotartser;
                                    $ls_montotcuenta = $ls_montotcuenta + $ls_montocuenta;
                                    
                                    $ldec_monto      = $ldec_monto+$ls_montotartser;
                                    $ls_montotartser = number_format($ls_montotartser,2,",",".");       
                                    
                                    $la_data_oc[$li_rec]= array('orden'=>$ls_orden,'codartser'=>$ls_codas,
                                                                  'denartser'=>$ls_denas,'codestpro'=>$ls_codestpro,
                                                                  'spg_cuenta'=>$ls_spg_cuenta,'montotartser'=>number_format($ls_montocuenta,2,",","."),
                                                                  'total'=>'','orden_salida'=>'');                                   
                                    if ($ls_cuenta<>$ls_cuenta_next)
                                    {
                                            $li_rec++;
                                            //$ldec_monto_ep = number_format($ldec_monto_ep,2,",",".");    
                                            //$ldec_monto_ep = "<b> $ldec_monto_ep </b> ";
                                            $ls_montotcuenta = number_format($ls_montotcuenta,2,",",".");
                                            $ls_montotcuenta = "<b> $ls_montotcuenta </b>";
                                            $la_data_oc[$li_rec]= array('orden'=>$ls_orden,'codartser'=>'',
                                                                  'denartser'=>'<i><b> TOTAL - CUENTA </b></i>','codestpro'=>'',
                                                                  'spg_cuenta'=>'','montotartser'=>'','total'=>$ls_montotcuenta,'orden_salida'=>'');
                                            $ls_montotcuenta = 0;
                                    }
                                    
                                    
                                    if ($ls_orden<>$ls_orden_next)
                                    {
                                            $li_rec++;
                                            $ldec_monto_ep = number_format($ldec_monto_ep,2,",",".");
                                            $ldec_monto_ep = "<b> $ldec_monto_ep </b> ";
                                            $la_data_oc[$li_rec]= array('orden'=>$ls_orden,'codartser'=>'',
                                                                  'denartser'=>'<i><b> SUB - TOTAL... </b></i>','codestpro'=>'',
                                                                  'spg_cuenta'=>'','montotartser'=>'','total'=>$ldec_monto_ep,'orden_salida'=>'');
                                            
                                            $li_rec++;
                                            $la_data_oc[$li_rec]= array('orden'=>$ls_orden,'codartser'=>'',
                                                                  'denartser'=>'','codestpro'=>'',
                                                                  'spg_cuenta'=>'','montotartser'=>'','total'=>'','orden_salida'=>''); 
                                            $ldec_monto_ep = 0;
                                            
                                            $li_rec++;
                                            $ldec_monto   = number_format($ldec_monto,2,",",".");
                                            $ldec_monto = "<b> $ldec_monto </b> ";
                                            $la_data_oc[$li_rec]= array('orden'=>$ls_orden,'codartser'=>'',
                                                                  'denartser'=>'<i><b> TOTAL CON IVA </b></i>','codestpro'=>'',
                                                                  'spg_cuenta'=>'','montotartser'=>'','total'=>$ldec_monto,'orden_salida'=>'');
                                            
                                            $li_rec++;
                                            $la_data_oc[$li_rec]= array('orden'=>$ls_orden,'codartser'=>'',
                                                                  'denartser'=>'','codestpro'=>'',
                                                                  'spg_cuenta'=>'','montotartser'=>'','total'=>'','orden_salida'=>''); 
                                            $ldec_monto = 0;
                                            $ldec_monto_ep = 0;
                                            $ls_montotcuenta = 0;
                                    }
				}
                                
                                $la_data_oc[1]['orden_salida']=$la_data_oc[1]['orden'];
                                $ls_orden_ant   = $la_data_oc[1]['orden'];
                                for ($li_i=2;$li_i<=$li_rec;$li_i++)
                                {
                                    $ls_orden      = $la_data_oc[$li_i]['orden'];
                                    if ($ls_orden<>$ls_orden_ant)
                                    {                                       
                                        $la_data_oc[$li_i]['orden_salida']=$ls_orden;
                                    }
                                    if ($li_i<$li_rec)
                                    {
                                        if ($ls_orden<>$ls_orden_ant)
                                        {
                                            $ls_orden_ant   = $ls_orden;
                                        }
                                        else
                                        {
                                            $ls_orden_ant   = $la_data_oc[$li_i-1]['orden'];
                                        }
                                        
                                    }
                                    else
                                    {
                                        $ls_orden_ant   = '';
                                    }
                                }
				uf_print_listado($la_data_oc,$io_pdf); // Imprimimos el detalle 		
				$ldec_monto  = number_format($ldec_monto,2,",",".");	
				//uf_print_pie_cabecera($li_i,$ldec_monto,$io_pdf);		
				if($lb_valido) // Si no ocurrio ning�n error
				{
					$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
					$io_pdf->ezStream(); // Mostramos el reporte
				}
				else  // Si hubo alg�n error
				{
					print("<script language=JavaScript>");
					print("alert('Ocurrio un problema al generar el reporte. Intente de Nuevo');"); 
					//print("close();");
					print("</script>");		
				}
				unset($io_pdf);
			}
			else
			{
				print("<script language=JavaScript>");
				print("alert('No hay nada que reportar');"); 
				//print("close();");
				print("</script>");		
			}				
		}	
		unset($io_report);
		unset($io_funciones);
	}	
?> 