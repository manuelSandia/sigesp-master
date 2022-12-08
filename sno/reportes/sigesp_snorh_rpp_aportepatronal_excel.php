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
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del reporte
		//	    		   as_desnom // descripci�n de la n�mina
		//	    		   as_periodo // per�odo actual de la n�mina
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Genero el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_aportepatronal.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/tmp", "pagoaportenomina.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	//-----------------------------------------------------------------------------------------------------------------------------------
	

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
//	require_once("../sigesp_sno.php");
//$io_sno3=new sigesp_sno();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo="Retenciones y Aportes Patronales";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codconc=$io_fun_nomina->uf_obtenervalor_get("codconc","");
	$ls_nomcon=$io_fun_nomina->uf_obtenervalor_get("nomcon","");
	$ls_anodes=$io_fun_nomina->uf_obtenervalor_get("anodes","");
	$ls_mesdes=$io_fun_nomina->uf_obtenervalor_get("mesdes","");
	$ls_anohas=$io_fun_nomina->uf_obtenervalor_get("anohas","");
	$ls_meshas=$io_fun_nomina->uf_obtenervalor_get("meshas","");
	$ls_perdes=$io_fun_nomina->uf_obtenervalor_get("perdes","");
	$ls_perhas=$io_fun_nomina->uf_obtenervalor_get("perhas","");
	//$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tipo=$io_fun_nomina->uf_obtenervalor_get("rdbtipo","");
	
	global $ls_tiporeporte;
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_snorh_class_reportbsf.php");
		$io_report=new sigesp_snorh_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
	$ls_rango= "Nominas: ".$ls_codnomdes;
	if($ls_anodes==$ls_anohas)
	{
		$ls_des_ano=$ls_anodes;
	}
	else
	{
		$ls_des_ano=$ls_anodes." al ".$ls_anohas;
	}
	if($ls_mesdes==$ls_meshas)
	{
		$ls_des_mes=$io_fecha->uf_load_nombre_mes($ls_mesdes);
	}
	else
	{
		$ls_des_mes=$io_fecha->uf_load_nombre_mes($ls_mesdes)." a ".$io_fecha->uf_load_nombre_mes($ls_meshas);
	}
	if($ls_perdes==$ls_perhas)
	{
		$ls_des_periodo=$ls_perdes;
	}
	else
	{
		$ls_des_periodo=$ls_perdes." al ".$ls_perhas;
	}
	
	$ls_periodo= "Año: ".$ls_des_ano." Mes: ".$ls_des_mes." - Periodo ".$ls_des_periodo;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_rango,$ls_periodo); // Seguridad de Reporte
	if($lb_valido)
	{
		//$lb_valido=$io_report->uf_aportepatronal_personal($ls_codnomdes,$ls_codnomdes,$ls_anodes,$ls_mesdes,$ls_anohas,$ls_meshas,
			//											  $ls_perdes,$ls_perhas,$ls_codconc,$ls_conceptocero,"","",1); // Cargar el DS con los datos del reporte
			
		
		
		
		$ld_fecpro='01/'.$ls_mesdes.'/'.$ls_anocur;
		$la_fpa=split(",",$ls_concepto_fpa);
		$la_fpj=split(",",$ls_concepto_fpj);
		$la_lph=split(",",$ls_concepto_lph);
		$li_total_fpa=count($la_fpa);
		$li_total_fpj=count($la_fpj);
		$li_total_lph=count($la_lph);
			
		$ls_codconcapo="";
		$ls_codconcded="";
		$ls_codconcpcp="";
		$ls_codconcpmp="";
		$ls_codconcbic="";
		$ls_codconcuti="";

		

		$nomina[]="";
		$conceptos[]="";



		$als_codnom=split("-",$ls_codnomdes);
		$totoalnomina=count($als_codnom);
		$ls_codnom="";
		$ls_sql="";
		$ls_sql2="";

		
	for($li_i=0;$li_i<$totoalnomina;$li_i++)
		{


			$nomina=$als_codnom[$li_i];


		if($ls_tipo=='1'){
				$ls_concepto_lph2=trim($io_report->uf_select_config("SNO","CONFIG","FAOV_".$als_codnom[$li_i],"XXXXXXXX","C"));

				$ls_codconc=$ls_concepto_lph2;
				$als_codconc=split("-",$ls_codconc);
				$totoalconcepto=count($als_codconc);
				$ls_codconc="";
				for($li_m=0;$li_m<$totoalconcepto;$li_m++)
				{

					if($ls_codconc==''){
						$ls_codconc="'".$als_codconc[$li_m]."'";
					}else{
						$ls_codconc=$ls_codconc.",'".$als_codconc[$li_m]."'";
					}
				}

				$conceptofaov[$li_i]=$ls_codconc;
				//$ls_metodo_lph=rtrim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","METODO LPH","SIN METODO","C"));

				$ls_sql1111=$io_report->uf_aportepatronal_personal2($ls_codconc,$nomina,$nomina,$ls_anodes,$ls_mesdes,$ls_meshas,$ls_perdes,$ls_perhas);
			
	
				if($ls_sql2==""){
					$ls_sql2=$ls_sql1111."  ";
				}else{
					$ls_sql2=$ls_sql2." union  ".$ls_sql1111;
				}


					
			}else{
				if($ls_tipo=='2'){
					$ls_concepto_fpj2=trim($io_report->uf_select_config("SNO","CONFIG","FJU_".$als_codnom[$li_i],"XXXXXXXX","C"));
					//	echo "<br>".$ls_concepto_lph2;

					$ls_codconc=$ls_concepto_fpj2;
					$als_codconc=split("-",$ls_codconc);
					$totoalconcepto=count($als_codconc);
					$ls_codconc="";
					for($li_m=0;$li_m<$totoalconcepto;$li_m++)
					{

						if($ls_codconc==''){
							$ls_codconc="'".$als_codconc[$li_m]."'";
						}else{
							$ls_codconc=$ls_codconc.",'".$als_codconc[$li_m]."'";
						}
					}
					$conceptofju[$li_i]=$ls_codconc;

					$ls_sql1111=$io_report->uf_aportepatronal_personal2($ls_codconc,$nomina,$nomina,$ls_anodes,$ls_mesdes,$ls_meshas,$ls_perdes,$ls_perhas);


					if($ls_sql2==""){
						$ls_sql2=$ls_sql1111."  ";
					}else{
						$ls_sql2=$ls_sql2." union  ".$ls_sql1111;
					}
					//echo "<br>".$ls_codconc;

				}else{
					if($ls_tipo=='3'){
							
						
						/****codigos en la nomina por concepto y tipo de nomina*////
						$ls_codconcapo=trim($io_report->uf_select_config("SNO","CONFIG","CAH_APO_".$als_codnom[$li_i],"XXXXXXXX","C"));//APORTE PATRONAL
						$ls_codconcded=trim($io_report->uf_select_config("SNO","CONFIG","CAH_DED_".$als_codnom[$li_i],"XXXXXXXX","C"));//DEDUCCION EMPLEADO
						$ls_codconcpcp=trim($io_report->uf_select_config("SNO","CONFIG","CAH_PCP_".$als_codnom[$li_i],"XXXXXXXX","C"));//PRESTAMO CORTO PLAZO
						$ls_codconcpmp=trim($io_report->uf_select_config("SNO","CONFIG","CAH_PMP_".$als_codnom[$li_i],"XXXXXXXX","C"));//PRESTAMO MEDIANO PLAZO
						$ls_codconcbic=trim($io_report->uf_select_config("SNO","CONFIG","CAH_BIC_".$als_codnom[$li_i],"XXXXXXXX","C"));//PRESTAMO BICENTENARIO
						$ls_codconcuti=trim($io_report->uf_select_config("SNO","CONFIG","CAH_UTI_".$als_codnom[$li_i],"XXXXXXXX","C"));//UTILES
						$ls_codconcma=trim($io_report->uf_select_config("SNO","CONFIG","CAH_MA_".$als_codnom[$li_i],"XXXXXXXX","C"));//MUTUO AUXILIO
						$ls_codconcsfu=trim($io_report->uf_select_config("SNO","CONFIG","CAH_SFU_".$als_codnom[$li_i],"XXXXXXXX","C"));//SERVICIO FUNERARIO
						$ls_codconcpco=trim($io_report->uf_select_config("SNO","CONFIG","CAH_PCO_".$als_codnom[$li_i],"XXXXXXXX","C"));//PRESTAMO COMERCIAL
						$ls_codconclma=trim($io_report->uf_select_config("SNO","CONFIG","CAH_LMA_".$als_codnom[$li_i],"XXXXXXXX","C"));//LINEA MARRON
						$ls_codconcopt=trim($io_report->uf_select_config("SNO","CONFIG","CAH_OPT_".$als_codnom[$li_i],"XXXXXXXX","C"));//OPTICA
						$ls_codconcsir=trim($io_report->uf_select_config("SNO","CONFIG","CAH_SIR_".$als_codnom[$li_i],"XXXXXXXX","C"));//SIRAGON
						
						
						
						/***codigos ante caja de ahorro******///
						
						
						$ls_codapo=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_APO","XXXXXXXX","C"));//APORTE PATRONAL
						$ls_codded=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_DED","XXXXXXXX","C"));//DEDUCCION EMPLEADO
						$ls_codpcp=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_PCP","XXXXXXXX","C"));//PRESTAMO CORTO PLAZO
						$ls_codpmp=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_PMP","XXXXXXXX","C"));//PRESTAMO MEDIANO PLAZO
						$ls_codbic=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_BIC","XXXXXXXX","C"));//PRESTAMO BICENTENARIO
						$ls_coduti=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_UTI","XXXXXXXX","C"));//UTILES
						$ls_codma=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_MA","XXXXXXXX","C"));//MUTUO AUXILIO
						$ls_codsfu=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_SFU","XXXXXXXX","C"));//SERVICIO FUNERARIO
						$ls_codpco=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_PCO","XXXXXXXX","C"));//PRESTAMO COMERCIAL
						$ls_codlma=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_LMA","XXXXXXXX","C"));//LINEA MARRON
						$ls_codopt=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_OPT","XXXXXXXX","C"));//OPTICA
						$ls_codsir=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_SIR","XXXXXXXX","C"));//SIRAGON
						
						
						
					
					echo "";	
						
						$todosconcepto=	"";	

						$als_codconcapo=split("-",$ls_codconcapo);
						$totoalconceptoapo=count($als_codconcapo);
						$ls_codconcapo="''";
						for($li_m=0;$li_m<$totoalconceptoapo;$li_m++)
						{

							if($ls_codconcapo==''){
								$ls_codconcapo="'".$als_codconcapo[$li_m]."'";
							}else{
								$ls_codconcapo=$ls_codconcapo.",'".$als_codconcapo[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcapo[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcapo[$li_m]."'";
							}
						}


						$conceptoapo[$li_i]=$ls_codconcapo;

						$als_codconcded=split("-",$ls_codconcded);
						$totoalconceptoded=count($als_codconcded);
						$ls_codconcded="''";
						for($li_m=0;$li_m<$totoalconceptoded;$li_m++)
						{

							if($ls_codconcded==''){
								$ls_codconcded="'".$als_codconcded[$li_m]."'";
							}else{
								$ls_codconcded=$ls_codconcded.",'".$als_codconcded[$li_m]."'";

							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcded[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcded[$li_m]."'";
							}
						}

						$conceptoded[$li_i]=$ls_codconcded;



						$als_codconcpcp=split("-",$ls_codconcpcp);
						$totoalconceptopcp=count($als_codconcpcp);
						$ls_codconcpcp="''";
						for($li_m=0;$li_m<$totoalconceptopcp;$li_m++)
						{

							if($ls_codconcpcp==''){
								$ls_codconcpcp="'".$als_codconcpcp[$li_m]."'";
							}else{
								$ls_codconcpcp=$ls_codconcpcp.",'".$als_codconcpcp[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcpcp[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcpcp[$li_m]."'";
							}
						}


						$conceptopcp[$li_i]=$ls_codconcpcp;

						$als_codconcpmp=split("-",$ls_codconcpmp);
						$totoalconceptopmp=count($als_codconcpmp);
						$ls_codconcpmp="''";
						for($li_m=0;$li_m<$totoalconceptopmp;$li_m++)
						{

							if($ls_codconcpmp==''){
								$ls_codconcpmp="'".$als_codconcpmp[$li_m]."'";
							}else{
								$ls_codconcpmp=$ls_codconcpmp.",'".$als_codconcpmp[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcpmp[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcpmp[$li_m]."'";
							}
						}



						$conceptopmp[$li_i]=$ls_codconcpmp;

						$als_codconcbic=split("-",$ls_codconcbic);
						$totoalconceptobic=count($als_codconcbic);
						$ls_codconcbic="''";
						for($li_m=0;$li_m<$totoalconceptobic;$li_m++)
						{

							if($ls_codconcbic==''){
								$ls_codconcbic="'".$als_codconcbic[$li_m]."'";
							}else{
								$ls_codconcbic=$ls_codconcbic.",'".$als_codconcbic[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcbic[$li_i]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcbic[$li_m]."'";
							}
						}


						$conceptobic[$li_i]=$ls_codconcbic;

						$als_codconcuti=split("-",$ls_codconcuti);
						$totoalconceptouti=count($als_codconcuti);
						$ls_codconcuti="''";
						for($li_m=0;$li_m<$totoalconceptouti;$li_m++)
						{

							if($ls_codconcuti==''){
								$ls_codconcuti="'".$als_codconcuti[$li_m]."'";
							}else{
								$ls_codconcuti=$ls_codconcuti.",'".$als_codconcuti[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcuti[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcuti[$li_m]."'";
							}
						}
						
						$conceptouti[$li_i]=$ls_codconcuti;
						
						
						
						$als_codconcma=split("-",$ls_codconcma);
						$totoalconceptoma=count($als_codconcma);


						$ls_codconcma="''";
						for($li_m=0;$li_m<$totoalconceptoma;$li_m++)
						{



							if($ls_codconcma==''){
								$ls_codconcma="'".$als_codconcma[$li_m]."'";
							}else{
								$ls_codconcma=$ls_codconcma.",'".$als_codconcma[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcma[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcma[$li_m]."'";
							}


						}
						
						$conceptoma[$li_i]=$ls_codconcma;
						
						
						
						$als_codconcsfu=split("-",$ls_codconcsfu);
						$totoalconceptosfu=count($als_codconcsfu);
						
						$ls_codconcsfu="''";
						for($li_m=0;$li_m<$totoalconceptosfu;$li_m++)
						{



							if($ls_codconcsfu==''){
								$ls_codconcsfu="'".$als_codconcsfu[$li_m]."'";
							}else{
								$ls_codconcsfu=$ls_codconcsfu.",'".$als_codconcsfu[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcsfu[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcsfu[$li_m]."'";
							}


						}
						
						$conceptosfu[$li_i]=$ls_codconcsfu;
						
						
						
						$als_codconcpco=split("-",$ls_codconcpco);
						$totoalconceptopco=count($als_codconcpco);
						$ls_codconcpco="''";
						for($li_m=0;$li_m<$totoalconceptopco;$li_m++)
						{



							if($ls_codconcpco==''){
								$ls_codconcpco="'".$als_codconcpco[$li_m]."'";
							}else{
								$ls_codconcpco=$ls_codconcpco.",'".$als_codconcpco[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcpco[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcpco[$li_m]."'";
							}


						}
						
						$conceptopco[$li_i]=$ls_codconcpco;
						
						
						
						$als_codconclma=split("-",$ls_codconclma);
						$totoalconceptolma=count($als_codconclma);
						$ls_codconclma="''";
						for($li_m=0;$li_m<$totoalconceptolma;$li_m++)
						{



							if($ls_codconclma==''){
								$ls_codconclma="'".$als_codconclma[$li_m]."'";
							}else{
								$ls_codconclma=$ls_codconclma.",'".$als_codconclma[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconclma[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconclma[$li_m]."'";
							}


						}
						
						$conceptolma[$li_i]=$ls_codconclma;
						
						
						
						
						$als_codconcopt=split("-",$ls_codconcopt);
						$totoalconceptoopt=count($als_codconcopt);
						
						$ls_codconcopt="''";
						for($li_m=0;$li_m<$totoalconceptoopt;$li_m++)
						{



							if($ls_codconcopt==''){
								$ls_codconcopt="'".$als_codconcopt[$li_m]."'";
							}else{
								$ls_codconcopt=$ls_codconcopt.",'".$als_codconcopt[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcopt[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcopt[$li_m]."'";
							}


						}
						
						$conceptoopt[$li_i]=$ls_codconcopt;
						
						
						
						$als_codconcsir=split("-",$ls_codconcsir);
						$totoalconceptosir=count($als_codconcsir);
						
						$ls_codconcsir="''";
						for($li_m=0;$li_m<$totoalconceptosir;$li_m++)
						{



							if($ls_codconcsir==''){
								$ls_codconcsir="'".$als_codconcsir[$li_m]."'";
							}else{
								$ls_codconcsir=$ls_codconcsir.",'".$als_codconcsir[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcsir[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcsir[$li_m]."'";
							}


						}
						
						$conceptosir[$li_i]=$ls_codconcsir;
						
						
						


$ls_sql1111=$io_report->uf_aportepatronal_personal3($ls_codconcapo,$ls_codconcded,$ls_codconcpcp,$ls_codconcpmp,$ls_codconcbic,$ls_codconcuti,$ls_codconcma,$als_codnom[$li_i],$als_codnom[$li_i],$ls_anodes,$ls_mesdes,$ls_meshas,$ls_perdes,$ls_perhas,$todosconcepto,$ls_codconcsfu,$ls_codconcpco,$ls_codconclma,$ls_codconcopt,$ls_codconcsir);


					if($ls_sql2==""){
						$ls_sql2=$ls_sql1111."  ";
					}else{
						$ls_sql2=$ls_sql2." union  ".$ls_sql1111;
					}

					}
				}
			}

			/*$als_codconc=split("-",$ls_codconc);
			 $totoalconcepto=count($als_codconc);
			 $ls_codconc="";
			 for($li_i=0;$li_i<$totoalconcepto;$li_i++)
			 {

				if($ls_codconc==''){
				$ls_codconc="'".$als_codconc[$li_i]."'";
				}else{
				$ls_codconc=$ls_codconc.",'".$als_codconc[$li_i]."'";
				}
				}*/

			$conceptoconc[$li_i]=$ls_codconcconc;


		}
$ls_sql2=$ls_sql2."  order by 1";

//echo "<br>".$ls_sql2;
//die();
$valido=$io_report->uf_generar_reporte($ls_sql2);
		
		
	//	echo "<br>55555".$ls_sql2;
	
		
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
		$lo_titulo->set_text_wrap();
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
		$lo_dataright= &$lo_libro->addformat(array("num_format"=> "###0.00"));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		//$lo_hoja->set_column(0,0,60);
		//$lo_hoja->set_column(1,1,20);
		//$lo_hoja->set_column(2,2,20);
		//$lo_hoja->write(0,2,$ls_titulo,$lo_encabezado);
		//$lo_hoja->write(1,2,$ls_periodo,$lo_encabezado);
		$li_fila=0;
		
		$li_totrow=$io_report->DS->getRowCount("cedper");
		$li_totper=0;
		$li_totpat=0;
		$li_totalgeneral=0;
		$li_totper=0;
			$li_totpat=0;
			$li_totpcp=0;
			$li_totpmp=0;
			$li_totbic=0;
			$li_totuti=0;
			
			
			
			
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_cedper=$io_report->DS->data["cedper2"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$li_personal=abs($io_report->DS->data["personal"][$li_i]);
			$li_patron=abs($io_report->DS->data["patron"][$li_i]);
			
			
			
			if($ls_tipo=='3'){
				
			$li_sfu=abs($io_report->DS->data["sfu"][$li_i]);	
			$li_pco=abs($io_report->DS->data["pco"][$li_i]);
			$li_lma=abs($io_report->DS->data["lma"][$li_i]);
			$li_opt=abs($io_report->DS->data["opt"][$li_i]);
			$li_sir=abs($io_report->DS->data["sir"][$li_i]);
				
				
			$li_pcp=abs($io_report->DS->data["pcp"][$li_i]);
			$li_pmp=abs($io_report->DS->data["pmp"][$li_i]);
			$li_bic=abs($io_report->DS->data["bicentenario"][$li_i]);
			$li_uti=abs($io_report->DS->data["utiles"][$li_i]);
			$li_ma=abs($io_report->DS->data["ma"][$li_i]);
			$li_total=abs($io_report->DS->data["acumulado"][$li_i]);
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$li_totper=$li_totper+abs($io_report->DS->data["personal"][$li_i]);
			$li_totpat=$li_totpat+abs($io_report->DS->data["patron"][$li_i]);
			$li_totpcp=$li_totpcp+abs($io_report->DS->data["pcp"][$li_i]);
			$li_totpmp=$li_totpmp+abs($io_report->DS->data["pmp"][$li_i]);
			$li_totbic=$li_totbic+abs($io_report->DS->data["bicentenario"][$li_i]);
			$li_totuti=$li_totuti+abs($io_report->DS->data["utiles"][$li_i]);
			$li_totma=$li_totma+abs($io_report->DS->data["ma"][$li_i]);
			$li_totalgeneral=$li_totalgeneral+abs($io_report->DS->data["acumulado"][$li_i]);;
			$la_data[$li_i]=array('nro'=>$li_i,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'personal'=>$li_personal,'patron'=>$li_patron,'pcp'=>$li_pcp,'pmp'=>$li_pmp,'bic'=>$li_bic,'uti'=>$li_uti,'ma'=>$li_ma,'total'=>$li_total);
				/*$lo_hoja->write($li_fila,0,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,1,$ls_nomper,$lo_dataleft);
			$lo_hoja->write($li_fila,2,$li_personal,$lo_dataright);
			$lo_hoja->write($li_fila,3,$li_patron,$lo_dataright);			
			$lo_hoja->write($li_fila,4,$li_total,$lo_dataright);*/
			
			$fechasis= date("d/m/Y"); 
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codded,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_personal,$lo_dataright);
			$li_fila++;	
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codapo,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_patron,$lo_dataright);
			$li_fila++;	
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codpcp,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_pcp,$lo_dataright);
			$li_fila++;
			
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codpmp,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_pmp,$lo_dataright);
			$li_fila++;
			
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codbic,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_bic,$lo_dataright);
			$li_fila++;
			
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_coduti,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_uti,$lo_dataright);
			$li_fila++;
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codsfu,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_sfu,$lo_dataright);
			$li_fila++;
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codpco,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_pco,$lo_dataright);
			$li_fila++;
			
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codlma,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_lma,$lo_dataright);
			$li_fila++;
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codopt,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_opt,$lo_dataright);
			$li_fila++;
			
			
			$lo_hoja->write($li_fila,0,' ',$lo_dataright);
			$lo_hoja->write($li_fila,1,' '.$ls_codsir,$lo_dataright);
			$lo_hoja->write($li_fila,2,$fechasis,$lo_dataright);
			$lo_hoja->write($li_fila,3,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,11,$li_sir,$lo_dataright);
			$li_fila++;
			
			
			
}else{
			$li_total=abs($io_report->DS->data["personal"][$li_i]+$io_report->DS->data["patron"][$li_i]);
			//$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$li_totper=$li_totper+abs($io_report->DS->data["personal"][$li_i]);
			$li_totpat=$li_totpat+abs($io_report->DS->data["patron"][$li_i]);
			$li_totalgeneral=$li_totalgeneral+abs($io_report->DS->data["acumulado"][$li_i]);;
			$la_data[$li_i]=array('nro'=>$li_i,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'personal'=>$li_personal,'patron'=>$li_patron,'total'=>$li_total);
			$lo_hoja->write($li_fila,0,' '.$ls_cedper.'',$lo_dataright);
			$lo_hoja->write($li_fila,1,$ls_nomper,$lo_dataleft);
			$lo_hoja->write($li_fila,2,$li_personal,$lo_dataright);
			$lo_hoja->write($li_fila,3,$li_patron,$lo_dataright);			
			$lo_hoja->write($li_fila,4,$li_total,$lo_dataright);
			
						
}
		}
			
		


		

		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"pagoaportenomina.xls\"");
		header("Content-Disposition: inline; filename=\"pagoaportenomina.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 
