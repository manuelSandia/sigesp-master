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
		$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_rendimientofinanciero.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_is_negative($ad_monto) {
		if ($ad_monto<0) {
			return number_format(abs($ad_monto),2,",",".");
		}
		else{
			return number_format($ad_monto,2,",",".");
		}
	}
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/tmp", "rendimiento_financiero.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../class_funciones_scg.php");
	require_once("sigesp_scg_class_rendimientofinanciero.php");
	$io_funciones = new class_funciones();
	$io_report    = new sigesp_scg_class_rendimientofinanciero();
	$io_fecha     = new class_fecha();
	$io_fun_scg   = new class_funciones_scg();
	
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_hidbot=$_GET["hidbot"];
	if($ls_hidbot==1){
		$ls_cmbmesdes=$_GET["cmbmesdes"];
		$ls_cmbagnodes=$_GET["cmbagnodes"];
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
		
		$ls_cmbmeshas=$_GET["cmbmeshas"];
		$ls_cmbagnohas=$_GET["cmbagnohas"];
		$ls_last_day=$io_fecha->uf_last_day($ls_cmbmeshas,$ls_cmbagnohas);
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day)." 00:00:00";
	}
	elseif($ls_hidbot==2){
		$ldt_fecdes=$io_funciones->uf_convertirdatetobd($_GET["txtfecdes"])." 00:00:00";
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($_GET["txtfechas"])." 00:00:00";
	}
	elseif ($ls_hidbot==3){
		$ls_cmbmesdes=$_GET["cmbmesdes"];
		$ls_cmbagnodes=$_GET["cmbagnodes"];
		$ldt_fecdes=$ls_cmbagnodes."-".$ls_cmbmesdes."-01"." 00:00:00";
		
		$ls_cmbmeshas=$_GET["cmbmeshas"];
		$ls_cmbagnohas=$_GET["cmbagnohas"];
		$ls_last_day=$io_fecha->uf_last_day($ls_cmbmeshas,$ls_cmbagnohas);
		$ldt_fechas =$io_funciones->uf_convertirdatetobd($ls_last_day)." 00:00:00";
	}
	
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$li_ano     = substr($_SESSION["la_empresa"]["periodo"],0,4);
	$ld_fecdes  = $io_funciones->uf_convertirfecmostrar($ldt_fecdes);
	$ld_fechas  = $io_funciones->uf_convertirfecmostrar($ldt_fechas);
	$ls_titulo  = $_SESSION["la_empresa"]["nombre"];
	$ls_titulo1 = "ESTADO DE REDIMIENTO FINANCIERO";
	$ls_titulo2 = "DEL ".$ld_fecdes." AL ".$ld_fechas;
	$ls_titulo3 = "(EN BOL�VARES)";  
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar datastore con los datos del reporte
	$lb_valido=uf_insert_seguridad("<b>Rendimiento Financiero en PDF</b>"); // Seguridad de Reporte
	if($lb_valido){
		$data = $io_report->uf_rendimiento_financiero($ldt_fecdes, $ldt_fechas); 
	}
	
	if($data===false){// Existe alg�n error 
		print("<script language=JavaScript>");
		print(" alert('Ocurrio un error al emitir el reporte');"); 
		print(" close();");
		print("</script>");
	}	
	elseif(!$data->EOF){
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
		$lo_subtitulo= &$lo_libro->addformat();
		$lo_subtitulo->set_bold();
		$lo_subtitulo->set_font("Verdana");
		$lo_subtitulo->set_align('left');
		$lo_subtitulo->set_size('9');		
		$lo_datacenter= &$lo_libro->addformat();
		$lo_datacenter->set_font("Verdana");
		$lo_datacenter->set_align('center');
		$lo_datacenter->set_size('9');
		$lo_dataleft= &$lo_libro->addformat();
		$lo_dataleft->set_text_wrap();
		$lo_dataleft->set_font("Verdana");
		$lo_dataleft->set_align('left');
		$lo_dataleft->set_size('9');
		$lo_dataright= &$lo_libro->addformat();//array(num_format => '#,##0.00')
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');

		$lo_hoja->set_column(0,0,50);
		$lo_hoja->set_column(1,1,20);
		$lo_hoja->set_column(2,5,20);
		
		$lo_hoja->write(0, 2, $ls_titulo,$lo_encabezado);
		$lo_hoja->write(1, 2, $ls_titulo1,$lo_encabezado);
		$lo_hoja->write(2, 2, $ls_titulo2,$lo_encabezado);
		$lo_hoja->write(3, 2, $ls_titulo3,$lo_encabezado);
		$lo_hoja->write(6, 0, "",$lo_titulo);
		$lo_hoja->write(6, 1, "Nota",$lo_titulo);
		$lo_hoja->write(6, 3, $li_ano-1,$lo_titulo);
		$lo_hoja->write(6, 4, $li_ano,$lo_titulo);
		
		
		//totales y otras variables
		$ld_totalniv1     = 0;
		$ld_totalantniv1  = 0;
		$ls_dentotniv1    = '';
		$ld_totalniv2     = 0;
		$ld_totalantniv2  = 0;
		$ls_dentotniv2    = '';
		$cambioniv2       = false;
		$cambioultimo     = false;
		$nrecord          = $data->_numOfRows;
		$arrdata          = $data->GetArray();
		$li_indice        = 0;
		$li_cuenta        = 0;
		$ld_totingreso    = 0;
		$ld_totantingreso = 0;
		
		$li_row = 7;
		foreach ($arrdata as $registro) {
			$ls_cuenta       = $registro['sc_cuenta'];
			$ls_denominacion = $registro['denominacion'];
			$ls_nivel        = $registro['nivel'];
			$ld_saldoant     = $registro['saldo_anterior'];
			$ld_saldo        = $registro['saldo'];
			
			switch ($ls_nivel) {
				case '1':
					//nivel uno;
					if($cambioniv2){
							$cambioniv2    = false;
							$ls_dentotniv2 = 'TOTAL '.$ls_dentotniv2;
							$lo_hoja->write($li_row, 0, $ls_dentotniv2, $lo_dataleft);
							$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
							$lo_hoja->write($li_row, 3, uf_is_negative($ld_totalantniv2), $lo_dataright);
							$lo_hoja->write($li_row, 4, uf_is_negative($ld_totalniv2), $lo_dataright);
							
							
							$li_row++;
							$ls_dentotniv1 = 'TOTAL '.$ls_dentotniv1; 
							$lo_hoja->write($li_row, 0, $ls_dentotniv1, $lo_subtitulo);
							$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
							$lo_hoja->write($li_row, 3, uf_is_negative($ld_totalantniv1), $lo_dataright);
							$lo_hoja->write($li_row, 4, uf_is_negative($ld_totalniv1), $lo_dataright);
							
							//linea en blanco
							$li_row++;
							$lo_hoja->write($li_row);
							
							$li_row++;
							$lo_hoja->write($li_row, 0, $ls_denominacion, $lo_subtitulo);
							$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
							$ld_totalniv1    = $ld_saldo;
							$ld_totalantniv1 = $ld_saldoant;
							$ls_dentotniv1   = $ls_denominacion;
					}
					else{
						$ls_dentotniv1   = $ls_denominacion;
						$ld_totalniv1    = $ld_saldo;
						$ld_totalantniv1 = $ld_saldoant;
						$lo_hoja->write($li_row, 0, $ls_denominacion, $lo_subtitulo);
						$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
					}
					
					if(substr($ls_cuenta, 0, 1)==$_SESSION['la_empresa']['ingreso']){
						$ld_totingreso    = $ld_saldo;
						$ld_totantingreso = $ld_saldoant;
					}
					break;
				
				case '2':
					//nivel dos;
					if($cambioniv2){
							$cambioniv2    = false;
							$ls_dentotniv2 = 'TOTAL '.$ls_dentotniv2;
							$lo_hoja->write($li_row, 0, $ls_dentotniv2, $lo_dataleft);
							$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
							$lo_hoja->write($li_row, 3, uf_is_negative($ld_totalantniv2), $lo_dataright);
							$lo_hoja->write($li_row, 4, uf_is_negative($ld_totalniv2), $lo_dataright);
							$li_row++;
							//linea en blanco
							$lo_hoja->write($li_row);

							if($arrdata[$li_indice+1]['nivel']=='1'){
								$lo_hoja->write($li_row, 0, $ls_denominacion, $lo_dataleft);
								$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
								$li_row++;
								//linea en blanco
								$lo_hoja->write($li_row);
								$li_row++;
								$ls_dentotniv1 = '<b>TOTAL '.$ls_dentotniv1.'</b>'; 
								$lo_hoja->write($li_row, 0, $ls_dentotniv1, $lo_subtitulo);
								$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
								$lo_hoja->write($li_row, 3, uf_is_negative($ld_totalantniv1), $lo_dataright);
								$lo_hoja->write($li_row, 4, uf_is_negative($ld_totalniv1), $lo_dataright);
								$li_row++;
								//linea en blanco
								$lo_hoja->write($li_row);
							}
							else{
								$li_row++;
								$lo_hoja->write($li_row, 0, $ls_denominacion, $lo_dataleft);
								$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
								$ld_totalniv2     = $ld_saldo;
								$ld_totalantniv2  = $ld_saldoant;
								$ls_dentotniv2    = $ls_denominacion;
							}
					}
					else{
						$ld_totalniv2    = $ld_saldo;
						$ld_totalantniv2 = $ld_saldoant;
						$ls_dentotniv2   = $ls_denominacion;
						$lo_hoja->write($li_row, 0, $ls_denominacion, $lo_dataleft);
						$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
					}
					break;
					
				case '4':
					//nivel cuatro;
					if($arrdata[$li_indice+1]['nivel']=='2' || $arrdata[$li_indice+1]['nivel']=='1'){
						$lo_hoja->write($li_row, 0, $ls_denominacion, $lo_dataleft);
						$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
						$lo_hoja->write($li_row, 3, uf_is_negative($ld_saldoant), $lo_dataright);
						$lo_hoja->write($li_row, 4, uf_is_negative($ld_saldo), $lo_dataright);
						$cambioniv2    = true;
					}
					else{
						$lo_hoja->write($li_row, 0, $ls_denominacion, $lo_dataleft);
						$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
						$lo_hoja->write($li_row, 3, uf_is_negative($ld_saldoant), $lo_dataright);
						$lo_hoja->write($li_row, 4, uf_is_negative($ld_saldo), $lo_dataright);
					}
					
					if($li_cuenta+1==$nrecord){
						$li_row++;
						$ls_dentotniv2 = 'TOTAL '.$ls_dentotniv2;
						$lo_hoja->write($li_row, 0, $ls_dentotniv2, $lo_dataleft);
						$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
						$lo_hoja->write($li_row, 3, uf_is_negative($ld_totalantniv2), $lo_dataright);
						$lo_hoja->write($li_row, 4, uf_is_negative($ld_totalniv2), $lo_dataright);
						$li_row++;
						
						//linea en blanco
						$lo_hoja->write($li_row);
						$li_row++;
						$ls_dentotniv1 = 'TOTAL '.$ls_dentotniv1; 
						$lo_hoja->write($li_row, 0, $ls_dentotniv1, $lo_subtitulo);
						$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
						$lo_hoja->write($li_row, 3, uf_is_negative($ld_totalantniv1), $lo_dataright);
						$lo_hoja->write($li_row, 4, uf_is_negative($ld_totalniv1), $lo_dataright);
						$li_row++;
						
						//linea en blanco
						$lo_hoja->write($li_row);
						$li_row++;
						$ls_dentotniv1 = 'RESULTADO DEL EJERCICIO (AHORRO/DESAHORRO)'; 
						$lo_hoja->write($li_row, 0, $ls_dentotniv1, $lo_subtitulo);
						$lo_hoja->write($li_row, 1, '    ', $lo_dataleft);
						$lo_hoja->write($li_row, 3, uf_is_negative($ld_totantingreso-$ld_totalantniv1), $lo_dataright);
						$lo_hoja->write($li_row, 4, uf_is_negative(abs($ld_totingreso)-abs($ld_totalniv1)), $lo_dataright);
					}
					break;
			}
			
			//echo 'cuenta nivel ->'.$ls_nivel.' nivel next cuenta'.$arrdata[$li_indice+1]['nivel'].'<br>';
			if($li_indice<$nrecord-2){
				$li_indice++;
			}
			
			//echo 'in '.$li_cuenta.'  nr'.$nrecord.'<br>';
			if($li_cuenta<$nrecord-1){
				$li_cuenta++;
			}
			
			$li_row++;
		}
		
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"rendimiento_financiero.xls\"");
		header("Content-Disposition: inline; filename=\"rendimiento_financiero.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}
	else {
		print("<script language=JavaScript>");
		print(" alert('No hay data para emitir el reporte');"); 
		print(" close();");
		print("</script>");
	}
	 
	unset($io_report);
    unset($io_funciones);		
?> 