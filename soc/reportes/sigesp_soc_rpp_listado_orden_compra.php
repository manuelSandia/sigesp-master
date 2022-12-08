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
		//	    Arguments: as_titulo // T_tulo del Reporte
		//	    		   as_desnom // Descripci_n de la n_mina
		//	    		   as_periodo // Descripci_n del per_odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci_n que imprime los encabezados por p_gina
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci_n: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$io_encabezado=$io_pdf->openObject();		
		$io_pdf->saveState();		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(10,$as_titulo);
		$tm=280-($li_tm/2);
		$io_pdf->addText($tm,730,12,"<b>".$as_titulo."</b>"); // Agregar el t_tulo
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
		//	    Arguments: la_data // arreglo de informaci_n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci_n que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci_n: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		global $ls_bolivares;
		
		$la_columna=array('codigo'=>'<b>Código</b>',
						  'nombre'=>'<b>Nombre</b>',
						  'estcondat'=>'<b>Tipo</b>',
						  'fecha'=>'<b>Fecha</b>',
						  'estatus'=>'<b>Estatus</b>',
						  'monto'=>'<b>Monto '.$ls_bolivares.'</b>');
						  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama_o de Letras
						 'titleFontSize' => 10,  // Tama_o de Letras de los t_tulos
						 'showLines'=>1, // Mostrar L_neas						 
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M_ximo de la tabla
						 'xPos'=>310, // Orientaci_n de la tabla
						 'shaded'=>0, // Sombra entre l_neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>80), // Justificaci_n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>150), // Justificaci_n y ancho de la columna
									   'estcondat'=>array('justification'=>'center','width'=>45), // Justificaci_n y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>60), // Justificaci_n y ancho de la columna
						 			   'estatus'=>array('justification'=>'center','width'=>150),
   						 			   'monto'=>array('justification'=>'right','width'=>90))); // Justificaci_n y ancho de la columna
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
		//    Description: funci_n que imprime la cabecera de cada p_gina
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci_n: 16/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
	    $io_pdf->ezSetDy(-10);
		$la_data=array(array('name'=>'<b>Nro de Registros:</b>'.$ad_numreg,
		                     'name1'=>'<b>Total '.$ls_bolivares.':</b> '.$ad_totmon));				
		$la_columna=array('name'=>'','name1'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama_o de Letras
						 'showLines'=>0, // Mostrar L_neas
						 'shaded'=>0, // Sombra entre l_neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>310, // Orientaci_n de la tabla
						 'width'=>750, // Ancho de la tabla						 
						 'maxWidth'=>750, // Orientaci? de la tabla
						 'cols'=>array('name'=>array('justification'=>'left','width'=>250),      // Justificaci? y ancho de la columna
						 			   'name1'=>array('justification'=>'right','width'=>335))); // Ancho M_ximo de la tabla
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
	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_soc   = new class_funciones_soc();
	$io_report    = new sigesp_soc_class_report($con);
	$ls_tiporeporte=$io_fun_soc->uf_obtenervalor_get("tiporeporte",0);
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
		
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido=true;
	//----------------------------------------------------  Par_metros del encabezado    -----------------------------------------------
	$ls_titulo ="LISTADO DE LAS ORDENES DE COMPRAS";	
	//--------------------------------------------------  Par_metros para Filtar el Reporte  -----------------------------------------
	
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
	
	$ls_rdanucom="0";
	$ls_rdemi=$io_fun_soc->uf_obtenervalor_get("rdemi","");
	$ls_rdpre=$io_fun_soc->uf_obtenervalor_get("rdpre","");	
	$ls_rdcon=$io_fun_soc->uf_obtenervalor_get("rdcon","");
	$ls_rdanu=$io_fun_soc->uf_obtenervalor_get("rdanu","");
	$ls_rdinv=$io_fun_soc->uf_obtenervalor_get("rdinv","");
	$ls_rdfin=$io_fun_soc->uf_obtenervalor_get("rdfin","");
	$ls_rdsdp=$io_fun_soc->uf_obtenervalor_get("rdsdp","");
	
	
	
	$ls_estcondat=$io_fun_soc->uf_obtenervalor_get("rdtipo","");
	$ls_tipo=$io_fun_soc->uf_obtenervalor_get("esttip","");
	$ls_ordenes=$io_fun_soc->uf_obtenervalor_get("ordenes","");
    $lr_ordenes= split('>>',$ls_ordenes);
    $lr_datos= array_unique($lr_ordenes);

	//--------------------------------------------------------------------------------------------------------------------------------
	if($ls_tiporeporte=="NORMAL")
	{
		$rs_data = $io_report->uf_select_listado_orden_compra($ls_numordcomdes,$ls_numordcomhas,$ls_codprodes,
																$ls_codprohas,$ls_fecordcomdes,$ls_fecordcomhas,$ls_coduniadmdes,
																$ls_coduniadmhas,$ls_rdanucom,$ls_rdemi,$ls_rdpre,$ls_rdcon,$ls_rdanu,
																$ls_rdinv,$ls_rdfin,$ls_rdsdp,$ls_codartdes,$ls_codarthas,$ls_codserdes,
																$ls_codserhas,$ls_estcondat,$ls_tipo,&$lb_valido);
	}
	if($lb_valido==false) // Existe alg_n error _ no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$ls_descripcion="Gener_ el Reporte de Listado de Orden de Compra";
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_r_orden_compra.php",$ls_descripcion);
		if($lb_valido)	
		{
			error_reporting(E_ALL);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuraci_n de los margenes en cent_metros
			uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la p_gina
			$io_pdf->ezStartPageNumbers(578,47,9,'','',1); // Insertar el n_mero de p_gina
			$ldec_monto=0;
			$li_i=0;
			if($ls_tiporeporte=="SELECTIVO")
			{
   				$li_row= count($lr_datos);
			}
			else
			{
				$li_row=$io_sql->num_rows($rs_data);
			}
			if ($li_row>0)
			{     
				if($ls_tiporeporte=="SELECTIVO")
				{
					for($li_i=0;$li_i<$li_row;$li_i++)
					{
						$ls_ordcom=$lr_datos[$li_i];
						$lr_ordenesdet= split('>',$ls_ordcom);
						$lr_detalle= array_unique($lr_ordenesdet);
						$ls_numordcom=$lr_detalle[0];
						$ls_estcondat=$lr_detalle[1];
						$rs_data=$io_report->uf_select_orden_compra($ls_numordcom,$ls_estcondat,&$lb_valido);
						while($row=$io_sql->fetch_row($rs_data))
						{
							  $li_i=$li_i+1;
							  $ls_nompro     ="";
							  $ls_estcon     ="";
							  $ls_estcondat  =""; 
							  $ls_numord  = $row["numordcom"]; 
							  $ls_estcon  = $row["estcondat"];
							  $ls_codpro  = $row["cod_pro"];
							  $ls_estatus = $row["estcom"];
							  $ls_estapro = $row["estapro"];
							  $ls_fecord  = $row["fecordcom"];
							  $ld_monto   = $row["montot"];
							  $ldec_monto= $ldec_monto+$ld_monto;
							  $ld_monto   = number_format($ld_monto,2,",",".");	
							  $ls_nombre  = $io_report->uf_select_nombre_proveedor($ls_codpro);
							  $ls_fecha   = $io_funciones->uf_convertirfecmostrar($ls_fecord);	
							  
							  if($ls_estcon=="B") {  $ls_estcondat="Bienes";  }
							  
			
							  if($ls_estcon=="S") {  $ls_estcondat="Servicios";	  }
			
							  if( ($ls_estcon=="-") || ($ls_estcon=="") )  {   $ls_estcondat="";  }
			
							  $status = $io_fun_soc->uf_get_estado_ordencompra($ls_estatus, $ls_estapro);		   							   
					
							 $la_data[$li_i]= array('codigo'=>$ls_numord,'nombre'=>$ls_nombre,'estcondat'=>$ls_estcondat,
												 'fecha'=>$ls_fecha,'estatus'=>$status,'monto'=>$ld_monto);
						}
						$li_i=$li_i-1;
					}
				}
				else
				{
					while($row=$io_sql->fetch_row($rs_data))
					{
					  $li_i=$li_i+1;
					  $ls_nompro     ="";
					  $ls_estcon     ="";
					  $ls_estcondat  =""; 
					  $ls_numord  = $row["numordcom"];
					  $ls_estcon  = $row["estcondat"];
					  $ls_codpro  = $row["cod_pro"];
					  $ls_estatus = $row["estcom"];
					  $ls_estapro = $row["estapro"];
					  $ls_fecord  = $row["fecordcom"];
					  $ld_monto   = $row["montot"];
					  $ldec_monto= $ldec_monto+$ld_monto;
					  $ld_monto   = number_format($ld_monto,2,",",".");	
					  $ls_nombre  = $io_report->uf_select_nombre_proveedor($ls_codpro);
					  $ls_fecha   = $io_funciones->uf_convertirfecmostrar($ls_fecord);	
					  
					  if($ls_estcon=="B") {  $ls_estcondat="Bienes";  }
					  
	
					  if($ls_estcon=="S") {  $ls_estcondat="Servicios";	  }
	
					  if( ($ls_estcon=="-") || ($ls_estcon=="") )  {   $ls_estcondat="";  }
	
					  $status = $io_fun_soc->uf_get_estado_ordencompra($ls_estatus, $ls_estapro);
					  
					 $la_data[$li_i]= array('codigo'=>$ls_numord,'nombre'=>$ls_nombre,'estcondat'=>$ls_estcondat,
										 'fecha'=>$ls_fecha,'estatus'=>$status,'monto'=>$ld_monto);
					}
				}
			    uf_print_listado($la_data,$io_pdf); // Imprimimos el detalle 		
				$ldec_monto  = number_format($ldec_monto,2,",",".");	
				uf_print_pie_cabecera($li_i,$ldec_monto,$io_pdf);		
				if($lb_valido) // Si no ocurrio ning_n error
				{
					$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi_n de los n_meros de p_gina
					$io_pdf->ezStream(); // Mostramos el reporte
				}
				else  // Si hubo alg_n error
				{
					print("<script language=JavaScript>");
					print("alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
					print("close();");
					print("</script>");		
				}
				unset($io_pdf);
			}
			else
			{
				print("<script language=JavaScript>");
				print("alert('No hay nada que reportar');"); 
			//	print("close();");
				print("</script>");		
			}				
		}	
		unset($io_report);
		unset($io_funciones);
	}	
?> 