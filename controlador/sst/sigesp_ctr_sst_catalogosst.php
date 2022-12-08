<?php
session_start(); 
$datosempresa=$_SESSION["la_empresa"];
$codusu=$_SESSION["la_logusr"];
require_once ('../../base/librerias/php/general/Json.php');
require_once ('sigesp_ctr_sst_servicio.php');

if ($_POST['ObjSon']) {
	$submit = str_replace ( "\\", "", $_POST['ObjSon'] );
	$json = new Services_JSON ( );
	$ArJson = $json->decode ( $submit );
	$evento = $ArJson->operacion;
	
	switch ($evento) {
		case 'catalogounidad' :
			$oservicio = new ServicioSst ( 'spg_unidadadministrativa' );
			$datos = $oservicio->buscarTodos ('coduniadm',1);
			$objSon = generarJson ( $datos );
			echo $objSon;
			break;
			
		case 'catalogousuarios' :
			$oservicio = new ServicioSst ( 'sss_usuarios' );
			$datos = $oservicio->buscarTodos ('codusu',1);
			$objSon = generarJson ( $datos );
			echo $objSon;
			break;
			
		case 'catalogodocumentos' :
			switch($ArJson->tipodoc){
				case 'SEP':
					$oservicio = new ServicioSst ( 'sep_solicitud' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro = " AND sep_solicitud.tipo_destino='P' AND sep_solicitud.cod_pro='".$ArJson->codprop."'";
					}
					elseif($ArJson->tipprop=='B'){
						$filtro = " AND sep_solicitud.tipo_destino='B' AND sep_solicitud.ced_bene='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND sep_solicitud.fecregsol>='".$ArJson->fecdes."' AND sep_solicitud.fecregsol<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT sep_solicitud.numsol AS numdoc,sep_solicitud.fecregsol AS fecha 
					              FROM sep_solicitud
								  WHERE sep_solicitud.codemp='".$datosempresa["codemp"]."' AND sep_solicitud.numsol LIKE '%".$ArJson->numdoc."%'".$filtro.
								 " AND (sep_solicitud.numsol NOT IN(SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.tipdocenv='SEP') 
										OR sep_solicitud.numsol IN (SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.codusurec='".$codusu."'  AND sst_asignacion_tramite.tipdocenv='SEP')) 
								 	ORDER BY numsol";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
					
				case 'SOCB':
					$oservicio = new ServicioSst ( 'soc_ordencompra' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro=" AND soc_ordencompra.cod_pro='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND soc_ordencompra.fecordcom>='".$ArJson->fecdes."' AND soc_ordencompra.fecordcom<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT soc_ordencompra.numordcom AS numdoc,soc_ordencompra.fecordcom AS fecha 
					              FROM soc_ordencompra
								  WHERE soc_ordencompra.codemp='".$datosempresa["codemp"]."' AND soc_ordencompra.numordcom LIKE '%".$ArJson->numdoc."%' AND soc_ordencompra.estcondat='B'".$filtro.
								 "  AND (soc_ordencompra.numordcom NOT IN(SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.tipdocenv='SOCB') 
										OR soc_ordencompra.numordcom IN (SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.codusurec='".$codusu."'  AND sst_asignacion_tramite.tipdocenv='SOCB'))
								   ORDER BY numordcom";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
				
				case 'SOCS':
					$oservicio = new ServicioSst ( 'soc_ordencompra' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro=" AND soc_ordencompra.cod_pro='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND soc_ordencompra.fecordcom>='".$ArJson->fecdes."' AND soc_ordencompra.fecordcom<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT soc_ordencompra.numordcom AS numdoc,soc_ordencompra.fecordcom AS fecha 
					              FROM soc_ordencompra
								  WHERE soc_ordencompra.codemp='".$datosempresa["codemp"]."' AND soc_ordencompra.numordcom LIKE '%".$ArJson->numdoc."%' AND soc_ordencompra.estcondat='S'".$filtro.
								 " AND (soc_ordencompra.numordcom NOT IN(SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.tipdocenv='SOCS') 
										OR soc_ordencompra.numordcom IN (SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.codusurec='".$codusu."'  AND sst_asignacion_tramite.tipdocenv='SOCS'))
								   ORDER BY numordcom";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
					
				case 'SCBCH':
					$oservicio = new ServicioSst ( 'scb_movbco' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro=" AND scb_movbco.tipo_destino='P' AND scb_movbco.cod_pro='".$ArJson->codprop."'";
					}
					elseif($ArJson->tipprop=='B'){
						$filtro=" AND scb_movbco.tipo_destino='B' AND scb_movbco.ced_bene='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND scb_movbco.fecmov>='".$ArJson->fecdes."' AND scb_movbco.fecmov<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT scb_movbco.numdoc AS numdoc,scb_movbco.fecmov AS fecha 
					              FROM scb_movbco
								  WHERE scb_movbco.codemp='".$datosempresa["codemp"]."' AND scb_movbco.numdoc LIKE '%".$ArJson->numdoc."%'".$filtro.
								 " AND (scb_movbco.numdoc NOT IN(SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.tipdocenv='SCBCH') 
										OR scb_movbco.numdoc IN (SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.codusurec='".$codusu."'  AND sst_asignacion_tramite.tipdocenv='SCBCH'))
								   ORDER BY numdoc";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
					
				case 'CXPSP':
					$oservicio = new ServicioSst ( 'cxp_solicitudes' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro=" AND cxp_solicitudes.tipproben='P' AND cxp_solicitudes.cod_pro='".$ArJson->codprop."'";
					}
					elseif($ArJson->tipprop=='B'){
						$filtro=" AND cxp_solicitudes.tipproben='B' AND cxp_solicitudes.ced_bene='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND cxp_solicitudes.fecemisol>='".$ArJson->fecdes."' AND fecemisol<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT cxp_solicitudes.numsol AS numdoc,cxp_solicitudes.fecemisol AS fecha 
					              FROM cxp_solicitudes
								  WHERE cxp_solicitudes.codemp='".$datosempresa["codemp"]."' AND cxp_solicitudes.numsol LIKE '%".$ArJson->numdoc."%'".$filtro.
								 " AND (cxp_solicitudes.numsol NOT IN(SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.tipdocenv='CXPSP') 
										OR cxp_solicitudes.numsol IN (SELECT sst_asignacion_tramite.coddocenv 
										FROM sst_asignacion_tramite
										WHERE sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND sst_asignacion_tramite.codusurec='".$codusu."'  AND sst_asignacion_tramite.tipdocenv='CXPSP'))
								   ORDER BY numsol";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
			}
			break;
			
		case 'catalogodocumentosvalidar' :
			switch($ArJson->tipodoc){
				case 'SEP':
					$oservicio = new ServicioSst ( 'sep_solicitud' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro=" AND sep_solicitud.tipo_destino='P' AND sep_solicitud.cod_pro='".$ArJson->codprop."'";
					}
					elseif($ArJson->tipprop=='B'){
						$filtro=" AND sep_solicitud.tipo_destino='B' AND sep_solicitud.ced_bene='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND sep_solicitud.fecregsol>='".$ArJson->fecdes."' AND sep_solicitud.fecregsol<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT sep_solicitud.numsol AS numdoc,sep_solicitud.fecregsol AS fecha,
										 sep_solicitud.coduniadm AS codunienv,spg_unidadadministrativa.denuniadm AS denunienv
					              FROM sep_solicitud,spg_unidadadministrativa
								  WHERE sep_solicitud.codemp='".$datosempresa["codemp"]."' AND sep_solicitud.numsol LIKE '%".$ArJson->numdoc."%'
								   AND sep_solicitud.numsol NOT IN(SELECT sst_tramite.coddocini FROM sst_tramite WHERE sst_tramite.codemp='".$datosempresa["codemp"]."' AND tipdocini='SEP')".$filtro.
								 " AND sep_solicitud.codemp=spg_unidadadministrativa.codemp AND sep_solicitud.coduniadm=spg_unidadadministrativa.coduniadm".
								 " ORDER BY sep_solicitud.numsol";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
					
				case 'SOCB':
					$oservicio = new ServicioSst ( 'soc_ordencompra' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro=" AND soc_ordencompra.cod_pro='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND soc_ordencompra.fecordcom>='".$ArJson->fecdes."' AND soc_ordencompra.fecordcom<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT soc_ordencompra.numordcom AS numdoc,soc_ordencompra.fecordcom AS fecha,
								   		 soc_ordencompra.coduniadm AS codunienv,spg_unidadadministrativa.denuniadm AS denunienv 
					              FROM soc_ordencompra,spg_unidadadministrativa
								  WHERE soc_ordencompra.codemp='".$datosempresa["codemp"]."' AND soc_ordencompra.numordcom LIKE '%".$ArJson->numdoc."%' AND soc_ordencompra.estcondat='B'
								   AND soc_ordencompra.numordcom NOT IN(SELECT sst_tramite.coddocini FROM sst_tramite WHERE sst_tramite.codemp='".$datosempresa["codemp"]."' AND tipdocini='SOCB')".$filtro.
								 " AND soc_ordencompra.codemp=spg_unidadadministrativa.codemp AND soc_ordencompra.coduniadm=spg_unidadadministrativa.coduniadm".
								 " ORDER BY soc_ordencompra.numordcom";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
				
				case 'SOCS':
					$oservicio = new ServicioSst ( 'soc_ordencompra' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro=" AND soc_ordencompra.cod_pro='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND soc_ordencompra.fecordcom>='".$ArJson->fecdes."' AND soc_ordencompra.fecordcom<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT soc_ordencompra.numordcom AS numdoc,soc_ordencompra.fecordcom AS fecha,
										 soc_ordencompra.coduniadm AS codunienv,spg_unidadadministrativa.denuniadm AS denunienv 
					              FROM soc_ordencompra,spg_unidadadministrativa
								  WHERE soc_ordencompra.codemp='".$datosempresa["codemp"]."' AND soc_ordencompra.numordcom LIKE '%".$ArJson->numdoc."%' AND soc_ordencompra.estcondat='S'
								   AND soc_ordencompra.numordcom NOT IN(SELECT sst_tramite.coddocini FROM sst_tramite WHERE sst_tramite.codemp='".$datosempresa["codemp"]."' AND tipdocini='SOCS')".$filtro.
								 " AND soc_ordencompra.codemp=spg_unidadadministrativa.codemp AND soc_ordencompra.coduniadm=spg_unidadadministrativa.coduniadm".
								 " ORDER BY soc_ordencompra.numordcom";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
				
				case 'CXPSP':
					$oservicio = new ServicioSst ( 'cxp_solicitudes' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro=" AND cxp_solicitudes.tipproben='P' AND cxp_solicitudes.cod_pro='".$ArJson->codprop."'";
					}
					elseif($ArJson->tipprop=='B'){
						$filtro=" AND cxp_solicitudes.tipproben='B' AND cxp_solicitudes.ced_bene='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND cxp_solicitudes.fecemisol>='".$ArJson->fecdes."' AND cxp_solicitudes.fecemisol<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT cxp_solicitudes.numsol AS numdoc,cxp_solicitudes.fecemisol AS fecha 
					              FROM cxp_solicitudes
								  WHERE cxp_solicitudes.codemp='".$datosempresa["codemp"]."' AND cxp_solicitudes.numsol LIKE '%".$ArJson->numdoc."%'
								    AND cxp_solicitudes.numsol NOT IN(SELECT sst_tramite.coddocini FROM sst_tramite WHERE sst_tramite.codemp='".$datosempresa["codemp"]."' AND tipdocini='CXPSP')".$filtro.
								 " ORDER BY numsol";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
					
				case 'SCBCH':
					$oservicio = new ServicioSst ( 'scb_movbco' );
					$filtro    = "";
					
					if ($ArJson->tipprop=='P') {
						$filtro=" AND scb_movbco.tipo_destino='P' AND scb_movbco.cod_pro='".$ArJson->codprop."'";
					}
					elseif($ArJson->tipprop=='B'){
						$filtro=" AND scb_movbco.tipo_destino='B' AND scb_movbco.ced_bene='".$ArJson->codprop."'";
					}
					
					if ($ArJson->fecdes!='') {
						$filtro = $filtro." AND scb_movbco.fecmov>='".$ArJson->fecdes."' AND scb_movbco.fecmov<='".$ArJson->fechas."'";
					}
					
					$cadenasql = "SELECT scb_movbco.numdoc AS numdoc,scb_movbco.fecmov AS fecha 
					              FROM scb_movbco
								  WHERE scb_movbco.codemp='".$datosempresa["codemp"]."' AND scb_movbco.numdoc LIKE '%".$ArJson->numdoc."%' AND scb_movbco.codope='CH'
								    AND scb_movbco.numdoc NOT IN(SELECT sst_tramite.coddocini FROM sst_tramite WHERE sst_tramite.codemp='".$datosempresa["codemp"]."' AND tipdocini='SCBCH')".$filtro.
								 " ORDER BY scb_movbco.numdoc";
								  
					$datos=$oservicio->buscarSql($cadenasql);
					$objSon = generarJson ( $datos );
					echo $objSon;
					break;
			}
			break;
			
		case 'catalogoasignarec' :
			$oservicio = new ServicioSst ( 'sst_tramite' );
			$cadenasql = "	SELECT 
  							sst_asignacion_tramite.numtramite,
 							sst_asignacion_tramite.numasi, 
  							sst_asignacion_tramite.coddocenv, 
  							sst_asignacion_tramite.tipdocenv, 
  							sst_asignacion_tramite.fecenv
							FROM 
							sst_tramite,
							sst_asignacion_tramite
							WHERE 
  							sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND
  							sst_asignacion_tramite.codusurec='".$codusu."' AND 
  							sst_asignacion_tramite.estrec = 'N' AND
  							sst_tramite.codemp = sst_asignacion_tramite.codemp AND
  							sst_tramite.numtramite = sst_asignacion_tramite.numtramite AND
  							sst_tramite.esttra <> 'C' 
  							ORDER BY sst_asignacion_tramite.numtramite";
			$datos=$oservicio->buscarSql($cadenasql);
			$objSon = generarJson ( $datos );
			echo $objSon;
			break;
			
		case 'catalogoasignaenv' :
			$oservicio = new ServicioSst ( 'sst_tramite' );
			$cadenasql = "  SELECT 
  							sst_tramite.numtramite,
  							sst_tramite.tipprop,
  							sst_tramite.codprop, 
  							sst_asignacion_tramite.numasi as numasiant, 
  							sst_asignacion_tramite.coddocenv, 
  							sst_asignacion_tramite.tipdocenv, 
  							sst_asignacion_tramite.fecenv,
  							sst_asignacion_tramite.codunirec as codunienv
							FROM 
  							sst_tramite, 
  							sst_asignacion_tramite
							WHERE 
  							sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND
  							sst_asignacion_tramite.codusurec='".$codusu."' AND 
  							sst_asignacion_tramite.estrec = 'S' AND
  							sst_tramite.codemp = sst_asignacion_tramite.codemp AND
  							sst_tramite.numtramite = sst_asignacion_tramite.numtramite AND
  							sst_tramite.esttra <> 'C' 
							ORDER BY sst_asignacion_tramite.numtramite";
			
			$datos=$oservicio->buscarSql($cadenasql);
			$objSon = generarJson ( $datos );
			echo $objSon;
			break;
			
		case 'catalogotramite' :
			$oservicio = new ServicioSst ( 'sst_tramite' );
			$cadenasql = "SELECT numtramite,nomprop,tipprop as tipoprop,codprop 
					        FROM sst_tramite
							WHERE codemp='".$datosempresa["codemp"]."' AND codusuact='".$codusu."' AND esttra<>'C'";
			$datos=$oservicio->buscarSql($cadenasql);
			$objSon = generarJson ( $datos );
			echo $objSon;
			break;
			
		case 'catalogotramitecon' :
			$oservicio = new ServicioSst ( 'sst_tramite' );
			$datos     = $oservicio->buscarTramiteCatalogoCon($datosempresa["codemp"],$ArJson->catnumtramite,$ArJson->tipo_destino,$ArJson->codprovben,$ArJson->catnumdoc,$ArJson->fecdescat,$ArJson->fechascat);
			$objSon = generarJson ( $datos );
			echo $objSon;
			break;
			
		case 'consultar':
			$oservicio = new ServicioSst ( 'sst_tramite' );
			$cadenasql = "SELECT 
  							sst_asignacion_tramite.codusuenv, 
							sst_asignacion_tramite.codunienv,
							(SELECT 
								spg_unidadadministrativa.denuniadm
							FROM 
								spg_unidadadministrativa
							WHERE 
								spg_unidadadministrativa.codemp=sst_asignacion_tramite.codemp AND
								spg_unidadadministrativa.coduniadm=sst_asignacion_tramite.codunienv
							) AS denunienv,
  							sst_asignacion_tramite.coddocenv, 
  							sst_asignacion_tramite.tipdocenv, 
  							sst_asignacion_tramite.fecenv,
							sst_asignacion_tramite.codunirec,
							(SELECT 
								spg_unidadadministrativa.denuniadm
							FROM 
								spg_unidadadministrativa
							WHERE 
								spg_unidadadministrativa.codemp=sst_asignacion_tramite.codemp AND
								spg_unidadadministrativa.coduniadm=sst_asignacion_tramite.codunirec
							) AS denunirec, 
  							sst_asignacion_tramite.codusurec,
							sst_asignacion_tramite.fecrec
							FROM 
  							sst_asignacion_tramite
							WHERE 
  							sst_asignacion_tramite.codemp='".$datosempresa["codemp"]."' AND
							sst_asignacion_tramite.numtramite = '".$ArJson->numtramite."' 
							ORDER BY sst_asignacion_tramite.numasi";
			
			$datos=$oservicio->buscarSql($cadenasql);
			$objSon = generarJson ( $datos );
			echo $objSon;
			break;
			
		case 'paneltramite':
			$oservicio = new ServicioSst ();
			$datos= $oservicio->buscarTramites($datosempresa["codemp"],$codusu);
			$objSon = generarJson ( $datos );
			echo $objSon;
			break;
	}
}
?>