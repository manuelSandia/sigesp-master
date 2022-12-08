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
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_soc;
		
		$ls_descripcion="Generó el Reporte de Formato de salida de ".$as_titulo;
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_p_solicitud_cotizacion.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numsolcot,$as_fecsolcot,$ls_numconsulta,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   hidnumero // Número de solicitud
		//	    		   ls_fecsolcot // Número de solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_rifemp = strtoupper($_SESSION["la_empresa"]["rifemp"]);
		$ls_diremp = strtoupper($_SESSION["la_empresa"]["direccion"]);
		$ls_nomemp = strtoupper($_SESSION["la_empresa"]["nombre"]);
		$ls_titemp = strtoupper($_SESSION["la_empresa"]["titulo"]);
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/banner_cnti.jpg',40,715,555,45); // Agregar Logo
		
		$io_pdf->addText(50,690,9,"<b>RAZON SOCIAL:   ".$ls_nomemp." (".$ls_titemp.")</b>"); // Agregar el título
		$io_pdf->addText(50,680,9,"<b>RIF:                         ".$ls_rifemp."</b>"); // Agregar el título
		$io_pdf->addText(50,670,9,"<b>DIRECCION:          ".utf8_decode($ls_diremp)."</b>"); // Agregar el título
		
		
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);		
		$io_pdf->addText(200,650,14,"<b>".$as_titulo."</b>"); // Agregar el título	
		$io_pdf->addText(170,635,14,"<b>CONSULTA DE PRECIO N° ".$ls_numconsulta."</b>"); // Agregar el título
		$io_pdf->addText(490,705,9,"<b> CARACAS, ".$as_fecsolcot."</b>"); // Agregar la Fecha
		$io_pdf->addText(495,695,8,"<b> N° :  </b>".$as_numsolcot); // Agregar la Fecha
		
		$li_tm=$io_pdf->getTextWidth(9,$_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"]);
		$tm=450-($li_tm/2);
		//$io_pdf->addText($tm,730,14,$ls_titulo); // Agregar el título
		$io_pdf->addText(120,43,7,"<b>".$_SESSION["la_empresa"]["direccion"]. ", Municipio Libertador, ".$_SESSION["la_empresa"]["ciuemp"]. " - Venezuela</b>"); //  Pie pagina
		$io_pdf->addText(220,33,7,"<b>Master: ".$_SESSION["la_empresa"]["telemp"]. " - ".$_SESSION["la_empresa"]["website"]."</b>"); //  Pie pagina
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],510,33,$_SESSION["ls_width"],45); // Agregar Logo
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_datos_proveedor($as_codpro,$as_nompro,$as_dirpro,$as_telpro,$as_email,$as_rifpro,$ls_telefono,$ls_nomusu, $ls_apeusu, &$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_datos_proveedor
		//		   Access: private 
		//	    Arguments: as_numsolcot // Número
		//	    		   as_fecsolcot // Fecha
		//	    		   as_obssolcot // Observación
		//	    		   as_codpro // Código de Proveedor
		//	    		   as_nompro // Nombre de Proveedor
		//	    		   as_dirpro // Dirección de Proveedor
		//	    		   as_telpro // Teléfono de Proveedor
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 19/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_pdf->saveState();

		$size = strlen($as_dirpro) > 100 ? 7 : 9;  
		
		$io_pdf->addText( 50,620,9,'<b>PARA: '.$as_nompro.'</b>'); 
		$io_pdf->addText( 50,605,9,'<b>RIF:     '.$as_rifpro.'</b>');  
		$io_pdf->addText( 50,590,$size,'<b>DIRECCION: '.utf8_decode($as_dirpro).'</b>');
		$io_pdf->addText( 50,575,9,'<b>TLF:  '.$as_telpro.'</b>');
		$io_pdf->addText(340,575,9,'<b>CORREO: '.$as_email.'</b>');
		$io_pdf->addText( 50,560,9,'<b>DE:  </b>'.strtoupper($ls_nomusu." ".$ls_apeusu));
		$io_pdf->addText(340,560,9,'<b>TLF/FAX:  </b>'.$ls_telefono);
		
		//$io_pdf->restoreState();
		//$io_pdf->closeObject();
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf,$ls_correosolcot,$ld_fecha,$as_obssolcot,$ls_consolcot)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 17/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		
		$io_pdf->ezSetDy(-70);
		
		$la_columna=array('codigo'=>'<b>CÓDIGO</b>',
						  'denunimed'=>'<b>UNIDAD</b>',
						  'cantidad'=>'<b>CANTIDAD</b>',
						  'denominacion'=>'<b>DESCRIPCIÓN</b>') ;
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xPos'=>320, // Orientación de la tabla
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70),
						 			   'denunimed'=>array('justification'=>'center','width'=>50),
						 			   'cantidad'=>array('justification'=>'center','width'=>60),      // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>340) // Justificación y ancho de la columna
						 			   )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'<b>DETALLE DE LOS MATERIALES, SUMINISTROS O SERVICIOS REQUERIDOS</b>',$la_config);
		
		$io_pdf->ezSetDy(-5);
		
		$observaciones = $as_obssolcot == "N/A" ? $ls_consolcot : 
								     		   $ls_consolcot. ' 
								 			 '.$as_obssolcot;
		
		$la_data=array(array('name'=> $observaciones));
		$la_columna=array('name'=>'<b><u>OBSERVACIONES:</u><b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
				'fontSize' => 9, // Tamaño de Letras
				'showLines'=>0, // Mostrar Líneas
				'titleFontSize' => 9,
				'shaded'=>0, // Sombra entre líneas
				'xPos'=>320, // Orientación de la tabla
				'width'=>548, // Ancho de la tabla
				'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
				
		$ls_nota = "1- <b>Presentar su cotización en Bolívares.</b>
				2- <b>Incluir en su cotización:</b>
				
	-      Validez de la oferta mínimo seis (06) días.
	-      Tiempo de entrega
	-      Condiciones de pago. (Preferiblemente 15).
	-      Mencionar si tiene Garantía el servicio y/o bien.
	-      Indicar si está o no incluido el I.V.A. en los precios cotizados.
				
3- En este proceso de adquisión solo podrán participar las empresas que estén vigentes con la solvencia laboral según el Decreto N° 4.248 
   publicado en Gaceta Oficial N° 38.371 de fecha 02/02/2006.
4- Tiempo para cotizar el día: <b>".$ld_fecha.". CUMPLIDO ESTE PERÍODO NO SERÁ CONSIDERADA SU OFERTA,</b> enviar vía fax su oferta y/o 
   por el correo ".$ls_correosolcot." firmada y sellada
5- Es <b>OBLIGATORIO</b> devolver firmada y sellada ésta solicitud junto con la oferta el día indicado (de lo contrario no será considerada su oferta).";
   
		
		$io_pdf->ezSetDy(-5);
		$la_data1=array(array('name'=>$ls_nota));
		$la_columna=array('name'=>'<b><u>CONSIDERACIONES GENERALES:</u><b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
				'fontSize' => 8, // Tamaño de Letras
				'showLines'=>0, // Mostrar Líneas
				'titleFontSize' => 9,
				'shaded'=>0, // Sombra entre líneas			
				'xPos'=>320, // Orientación de la tabla
				'width'=>548, // Ancho de la tabla
				'maxWidth'=>548); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		$io_pdf->ezSetDy(-5);
		$la_data1=array(array('name'=>''));
		$la_columna=array('name'=>'<b>"URGENTE"  "URGENTE"  "URGENTE"<b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
				'fontSize' => 12, // Tamaño de Letras
				'showLines'=>0, // Mostrar Líneas
				'shaded'=>0, // Sombra entre líneas
				'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
				'xPos'=>350, // Orientación de la tabla
				'width'=>300, // Ancho de la tabla
				'maxWidth'=>300); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		
		
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	require_once("sigesp_soc_class_report.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	
	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_report    = new sigesp_soc_class_report($con);
	$io_funciones = new class_funciones();
	$io_fun_soc	  = new class_funciones_soc();
	
	$ls_numsolcot = $_GET["numsolcot"];
	$ls_tipsolcot = $_GET["tipsolcot"];
	$ls_fecsolcot = $_GET["fecsolcot"];
	$ls_correosolcot = $_GET["correosolcot"];
	$ls_telefono = $_GET["telefono"];
	$ls_numconsulta = $_GET["numconsulta"];
	$ld_fecha = $_GET["fecha"];
	
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
	$ls_titulo = "SOLICITUD DE COTIZACIÓN";

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
				   $io_pdf->ezSetCmMargins(6.2,3,3,3); // Configuración de los margenes en centímetros
				   //$io_pdf->ezStartPageNumbers(580,20,8,'','',1); // Insertar el número de página
				   $li_count = 0; 
				   uf_print_encabezado_pagina($ls_titulo,$ls_numsolcot,$ls_fecsolcot,$ls_numconsulta,$io_pdf);
				   while (($row=$io_sql->fetch_row($rs_data)) && $lb_valido)
						 {
                           $li_count++;
					       if ($li_count>1)
					          {
						        $io_pdf->ezNewPage(); 					  
						      }   
 					  	   $ls_codpro    = $row["cod_pro"];
					  	   $ls_nompro    = $row["nompro"];
						   $ls_dirpro    = $row["dirpro"];
						   $ls_telpro    = $row["telpro"];
						   $ls_obssolcot = $row["obssol"];
						   $ls_fecsolcot = $row["fecsol"];
						   $ls_mailpro   = $row["email"];
						   $ls_rifpro    = $row["rifpro"];
						   $ls_consolcot = $row["consolcot"];
						   $ls_nomusu 	 = $row["nomusu"];
						   $ls_apeusu 	 = $row["apeusu"];
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
										    $ld_canite       = number_format($row["canite"],2,',','.');
										    $ls_denunimed    = $row["denunimed"];
									        $la_datos[$li_i] = array('codigo'=>$ls_codigo,'denominacion'=>utf8_decode($ls_denite),'cantidad'=>$ld_canite,'denunimed'=>$ls_denunimed);
									      }
				    		       }
						        else
							       {
							         $lb_valido = false;
							       }
						      }
					       
					       uf_print_datos_proveedor($ls_codpro,$ls_nompro,$ls_dirpro,$ls_telpro,$ls_mailpro,$ls_rifpro,$ls_telefono,$ls_nomusu, $ls_apeusu, $io_pdf);
					       uf_print_detalle($la_datos,$io_pdf,$ls_correosolcot,$ld_fecha,$ls_obssolcot,$ls_consolcot);
					     }
			        //$io_pdf->ezStopPageNumbers(1,1);
			        $io_pdf->ezStream();
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