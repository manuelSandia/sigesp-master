<?Php
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
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_desnom // Descripción de la nómina
		//	    		   as_periodo // Descripción del período
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(15,'<b>'.$as_titulo.'</b>');
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,15,'<b>'.$as_titulo.'</b>'); // Agregar el título
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_nomper,$as_desnom,$as_descar, $as_cuefidper,$ai_sueper,$ad_fecingper,$as_servicio,
							   &$io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: $as_codper  // Código del Personal
		//	   			   $as_nomper  // Nombre del Personal 
		//	   			   $as_apeper  // apellido del Personal
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf      // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por personal
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(700);
		$la_data[0]=array('campo1'=>'<b>Cédula:</b>','campo2'=>''.$as_cedper.' - '.$as_nomper.'','campo3'=>'<b>Cta. Banco:</b>','campo4'=>''.$as_cuefidper.'');
		$la_data[1]=array('campo1'=>'<b>Cargo:</b>','campo2'=>''.$as_descar.'','campo3'=>'<b>Ingreso:</b>','campo4'=>''.$ad_fecingper.'');
		$la_data[2]=array('campo1'=>'<b>Nomina:</b>','campo2'=>''.$as_desnom.'','campo3'=>'<b>Sueldo:</b>','campo4'=>''.$ai_sueper.'');
		$la_data[3]=array('campo1'=>'<b>Servicio:</b>','campo2'=>''.$as_servicio.'','campo3'=>'','campo4'=>'');
		$la_columnas=array('campo1'=>'','campo2'=>'','campo3'=>'','campo4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('campo1'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
									   'campo2'=>array('justification'=>'left','width'=>275), // Justificación y ancho de la columna
									   'campo3'=>array('justification'=>'right','width'=>75), // Justificación y ancho de la columna
									   'campo4'=>array('justification'=>'left','width'=>125))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);   
		$la_columnas=array('desde'=>'<b>DESDE</b>',
						   'hasta'=>'<b>HASTA</b>',
						   'sueldo'=>'<b>SUELDO INTEGRAL</b>   ',
						   'antiguedad'=>'<b>ANTIGUEDAD</b>       ',
						   'anticipo'=>'<b>ANTICIPO</b>            ');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8,   // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1,    // Mostrar Líneas
						 'shaded'=>0,       // Sombra entre líneas
						 'width'=>500,      // Ancho de la tabla
						 'maxWidth'=>500,   // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('desde'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
									   'hasta'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
									   'sueldo'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'antiguedad'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'anticipo'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
	}// end function uf_print_detalle
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------//

	//----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function uf_print_total($ai_totalantiguedad,$ai_totalanticipo,$ai_disponible,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_total
		//		    Acess : private
		//	    Arguments : $ld_total
		//    Description : función que imprime el total de asignaciones
		//	   Creado Por : Ing. Maria Alejandra Roa
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 
		$la_data[0]=array('campo1'=>'','campo2'=>'','campo3'=>'');
		$la_data[1]=array('campo1'=>'','campo2'=>'','campo3'=>'');
		$la_data[2]=array('campo1'=>'<b>ANTIGUEDAD</b>','campo2'=>'<b>ANTICIPO</b>','campo3'=>'<b>DISPONIBILIDAD</b>');
		$la_data[3]=array('campo1'=>'<b>'.$ai_totalantiguedad.'</b>','campo2'=>'<b>'.$ai_totalanticipo.'</b>','campo3'=>'<b>'.$ai_disponible.'</b>');
		$la_columna=array('campo1'=>'','campo2'=>'','campo3'=>'');
		$la_config=array('showHeadings'=>0,      // Mostrar encabezados
						 'fontSize' => 9,        // Tamaño de Letras
						 'showLines'=>0,         // Mostrar Líneas
						 'shaded'=>0,            // Sombra entre líneas
						 'width'=>450,           // Ancho Máximo de la tabla
						 'rowGap'=>2,
						 'colGap'=>0,
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('campo1'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
									   'campo2'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
									   'campo3'=>array('justification'=>'center','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	
	}// end function uf_print_total
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------//

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../../sps/class_folder/dao/sps_pro_antiguedad_dao.php");
	require_once("../../../shared/ezpdf/class.ezpdf.php");
	require_once("../../../shared/class_folder/class_fecha.php");
	$lo_antig_dao = new sps_pro_antiguedad_dao();
	$lo_fecha = new class_fecha();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$lb_valido=$lo_antig_dao->getCabeceraReportemovimientos("ORDER BY ".$_GET["orden"],$_GET["codper1"],$_GET["codper2"],$_GET["codnom"],$rs_data);
	if ($lb_valido===false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		$lo_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$lo_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$lo_pdf->ezSetCmMargins(5.3,2.5,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina('PRESTACIÓN DE ANTIGUEDAD',$lo_pdf); // Imprimimos el encabezado de la página
		$lo_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
		while(!$rs_data->EOF)
		{
			$ls_cedper = $rs_data->fields["cedper"];
			$ls_nomper = $rs_data->fields["nomper"]." ".$rs_data->fields["apeper"];
			$ls_desnom = $rs_data->fields["desnom"];
			$ls_descar=$rs_data->fields["descar"];
			if($rs_data->fields["codcar"]=='0000000000')
			{
				$ls_descar=$rs_data->fields["denasicar"];
			}
			$ld_fecingper=substr($rs_data->fields["fecingper"],8,2)."/".substr($rs_data->fields["fecingper"],5,2)."/".substr($rs_data->fields["fecingper"],0,4);
			$li_sueper=number_format($rs_data->fields["sueintper"],2,",",".");
			$ls_cuefidper=$rs_data->fields["cuefidper"];
			$li_anioserv=date('Y')-substr($rs_data->fields["fecingper"],0,4);
			$li_messerv=date('m');
			if (intval(substr($rs_data->fields["fecingper"],5,2))<=intval($li_messerv))
			{
				$li_messerv=date('m')-intval(substr($rs_data->fields["fecingper"],5,2));
			}
			else
			{
				$li_anioserv=$li_anioserv-1;
				$li_messerv=(12-intval(substr($rs_data->fields["fecingper"],5,2))+date('m'));
			}
			$li_diaserv=date('d');
			if (intval(substr($rs_data->fields["fecingper"],8,2))<=intval($li_diaserv))
			{
				$li_diaserv=date('d')-intval(substr($rs_data->fields["fecingper"],8,2));
			}
			else
			{
				$li_messerv=$li_messerv-1;
				$li_diaserv=(30-intval(substr($rs_data->fields["fecingper"],8,2))+date('d'));
			}
			$ls_servicio=$li_anioserv." Años ".$li_messerv." Meses ".$li_diaserv." Días";
			$lo_cabecera= $lo_pdf->openObject();   // Creamos el objeto cabecera
			uf_print_cabecera($ls_cedper,$ls_nomper,$ls_desnom,$ls_descar, $ls_cuefidper,$li_sueper,$ld_fecingper,$ls_servicio,&$lo_cabecera,&$lo_pdf);
			// Obtenemos el detalle del reporte
			$lb_hay = $lo_antig_dao->getDetalleMovimiento($rs_data->fields['codnom'],$rs_data->fields['codper'],&$rs_detalle);									   
			if($lb_hay)
			{
				$li_totalantiguedad =0;
				$li_totalanticipo =0;
				$li_i=0;
				$li_dia=substr($rs_data->fields["fecingper"],8,2);
				$ld_desdeant='';
				$ld_hastaant='';
				$li_anticipo=0;
				$li_valant=0;
				$lb_antiguedad=false;
				$li_deudaant=0;
				while(!$rs_detalle->EOF)
				{
					$li_tipo = $rs_detalle->fields["tipo"];
					$li_mesdes =substr($rs_detalle->fields["fecha"],5,2)-1;
					$li_aniodes =substr($rs_detalle->fields["fecha"],0,4);
					$li_aniohas =substr($rs_detalle->fields["fecha"],0,4);
					if($li_mesdes==0)
					{
						$li_mesdes='12';
						$li_aniodes=$li_aniodes-1;
					}
					$li_mesdes=str_pad($li_mesdes,2,"0",0);
					$li_meshas =substr($rs_detalle->fields["fecha"],5,2);
					$ld_desde=$li_dia."/".$li_mesdes."/".$li_aniodes;
					$ld_hasta=$li_dia."/".$li_meshas."/".$li_aniohas;
					$li_sueldo=number_format($rs_detalle->fields["sueldo"],2,",",".");
					$li_antiguedad=number_format($rs_detalle->fields["antiguedad"],2,",",".");
					if ($lo_fecha->uf_comparar_fecha($ld_hasta,'14/12/2007'))
					{
						$li_totalantiguedad =$li_totalantiguedad+($rs_detalle->fields["antiguedad"]/1000);
						$li_totalanticipo =$li_totalanticipo+($rs_detalle->fields["anticipo"]/1000);
					}
					else
					{
						$li_totalantiguedad =$li_totalantiguedad+$rs_detalle->fields["antiguedad"];
						$li_totalanticipo =$li_totalanticipo+$rs_detalle->fields["anticipo"];
					}
					$li_anticipo=0;
					switch ($li_tipo)
					{
						case 0:
							$li_fecant=$rs_detalle->fields["fecha"];
							$li_deudaant=$rs_detalle->fields["antiguedad"]-$rs_detalle->fields["anticipo"];
						break;
						
						case 1:
							$lb_antiguedad=true;
							if($li_valant>0)
							{
								if ($lo_fecha->uf_comparar_fecha($li_fecant,$ld_hasta))
								{
									$li_anticipo=$li_valant;
									$li_valant=0;
								}
							}
							$li_anticipo=number_format($li_anticipo,2,",",".");
							$li_i++;
							$la_data[$li_i]=array('desde'=>$ld_desde,'hasta'=>$ld_hasta,'sueldo'=>$li_sueldo,'antiguedad'=>$li_antiguedad,'anticipo'=>$li_anticipo);
						break;

						case 2:
							$li_valant=$rs_detalle->fields["anticipo"];
							$li_fecant=$rs_detalle->fields["fecha"];
							if ($lo_fecha->uf_comparar_fecha($li_fecant,$ld_hasta))
							{
								$li_anticipo=$li_valant;
								$li_valant=0;
								if(!$lb_antiguedad)
								{
									$li_i++;
									$la_data[$li_i]=array('desde'=>$ld_desde,'hasta'=>$ld_hasta,'sueldo'=>$li_sueldo,'antiguedad'=>$li_antiguedad,'anticipo'=>$li_anticipo);
								}
								$la_data[$li_i]['anticipo']=number_format($li_anticipo,2,",",".");
							}
						break;
					}
					$rs_detalle->MoveNext();					
				}
				uf_print_detalle($la_data,$lo_pdf); // Imprimimos el detalle 
			}
			$li_disponible = number_format($li_deudaant + $li_totalantiguedad - $li_totalanticipo,2,",",".");
			$li_totalantiguedad =number_format($li_deudaant + $li_totalantiguedad,2,",",".");
			$li_totalanticipo =number_format($li_totalanticipo,2,",",".");
			uf_print_total($li_totalantiguedad,$li_totalanticipo,$li_disponible,&$lo_pdf);
			$lo_pdf->stopObject($lo_cabecera); 
			unset($lo_cabecera);
			unset($la_data);
			$rs_data->MoveNext();
			if (!$rs_data->EOF)
			{
				$lo_pdf->ezNewPage();
			}
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$lo_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$lo_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($lo_pdf);
	}
	unset($lo_antig_dao);
?>