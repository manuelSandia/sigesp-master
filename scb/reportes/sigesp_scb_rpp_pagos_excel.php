<?php
    session_start();   
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/tmp", "listado_pagos.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
		require_once("sigesp_scb_class_report.php");
		require_once("../../shared/class_folder/class_fecha.php");
		require_once("../../shared/class_folder/class_funciones.php");
		require_once("../../shared/class_folder/sigesp_include.php");
        require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_datastore.php");    
		
		$io_conect  = new sigesp_include();
		$con        = $io_conect->uf_conectar();
		$io_report  = new sigesp_scb_class_report($con);
		$io_funcion = new class_funciones();			
		$io_fecha   = new class_fecha();
	    $io_sql     = new class_sql($con);
	//---------------------------------------------------------------------------------------------------------------------------
	//Par�metros para Filtar el Reporte
	$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
	$ld_fecdesde    = $_GET["fecdes"];
	$ld_fechasta    = $_GET["fechas"];
	$ls_tiprep	    = $_GET["tiprep"];
	$ls_orden	    = $_GET["orden"];
	$ls_codope	    = $_GET["operacion"];
    $ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	if($ls_tiprep=="E")
	{
		$ls_probendesde = $_GET["probendes"];
		$ls_probenhasta = $_GET["probenhas"];
		$ls_tipproben   = $_GET["tipproben"];
		$ls_codban      = $_GET["codban"];
		$ls_ctaban      = $_GET["ctaban"];
		$ls_nomban		= $_GET["nomban"];
		$ls_dencta      = $_GET["dencta"];
	}
	else
	{
		$ls_probendesde="";
		$ls_probenhasta="";
		$ls_tipproben="";
		$ls_codban="";
		$ls_ctaban="";
		$ls_nomban="";
		$ls_dencta="";
	}
	
	/*//Opci�n para los selectivos
	$lr_numdocchk= split('>>',$ls_numdocchk);
    $lr_datos= array_unique($lr_numdocchk);
    $li_total= count($lr_datos);
	sort($lr_datos,SORT_STRING);
	//Opci�n para los selectivos*/

	
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scb_class_reportbsf.php");
		$io_report = new sigesp_scb_class_reportbsf($con);
		$ls_tipbol = 'Bs.F.';
	}
	
	//---------------------------------------------------------------------------------------------------------------------------
	//Par�metros del encabezado
		$ldt_fecha="Desde  ".$ld_fecdesde."  al ".$ld_fechasta."";
		$ls_titulo="LISTADO DE PAGOS";       
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$rs_data    = $io_report->uf_find_pagos($ls_tipproben,$ls_probendesde,$ls_probenhasta,$ld_fecdesde,$ld_fechasta,$ls_codban,$ls_ctaban,$ls_tiprep,$ls_orden,$ls_codope);
	$lb_valido  = true;
	$li_total   = $io_sql->num_rows($rs_data);
	if($li_total>0)
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
			$lo_hoja->set_column(0,0,30);
			$lo_hoja->set_column(1,1,20);
			$lo_hoja->set_column(2,5,30);
			$lo_hoja->set_column(4,6,15);
			$lo_hoja->set_column(4,7,15);
			$lo_hoja->set_column(4,8,15);
			$lo_hoja->set_column(4,4,20);
			
			$lo_hoja->write(0, 3, $ls_titulo,$lo_encabezado);
			$lo_hoja->write(1, 3, $ldt_fecha,$lo_encabezado);
			$lo_hoja->write(4, 0, "Proveedor / Benef",$lo_titulo);
			$lo_hoja->write(4, 1, "Solicitud",$lo_titulo);
			$lo_hoja->write(4, 2, "Banco",$lo_titulo);
			$lo_hoja->write(4, 3, "Cuenta",$lo_titulo);
			$lo_hoja->write(4, 4, "Documento",$lo_titulo);
			$lo_hoja->write(4, 5, "Operaci�n",$lo_titulo);
			$lo_hoja->write(4, 6, "Fecha",$lo_titulo);
			$lo_hoja->write(4, 7, "Monto",$lo_titulo);
			$lo_hoja->write(4, 8, "Retenido",$lo_titulo);
			
			$li_row=4;
			
			
			$ldec_totaldebitos  = 0;
			$ldec_totalcreditos = 0;
			$ldec_saldo         = 0;
			$ld_totanu          = 0;
			$i=0;
			$ldec_total = 0;
			while ($row=$io_sql->fetch_row($rs_data))
			{
				$i++;
				$ls_numdoc     = $row["numdoc"];
				$ls_ctaban	   = $row["ctaban"];
				$ldec_monto	   = $row["monto"];
				$ldec_monsol   = $row["monsol"];
				$ld_fecmov	   = $io_report->fun->uf_formatovalidofecha($row["fecmov"]);
				$ld_fecmov	   = $io_report->fun->uf_convertirfecmostrar($ld_fecmov);
				$ls_nomproben  = $row["nomproben"];
				$ls_numsol     = $row["numsol"];
				$ls_conmov	   = $row["conmov"];
				$ls_tipoope	   = $row["codope"];
				$ls_estbpd	   = $row["estbpd"];
				$ls_estmov     = $row["estmov"];
				$ld_monret     = $row["monret"];
				$ls_nomban	   = $row["nomban"];
				$ls_ctaban	   = $row["ctaban"];
				$ls_bene	   = $row["nombene"];
				$ls_apeben     = $row["apebene"];
				$ls_pro		   = $row["nompro"];
				$ls_codpro     = $row["codpro"];
				$ls_estbpd	   = $row["estbpd"];
				if ($ls_codpro=='----------'){
					$ls_nombrellen=$ls_bene.",".$ls_apeben;
				}
				else{
					$ls_nombrellen=$ls_pro;
				}
				
				if ($ls_nombrellen==""){
					$ls_nombrellen=$ls_nomproben;
				}
				
				if ($ls_estbpd=='D'){
					$ldec_monsol=$ldec_monto;
				}
				else{
					$ldec_monto=$ldec_monsol;
				}
				
				if ($ls_estmov=="A"){
					$ldec_monto=$ldec_monto * (-1);
					$ldec_monsol=$ldec_monsol*(-1);
				}
				
				if ($ls_tipoope=="CH"){
					 $ls_tipoope="CHEQUE";
				}
				elseif(($ls_tipoope=="ND") && ($ls_estbpd=="T")){
					$ls_tipoope="CARTA ORDEN";
				}
				else{
					$ls_tipoope="NOTA DE DEBITO";
				}
				$ldec_total=$ldec_total+$ldec_monto;
				if (strlen($ls_conmov)>48)
				{
					$ls_conmov=substr($ls_conmov,0,46)."..";
				}
				   $li_row=$li_row+1;
				   $lo_hoja->write($li_row, 0, $ls_nombrellen, $lo_dataleft);
				   $lo_hoja->write($li_row, 1, " ".$ls_numsol, $lo_datacenter);
				   $lo_hoja->write($li_row, 2, $ls_nomban, $lo_datacenter);
				   $lo_hoja->write($li_row, 3, $ls_ctaban, $lo_datacenter);
				   $lo_hoja->write($li_row, 4, " ".$ls_numdoc, $lo_datacenter);
				   $lo_hoja->write($li_row, 5, $ls_tipoope, $lo_datacenter);
				   $lo_hoja->write($li_row, 6, $ld_fecmov, $lo_datacenter);
				   $lo_hoja->write($li_row, 7, $ldec_monto, $lo_dataright);
				   $lo_hoja->write($li_row, 8, $ld_monret, $lo_dataright);
			
			}
		
				   $li_row++;
				   $lo_hoja->write($li_row, 6, "Total:",$lo_titulo);
				   $lo_hoja->write($li_row, 7, $ldec_total,$lo_dataright);


				   $lo_libro->close();
				   header("Content-Type: application/x-msexcel; name=\"listado_pagos.xls\"");
				   header("Content-Disposition: inline; filename=\"listado_pagos.xls\"");
				   $fh=fopen($lo_archivo, "rb");
				   fpassthru($fh);
				   unlink($lo_archivo);
				   print("<script language=JavaScript>");
				   //print(" close();");
				   print("</script>");
	 }
	 else
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar !!!');"); 
		//print(" close();");
		print("</script>");
	 }

?> 