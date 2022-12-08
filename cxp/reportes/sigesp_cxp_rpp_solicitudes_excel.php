<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Cuentas por Pagar
//  ORGANISMO: Ninguno en particular
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
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
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_solicitudes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($lo_libro,$lo_hoja,$as_codigo,$as_nombre,$ad_saldo_anterior,&$li_fila)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_codigo         // Codigo del Proveedor/Beneficiario
		//	    		   as_nombre         // Nombre del Proveedor/Beneficiario
		//	    		   ad_saldo_anterior // Saldo hasta la fecha de inicio del Intervalo
		//	    		   io_pdf            // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$lo_hoja->write($li_fila, 0, 'C�digo',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 1, " ".$as_codigo,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$li_fila++;

		$lo_hoja->write($li_fila, 0, 'Nombre',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 1, $as_nombre,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));

		$lo_hoja->write($li_fila, 5, 'Saldo Anterior:',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 6, $ad_saldo_anterior,$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'9')));

		$li_fila++;

	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_solicitudes_actuales($li_totsolact,$lo_libro,$lo_hoja,$la_data,&$li_fila)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_solicitudes_actuales
		//		   Access: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_datadate= &$lo_libro->addformat(array(num_format => 'dd/mm/yyyy'));
		$lo_datadate->set_text_wrap();
		$lo_datadate->set_font("Verdana");
		$lo_datadate->set_align('center');
		$lo_datadate->set_size('9');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		$lo_hoja->write($li_fila, 0, 'Documento',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 1, 'Facturas Relacionadas',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 2, 'Procedencia',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 3, 'F. Emisi�n',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 4, 'Debe',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 5, 'Haber',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 6, 'Saldo',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$li_fila++;

		for($li_j=1;$li_j<=$li_totsolact;$li_j++)
		{
			$lo_hoja->write($li_fila, 0, " ".$la_data[$li_j]['numsol'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 1, $la_data[$li_j]['consol'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 2, $la_data[$li_j]['procedencia'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 3, $la_data[$li_j]['fecha'],$lo_datadate);
			$lo_hoja->write($li_fila, 4, $la_data[$li_j]['debe'],$lo_dataright);
			$lo_hoja->write($li_fila, 5, $la_data[$li_j]['haber'],$lo_dataright);
			$lo_hoja->write($li_fila, 6, $la_data[$li_j]['saldo'],$lo_dataright);
			$li_fila++;
		}
	}// end uf_print_detalle_solicitudes_actuales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_ndnc_actuales($li_totndnc,$lo_libro,$lo_hoja,$la_data,&$li_fila)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_ndnc_actuales
		//		   Access: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_datadate= &$lo_libro->addformat(array(num_format => 'dd/mm/yyyy'));
		$lo_datadate->set_text_wrap();
		$lo_datadate->set_font("Verdana");
		$lo_datadate->set_align('center');
		$lo_datadate->set_size('9');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');

		$li_fila++;
		$lo_hoja->write($li_fila, 0, 'Notas Debito/Credito',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$li_fila++;

		$lo_hoja->write($li_fila, 0, 'Documento',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 1, 'Facturas Relacionadas',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 2, 'Procedencia',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 3, 'F. Emisi�n',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 4, 'Debe',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 5, 'Haber',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 6, 'Saldo',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$li_fila++;
		for($li_j=1;$li_j<=$li_totndnc;$li_j++)
		{
			$lo_hoja->write($li_fila, 0, " ".$la_data[$li_j]['numsol'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 1, $la_data[$li_j]['consol'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 2, $la_data[$li_j]['procedencia'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 3, $la_data[$li_j]['fecha'],$lo_datadate);
			$lo_hoja->write($li_fila, 4, $la_data[$li_j]['debe'],$lo_dataright);
			$lo_hoja->write($li_fila, 5, $la_data[$li_j]['haber'],$lo_dataright);
			$lo_hoja->write($li_fila, 6, $la_data[$li_j]['saldo'],$lo_dataright);
			$li_fila++;
		}
	}// end function uf_print_detalle_ndnc_actuales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_pagos_actuales($li_totpagact,$lo_libro,$lo_hoja,$la_data,&$li_fila)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_pagos_actuales
		//		   Access: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_datadate= &$lo_libro->addformat(array(num_format => 'dd/mm/yyyy'));
		$lo_datadate->set_text_wrap();
		$lo_datadate->set_font("Verdana");
		$lo_datadate->set_align('center');
		$lo_datadate->set_size('9');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');

		$li_fila++;
		$lo_hoja->write($li_fila, 0, 'Pagos',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'right','size'=>'9')));
		$li_fila++;

		$lo_hoja->write($li_fila, 0, 'Documento',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 1, 'Facturas Relacionadas',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 2, 'Procedencia',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 3, 'F. Emisi�n',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 4, 'Debe',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 5, 'Haber',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 6, 'Saldo',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$li_fila++;
		for($li_j=1;$li_j<=$li_totpagact;$li_j++)
		{
			$lo_hoja->write($li_fila, 0, " ".$la_data[$li_j]['numsol'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 1, $la_data[$li_j]['consol'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 2, $la_data[$li_j]['procedencia'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'9')));
			$lo_hoja->write($li_fila, 3, $la_data[$li_j]['fecha'],$lo_datadate);
			$lo_hoja->write($li_fila, 4, $la_data[$li_j]['debe'],$lo_dataright);
			$lo_hoja->write($li_fila, 5, $la_data[$li_j]['haber'],$lo_dataright);
			$lo_hoja->write($li_fila, 6, $la_data[$li_j]['saldo'],$lo_dataright);
			$li_fila++;
		}
	}// end function uf_print_detalle_pagos_actuales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales($lo_libro,$lo_hoja,$ai_totaldebe,$ai_totalhaber,$ai_totalsaldo,&$li_fila)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private
		//	    Arguments: ai_totaldebe  // Total de la Columna Debe
		//	   			   ai_totalhaber // Total de la Columna Haber
		//	   			   ai_totalsaldo // Total de la Columna Saldo
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los totales por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');

		$li_fila++;
		$lo_hoja->write($li_fila, 3, '____________________________________________________________________________________________________',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$li_fila++;

		$lo_hoja->write($li_fila, 3, 'TOTALES:',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$lo_hoja->write($li_fila, 4, $ai_totaldebe,$lo_dataright);
		$lo_hoja->write($li_fila, 5, $ai_totalhaber,$lo_dataright);
		$lo_hoja->write($li_fila, 6, $ai_totalsaldo,$lo_dataright);
		$li_fila++;
		$li_fila++;


	 }// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales_generales($lo_libro,$lo_hoja,$ai_totgendeb,$ai_totgenhab,$ai_totgensal,&$li_fila)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private
		//	    Arguments: ai_totaldebe  // Total de la Columna Debe
		//	   			   ai_totalhaber // Total de la Columna Haber
		//	   			   ai_totalsaldo // Total de la Columna Saldo
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los totales por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');

		$ai_totgendeb= number_format($ai_totgendeb,2,',','.');
		$ai_totgenhab= number_format($ai_totgenhab,2,',','.');
		if(doubleval($ai_totgensal)>0)
		{
			$ai_totgensal= "(".number_format($ai_totgensal,2,',','.').")";
		}
		else
		{
			$ai_totgensal= abs($ai_totgensal);
			$ai_totgensal= number_format($ai_totgensal,2,',','.');
		}

		$li_fila++;
		$lo_hoja->write($li_fila, 3, '____________________________________________________________________________________________________',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$li_fila++;

		$lo_hoja->write($li_fila, 3, 'TOTAL GENERAL:',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'10')));
		$lo_hoja->write($li_fila, 4, $ai_totgendeb,$lo_dataright);
		$lo_hoja->write($li_fila, 5, $ai_totgenhab,$lo_dataright);
		$lo_hoja->write($li_fila, 6, $ai_totgensal,$lo_dataright);
		$li_fila++;
	 }// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales_totales($lo_libro,$lo_hoja,$ai_totgendeb,$ai_totgenhab,$ai_totgensal,&$li_fila)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales_totales
		//		   Access: private
		//	    Arguments: ai_totaldebe  // Total de la Columna Debe
		//	   			   ai_totalhaber // Total de la Columna Haber
		//	   			   ai_totalsaldo // Total de la Columna Saldo
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los totales por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');

		$ai_totgendeb= number_format($ai_totgendeb,2,',','.');
		$ai_totgenhab= number_format($ai_totgenhab,2,',','.');
		if(doubleval($ai_totgensal)>0)
		{
			$ai_totgensal= "(".number_format($ai_totgensal,2,',','.').")";
		}
		else
		{
			$ai_totgensal= abs($ai_totgensal);
			$ai_totgensal= number_format($ai_totgensal,2,',','.');
		}

		$li_fila++;
		$lo_hoja->write($li_fila, 3, '____________________________________________________________________________________________________',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'9')));
		$li_fila++;

		$lo_hoja->write($li_fila, 3, 'TOTAL REPORTE:',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'left','size'=>'10')));
		$lo_hoja->write($li_fila, 4, $ai_totgendeb,$lo_dataright);
		$lo_hoja->write($li_fila, 5, $ai_totgenhab,$lo_dataright);
		$lo_hoja->write($li_fila, 6, $ai_totgensal,$lo_dataright);
		$li_fila++;
	 }// end function uf_print_totales_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------  Llamada a clases de gneracion de excel  ------------------------------------------
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo =  tempnam("/tmp", "cuentas_x_pagar.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_dsctasxpagar= new class_datastore();
	$io_dsctasxpagar = new class_datastore();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo= "Reporte de Cuentas Por Pagar";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_proben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ld_fecemides=$io_fun_cxp->uf_obtenervalor_get("fecemides","");
	$ld_fecemihas=$io_fun_cxp->uf_obtenervalor_get("fecemihas","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$ls_estretiva=$_SESSION["la_empresa"]["estretiva"];
	$lb_valido= $io_report->uf_select_solicitudes($ls_proben,$ls_codprobendes,$ls_codprobenhas,$ld_fecemides,$ld_fecemihas);
	$li_tottotdeb=0;
	$li_tottothab=0;
	$li_tottotsal=0;
	$li_tolalsaldodebe=0;
	$li_tolalsaldohaber=0;
	if (!$lb_valido) // Existe alg�n error � no hay registros.
	{
//		print("<script language=JavaScript>");
//		print(" alert('No hay nada que Reportar ');"); 
//		//print(" close();");
//		print("</ script>");
	}
	else // Imprimimos el reporte
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
		$lo_hoja->set_column(2,2,50);
		$lo_hoja->set_column(3,3,20);
		$lo_hoja->set_column(4,4,30);
		$lo_hoja->set_column(5,5,30);
		$lo_hoja->set_column(6,6,30);

		$lo_hoja->write(0, 3, $ls_titulo,$lo_encabezado);

		$li_fila=2;

		$li_totrow= $io_report->rs_data->RecordCount();
		$li_totgendeb=0;
		$li_totgenhab=0;
		$li_totgensal=0;
		while(!$io_report->rs_data->EOF)
		{			
			$li_salsol=0;
			$li_totaldebe=0;
			$li_totalhaber=0;
			$li_totalsaldo=0;
			$ls_tipproben= $io_report->rs_data->fields['tipproben'];
			$ls_cedbene= $io_report->rs_data->fields['ced_bene'];
			$ls_codpro= $io_report->rs_data->fields['cod_pro'];
			$ls_nombre= $io_report->rs_data->fields['nombre'];
			if($ls_tipproben=="B")
			{
				$ls_codigo=$ls_cedbene;
			}
			else
			{
				$ls_codigo=$ls_codpro;
			}
			if($lb_valido)
			{
				$li_monsolpre=0;
				//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
				$lb_valido= $io_report->uf_select_solicitudes_previas($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas);
				if($lb_valido)
				{
					$li_solcont=0;
					$li_solanul=0;
					while(!$io_report->rs_solprevias->EOF)
					{
						$li_numsolprevias=$io_report->rs_solprevias->fields['numsol'];
						$ls_estatus= $io_report->rs_solprevias->fields['estatus'];
						$li_monsol= $io_report->rs_solprevias->fields['monsol'];
						$ls_numsolp= $io_report->rs_solprevias->fields['numsol'];
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsolp);
							$li_monsol=$li_monsol+$li_monretiva;
						}
						switch ($ls_estatus)
						{
							case "C":
								$li_solcont=($li_solcont+$li_monsol);
							break;
							case "A":
								$li_solanul=($li_solanul+$li_monsol);
							break;
						}
						$io_report->rs_solprevias->MoveNext();
					}
					$li_monsolpre=($li_solcont-$li_solanul);
				}
				$io_report->io_sql->free_result($io_report->rs_solprevias);
				$li_monpagpre=0;
				$lb_valido= $io_report->uf_select_pagosprevios($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas,
															   $li_monpagpre,$li_monretpre);
				if($ls_estretiva=="B")
				{ 
					$li_monpagpre=$li_monpagpre+$li_monretpre;
				}
				//////////////////////////////////    NOTAS DEBITO/CREDITO  PREVIO  //////////////////////////////////
				$li_monndncdebe=0;
				$li_monndnchaber=0;
				$li_monndncant=0;
				if($ls_tipproben=="P")
				{
					$ls_codigo=$ls_codpro;
				}
				else
				{
					$ls_codigo=$ls_cedbene;
				}
				$lb_valido=$io_report->uf_select_informacionndnc($ls_tipproben,$ls_codigo,"",$ld_fecemides,"");
				if($lb_valido)
				{
					$li_j=0;
					while(!$io_report->rs_ndnc->EOF)
					{
						$ls_codope= $io_report->rs_ndnc->fields['codope'];
						$li_monto=  $io_report->rs_ndnc->fields['monto']; //Monto de la Solicitudes de Pago actuales.
						if($ls_codope=="ND")
						{
							$li_monndnchaber= $li_monndnchaber+$li_monto;
						}
						else
						{
							$li_monndncdebe= $li_monndncdebe-$li_monto;
						}
						$io_report->rs_ndnc->MoveNext();
					}
					$li_monndncant=$li_monndnchaber-$li_monndncdebe;
				}
				$io_report->io_sql->free_result($io_report->rs_ndnc);
				//////////////////////////////////    NOTAS DEBITO/CREDITO  PREVIO  //////////////////////////////////

				$li_monsalant=($li_monsolpre-$li_monpagpre-$li_monndncant);
				$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monsalant;
				if($li_monsalant>0)
				{
					$ls_saldoanterior= number_format($li_monsalant,2,',','.');
					$ls_saldoanterior="(".$ls_saldoanterior.")";
				}
				else
				{
					$ls_saldoanterior= abs($li_monsalant);
					$ls_saldoanterior= number_format($ls_saldoanterior,2,',','.');
				}
				uf_print_cabecera($lo_libro,$lo_hoja,$ls_codigo,$ls_nombre,$ls_saldoanterior,&$li_fila);
				//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
				
				//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
				$lb_valido= $io_report->uf_select_solicitudesactualescxp($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas);
				if($lb_valido)
				{
					$li_salsol=$li_monsalant;
					$li_k=0;
					while(!$io_report->rs_solicitudes->EOF)
					{
						$ls_numsol= $io_report->rs_solicitudes->fields['numsol'];
						$ls_estprodoc= $io_report->rs_solicitudes->fields['estprodoc'];
						$ls_consol= $io_report->rs_solicitudes->fields['consol'];
						$li_monsol= $io_report->rs_solicitudes->fields['monsol']; //Monto de la Solicitudes de Pago actuales.
						$ld_fecsol= $io_report->rs_solicitudes->fields['fecha'];
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
							$li_monsol=$li_monsol+$li_monretiva;
						}
						$li_salsol= $li_salsol+$li_monsol;
						$li_totalhaber=$li_totalhaber+$li_monsol;
						$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monsol;
						$ld_fecsol=$io_funciones->uf_convertirfecmostrar($ld_fecsol);
						$ls_monto= number_format($li_monsol,2,',','.');
						$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
						$li_k++;
						$la_datasol[1]= array('numsol'=>$ls_numsol,'consol'=>$ls_consol,'procedencia'=>"Solicitud",'fecha'=>$ld_fecsol,'debe'=>"0,00",'haber'=>$ls_monto,'saldo'=>$ls_salsol);
						uf_print_detalle_solicitudes_actuales(1,$lo_libro,$lo_hoja,$la_datasol,&$li_fila);
						unset($la_datasol);
						
						//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
						$lb_valido=$io_report->uf_select_informacionndnc($ls_tipproben,$ls_codigo,$ld_fecemides,$ld_fecemihas,$ls_numsol);
						if($lb_valido)
						{
							$li_j=0;
							while(!$io_report->rs_ndnc->EOF)
							{
								$ls_numdc= $io_report->rs_ndnc->fields['numdc'];
								$ls_codope= $io_report->rs_ndnc->fields['codope'];
								$ls_desope= $io_report->rs_ndnc->fields['desope'];
								$li_monto=  $io_report->rs_ndnc->fields['monto']; //Monto de la Solicitudes de Pago actuales.
								$ld_fecope= $io_report->rs_ndnc->fields['fecope'];
								if($ls_codope=="ND")
								{
									$li_salsol= $li_salsol+$li_monto;
									$li_debe=0;
									$li_haber=$li_monto;
									$ls_procedencia="Debito";
									$li_totalhaber=$li_totalhaber+$li_monto;
									$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monto;
								}
								else
								{
									$li_salsol= $li_salsol-$li_monto;
									$li_debe=$li_monto;
									$li_haber=0;
									$ls_procedencia="Credito";
									$li_totaldebe=$li_totaldebe+$li_monto;
									$li_tolalsaldodebe=$li_tolalsaldodebe+$li_monto;
								}
								$ld_fecope=$io_funciones->uf_convertirfecmostrar($ld_fecope);
								$li_debe= number_format($li_debe,2,',','.');
								$li_haber= number_format($li_haber,2,',','.');
								$li_salsol=round($li_salsol,2);
								if(doubleval($li_salsol)>0)
								{
									$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
								}
								else
								{
									$ls_salsol= abs($li_salsol);
									$ls_salsol= number_format($ls_salsol,2,',','.');
								}
								$li_j++;
								$la_datandnc[$li_j]= array('numsol'=>$ls_numdc,'consol'=>$ls_desope,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecope,'debe'=>$li_debe,'haber'=>$li_haber,'saldo'=>$ls_salsol);
								$io_report->rs_ndnc->MoveNext();
							}
							if($li_j>0)
							{
								uf_print_detalle_ndnc_actuales($li_j,$lo_libro,$lo_hoja,$la_datandnc,&$li_fila);
								unset($la_datandnc);
							}
						}
						$io_report->io_sql->free_result($io_report->rs_ndnc);
						//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////

						//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
						$lb_valido=$io_report->uf_select_informacionpagoscxp($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas,$ls_numsol);
						if($lb_valido)
						{
							$li_j=0;
							while(!$io_report->rs_pagactuales->EOF)
							{
								$ls_salsol="";
								$ls_numsol= $io_report->rs_pagactuales->fields['numsol'];
								$ls_numdoc= $io_report->rs_pagactuales->fields['numdoc'];
								$ls_codope= $io_report->rs_pagactuales->fields['codope'];
								$ls_conmov= $io_report->rs_pagactuales->fields['conmov'];
								$li_monto= $io_report->rs_pagactuales->fields['monto']; //Monto de la Solicitudes de Pago actuales.
								$ld_fecmov= $io_report->rs_pagactuales->fields['fecmov'];
								$ls_estmov= $io_report->rs_pagactuales->fields['estmov'];
								if($ls_estretiva=="B")
								{
									$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
									$li_monto=$li_monto+$li_monretiva;
								}
								if ($ls_estmov=='O' || $ls_estmov=='C')
								{
									$li_salsol= $li_salsol-$li_monto;
									$li_totaldebe=$li_totaldebe+$li_monto;
									$li_tolalsaldodebe=$li_tolalsaldodebe+$li_monto;
									$ld_debe=number_format($li_monto,2,',','.');
									$ld_haber="0,00";
									$ls_anulado ="";
								}
								else 
								{
									$li_salsol= $li_salsol+$li_monto;
									$li_totalhaber=$li_totalhaber+$li_monto;
									$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monto;
									$ld_debe="0,00";
									$ld_haber=number_format($li_monto,2,',','.');
									$ls_anulado =" Anulado";
								}
								$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
								$li_salsol=round($li_salsol,2);
								if(doubleval($li_salsol)>0)
								{
									$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
								}
								else
								{
									$ls_salsol= abs($li_salsol);
									$ls_salsol= number_format($ls_salsol,2,',','.');
								}
								$ls_procedencia="";
								switch($ls_codope)
								{
									case "CH":
										$ls_procedencia="Cheque".$ls_anulado;
									break;
									case "ND":
										$ls_procedencia="Nota de Debito";
									break;
									case "NC":
										$ls_procedencia="Nota de Credito";
									break;
								}
								$ls_monto= number_format($li_monto,2,',','.');
								$li_j++;
								$la_datapag[$li_j]= array('numsol'=>$ls_numdoc,'consol'=>$ls_conmov,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecmov,'debe'=>$ld_debe,'haber'=>$ld_haber,'saldo'=>$ls_salsol);
								$io_report->rs_pagactuales->MoveNext();
							}
							if($li_j>0)
							{
								uf_print_detalle_pagos_actuales($li_j,$lo_libro,$lo_hoja,$la_datapag,&$li_fila);
								unset($la_datapag);
							}
						}
						$io_report->io_sql->free_result($io_report->rs_pagactuales);
						//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////

						$io_report->rs_solicitudes->MoveNext();
						$li_fila++;
						$li_fila++;
					}
					$io_report->io_sql->free_result($io_report->rs_solicitudes);
					//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
				}
			}
			else
			{
				break;
			}
			$li_totalsaldo=$li_salsol;
			$li_totgendeb=$li_totgendeb+$li_totaldebe;
			$li_totgenhab=$li_totgenhab+$li_totalhaber;
			$li_totgensal=$li_totgensal+$li_totalsaldo;
			if(doubleval($li_totalsaldo)>0)
			{
				$li_totalsaldo= "(".number_format($li_totalsaldo,2,',','.').")";
			}
			else
			{
				$li_totalsaldo= abs($li_totalsaldo);
				$li_totalsaldo= number_format($li_totalsaldo,2,',','.');
			}
			$li_totalhaber= number_format($li_totalhaber,2,',','.');
			$li_totaldebe= number_format($li_totaldebe,2,',','.');
			uf_print_totales($lo_libro,$lo_hoja,$li_totaldebe,$li_totalhaber,$li_totalsaldo,&$li_fila);
			if(!$lb_valido)
			{break;}
			//////////////////////////////////       PAGOS ACTUALES II      //////////////////////////////////
			$lb_valido=$io_report->uf_select_informacionpagosactuales($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas,"");
			if($lb_valido)
			{
				$li_j=0;
				$li_salsol=$li_monsalant;
				$li_totaldebe=0;
				$li_totalhaber=0;
				while(!$io_report->rs_pagactuales->EOF)
				{
					$ls_numsol= $io_report->rs_pagactuales->fields['numsol'];
					$ls_numdoc= $io_report->rs_pagactuales->fields['numdoc'];
					$ls_codope= $io_report->rs_pagactuales->fields['codope'];
					$ls_conmov= $io_report->rs_pagactuales->fields['conmov'];
					$li_monto= $io_report->rs_pagactuales->fields['monto']; //Monto de la Solicitudes de Pago actuales.
					$ld_fecmov= $io_report->rs_pagactuales->fields['fecmov'];
					$ls_estmov= $io_report->rs_pagactuales->fields['estmov'];
					if($ls_estretiva=="B")
					{
						$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
						$li_monto=$li_monto+$li_monretiva;
					}
					if ($ls_estmov=='O' || $ls_estmov=='C')
					{
						$li_salsol= $li_salsol-$li_monto;
						$li_totaldebe=$li_totaldebe+$li_monto;
						$li_tolalsaldodebe=$li_tolalsaldodebe+$li_monto;
						$ld_debe=number_format($li_monto,2,',','.');
						$ld_haber="0,00";
						$ls_anulado ="";
					}
					else 
					{
						$li_salsol= $li_salsol+$li_monto;
						$li_totalhaber=$li_totalhaber+$li_monto;
						$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monto;
						$ld_debe="0,00";
						$ld_haber=number_format($li_monto,2,',','.');
						$ls_anulado =" Anulado";
					}
					$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
					$li_salsol=round($li_salsol,2);
					if(doubleval($li_salsol)>0)
					{
						$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
					}
					else
					{
						$ls_salsol= abs($li_salsol);
						$ls_salsol= number_format($ls_salsol,2,',','.');
					}
					$ls_procedencia="";
					if($ls_codope=="CH")
					{
						$ls_procedencia="Cheque".$ls_anulado;
					}
					$ls_monto= number_format($li_monto,2,',','.');
					$li_j++;
					$la_datapag_II[$li_j]= array('numsol'=>$ls_numdoc,'consol'=>$ls_conmov,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecmov,'debe'=>$ld_debe,'haber'=>$ld_haber,'saldo'=>$ls_salsol);
					$io_report->rs_pagactuales->MoveNext();
				}
				if($li_j>0)
				{
					uf_print_detalle_pagos_actuales($li_j,$lo_libro,$lo_hoja,$la_datapag_II,&$li_fila);
					unset($la_datapag_II);
				}
			}
			$io_report->io_sql->free_result($io_report->rs_pagactuales);
			$li_totalsaldo=$li_salsol;
			$li_totgendeb=$li_totgendeb+$li_totaldebe;
			$li_totgenhab=$li_totgenhab+$li_totalhaber;
			$li_totgensal=$li_totgensal+$li_totalsaldo;
			if(doubleval($li_totalsaldo)>0)
			{
				$li_totalsaldo= "(".number_format($li_totalsaldo,2,',','.').")";
			}
			else
			{
				$li_totalsaldo= abs($li_totalsaldo);
				$li_totalsaldo= number_format($li_totalsaldo,2,',','.');
			}
			$li_totalhaber= number_format($li_totalhaber,2,',','.');
			$li_totaldebe= number_format($li_totaldebe,2,',','.');
			if($li_j>0)
			{
				uf_print_totales($lo_libro,$lo_hoja,$li_totaldebe,$li_totalhaber,$li_totalsaldo,&$li_fila);
			}
			//////////////////////////////////       PAGOS ACTUALES  II     //////////////////////////////////
			$io_report->rs_data->MoveNext();
		}// fin for uf_select_solicitudes
		$li_totgensal=($li_tolalsaldohaber-$li_tolalsaldodebe);
		$io_report->io_sql->free_result($io_report->rs_data);
		$li_tottotdeb=$li_tottotdeb+$li_totgendeb;
		$li_tottothab=$li_tottothab+$li_totgenhab;
		$li_tottotsal=$li_tottotsal+$li_totgensal;
		uf_print_totales_generales($lo_libro,$lo_hoja,$li_totgendeb,$li_totgenhab,$li_totgensal,&$li_fila);
		
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////       PROVEEDORES SOLO CON SALDO ANTERIOR        ///////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_existe= $io_report->uf_select_saldosprevios($ls_proben,$ls_codprobendes,$ls_codprobenhas,$ld_fecemides,$ld_fecemihas);
	$li_totgendeb=0;
	$li_totgenhab=0;
	$li_totgensal=0;
	while(!$io_report->rs_provanteriores->EOF)
	{
		$ls_codpro= $io_report->rs_provanteriores->fields['cod_pro'];
		$ls_cedbene= $io_report->rs_provanteriores->fields['ced_bene'];
		$ls_tipproben= $io_report->rs_provanteriores->fields['tipproben'];
		$ls_nombre= $io_report->rs_provanteriores->fields['nombre'];
		$lb_valido= $io_report->uf_select_solicitudesanteriores($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas);
		if($lb_valido)
		{
			$li_solcont=0;
			$li_solanul=0;
			$li_monsolpre=0;
			while(!$io_report->rs_solanteriores->EOF)
			{
				$li_monsol= $io_report->rs_solanteriores->fields['monsol'];
				$ls_numsolp= $io_report->rs_solanteriores->fields['numsol'];
				if($ls_tipproben=="B")
				{
					$ls_codigo=$ls_cedbene;
				}
				else
				{
					$ls_codigo=$ls_codpro;
				}
				if($ls_estretiva=="B")
				{
					$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsolp);
					$li_monsol=$li_monsol+$li_monretiva;
				}
				$li_monsolpre=$li_monsolpre+$li_monsol;
				$io_report->rs_solanteriores->MoveNext();
			}
			$io_report->io_sql->free_result($io_report->rs_solanteriores);
			$li_monpagpre=0;
			$lb_valido= $io_report->uf_select_pagosanteriores($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas,
														   $li_monpagpre);
			
			//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
			$li_monndncdebe=0;
			$li_monndnchaber=0;
			$li_monndncant=0;
			if($ls_tipproben=="P")
			{
				$ls_codigo=$ls_codpro;
			}
			else
			{
				$ls_codigo=$ls_cedbene;
			}
			$lb_valido=$io_report->uf_select_informacionndnc($ls_tipproben,$ls_codigo,"",$ld_fecemides,"");
			if($lb_valido)
			{
				$li_j=0;
				while(!$io_report->rs_ndnc->EOF)
				{
					$ls_codope= $io_report->rs_ndnc->fields['codope'];
					$li_monto=  $io_report->rs_ndnc->fields['monto']; //Monto de la Solicitudes de Pago actuales.
					if($ls_codope=="ND")
					{
						$li_monndnchaber= $li_monndnchaber+$li_monto;
					}
					else
					{
						$li_monndncdebe= $li_monndncdebe-$li_monto;
					}
					$io_report->rs_ndnc->MoveNext();
				}
				$li_monndncant=$li_monndnchaber-$li_monndncdebe;
			}
			$io_report->io_sql->free_result($io_report->rs_ndnc);
			//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
			$li_monsalant=($li_monsolpre-$li_monpagpre-$li_monndncant);
			if(($li_monsalant>=0))
			{
				if($li_monsalant>0)
				{
					$ls_saldoanterior= number_format($li_monsalant,2,',','.');
					$ls_saldoanterior="(".$ls_saldoanterior.")";
				}
				else
				{
					$ls_saldoanterior= abs($li_monsalant);
					$ls_saldoanterior= number_format($ls_saldoanterior,2,',','.');
				}
				uf_print_cabecera($lo_libro,$lo_hoja,$ls_codigo,$ls_nombre,$ls_saldoanterior,&$li_fila);
				//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
				$lb_valido=$io_report->uf_select_informacionpagosactuales($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas,"");
				if($lb_valido)
				{
					$li_j=0;
					$li_salsol=$li_monsalant;
					$li_totaldebe=0;
					$li_totalhaber=0;
					while(!$io_report->rs_pagactuales->EOF)
					{
						$ls_numsol= $io_report->rs_pagactuales->fields['numsol'];
						$ls_numdoc= $io_report->rs_pagactuales->fields['numdoc'];
						$ls_codope= $io_report->rs_pagactuales->fields['codope'];
						$ls_conmov= $io_report->rs_pagactuales->fields['conmov'];
						$li_monto= $io_report->rs_pagactuales->fields['monto']; //Monto de la Solicitudes de Pago actuales.
						$ld_fecmov= $io_report->rs_pagactuales->fields['fecmov'];
						$ls_estmov= $io_report->rs_pagactuales->fields['estmov'];
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
							$li_monto=$li_monto+$li_monretiva;
						}
						if ($ls_estmov=='O' || $ls_estmov=='C')
						{
							$li_salsol= $li_salsol-$li_monto;
							$li_totaldebe=$li_totaldebe+$li_monto;
							$ld_debe=number_format($li_monto,2,',','.');
							$ld_haber="0,00";
							$ls_anulado ="";
						}
						else 
						{
							$li_salsol= $li_salsol+$li_monto;
							$li_totalhaber=$li_totalhaber+$li_monto;
							$ld_debe="0,00";
							$ld_haber=number_format($li_monto,2,',','.');
							$ls_anulado =" Anulado";
						}
						$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
						$li_salsol=round($li_salsol,2);
						if(doubleval($li_salsol)>0)
						{
							$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
						}
						else
						{
							$ls_salsol= abs($li_salsol);
							$ls_salsol= number_format($ls_salsol,2,',','.');
						}
						$ls_procedencia="";
						if($ls_codope=="CH")
						{
							$ls_procedencia="Cheque".$ls_anulado;
						}
						$ls_monto= number_format($li_monto,2,',','.');
						$la_datapag[$li_j]= array('numsol'=>$ls_numdoc,'consol'=>$ls_conmov,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecmov,'debe'=>$ld_debe,'haber'=>$ld_haber,'saldo'=>$ls_salsol);
						$li_j++;
						$io_report->rs_pagactuales->MoveNext();
					}
					if($li_j>0)
					{
						uf_print_detalle_pagos_actuales($li_j,$lo_libro,$lo_hoja,$la_datapag,&$li_fila);
						unset($la_datapag);
					}
				}
				$io_report->io_sql->free_result($io_report->rs_pagactuales);
				$li_totalsaldo=$li_salsol;
				$li_totgendeb=$li_totgendeb+$li_totaldebe;
				$li_totgenhab=$li_totgenhab+$li_totalhaber;
				$li_totgensal=$li_totgensal+$li_totalsaldo;
				if(doubleval($li_totalsaldo)>0)
				{
					$li_totalsaldo= "(".number_format($li_totalsaldo,2,',','.').")";
				}
				else
				{
					$li_totalsaldo= abs($li_totalsaldo);
					$li_totalsaldo= number_format($li_totalsaldo,2,',','.');
				}
				$li_totalhaber= number_format($li_totalhaber,2,',','.');
				$li_totaldebe= number_format($li_totaldebe,2,',','.');
				uf_print_totales($lo_libro,$lo_hoja,$li_totaldebe,$li_totalhaber,$li_totalsaldo,&$li_fila);
				//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
			
			}
		}
		$io_report->rs_provanteriores->MoveNext();
	}
	$li_tottotdeb=$li_tottotdeb+$li_totgendeb;
	$li_tottothab=$li_tottothab+$li_totgenhab;
	$li_tottotsal=$li_tottotsal+$li_totgensal;
	uf_print_totales_generales($lo_libro,$lo_hoja,$li_totgendeb,$li_totgenhab,$li_totgensal,&$li_fila);
	
	uf_print_totales_totales($lo_libro,$lo_hoja,$li_tottotdeb,$li_tottothab,$li_tottotsal,&$li_fila);
	if($lb_valido)
	{
		unset($io_report);
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"cuentas_x_pagar.xls\"");
		header("Content-Disposition: inline; filename=\"cuentas_x_pagar.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
	}
	else
	{
		print("<script language=JavaScript>");
		print(" alert('Ocurrio un error al generarse el Reporte');"); 
		print(" close();");
		print("</script>");
	}
	
?>