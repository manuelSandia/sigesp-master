<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Listado de Solicitudes de Ejecucion Presupuestaria
	//  ORGANISMO: FONCREI
	//  ESTE FORMATO SE IMPRIME EN Bs Y EN BsF. SEGUN LO SELECCIONADO POR EL USUARIO
	//  MODIFICADO POR: ING.YOZELIN BARRAGAN         FECHA DE MODIFICACION : 14/08/2007
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		global $io_fun_sep;
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sep->uf_load_seguridad_reporte("SEP","sigesp_sep_r_solicitudes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($lo_libro,$lo_hoja,&$li_fila)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Modificado Por: Ing.Yozelin Barragan
		// Fecha Creaci�n: 11/03/2007      Fecha de Modificacion: 29/05/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		if($li_estmodest==1)
		{
			$ls_titulo="Estructura Presupuestaria";
		}
		else
		{
			$ls_titulo="Estructura Programatica";
		}
		$lo_hoja->write($li_fila, 0, 'Solicitud',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 1, 'Concepto',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 2, $ls_titulo,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 3, 'Unidad Administrativa',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 4, 'Proveedor / Beneficiario',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 5, 'Fecha de Registro',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 6, 'Estatus',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 7, 'Monto',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 8, 'Usuario',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Modificado Por: Ing.Yozelin Barragan
		// Fecha Creaci�n: 11/03/2007      Fecha de Modificacion: 29/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for ($index = 1; $index < ($li_totrow+1); $index++)
		{

			$lo_hoja->write($li_fila, 0, ' '.str_pad($la_data[$index]["codigo"],10,'0',STR_PAD_LEFT),$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'center','size'=>'9')));
			$lo_hoja->write($li_fila, 1, $la_data[$index]["nombre"],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 2, $la_data[$index]["numsol"],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 3, $la_data[$index]["fecemisol"],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 4, $la_data[$index]["consol"],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 5, $la_data[$index]["denest"],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 6, $la_data[$index]["monsol"],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'right','size'=>'9')));
			$lo_hoja->write($li_fila, 7, $la_data[$index]["monsol"],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'right','size'=>'9')));

			$li_fila++;

		}
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_total,$ai_totrows,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_total // Total por personal
		//	   			   ai_totrows // Total por patr�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno /Ing. Luis Lang
		// Modificado Por: Ing.Yozelin Barragan
		// Fecha Creaci�n: 11/03/2007      Fecha de Modificacion: 29/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Total Solicitudes</b>','totrows'=>$ai_totrows,'total'=>$ai_total));
		$la_columna=array('name'=>'','totrows'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('name'=>array('justification'=>'right','width'=>790), // Justificaci�n y ancho de la columna
						 			   'totrows'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_sep.php");
	$io_fun_sep=new class_funciones_sep();
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo =  tempnam("/tmp", "solicitudes.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo="SOLICITUDES DE EJECUCI�N PRESUPUESTARIA";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
	$ls_numsoldes=$io_fun_sep->uf_obtenervalor_get("numsoldes","");
	$ls_numsolhas=$io_fun_sep->uf_obtenervalor_get("numsolhas","");
	$ls_tipproben=$io_fun_sep->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_sep->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_sep->uf_obtenervalor_get("codprobenhas","");
	$ld_fegregdes=$io_fun_sep->uf_obtenervalor_get("fegregdes","");
	$ld_fegreghas=$io_fun_sep->uf_obtenervalor_get("fegreghas","");
	$ls_codunides=$io_fun_sep->uf_obtenervalor_get("codunides","");
	$ls_codunihas=$io_fun_sep->uf_obtenervalor_get("codunihas","");
	$ls_codusudes=$io_fun_sep->uf_obtenervalor_get("codusudes","");
	$ls_codusuhas=$io_fun_sep->uf_obtenervalor_get("codusuhas","");
	$ls_tipsol=$io_fun_sep->uf_obtenervalor_get("tipsol","");
	$li_registrada=$io_fun_sep->uf_obtenervalor_get("registrada",0);
	$li_emitida=$io_fun_sep->uf_obtenervalor_get("emitida",0);
	$li_contabilizada=$io_fun_sep->uf_obtenervalor_get("contabilizada",0);
	$li_procesada=$io_fun_sep->uf_obtenervalor_get("procesada",0);
	$li_anulada=$io_fun_sep->uf_obtenervalor_get("anulada",0);
	$li_despachada=$io_fun_sep->uf_obtenervalor_get("despachada",0);
	$li_aprobada=$io_fun_sep->uf_obtenervalor_get("aprobada",0);
	$li_pagada=$io_fun_sep->uf_obtenervalor_get("pagada",0);
	//Se agreg� el nuevo estatus de SEP "Sin Disponibilidad Presupuestaria"
	$li_sindisp=$io_fun_sep->uf_obtenervalor_get("sindisp",0);
//Se agreg� el nuevo estatus de SEP "Finalizada-ANulada Parcial"
	$li_sindisp=$io_fun_sep->uf_obtenervalor_get("finalizada",0);
	$ls_orden=$io_fun_sep->uf_obtenervalor_get("orden","numsol");
	$ls_tipoformato=$io_fun_sep->uf_obtenervalor_get("tipoformato",0);
	$ls_solicitudes=$io_fun_sep->uf_obtenervalor_get("solicitudes","");
    $lr_solicitudes= split('>>',$ls_solicitudes);
    $lr_datos= array_unique($lr_solicitudes);
	//--------------------------------------------------------------------------------------------------------------------------------
	global $ls_tipoformato;
	if($ls_tipoformato==1)
	{
		require_once("sigesp_sep_class_reportbsf.php");
		$io_report=new sigesp_sep_class_reportbsf();
	}
	else
	{
		require_once("sigesp_sep_class_report.php");
		$io_report=new sigesp_sep_class_report();
	}	
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		if($ls_tipoformato=="NORMAL")
		{
			//Se agreg� el par�metro del nuevo estatus de SEP "Sin disponibilidad presupuestaria"
			$lb_valido=$io_report->uf_select_solicitudes($ls_numsoldes,$ls_numsolhas,$ls_tipproben,$ls_codprobendes,$ls_codprobenhas,
														 $ld_fegregdes,$ld_fegreghas,$ls_codunides,$ls_codunihas,$ls_tipsol,
														 $li_registrada,
														 $li_emitida,$li_contabilizada,$li_procesada,$li_anulada,
														 $li_despachada,$ls_orden,$ls_codusudes,$ls_codusuhas,
														 $li_aprobada, $li_pagada, $li_sindisp,$li_finalizada); // Cargar el DS con los datos del reporte
		}
		if($lb_valido==false) // Existe alg�n error � no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{

			$lo_encabezado= &$lo_libro->addformat();
			$lo_encabezado->set_bold();
			$lo_encabezado->set_font("Verdana");
			$lo_encabezado->set_align('center');
			$lo_encabezado->set_size('11');
			$lo_titulo= &$lo_libro->addformat();
			$lo_titulo->set_bold();
			$lo_titulo->set_font("Verdana");
			$lo_titulo->set_align('center');
			$lo_titulo->set_size('9');
			$lo_datacenter= &$lo_libro->addformat();
			$lo_datacenter->set_font("Verdana");
			$lo_datacenter->set_align('center');
			$lo_datacenter->set_size('9');
			$lo_dataleft= &$lo_libro->addformat();
			$lo_dataleft->set_text_wrap();
			$lo_dataleft->set_font("Verdana");
			$lo_dataleft->set_align('left');
			$lo_dataleft->set_size('9');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
			$lo_hoja->set_column(0,0,15);
			$lo_hoja->set_column(1,1,20);
			$lo_hoja->set_column(2,2,30);
			$lo_hoja->set_column(3,3,20);
			$lo_hoja->set_column(4,4,30);
			$lo_hoja->set_column(5,5,30);
			$lo_hoja->set_column(6,6,30);

			$lo_hoja->write(0, 3, $ls_titulo,$lo_encabezado);

			$li_fila=2;


			uf_print_encabezado_pagina($lo_libro,$lo_hoja,&$li_fila);
			if($ls_tipoformato=="SELECTIVO")
			{
   				$li_totrow= count($lr_datos);
			}
			else
			{
				$li_totrow=$io_report->DS->getRowCount("numsol");
			}
			$li_total=0;
			$li_s=0;
			$li_row=3;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$li_s=$li_s+1;
				if($ls_tipoformato=="SELECTIVO")
				{
					$ls_numsol=$lr_datos[$li_i-1];
					$lb_valido=$io_report->uf_select_solicitud($ls_numsol);
					$li_totrowdet=$io_report->DS->getRowCount("numsol");
					for($li_j=1;$li_j<=$li_totrowdet;$li_j++)
					{
						$ls_numsol=$io_report->DS->data["numsol"][$li_j]." ";
						$ls_consol=$io_report->DS->data["consol"][$li_j];
						$ls_denuniadm=$io_report->DS->data["denuniadm"][$li_j];
						$ls_nombre=$io_report->DS->data["nombre"][$li_j];
						$ls_estsol=$io_report->DS->data["estsol"][$li_j];
						$ls_estapro=$io_report->DS->data["estapro"][$li_j];
						$li_monto=$io_report->DS->data["monto"][$li_j];
						$ls_codestpro1=$io_report->DS->data["codestpro1"][$li_j];
						$ls_codestpro2=$io_report->DS->data["codestpro2"][$li_j];
						$ls_codestpro3=$io_report->DS->data["codestpro3"][$li_j];
						$ls_codestpro4=$io_report->DS->data["codestpro4"][$li_j];
						$ls_codestpro5=$io_report->DS->data["codestpro5"][$li_j];
						$ls_codusu=$io_report->DS->data["codaprusu"][$li_j];
						$ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
						$io_fun_sep->uf_formato_estructura($ls_programatica,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
														   $ls_codestpro4,$ls_codestpro5);
						if($li_estmodest==1)
						{
							
							$ls_programatica=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
						}
						else
						{
							 $ls_programatica=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
						}
						$ld_fecregsol=$io_funciones->uf_convertirfecmostrar($io_report->DS->data["fecregsol"][$li_j]);
						$li_total=$li_total+$li_monto;
						switch ($ls_estsol)
						{
							case "R":
								$ls_estsol="Registro";					
								break;
							case "E":
								if ($ls_estapro==0)
								{
								  $ls_estsol="Emitida";					
								}
								else
								{
								  $ls_estsol="Aprobada";
								}					
								break;
							case "C":
								$ls_estsol="Contabilizada";					
								break;
							case "A":
								$ls_estsol="Anulada";					
								break;
							case "P":
								$ls_estsol="Procesada";					
								break;
							case "D":
								$ls_estsol="Despachada";
								break;
							//Se agreg� el nuevo estatus de la SEP
							case "S":
								$ls_estsol="Sin Disponibilidad Presupuestaria";
								break;
							case "F":
								$ls_estsol="Finalizada-Anulada Parcial";
								break;
						}
					}
					
				}
				else
				{
					$ls_numsol=$io_report->DS->data["numsol"][$li_i]." ";
					$ls_consol=$io_report->DS->data["consol"][$li_i];
					$ls_denuniadm=$io_report->DS->data["denuniadm"][$li_i];
					$ls_nombre=$io_report->DS->data["nombre"][$li_i];
					$ls_estsol=$io_report->DS->data["estsol"][$li_i];
					$ls_estapro=$io_report->DS->data["estapro"][$li_i];
					$li_monto=$io_report->DS->data["monto"][$li_i];
					$ls_codestpro1=$io_report->DS->data["codestpro1"][$li_i];
					$ls_codestpro2=$io_report->DS->data["codestpro2"][$li_i];
					$ls_codestpro3=$io_report->DS->data["codestpro3"][$li_i];
					$ls_codestpro4=$io_report->DS->data["codestpro4"][$li_i];
					$ls_codestpro5=$io_report->DS->data["codestpro5"][$li_i];
					$ls_codusu=$io_report->DS->data["codaprusu"][$li_i];
					$ls_programatica=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					$io_fun_sep->uf_formato_estructura($ls_programatica,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
													   $ls_codestpro4,$ls_codestpro5);
					if($li_estmodest==1)
					{
						
						$ls_programatica=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
					}
					else
					{
						 $ls_programatica=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3.'-'.$ls_codestpro4.'-'.$ls_codestpro5;
					}
					$ld_fecregsol=$io_report->DS->data["fecregsol"][$li_i]; 
					$ld_fecregsol=$io_funciones->uf_convertirfecmostrar($ld_fecregsol);
					$li_total=$li_total+$li_monto;
					switch ($ls_estsol)
					{
						case "R":
							$ls_estsol="Registro";					
							break;
						case "E":
							if ($ls_estapro==0)
							{
							  $ls_estsol="Emitida";					
							}
							else
							{
							  $ls_estsol="Aprobada";
							}				
							break;
						case "C":
							$ls_estsol="Contabilizada";					
							break;
						case "A":
							$ls_estsol="Anulada";					
							break;
						case "P":
							$ls_estsol="Procesada";					
							break;
						case "D":
							$ls_estsol="Despachada";
							break;
						case "PA":
							$ls_estsol="Pagada";
							break;
						//Se agreg� el nuevo estatus de la SEP
						case "S":
							$ls_estsol="Sin Disponibilidad Presupuestaria";
							break;
						case "F":
								$ls_estsol="Finalizada-Anulada Parcial";
								break;
					}
				}
				/*$la_data[$li_i]=array('numsol'=>$ls_numsol,'consol'=>$ls_consol,'programa'=>$ls_programatica,
				                      'denuniadm'=>$ls_denuniadm,'nombre'=>$ls_nombre,'fecregsol'=>$ld_fecregsol,
				                      'estsol'=>$ls_estsol,'monto'=>$li_monto,'usuario'=>$ls_codusu);*/
				$li_row=$li_row+1;

				$lo_hoja->write($li_row, 0, $ls_numsol, $lo_datacenter);
				$lo_hoja->write($li_row, 1, utf8_decode($ls_consol), $lo_dataleft);
				$lo_hoja->write($li_row, 2, $ls_programatica, $lo_datacenter);
				$lo_hoja->write($li_row, 3, utf8_decode($ls_denuniadm), $lo_dataleft);
				$lo_hoja->write($li_row, 4, utf8_decode($ls_nombre), $lo_dataleft);
				$lo_hoja->write($li_row, 5, $ld_fecregsol, $lo_datacenter);
				$lo_hoja->write($li_row, 6, $ls_estsol, $lo_datacenter);
				$lo_hoja->write($li_row, 7, $li_monto, $lo_dataright);
				$lo_hoja->write($li_row, 8, $ls_codusu, $lo_datacenter);
			}
		//	uf_print_detalle($la_data,&$io_pdf);
		//	$li_total=number_format($li_total,2,",",".");
		//	uf_print_piecabecera($li_total,$li_s,$io_pdf);
		//	unset($la_data);
			if($lb_valido) // Si no ocurrio ning�n error
			{
				$lo_libro->close();
				//Se cambi� la extensi�n del nombre del archivo a ods
				header("Content-Type: application/x-msexcel; name=\"solicitudes.ods\"");
				header("Content-Disposition: inline; filename=\"solicitudes.ods\"");
				$fh=fopen($lo_archivo, "rb");
				fpassthru($fh);
				unlink($lo_archivo);
				print("<script language=JavaScript>");
				print(" close();");
				print("</script>");		}
		}
	}

?>
