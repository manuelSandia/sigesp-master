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
	//	    Arguments: as_titulo // Tï¿½tulo del Reporte
	//    Description: funciï¿½n que guarda la seguridad de quien generï¿½ el reporte
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creaciï¿½n: 22/09/2006 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $io_fun_scg;

	$ls_descripcion="Genero el Reporte ".$as_titulo;
	$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_situacionfinanciera.php",$ls_descripcion);
	return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_encabezado_pagina($as_titulo,$as_titulo1,$as_titulo2,$as_titulo3,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		    Acess: private
	//	    Arguments: as_titulo // Tï¿½tulo del Reporte
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: funciï¿½n que imprime los encabezados por pï¿½gina
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaciï¿½n: 28/04/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	$io_pdf->line(20,40,578,40);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
	$li_tm=$io_pdf->getTextWidth(9,$as_titulo);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,715,9,$as_titulo); // Agregar el tï¿½tulo		

	$li_tm=$io_pdf->getTextWidth(9,$as_titulo1);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,700,9,$as_titulo1); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(9,$as_titulo2);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,685,9,$as_titulo2); // Agregar el tï¿½tulo

	$li_tm=$io_pdf->getTextWidth(9,$as_titulo3);
	$tm=306-($li_tm/2);
	$io_pdf->addText($tm,670,9,$as_titulo3); // Agregar el tï¿½tulo

	$io_pdf->addText(510,740,7,$_SESSION["ls_database"]); // Agregar la Base de datos
	$io_pdf->addText(510,730,8,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(510,720,8,date("h:i a")); // Agregar la hora
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle($la_data, $periodo_an, $periodo_ac, &$io_pdf){
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private
	//	    Arguments: la_data // arreglo de informaciï¿½n
	//	   			   io_pdf // Objeto PDF
	//    Description: funciï¿½n que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaciï¿½n: 28/04/2006 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaï¿½o de Letras
						 'titleFontSize' => 8,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'colGap'=>1, // separacion entre tablas
						 'rowGap' => 1,
						 'width'=>520, // Ancho de la tabla
						 'maxWidth'=>520, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'cols'=>array('denominacion'=>array('justification'=>'left','width'=>305), // Justificaciï¿½n y ancho de la columna
									   'nota'=>array('justification'=>'left','width'=>80), // Justificaciï¿½n y ancho de la columna	
									   'periodo_an'=>array('justification'=>'right','width'=>90),
									   'periodo_ac'=>array('justification'=>'right','width'=>90))); // Justificaciï¿½n y ancho de la columna
	$la_columnas=array('denominacion'=>'',
						   'nota'=>'<b>NOTA</b>',
						   'periodo_an'=>"<b>{$periodo_an}</b>",
						   'periodo_ac'=>"<b>{$periodo_ac}</b>");
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------

function uf_is_negative($ad_monto) {
	if ($ad_monto<0) {
		return number_format(abs($ad_monto),2,",",".");
	}
	else{
		return number_format($ad_monto,2,",",".");
	}
}

function uf_print_firmas(&$io_pdf) {
	
	
	$valor=$io_pdf->y;
	if($io_pdf->y<160){
		$io_pdf->ezNewPage();
	}
	
	
	$io_pdf->setStrokeColor(0,0,0);
	$io_pdf->setLineStyle(1);


	$io_pdf->line(45,200,160,200);
	$io_pdf->line(210,200,350,200);

	$io_pdf->addText(45,205,7,"Firma:"); // Agregar el tï¿½tulo
	$io_pdf->addText(45,190,7,"Nombre: William Acosta"); // Agregar el tï¿½tulo
	$io_pdf->addText(45,180,7,"Cargo: Gerente de Administración y Finanzas"); // Agregar el tï¿½tulo
	$io_pdf->addText(210,205,7,"Firma:"); // Agregar el tï¿½tulo
	$io_pdf->addText(210,190,7,"Nombre: José Sosa"); // Agregar el tï¿½tulo
	$io_pdf->addText(210,180,7,"Cargo: Presidente (E) "); // Agregar el tï¿½tulo

	$io_pdf->Rectangle(400,170,150,100);
	$io_pdf->addText(430,220,7,"SELLO INSTITUCIONAL"); // Agregar el tï¿½tulo
}

require_once("../../shared/ezpdf/class.ezpdf.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../class_funciones_scg.php");
require_once("sigesp_scg_class_situacionfinanciera.php");
$io_funciones = new class_funciones();
$io_report    = new sigesp_scg_class_situacionfinanciera();
$io_fecha     = new class_fecha();
$io_fun_scg   = new class_funciones_scg();

//--------------------------------------------------  Parï¿½metros para Filtar el Reporte  -----------------------------------------
$ls_cmbmes=$_GET["cmbmes"];
$ls_cmbagno=$_GET["cmbagno"];
$ls_last_day=$io_fecha->uf_last_day($ls_cmbmes,$ls_cmbagno);
$ldt_fechas=$io_funciones->uf_convertirdatetobd($ls_last_day)." 00:00:00";
//----------------------------------------------------  Parï¿½metros del encabezado  -----------------------------------------------
$ldt_periodo = $_SESSION["la_empresa"]["periodo"];
$li_ano      = substr($ldt_periodo,0,4);
$ls_titulo="<b> ".$_SESSION["la_empresa"]["nombre"]." </b>";
$ls_titulo1="<b>ESTADO DE SITUACIÓN FINANCIERA</b>";
$ls_titulo2="<b> AL ".substr($ls_last_day, 0, 2)." DE ".$io_fecha->uf_load_nombre_mes($ls_cmbmes)." DE ".$li_ano."</b>";
$ls_titulo3="<b>(EN BOLÍVARES)</b>";  
//--------------------------------------------------------------------------------------------------------------------------------
// Cargar datastore con los datos del reporte
$lb_valido=uf_insert_seguridad("<b>Situacion Financiera en PDF</b>"); // Seguridad de Reporte
if($lb_valido){
	$data=$io_report->uf_situacion_financiera($ldt_fechas);
//die();
}

if($data===false){// Existe algï¿½n error 
	print("<script language=JavaScript>");
	print(" alert('Ocurrio un error al emitir el reporte');");
	print(" close();");
	print("</script>");
}
elseif(!$data->EOF){
	error_reporting(E_ALL);
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(4.8,3,3,3); // Configuraciï¿½n de los margenes en centï¿½metros
	uf_print_encabezado_pagina($ls_titulo,$ls_titulo1,$ls_titulo2,$ls_titulo3,$io_pdf); // Imprimimos el encabezado de la pï¿½gina
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el nï¿½mero de pï¿½gina


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
	$ld_totpasivo     = 0;
	$ld_totantpasivo  = 0;

	//buscar la ganancia o resultado
	$ld_ganancia = $io_report->uf_buscar_ganancia($ldt_fechas);

	//buscar ganancia aano anterior

	$anoact=substr($ldt_fechas,0,4);
	if($anoact>1){
		$anoant=$anoact-1;
		$ad_actualo=$anoant."-12-31 00:00:00";
	}else{
			
		$ad_actualo="2012-12-31 00:00:00";
	}
	$ld_ganancia_ant = $io_report->uf_buscar_ganancia($ad_actualo);


	foreach ($arrdata as $registro) {
		$ls_cuenta       = $registro['sc_cuenta'];
		$ls_denominacion = $registro['denominacion'];
		$ls_nivel        = $registro['nivel'];
		$ld_saldoant     = $registro['saldo_anterior'];
		$ld_saldo        = $registro['saldo'];
		$ld_saldo_dep        = $registro['saldo_dep'];
			
		switch ($ls_nivel) {
			case '1':
				//nivel uno;
				if($cambioniv2){
					$cambioniv2    = false;
					$ls_dentotniv2 = 'TOTAL '.$ls_dentotniv2;
					$la_data[] = array('denominacion'=>'','nota'=>'    ','periodo_an'=>'_________________','periodo_ac'=>'_________________');
					$la_data[] = array('denominacion'=>$ls_dentotniv2,'nota'=>'    ','periodo_an'=>uf_is_negative($ld_totalantniv2),'periodo_ac'=>uf_is_negative($ld_totalniv2));
					$la_data[] = array('denominacion'=>'','nota'=>'    ','periodo_an'=>'_________________','periodo_ac'=>'_________________');
						
					$ls_dentotniv1 = '<b>TOTAL '.$ls_dentotniv1.'</b>';
					$la_data[] = array('denominacion'=>$ls_dentotniv1,'nota'=>'    ','periodo_an'=>'<b>'.uf_is_negative($ld_totalantniv1).'</b>','periodo_ac'=>'<b>'.uf_is_negative($ld_totalniv1).'</b>');
					$la_data[] = array('denominacion'=>' ','nota'=>'    ','periodo_an'=>'===============','periodo_ac'=>'===============');
					$la_data[] = array('denominacion'=>' ','nota'=>'    ','periodo_an'=>'','periodo_ac'=>'');

					$la_data[] = array('denominacion'=>'<b>'.$ls_denominacion.'</b>','nota'=>'    ','periodo_an'=>'','periodo_ac'=>'');
					$ld_totalniv1    = $ld_saldo;
					$ld_totalantniv1 = $ld_saldoant;
					$ls_dentotniv1   = $ls_denominacion;
				}
				else{
					$ls_dentotniv1   = $ls_denominacion;
					$ld_totalniv1    = $ld_saldo;
					$ld_totalantniv1 = $ld_saldoant;
					$la_data[] = array('denominacion'=>'<b>'.$ls_denominacion.'</b>','nota'=>'    ','periodo_an'=>'','periodo_ac'=>'');
				}
					
				if(substr($ls_cuenta, 0, 1)==$_SESSION['la_empresa']['pasivo']){
					$ld_totpasivo    = $ld_saldo;
					$ld_totantpasivo = $ld_saldoant;
				}
					
				break;

			case '2':
				//nivel dos;
				if($cambioniv2 && !$cambioultimo){
					$cambioniv2    = false;
					$ls_dentotniv2 = 'TOTAL '.$ls_dentotniv2;
					$la_data[] = array('denominacion'=>'','nota'=>'    ','periodo_an'=>'_________________','periodo_ac'=>'_________________');
					$la_data[] = array('denominacion'=>$ls_dentotniv2,'nota'=>'    ','periodo_an'=>uf_is_negative($ld_totalantniv2),'periodo_ac'=>uf_is_negative($ld_totalniv2));
					$la_data[] = array('denominacion'=>' ','nota'=>'    ','periodo_an'=>'','periodo_ac'=>'');

					
					//echo "<br>".$ls_dentotniv2."   ".$ld_totalantniv2."   ".$ld_totalniv2;
					
					
					
					$la_data[] = array('denominacion'=>$ls_denominacion,'nota'=>'    ','periodo_an'=>'','periodo_ac'=>'');
					$ld_totalniv2     = $ld_saldo;
					$ld_totalantniv2  = $ld_saldoant;
					$ls_dentotniv2    = $ls_denominacion;
				//	echo "<br>".$ls_denominacion."     f ".$ld_saldoant."    ".$ld_saldo;
					
					
				}
				else{
					//echo 'actual ->'.$ls_denominacion.' nivel siguiente->'.$arrdata[$li_indice+1]['nivel'].'<br>';
					if($cambioultimo){
						//echo 'aca tambien ';
						$cambioniv2    = false;
						$cambioultimo  = false;
						$ls_dentotniv2 = 'TOTAL '.$ls_dentotniv2;
						$la_data[] = array('denominacion'=>$ls_dentotniv2,'nota'=>'    ','periodo_an'=>uf_is_negative($ld_totalantniv2),'periodo_ac'=>uf_is_negative($ld_totalniv2));
							
							
						$ls_dentotniv1 = '<b>TOTAL '.$ls_dentotniv1.'</b>';
						$ld_totpasivo    = abs($ld_totpasivo) + abs($ld_totalniv1) + $ld_ganancia;
						$ld_totantpasivo = abs($ld_totantpasivo) + abs($ld_totalantniv1) + $ld_ganancia_ant;
						$la_data[] = array('denominacion'=>'','nota'=>'    ','periodo_an'=>'_________________','periodo_ac'=>'_________________');
						$la_data[] = array('denominacion'=>$ls_dentotniv1,'nota'=>'    ','periodo_an'=>'<b>'.uf_is_negative($ld_totalantniv1).'</b>','periodo_ac'=>'<b>'.uf_is_negative($ld_totalniv1).'</b>');
						$la_data[] = array('denominacion'=>'','nota'=>'    ','periodo_an'=>'_________________','periodo_ac'=>'_________________');
						$la_data[] = array('denominacion'=>'<b>TOTAL PASIVO Y PATRIMONIO</b>','nota'=>'    ','periodo_an'=>'<b>'.uf_is_negative($ld_totantpasivo).'</b>','periodo_ac'=>'<b>'.uf_is_negative($ld_totpasivo).'</b>');
						$la_data[] = array('denominacion'=>' ','nota'=>'    ','periodo_an'=>'===============','periodo_ac'=>'===============');
						$la_data[] = array('denominacion'=>' ','nota'=>'    ','periodo_an'=>'','periodo_ac'=>'');
							
						$la_data[] = array('denominacion'=>'<b>'.$ls_denominacion.'</b>','nota'=>'    ','periodo_an'=>'<b>'.uf_is_negative($ld_saldoant).'</b>','periodo_ac'=>'<b>'.uf_is_negative($ld_saldo).'</b>');
					}
					else {
						if(substr($ls_cuenta,0,1)=='4'){
							$ld_totalniv2    = $ld_saldo;
							$ld_totalantniv2 = $ld_saldoant;
							$ls_dentotniv2   = $ls_denominacion;
							$la_data[] = array('denominacion'=>'<b>'.$ls_denominacion.'</b>','nota'=>'    ','periodo_an'=>'<b>'.uf_is_negative($ld_saldoant).'</b>','periodo_ac'=>'<b>'.uf_is_negative($ld_saldo).'</b>');
						}
						else{
							$ld_totalniv2    = $ld_saldo;
							$ld_totalantniv2 = $ld_saldoant;
							$ls_dentotniv2   = $ls_denominacion;
							$la_data[] = array('denominacion'=>$ls_denominacion,'nota'=>'    ','periodo_an'=>'','periodo_ac'=>'');
						}
					}

				}
				break;
					
			case '4':
				//nivel cuatro;
				if($arrdata[$li_indice+1]['nivel']=='2'||$arrdata[$li_indice+1]['nivel']=='1'){
					$la_data[] = array('denominacion'=>$ls_denominacion,'nota'=>'    ','periodo_an'=>uf_is_negative($ld_saldoant),'periodo_ac'=>uf_is_negative($ld_saldo));
					$cambioniv2    = true;
				}
				else{
					$la_data[] = array('denominacion'=>$ls_denominacion,'nota'=>'    ','periodo_an'=>uf_is_negative($ld_saldoant),'periodo_ac'=>uf_is_negative($ld_saldo));
				}
					
				if(substr($arrdata[$li_indice+1]['sc_cuenta'],0,1)=='4' && substr($ls_cuenta,0,1)!='4'){
					//echo 'si entra';
					$cambioultimo   = true;
				}
				break;
		}

			
		//echo 'cuenta nivel ->'.$ls_nivel.' nivel next cuenta'.$arrdata[$li_indice+1]['nivel'].'<br>';
		if($li_indice+2<$nrecord){
			$li_indice++;
		}
	}
	//echo 'aca tambien ';
	$cambioniv2    = false;
	$cambioultimo  = false;
		
	$ls_dentotniv22 = 'RESULTADO DEL EJERCICIO ';
	$la_data[] = array('denominacion'=>$ls_dentotniv22,'nota'=>'    ','periodo_an'=>uf_is_negative($ld_ganancia_ant),'periodo_ac'=>uf_is_negative($ld_ganancia));
		
		
	$ls_dentotniv2 = 'TOTAL '.$ls_dentotniv2;
	$la_data[] = array('denominacion'=>$ls_dentotniv2,'nota'=>'    ','periodo_an'=>uf_is_negative(abs($ld_totalantniv2)+$ld_ganancia_ant),'periodo_ac'=>uf_is_negative(abs($ld_totalniv2) + $ld_ganancia));
		
		
	$ls_dentotniv1 = '<b>TOTAL '.$ls_dentotniv1.'</b>';
	$ld_totpasivo    = abs($ld_totpasivo) + abs($ld_totalniv1) + $ld_ganancia;
	$ld_totantpasivo = abs($ld_totantpasivo)+ abs($ld_totalantniv1) + $ld_ganancia_ant;
	$la_data[] = array('denominacion'=>'','nota'=>'    ','periodo_an'=>'_________________','periodo_ac'=>'_________________');
	$la_data[] = array('denominacion'=>$ls_dentotniv1,'nota'=>'    ','periodo_an'=>'<b>'.uf_is_negative(abs($ld_totalantniv1)+$ld_ganancia_ant).'</b>','periodo_ac'=>'<b>'.uf_is_negative(abs($ld_totalniv1)+$ld_ganancia).'</b>');
	$la_data[] = array('denominacion'=>'','nota'=>'    ','periodo_an'=>'_________________','periodo_ac'=>'_________________');
	$la_data[] = array('denominacion'=>'<b>TOTAL PASIVO Y PATRIMONIO</b>','nota'=>'    ','periodo_an'=>'<b>'.uf_is_negative($ld_totantpasivo).'</b>','periodo_ac'=>'<b>'.uf_is_negative($ld_totpasivo).'</b>');
	$la_data[] = array('denominacion'=>' ','nota'=>'    ','periodo_an'=>'===============','periodo_ac'=>'===============');
	$la_data[] = array('denominacion'=>' ','nota'=>'    ','periodo_an'=>'','periodo_ac'=>'');
		

	//	$la_data[] = array('denominacion'=>'<b>'.$ls_denominacion.'</b>','nota'=>'    ','periodo_an'=>'<b>'.uf_is_negative($ld_saldoant).'</b>','periodo_ac'=>'<b>'.uf_is_negative($ld_saldo).'</b>');
		
	uf_print_detalle($la_data, $li_ano-1, $li_ano, $io_pdf);

	uf_print_firmas($io_pdf);
	unset($data);
	unset($arrdata);
	unset($la_data);
	$io_pdf->ezStopPageNumbers(1,1);
	if (isset($d) && $d){
		$ls_pdfcode = $io_pdf->ezOutput(1);
		$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		echo '<html><body>';
		echo trim($ls_pdfcode);
		echo '</body></html>';
	}
	else{
		$io_pdf->ezStream();
	}
	unset($io_pdf);
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
