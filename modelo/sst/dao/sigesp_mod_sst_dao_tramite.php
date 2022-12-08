<?php
$dirmodsstdaotra = "";
$dirmodsstdaotra = dirname(__FILE__);
$dirmodsstdaotra = str_replace("\\","/",$dirmodsstdaotra);
$dirmodsstdaotra = str_replace("/modelo/sst/dao","",$dirmodsstdaotra);
require_once ($dirmodsstdaotra."/base/librerias/php/general/sigesp_lib_daogenerico.php");

class TramiteDao extends DaoGenerico{
	
	function __construct() {
		parent::__construct ( 'sst_tramite' );
	}
	
	public function getNumeroTramite($codemp){
		$this->codemp=$codemp;
		return $this->buscarCodigo ('numtramite',true,15);
	}
	
	public function getTramitesCatalogo($codemp,$codusu,$numtramite,$tipoprop,$codprovbene,$numdoc,$fecdescat,$fechascat){
		
		$filtro = "";
		$cadenacon="";
		$cadenaconprop="";
		
		if(!empty($numtramite)){
			$filtro = $filtro. " AND sst_tramite.numtramite like '%".$as_numsoldes."%'";
		}
		
		if(!empty($tipoprop)&&!empty($codprovbene)){
			$filtro = $filtro. " AND sst_tramite.tipprop='".$tipoprop."' AND sst_tramite.codprop='".$codprovbene."'";
		}
		
		if(!empty($numdoc)){
			$filtro = $filtro. " AND sst_asignacion_tramite.coddocenv='".$numdoc."'";
		}
		
		if(!empty($fecdescat)&&!empty($fechascat)){
			$filtro = $filtro. "  AND sst_tramite.fecini>='".$fecdescat."' AND sst_tramite.fecini<='".$fechascat."'";
		}
		
		switch ($_SESSION["ls_gestor"]){
			case "MYSQLT":
				$cadenacon="CONCAT(nomusu,' ',apeusu)";
				$cadenaconprop="CONCAT(sst_tramite.codprop,'-',sst_tramite.nomprop)";
				break;
			case "POSTGRES":
				$cadenacon="nomusu||' '||apeusu";
				$cadenaconprop="sst_tramite.codprop||'-'||sst_tramite.nomprop";
				break;
			case "INFORMIX":
				$cadenaconprop="sst_tramite.codprop||'-'||sst_tramite.nomprop";;
				break;
		}

		$cadenasql ="SELECT sst_tramite.numtramite,sst_tramite.tipprop,sst_tramite.nomprop,
		                    sst_tramite.codprop,".$cadenaconprop." as codprobene,
							sst_tramite.obstramite,sst_tramite.fecini,
							sst_tramite.codusuini,sst_tramite.codusuact,
							sst_asignacion_tramite.codunienv,sst_asignacion_tramite.codunirec,
							sst_asignacion_tramite.codusurec,sst_tramite.coddocini,
							(SELECT ".$cadenacon."
                             FROM sss_usuarios
                             WHERE sst_asignacion_tramite.codemp = sss_usuarios.codemp AND sss_usuarios.codusu=sst_asignacion_tramite.codusuenv)as nomusuini,
   							(SELECT ".$cadenacon."
   							 FROM sss_usuarios
                             WHERE sst_tramite.codemp = sss_usuarios.codemp and sss_usuarios.codusu=sst_tramite.codusuact)as nomusuact,
                            (SELECT denuniadm
                             FROM spg_unidadadministrativa
                             WHERE sst_tramite.codemp = spg_unidadadministrativa.codemp and spg_unidadadministrativa.coduniadm=sst_asignacion_tramite.codunienv)as denunienv,
                            (SELECT denuniadm
                             FROM spg_unidadadministrativa
                             WHERE sst_tramite.codemp = spg_unidadadministrativa.codemp and spg_unidadadministrativa.coduniadm=sst_asignacion_tramite.codunirec)as denunirec
                     FROM 
  							sst_tramite,sst_asignacion_tramite
					 WHERE 
  							sst_tramite.codemp = '".$codemp."' AND
  							(sst_tramite.codusuact = '".$codusu."' OR sst_tramite.codusuini = '".$codusu."')	 AND
							sst_tramite.codemp = sst_asignacion_tramite.codemp AND
  							sst_tramite.numtramite = sst_asignacion_tramite.numtramite AND
  							sst_asignacion_tramite.numasi = '000000000000001'".$filtro;
		$datatramites = $this->buscarSql($cadenasql);
		return $datatramites;
	}
	
public function getTramitesCatalogoCon($codemp,$numtramite,$tipoprop,$codprovbene,$numdoc,$fecdescat,$fechascat){
		
		$filtro = "";
		$cadenaconprop="";
		
		if(!empty($numtramite)){
			$filtro = $filtro. " AND sst_tramite.numtramite like '%".$as_numsoldes."%'";
		}
		
		if(!empty($tipoprop)&&!empty($codprovbene)){
			$filtro = $filtro. " AND sst_tramite.tipprop='".$tipoprop."' AND sst_tramite.codprop='".$codprovbene."'";
		}
		
		if(!empty($numdoc)){
			$filtro = $filtro. " AND (sst_asignacion_tramite.coddocenv='".$numdoc."' OR sst_asignacion_tramite.coddocrec='".$numdoc."')";
		}
		
		if(!empty($fecdescat)&&!empty($fechascat)){
			$filtro = $filtro. "  AND sst_tramite.fecini>='".$fecdescat."' AND sst_tramite.fecini<='".$fechascat."'";
		}
		
		switch ($_SESSION["ls_gestor"]){
			case "MYSQLT":
				$cadenaconprop="CONCAT(sst_tramite.codprop,'-',sst_tramite.nomprop)";
				break;
			case "POSTGRES":
				$cadenaconprop="sst_tramite.codprop||'-'||sst_tramite.nomprop";
				break;
		}

		$cadenasql ="SELECT sst_tramite.numtramite, sst_tramite.codusuact, sst_tramite.nomprop, 
							sst_tramite.esttra, sst_tramite.fecini,sst_tramite.codusuini, 
							sst_tramite.coddocini, sst_tramite.tipdocini, sst_tramite.fecfin, 
							sst_tramite.coddocfin,sst_tramite.tipdocfin,".$cadenaconprop." as codprobene
					 FROM 
  							sst_tramite,sst_asignacion_tramite
					 WHERE 
  							sst_tramite.codemp = '".$codemp."' AND
  							sst_tramite.codemp = sst_asignacion_tramite.codemp AND
  							sst_tramite.numtramite = sst_asignacion_tramite.numtramite ".$filtro."
  					 GROUP BY sst_tramite.numtramite, sst_tramite.codusuact, sst_tramite.nomprop, 
							sst_tramite.esttra, sst_tramite.fecini,sst_tramite.codusuini, 
							sst_tramite.coddocini, sst_tramite.tipdocini, sst_tramite.fecfin, 
							sst_tramite.coddocfin,sst_tramite.tipdocfin,sst_tramite.codprop
					 ORDER BY sst_tramite.numtramite";
		$datatramites = $this->buscarSql($cadenasql);
		return $datatramites;
	}
	
	public function getCabeceraTramite($codemp,$numtramite){
		switch ($_SESSION["ls_gestor"]){
			case "MYSQLT":
				$cadenadocini="CONCAT(sst_tramite.tipdocini,' - ',sst_tramite.coddocini) as coddocini";
				$cadenadocfin="CONCAT(sst_tramite.tipdocfin,' - ',sst_tramite.coddocfin) as coddocfin";
				break;
			case "POSTGRES":
				$cadenadocini="sst_tramite.tipdocini||' - '||sst_tramite.coddocini as coddocini";
				$cadenadocfin="sst_tramite.tipdocfin||' - '||sst_tramite.coddocfin as coddocfin";
				break;
		}
		
		$cadenasql    = "SELECT sst_tramite.nomprop,sst_tramite.fecini,".$cadenadocini.",
		                 	    sst_tramite.fecfin,".$cadenadocfin."          
						 FROM sst_tramite
						 WHERE sst_tramite.codemp='".$codemp."' AND sst_tramite.numtramite='".$numtramite."'";
		$datatramites = $this->buscarSql($cadenasql);
		return $datatramites;
	}
	
	public function getDetalleTramite($codemp,$numtramite){
		$arreglo[0]="sst_asignacion_tramite.coddocenv";
		$arreglo[1]="'  -  '";
		$arreglo[2]="sst_asignacion_tramite.tipdocenv";
		$coddocenv=$this->concatenarCadena($arreglo);
		
		$cadenasql    = "SELECT 
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
  							(".$coddocenv.") AS coddocenv, 
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
  							sst_asignacion_tramite.codemp='".$codemp."' AND
							sst_asignacion_tramite.numtramite = '".$numtramite."' 
							ORDER BY sst_asignacion_tramite.numasi";
		return $this->buscarSql($cadenasql);
	}
	
	public function buscarTramitesCerrados($codemp,$codusu,$arrjson){
		$filtro = "";
		if($arrjson->coddocenv!=""){
			$filtro = $filtro."sst_tramite.coddocfin = '".$arrjson->coddocenv."' AND sst_tramite.tipdocfin = '".$arrjson->tipdocenv."' AND ";
		}
		$filtro = $filtro.$this->obtenerConexionBd()->substr."( sst_tramite.fecfin, 1, 10) >= '".$arrjson->fecdes."' AND ".$this->obtenerConexionBd()->substr."(sst_tramite.fecfin, 1, 10) <= '".$arrjson->fechas."'";
		
		
		$cadenasql = "	SELECT 
  							sst_tramite.numtramite,
 							sst_tramite.coddocfin as coddocenv, 
  							sst_tramite.tipdocfin as tipdocenv, 
  							sst_tramite.fecfin as fecenv
							FROM 
							sst_tramite
							WHERE 
  							sst_tramite.codemp='".$codemp."' AND
  							sst_tramite.codusuact='".$codusu."' AND ".$filtro." AND 
  							sst_tramite.esttra = 'C' 
  							ORDER BY sst_tramite.numtramite";
		return $this->buscarSql ( $cadenasql );
	}
	
	public function guardarTramite($codemp,$codusu,$arrjson){
		$this->codemp=$codemp;
		$this->codusuini=$codusu;
		$this->codusuact=$codusu;
		$this->fecini = date('Y/m/d h:i');
		if($arrjson->codusurec == ""){
			$this->esttra = 'I';
		}
		else{
			$this->esttra = 'P';
			$this->codusuact=$arrjson->codusurec;
		}
		$this->setData($arrjson);
		$resultado=$this->incluir();
		
		if($resultado){
			return "2";
		}
		else{
			if($this->ErrorNo()==-5 || $this->ErrorNo()==-1 || $this->ErrorNo()==-239 || $this->ErrorNo()==1062 || $this->ErrorNo()==23505){
				$numero=$this->getNumeroTramite($codemp);
				$arrjson->numtramite=$numero;
				$resultado = $this->guardarTramite($codemp,$codusu,$arrjson);
				if($resultado!=0){
					return "3,".$numero;
				}
				else{
					return "0";
				}
			}
			else{
				return "0";
			}
		}
	}
	
	public function modificarTramite($codemp,$arrjson){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$arrjson->numtramite."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$this->tipprop    = $arrjson->tipprop;
			$this->codprop    = $arrjson->codprop;
			$this->nomprop    = $arrjson->nomprop;
			$this->obstramite = $arrjson->obstramite;
			$resultado = $this->modificar();
		}
		else{
			$resultado = 0;
		}
		return $resultado;
	}
	
	public function eliminarRegistroTramite($codemp,$numtramite){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$numtramite."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$resultado = $this->eliminar();
		}
		else{
			$resultado = 0;
		}
		
		return $resultado;
	}
	
	public function validarEliminarTramite($codemp,$numtramite){
		$cadenasql ="SELECT sst_asignacion_tramite.estrec
                     FROM 
  							sst_tramite,sst_asignacion_tramite
					 WHERE 
  							sst_tramite.codemp ='".$codemp."' AND
  							sst_tramite.numtramite='".$numtramite."' AND
							sst_tramite.codemp = sst_asignacion_tramite.codemp AND
  							sst_tramite.numtramite = sst_asignacion_tramite.numtramite AND
  							sst_asignacion_tramite.numasi = '000000000000001'";
		$datatramite = $this->buscarSql($cadenasql);
		if($datatramite->fields ['estrec']=='N'){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function actualizarUsuarioTramite($codemp,$codusu,$arrjson){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$arrjson->numtramite."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$this->codusuact    = $codusu;
			$resultado = $this->modificar();
		}
		else{
			$resultado = 0;
		}
		return $resultado;
	}
	
	public function registrarCierreTramite($codemp,$codusu,$arrjson){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$arrjson->numtramite."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$this->codusuact = $codusu;
			$this->fecfin	 = date('Y/m/d h:i');
			$this->coddocfin = $arrjson->coddocfin;
			$this->tipdocfin = $arrjson->tipdocfin;
			$this->obsfintra = $arrjson->obsfintra;
			$this->esttra    = 'C';
			$resultado = $this->modificar();
		}
		else{
			$resultado = 0;
		}
		return $resultado;
	}
	
	public function reversarCierreTramite($codemp,$arrjson){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$arrjson->numtramite."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$this->fecfin	 = "";
			$this->coddocfin = "";
			$this->tipdocfin = "";
			$this->obsfintra = "";
			$this->esttra    = "P";
			$resultado = $this->modificar();
		}
		else{
			$resultado = 0;
		}
		return $resultado;
	}
	
	public function verificarUltimo($codemp,$numtramite){
		$numeroultimo=intval($this->getNumeroTramite($codemp));
		$numeroactual=intval($numtramite);
		if($numeroactual==$numeroultimo){
			return true;
		}
		else{
			return false;
		}
	}
}
?>