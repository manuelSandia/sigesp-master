<?PHP
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
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sob;
		$lb_valido=true;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sob->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_constanciatrabajo.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,680,13,$as_titulo); // Agregar el título
		if($as_fecha=="1")
		{
			$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
			$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		}
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_leer_archivo($as_archivo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_leer_archivo
		//		   Access: private 
		//	    Arguments: as_archivo //  ruta donde se encuentra el archivo
		//    Description: función que lee un archivo de texto y lo mete en una cadena
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_texto = file($as_archivo);
		$li_tamano = sizeof($ls_texto);
		$ls_textocompleto="";
		for($li_i=0;$li_i<$li_tamano;$li_i++)
		{
			$ls_textocompleto=$ls_textocompleto.$ls_texto[$li_i];
		}
		return $ls_textocompleto;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_sob_class_report.php");
	$io_report=new sigesp_sob_class_report();
	include("../../shared/class_folder/class_numero_a_letra.php");
	$io_numero_letra= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$io_numero_letra->setMayusculas(1);
	//cambia a femenino
	$io_numero_letra->setGenero(1);
	//cambia moneda
	$io_numero_letra->setMoneda("Bolivares");
	//cambia prefijo
	$io_numero_letra->setPrefijo("");
	//cambia sufijo
	$io_numero_letra->setSufijo("");
	//imprime numero con los cambios
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codcondes=$io_fun_sob->uf_obtenervalor_get("codcondes","");
	$ls_codconhas=$io_fun_sob->uf_obtenervalor_get("codconhas","");
	$ls_coddoc=$io_fun_sob->uf_obtenervalor_get("coddoc","");
	$ls_fecha=$io_fun_sob->uf_obtenervalor_get("fecha","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad("DOCUMENTO_WORD".$ls_coddoc); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_documento($ls_coddoc,$ls_codcondes,$ls_codconhas); // Obtenemos el detalle del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
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
		$li_totrow=$io_report->DS->getRowCount("coddoc");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
/*			$ls_condoc=$io_report->DS->data["condoc"][$li_i];
			$ls_tipdoc=$io_report->DS->data["tipdoc"][$li_i];
			$li_tamletdoc=$io_report->DS->data["tamletdoc"][$li_i];
			$li_tamletpiedoc=$io_report->DS->data["tamletpiedoc"][$li_i];
			if($li_tamletpiedoc=="")
			{
				$li_tamletpiedoc=$li_tamletdoc;
			}
			$li_intlindoc=$io_report->DS->data["intlindoc"][$li_i];
			$li_marinfdoc=$io_report->DS->data["marinfdoc"][$li_i];
			$li_marsupdoc=$io_report->DS->data["marsupdoc"][$li_i];
			$ls_titdoc=$io_report->DS->data["titdoc"][$li_i];
			$ls_piepagdoc=$io_report->DS->data["piepagdoc"][$li_i];*/
			$ls_original=$io_report->DS->data["arcrtfdoc"][$li_i];
			switch($ls_tipdoc)
			{
				case "contrato":
					$lb_valido=$io_report->uf_documento_contratista($ls_codcondes,$ls_codconhas);
					if($lb_valido)
					{
						$ls_archivo="../documentos/original/".$ls_original;
						$ls_copia=substr($ls_original,0,strrpos($ls_original,"."));
						$ls_salida="../documentos/copia/".$ls_copia."-".$_SESSION["la_logusr"].".rtf";
						$ls_contenido="";
						$ls_contenido=uf_leer_archivo($ls_archivo);
						$la_matriz=explode("sectd",$ls_contenido);
						$ls_cabecera=$la_matriz[0]."sectd";
						$li_inicio=strlen($ls_cabecera);
						$li_final=strrpos($ls_contenido,"}");
						$li_longitud=$li_final-$li_inicio;
						$ls_nuevocuerpo=substr($ls_contenido,$li_inicio,$li_longitud);
						$ls_punt=fopen($ls_salida,"w");
						fputs($ls_punt,$ls_cabecera);
						$li_totrow_det=$io_report->DS_detalle->getRowCount("codcon");
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{
							$ls_obra=$io_report->DS_detalle->data["desobr"][$li_s];
							$ls_direccion=$io_report->DS_detalle->data["dirobr"][$li_s];
							$ls_responsable=$io_report->DS_detalle->data["resobr"][$li_s];		
							$ls_contrato=$io_report->DS_detalle->data["codcon"][$li_s];		
							$ld_feccontrato=$io_report->DS_detalle->data["feccon"][$li_s];		
							$ls_nompro=$io_report->DS_detalle->data["nompro"][$li_s];		
							$ls_dirpro=$io_report->DS_detalle->data["dirpro"][$li_s];		
							$ls_rifpro=$io_report->DS_detalle->data["rifpro"][$li_s];		
							$ls_telpro=$io_report->DS_detalle->data["telpro"][$li_s];		
							$li_capital=$io_report->DS_detalle->data["capital"][$li_s];		
							$li_monto=$io_report->DS_detalle->data["monto"][$li_s];		
							$li_monmaxcon=$io_report->DS_detalle->data["monmaxcon"][$li_s];		
							$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_feccontrato,5,2));
							$ls_observacion=$io_report->DS_detalle->data["obscon"][$li_s];		
							$ls_fechacontrato="el ".substr($ld_feccontrato,8,2)." de ".$ls_mes." de ".substr($ld_feccontrato,0,4);
							$ld_fecinicontrato=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecinicon"][$li_s]);

							$ls_cuerpo=$ls_nuevocuerpo;
							$ls_cuerpo=str_replace("\$ls_obra",$ls_obra,$ls_contenido);
							$ls_cuerpo=str_replace("\$ls_direccion",$ls_direccion,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_responsable",$ls_responsable,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_contrato",$ls_contrato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_fechacontrato",$ls_fechacontrato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_nompro",$ls_nompro,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_dirpro",$ls_dirpro,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_telpro",$ls_telpro,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_rifpro",$ls_rifpro,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_capital",$li_capital,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_fecinicontrato",$ld_fecinicontrato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_monto",$li_monto,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_monmaxcon",$li_monmaxcon,$ls_cuerpo);
							fputs($ls_punt,$ls_cuerpo);
						}
					}
				break;
				case "actas":
					$lb_valido=$io_report->uf_documento_actas($ls_codcondes,$ls_codconhas);
					if($lb_valido)
					{
						$ls_archivo="../documentos/original/".$ls_original;
						$ls_copia=substr($ls_original,0,strrpos($ls_original,"."));
						$ls_salida="../documentos/copia/".$ls_copia."-".$_SESSION["la_logusr"].".rtf";
						$ls_contenido="";
						$ls_contenido=uf_leer_archivo($ls_archivo);
						$la_matriz=explode("sectd",$ls_contenido);
						$ls_cabecera=$la_matriz[0]."sectd";
						$li_inicio=strlen($ls_cabecera);
						$li_final=strrpos($ls_contenido,"}");
						$li_longitud=$li_final-$li_inicio;
						$ls_nuevocuerpo=substr($ls_contenido,$li_inicio,$li_longitud);
						$ls_punt=fopen($ls_salida,"w");
						fputs($ls_punt,$ls_cabecera);
						$li_totrow_det=$io_report->DS_detalle->getRowCount("codcon");
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{
							$ls_contenido=$ls_condoc;
							$ls_obra=$io_report->DS_detalle->data["desobr"][$li_s];
							$ls_direccion=$io_report->DS_detalle->data["dirobr"][$li_s];
							$ls_responsable=$io_report->DS_detalle->data["resobr"][$li_s];		
							$ls_contrato=$io_report->DS_detalle->data["codcon"][$li_s];		
							$ld_feccontrato=$io_report->DS_detalle->data["feccon"][$li_s];		
							$ls_nompro=$io_report->DS_detalle->data["nompro"][$li_s];		
							$ls_dirpro=$io_report->DS_detalle->data["dirpro"][$li_s];		
							$ls_rifpro=$io_report->DS_detalle->data["rifpro"][$li_s];		
							$ls_telpro=$io_report->DS_detalle->data["telpro"][$li_s];		
							$li_capital=$io_report->DS_detalle->data["capital"][$li_s];		
							$li_monto=$io_report->DS_detalle->data["monto"][$li_s];		
							$li_monmaxcon=$io_report->DS_detalle->data["monmaxcon"][$li_s];		
							$ls_acta=$io_report->DS_detalle->data["codact"][$li_s];		
							$li_monact=$io_report->DS_detalle->data["monact"][$li_s];		
							$ls_obsact=$io_report->DS_detalle->data["obsact"][$li_s];		
							$ls_mes=$io_fecha->uf_load_nombre_mes(substr($ld_feccontrato,5,2));
							$ls_observacion=$io_report->DS_detalle->data["obscon"][$li_s];		
							$ls_fechacontrato="el ".substr($ld_feccontrato,8,2)." de ".$ls_mes." de ".substr($ld_feccontrato,0,4);
							$ld_fecinicontrato=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecinicon"][$li_s]);
							$ld_fecact=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["fecact"][$li_s]);
							$ld_feciniact=$io_funciones->uf_convertirfecmostrar($io_report->DS_detalle->data["feciniact"][$li_s]);

							$ls_cuerpo=$ls_nuevocuerpo;
							$ls_cuerpo=str_replace("\$ls_obra",$ls_obra,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_direccion",$ls_direccion,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_responsable",$ls_responsable,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_contrato",$ls_contrato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_fechacontrato",$ls_fechacontrato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_nompro",$ls_nompro,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_dirpro",$ls_dirpro,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_telpro",$ls_telpro,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_rifpro",$ls_rifpro,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_capital",$li_capital,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_fecinicontrato",$ld_fecinicontrato,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_monto",$li_monto,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_monmaxcon",$li_monmaxcon,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_acta",$ls_acta,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$li_monact",$li_monact,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ls_obsact",$ls_obsact,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_fecact",$ld_fecact,$ls_cuerpo);
							$ls_cuerpo=str_replace("\$ld_feciniact",$ld_feciniact,$ls_cuerpo);
						}
					}
				break;
			}
			if($ls_cuerpo!="")
			{
				if($li_s<$li_totrow_det)
				{
					$ls_salto="\par \page \par";
					fputs($ls_punt,$ls_salto);
				}
			}
		}
		$io_report->DS->resetds("coddoc");
		fputs($ls_punt,"}");
		fclose($ls_punt);
		@chmod($ls_salida,0755);
		if($lb_valido) // Si no ocurrio ningún error
		{
			header ("Content-Disposition: attachment; filename=".$ls_copia."-".$_SESSION["la_logusr"].".rtf\n\n");
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($ls_salida));
			readfile($ls_salida);
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_sob);
?> 