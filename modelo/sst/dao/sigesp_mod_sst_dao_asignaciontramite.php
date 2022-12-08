<?php
$dirmodsstdaoasitra = "";
$dirmodsstdaoasitra = dirname(__FILE__);
$dirmodsstdaoasitra = str_replace("\\","/",$dirmodsstdaoasitra);
$dirmodsstdaoasitra = str_replace("/modelo/sst/dao","",$dirmodsstdaoasitra);
require_once ($dirmodsstdaoasitra."/base/librerias/php/general/sigesp_lib_daogenerico.php");

class AsignacionTramiteDao extends DaoGenerico{
	
	function __construct() {
		parent::__construct ( 'sst_asignacion_tramite' );
	}
	
	public function buscarNumeroAsignacion($codemp,$numtramite) {
		$cadenasql="SELECT 
  					MAX(sst_asignacion_tramite.numasi) as numasi
					FROM 
  					sst_asignacion_tramite
					WHERE 
  					sst_asignacion_tramite.codemp ='".$codemp."' AND
					sst_asignacion_tramite.numtramite = '".$numtramite."'";
		$resultado = $this->buscarSql ( $cadenasql );
		if ($resultado->fields ['numasi'] == '') {
			return agregarUno(0,15);
		} else {
			return agregarUno($resultado->fields ['numasi'],15);
		}
	}
	
	public function buscarAsigancionRecibida($codemp,$codusu,$arrjson){
		$filtro = "";
		if($arrjson->coddocenv!=""){
			$filtro = $filtro."sst_asignacion_tramite.coddocenv = '".$arrjson->coddocenv."' AND sst_asignacion_tramite.tipdocenv = '".$arrjson->tipdocenv."' AND ";
		}
		$filtro = $filtro.$this->obtenerConexionBd()->substr."( sst_asignacion_tramite.fecenv, 1, 10) >= '".$arrjson->fecdes."' AND ".$this->obtenerConexionBd()->substr."(sst_asignacion_tramite.fecenv, 1, 10) <= '".$arrjson->fechas."'";
		
		
		$cadenasql = "  SELECT 
  						sst_tramite.numtramite,
  						sst_asignacion_tramite.numasi,
  						sst_asignacion_tramite.coddocenv, 
  						sst_asignacion_tramite.tipdocenv, 
  						sst_asignacion_tramite.fecenv
  						FROM 
  						sst_tramite, 
  						sst_asignacion_tramite
						WHERE 
  						sst_asignacion_tramite.codemp='".$codemp."' AND
  						sst_asignacion_tramite.codusurec='".$codusu."' AND 
  						sst_asignacion_tramite.estrec = 'S' AND ".$filtro." AND
  						sst_tramite.codemp = sst_asignacion_tramite.codemp AND
  						sst_tramite.numtramite = sst_asignacion_tramite.numtramite AND
  						sst_tramite.esttra <> 'C' 
						ORDER BY sst_asignacion_tramite.numtramite";
		return $this->buscarSql ( $cadenasql );
	}
	
	public function buscarAsigancionEnviada($codemp,$codusu,$arrjson){
		$filtro = "";
		if($arrjson->coddocenv!=""){
			$filtro = $filtro."sst_asignacion_tramite.coddocenv = '".$arrjson->coddocenv."' AND sst_asignacion_tramite.tipdocenv = '".$arrjson->tipdocenv."' AND ";
		}
		$filtro = $filtro.$this->obtenerConexionBd()->substr."( sst_asignacion_tramite.fecenv, 1, 10) >= '".$arrjson->fecdes."' AND ".$this->obtenerConexionBd()->substr."(sst_asignacion_tramite.fecenv, 1, 10) <= '".$arrjson->fechas."'";
		
		
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
  							sst_asignacion_tramite.codemp='".$codemp."' AND
  							sst_asignacion_tramite.codusuenv='".$codusu."' AND 
  							sst_asignacion_tramite.estrec = 'N' AND ".$filtro." AND
  							sst_tramite.codemp = sst_asignacion_tramite.codemp AND
  							sst_tramite.numtramite = sst_asignacion_tramite.numtramite AND
  							sst_tramite.esttra <> 'C' 
  							ORDER BY sst_asignacion_tramite.numtramite";
		return $this->buscarSql ( $cadenasql );
	}
	
	public function buscarAsignacionesUsuario($codemp,$codusu){
		$cadenasql = "	SELECT 
  							sst_asignacion_tramite.numtramite,
 							sst_asignacion_tramite.coddocenv, 
  							sst_asignacion_tramite.tipdocenv, 
  							sst_asignacion_tramite.fecenv,
							sst_asignacion_tramite.estrec
							FROM
							sst_tramite, 
							sst_asignacion_tramite
							WHERE 
  							sst_asignacion_tramite.codemp='".$codemp."' AND
							sst_asignacion_tramite.codusurec='".$codusu."' AND
							sst_asignacion_tramite.estrec <> 'P' AND 
							sst_tramite.codemp = sst_asignacion_tramite.codemp AND
  							sst_tramite.numtramite = sst_asignacion_tramite.numtramite AND
  							sst_tramite.esttra <> 'C' 
  							ORDER BY sst_asignacion_tramite.numtramite";
		return $this->buscarSql ( $cadenasql );
	}
	
	public function guardarAsignacionInicial($codemp,$codusu,$obsenv,$arrjson){
		$this->codemp    = $codemp;
		$this->numasi    = $this->buscarNumeroAsignacion($codemp,$arrjson->numtramite);
		$this->coddocenv = $arrjson->coddocini;
		$this->tipdocenv = $arrjson->tipdocini;
		$this->fecenv    = date('Y/m/d h:i');
		$this->obsenv    = $obsenv;
		$this->codusuenv = $codusu;
		$this->estrec    = "N";
		if($arrjson->codusurec == ""){
			$this->codusurec = $codusu;
		}
		$this->setData($arrjson);
		$resultado = $this->modificar();
		return $resultado;
	}
	
	public function eliminarAsignacion($codemp,$numtramite,$numasi="000000000000001"){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$numtramite."' AND numasi='".$numasi."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$resultado = $this->eliminar();
		}
		else{
			$resultado = 0;
		}
		return $resultado;
	}
	
	public function recibirAsignacion($codemp,$arrjson){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$arrjson->numtramite."' AND numasi='".$arrjson->numasi."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$this->estrec = "S";
			$this->obsrec = $arrjson->obsrec;
			$this->fecrec = date('Y/m/d h:i');
			$resultado = $this->modificar();
		}
		else{
			$resultado = 0;
		}
		return $resultado;
	}
	
	public function guardarAsigancion($codemp,$codusu,$arrjson){
		$this->codemp    = $codemp;
		$this->numasi    = $this->buscarNumeroAsignacion($codemp,$arrjson->numtramite);
		$this->codusuenv = $codusu;
		$this->fecenv    = date('Y/m/d h:i');
		$this->estrec    = "N";
		$this->setData($arrjson);
		$resultado = $this->modificar();
		return $resultado;
	}
	
	public function procesarAsignacion($codemp,$arrjson){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$arrjson->numtramite."' AND numasi='".$arrjson->numasiant."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$this->estrec = "P";
			$resultado = $this->modificar();
		}
		else{
			$resultado = 0;
		}
		return $resultado;
	}
	
	public function reiniciarRecepcion($codemp,$arrjson){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$arrjson->numtramite."' AND numasi='".$arrjson->numasi."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$this->estrec = "N";
			$this->obsrec = "";
			$this->fecrec = "";
			$resultado = $this->modificar();
		}
		else{
			$resultado = 0;
		}
		return $resultado;
	}
	
	public function actualizarEstadoAsignacion($codemp,$numtramite,$numasi,$estado){
		$cadenafiltro = "codemp = '".$codemp."' AND numtramite = '".$numtramite."' AND numasi='".$numasi."'";
		$resultado=$this->Load($cadenafiltro);
		if($resultado){
			$this->estrec = $estado;
			$resultado = $this->modificar();
		}
		else{
			$resultado = 0;
		}
		return $resultado;
	}
	
	
}
?>