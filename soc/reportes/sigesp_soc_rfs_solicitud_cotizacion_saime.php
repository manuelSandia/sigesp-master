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
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. N�stor Falc�n.
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;

		$ls_descripcion="Gener� el Reporte de Formato de salida de ".$as_titulo;
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_p_solicitud_cotizacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numsolcot,$as_fecsolcot,$as_dentipsolcot,$as_obssolcot,$as_nomsolic,$as_soltel,$as_nomusu,$as_cedusu,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   hidnumero // N�mero de solicitud
		//	    		   ls_fecsolcot // N�mero de solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. N�stor Falc�n.
		// Fecha Creaci�n: 17/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState(); 
		$io_pdf->rectangle(165,705,425,40);
		$io_pdf->line(450,705,450,745);
		$io_pdf->line(450,725,590,725);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],35,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);		
		$io_pdf->addText(200,720,14,"<b>".$as_titulo."</b>"); // Agregar el t�tulo
		$io_pdf->addText(460,730,10,"<b>   No.:</b>");      // Agregar texto
		$io_pdf->addText(495,730,10,$as_numsolcot); // Agregar Numero de la solicitud
		$io_pdf->addText(450,710,10,"<b>  Fecha:</b>"); // Agregar texto
		$io_pdf->addText(495,710,10,$as_fecsolcot); // Agregar la Fecha
		$io_pdf->addText(555,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(560,760,7,date("h:i a")); // Agregar la hora
		$io_pdf->addText(50,680,10,"Tipo: ".$as_dentipsolcot); // Agregar la Fecha
		$io_pdf->line(40,115,590,115);//horizontal		
		$io_pdf->line(40,73,40,115);//vertical
		$io_pdf->line(590,73,590,115);//vertical
		$io_pdf->line(40,73,590,73);//horizontal		
		$io_pdf->addText(45,80,7,"Elaborado por: ".$as_nomusu." C.I. ".$as_cedusu.", Firma y Sello"); // Agregar el t�tulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos_proveedor($as_codpro,$as_nompro,$as_dirpro,$as_telpro,$as_email,$as_rifpro,$as_faxpro,$as_consolcot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_proveedor
		//		   Access: private
		//	    Arguments: as_numsolcot // N�mero
		//	    		   as_fecsolcot // Fecha
		//	    		   as_obssolcot // Observaci�n
		//	    		   as_codpro // C�digo de Proveedor
		//	    		   as_nompro // Nombre de Proveedor
		//	    		   as_dirpro // Direcci�n de Proveedor
		//	    		   as_telpro // Tel�fono de Proveedor
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. N�stor Falc�n.
		// Fecha Creaci�n: 19/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(677);
		$la_data[1]=array('name'=>'<b>DATOS DEL PROVEEDOR</b>');
		$la_columna=array('name'=>'<b>DATOS DEL PROVEEDOR</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'titleFontSize' => 9,
						 'shaded'=>2, // Sombra entre l�neas
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>548, // Ancho de la tabla
						 'justification'=>'center', // Ancho de la tabla
						 'maxWidth'=>548); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('nombre'=>'<b>Nombre o Raz�n Social: </b>'.$as_codpro.'  -  '.$as_nompro,'rif'=>'<b>RIF: </b>'.$as_rifpro);
		$la_columna=array('nombre'=>'','rif'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'titleFontSize' => 9,
						 'shaded'=>0, // Sombra entre l�neas
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>548, // Ancho de la tabla
						 'justification'=>'center', // Ancho de la tabla
						 'maxWidth'=>548, // Ancho M�ximo de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>400),     // Justificaci�n y ancho de la columna
						 			   'rif'=>array('justification'=>'left','width'=>148))); 	// Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>Direcci�n: </b>'.$as_dirpro);
		$la_columna=array('name'=>'<b>Direcci�n</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'titleFontSize' => 9,
						 'shaded'=>0, // Sombra entre l�neas
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>548, // Ancho de la tabla
						 'justification'=>'center', // Ancho de la tabla
						 'maxWidth'=>548); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('telefono'=>'<b>Tel�fono: </b>'.$as_telpro,'correo'=>'<b>Correo Electr�nico: </b>'.$as_email,'fax'=>'<b>Fax: </b>'.$as_faxpro);
		$la_columna=array('telefono'=>'','correo'=>'','fax'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'titleFontSize' => 9,
						 'shaded'=>0, // Sombra entre l�neas
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>548, // Ancho de la tabla
						 'justification'=>'center', // Ancho de la tabla
						 'maxWidth'=>548, // Ancho M�ximo de la tabla
						 'cols'=>array('telefono'=>array('justification'=>'left','width'=>182),     // Justificaci�n y ancho de la columna
						 			   'correo'=>array('justification'=>'left','width'=>183), 	// Justificaci�n y ancho de la columna
						 			   'fax'=>array('justification'=>'left','width'=>183))); 	// Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data1=array(array('name'=>'<b>Concepto: </b>'.$as_consolcot));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>548, // Ancho de la tabla
						 'maxWidth'=>548); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);

	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$as_obssolcot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. N�stor Falc�n.
		// Fecha Creaci�n: 17/05/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data1=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>548, // Ancho de la tabla
						 'maxWidth'=>548); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		$la_data1[1]=array('renglon'=>'<b>Nro</b>',
						  'codigo'=>'<b>C�digo</b>',
						  'denominacion'=>'<b>Denominaci�n</b>',
						  'unidad'=>'<b>Unidad de Medida</b>',
  						  'cantidad'=>'<b>Cant.</b>');
		$la_columna=array('renglon'=>'<b>Nro</b>',
						  'codigo'=>'<b>C�digo</b>',
						  'denominacion'=>'<b>Denominaci�n</b>',
						  'unidad'=>'<b>Unidad</b>',
  						  'cantidad'=>'<b>Cant.</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>320, // Orientaci�n de la tabla
						 'cols'=>array('renglon'=>array('justification'=>'center','width'=>60),     // Justificaci�n y ancho de la columna
						 			   'codigo'=>array('justification'=>'center','width'=>110),     // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>280), // Justificaci�n y ancho de la columna
									   'unidad'=>array('justification'=>'center','width'=>60), 		// Justificaci�n y ancho de la columna
						 			   'cantidad'=>array('justification'=>'center','width'=>40))); 	// Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columna,'<b>DETALLE DE LOS MATERIALES, SUMINISTROS O SERVICIOS REQUERIDOS</b>',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		$la_columna=array('renglon'=>'<b>Nro</b>',
						  'codigo'=>'<b>C�digo</b>',
						  'denominacion'=>'<b>Denominaci�n</b>',
						  'unidad'=>'<b>Unidad</b>',
  						  'cantidad'=>'<b>Cant.</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>320, // Orientaci�n de la tabla
						 'cols'=>array('renglon'=>array('justification'=>'center','width'=>60),     // Justificaci�n y ancho de la columna
						 			   'codigo'=>array('justification'=>'center','width'=>110),     // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>280), // Justificaci�n y ancho de la columna
									   'unidad'=>array('justification'=>'left','width'=>60), 		// Justificaci�n y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>40))); 	// Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	$la_data=array(array('name'=>''),array('name'=>''),array('name'=>''),array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>548, // Ancho de la tabla
						 'maxWidth'=>548); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]=array('name'=>'<b>Observaciones: </b>'.$as_obssolcot);
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>320, // Orientaci�n de la tabla
						 'width'=>548, // Ancho de la tabla
						 'maxWidth'=>548); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	require_once("sigesp_soc_class_report.php");
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");

	print(' Generando PDFS...<br>');
	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);
	$io_report    = new sigesp_soc_class_report($con);
	$io_funciones = new class_funciones();
	$io_fun_soc	  = new class_funciones_soc();

	$ls_numsolcot = $_GET["numsolcot"];
	$ls_tipsolcot = $_GET["tipsolcot"];
	$ls_fecsolcot = $_GET["fecsolcot"];
	if ($ls_tipsolcot=='B')
	   {
	     $ls_tabla = "soc_dtsc_bienes";
	     $ls_campo = "codart";
	     $ls_table = "siv_articulo";
	     $ls_tipo  = "Bienes";
	   }
	elseif($ls_tipsolcot=='S')
	   {
	     $ls_tabla = "soc_dtsc_servicios";
	     $ls_campo = "codser";
	     $ls_table = "soc_servicios";
	     $ls_tipo  = "Servicios";
	   }
	$ls_codemp = $_SESSION["la_empresa"]["codemp"];
	$ls_titulo = "SOLICITUD DE COTIZACI�N";

	$lb_valido = uf_insert_seguridad($ls_titulo); // Seguridad de Reporte

	if ($lb_valido)
	   {
	     $rs_data = $io_report->uf_load_cabecera_formato_solicitud_cotizacion($ls_numsolcot,$ls_tipsolcot,$ls_fecsolcot,$ls_tabla,&$lb_valido);
	     if (!$lb_valido)
		    {
			  print("<script language=JavaScript>");
			  print(" alert('No hay nada que Reportar !!!');");
			  print(" close();");
			  print("</script>");
		    }
	     else
	        {
	          $li_numrows = $io_sql->num_rows($rs_data);
		      if ($li_numrows>0)
		         {
				   error_reporting(E_ALL);
				   $io_pdf = new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
				   $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
				   $io_pdf->ezSetCmMargins(5,3,3,3); // Configuraci�n de los margenes en cent�metros
				   $li_count = 0;
				   while (($row=$io_sql->fetch_row($rs_data)) && $lb_valido)

						 {
                           $li_count++;
					       if ($li_count>1)
					          {
						        //$io_pdf->ezNewPage();

						      }
						   $ls_nompro    = '';
						   $ls_dirpro    = '';
 					  	   $ls_codpro    = $row["cod_pro"];
					  	   $ls_nompro    = $row["nompro"];
						   $ls_dirpro    = $row["dirpro"];
						   $ls_telpro    = $row["telpro"];
						   $ls_obssolcot = $row["obssol"];
						   $ls_consolcot = $row["consolcot"];
						   $ls_soltel    = $row["soltel"];
						   $ls_solic	 = trim($row["apeper"]).', '.trim($row["nomper"]);
						   $ls_fecsolcot = $row["fecsol"];
						   $ls_faxpro   = $row["faxpro"];
						   $ls_mailpro   = $row["email"];
						   $ls_rifpro    = $row["rifpro"];
						   $ls_nomusu    = $row["nomusu"].'  '.$row["apeusu"];
						   $ls_cedusu    = $row["cedusu"];
						   $ls_namesolc	 = 'SolCot_'.$ls_numsolcot.'-'.$li_count.'.pdf';
						   $ls_fecsolcot = $io_funciones->uf_convertirfecmostrar($ls_fecsolcot);
						   $rs_datos     = $io_report->uf_load_dt_solicitud_cotizacion($ls_numsolcot,$ls_codpro,$ls_tabla,$ls_table,$ls_campo,&$lb_valido);
						   if ($lb_valido)
					          {
					     	    $li_totrows = $io_sql->num_rows($rs_datos);
							    if ($li_totrows>0)
							       {
							         $li_i = 0;
								     while($row=$io_sql->fetch_row($rs_datos))
								          {
									        $li_i++;
										    $ls_codigo       = $row["codite"];
										    $ls_denite       = $row["denite"];
										    $ls_uniite		 = $row["denunimed"];
										    $ls_dentipart		 = $row["dentipart"];
										    $ld_canite       = number_format($row["canite"],2,',','.');
									        $la_datos[$li_i] = array('renglon'=>$li_i,'codigo'=>$ls_codigo,'denominacion'=>$ls_denite,'unidad'=>$ls_uniite,'cantidad'=>$ld_canite);
									      }
				    		       }
						        else
							       {
							         $lb_valido = false;
							       }
						      }
					       uf_print_encabezado_pagina($ls_titulo,$ls_numsolcot,$ls_fecsolcot,$ls_tipo,$ls_obssolcot,$ls_solic,$ls_soltel,$ls_nomusu,$ls_cedusu,$io_pdf);
					       uf_print_datos_proveedor($ls_codpro,$ls_nompro,$ls_dirpro,$ls_telpro,$ls_mailpro,$ls_rifpro,$ls_faxpro,$ls_consolcot,$io_pdf);
					       uf_print_detalle($la_datos,$ls_obssolcot,$io_pdf);

	           		       $io_pdf->setStrokeColor(0,0,0);
					     //  $io_pdf->line(20,50,580,50);

							//Escribir el PDF a archivo en el directorio
							$pdfcode = $io_pdf->ezOutput();
							$fp=fopen(''.$ls_namesolc,'wb');
							fwrite($fp,$pdfcode);
							fclose($fp);
							$sc_name_path=''.$ls_namesolc;

							//---> muestra el pdf en un ventana adicional
							print('<script language=JavaScript>');
							print(" window.open('$sc_name_path','$ls_namesolc','menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes');");
							print('</script>');

							unset($io_pdf);
							$io_pdf = new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
							$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
							$io_pdf->ezSetCmMargins(5,3,3,3); // Configuraci�n de los margenes en cent�metros
							//$io_pdf->ezStartPageNumbers(550,30,10,'','',1); // Insertar el n�mero de p�gina

					     }


			        print('<script language=JavaScript>');
			        print(" alert('generacion de pdfs completada');");
					print(' close();');
					print('</script>');
			     }
		      else
		         {
			       print("<script language=JavaScript>");
			       print(" alert('No hay nada que Reportar');");
			       print(" close();");
			       print("</script>");
			     }
	        }
	   }
?>