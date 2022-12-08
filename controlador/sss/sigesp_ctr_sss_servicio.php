<?php
$dirctrsssser = "";
$dirctrsssser = dirname(__FILE__);
$dirctrsssser = str_replace("\\","/",$dirctrsssser);
$dirctrsssser = str_replace("/controlador/sss","",$dirctrsssser);
require_once ($dirctrsssser."/base/librerias/php/general/sigesp_lib_serviciogenerico.php");
require_once ($dirctrsssser."/modelo/cfg/dao/sigesp_mod_cfg_dao_empresa.php");
require_once ($dirctrsssser."/modelo/sss/dao/sigesp_mod_sss_dao_usuario.php");
require_once ($dirctrsssser."/modelo/sss/dao/sigesp_mod_sss_dao_sistema.php");
require_once ($dirctrsssser."/modelo/sss/dao/sigesp_mod_sss_dao_derechosusuario.php");


class ServicioSss extends ServicioGenerico {
	private $empresadao;
	private $usuariodao;
	private $sistemadao;
	private $derechousuariodao;
	private $conexionbd;
	
	public function __construct($tabla='') {
		parent::__construct ( $tabla );
	}
	
	
	/***************************************/
	/* Metodos Asociados al Servicio       */
	/***************************************/
	public function obtenerSistemaUsuario($codemp,$codusuario,$codsistema){
		$this->conexionbd = $this->obtenerConexionBd();
		
		try
		{
			$consultafecha = $this->conexionbd->OffsetDate(0, $this->conexionbd->sysTimeStamp);
			$consultafecha = $this->conexionbd->SQLDate('d/m/Y h:i A', $consultafecha);
			
			$consulta = "SELECT sss_sistemas.nomsis, sss_usuarios.nomusu, sss_usuarios.apeusu, ".
						"       (".$consultafecha.") AS fecha, ".
						"		1 as valido, sigesp_empresa.titulo as empresa  FROM sss_derechos_usuarios ". 
						" JOIN sss_sistemas     ON sss_derechos_usuarios.codsis = sss_sistemas.codsis ".  
						" JOIN sss_usuarios ON sss_derechos_usuarios.codemp = sss_usuarios.codemp ".
			            " JOIN sigesp_empresa ON sigesp_empresa.codemp = sss_derechos_usuarios.codemp  ".  
						"     AND sss_derechos_usuarios.codusu = sss_usuarios.codusu  ".
						"WHERE sss_derechos_usuarios.codemp = '$codemp'    ".
						"AND sss_derechos_usuarios.codusu = '$codusuario'    ".
						"AND sss_derechos_usuarios.codsis = '$codsistema' ";		
			$result = $this->conexionbd->SelectLimit($consulta,1); 	
			return $result;
		}
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje='Error al consultar el sistema '.$this->codsis.' y el usuario '.$this->codusu.' '.$this->conexionbd->ErrorMsg();
		}		
	}
	
	public function obtenerUsuario($codemp,$codusuario){
		$this->usuariodao = new UsuarioDao();
		return $this->usuariodao->getUsuarioSession($codemp,$codusuario);
	}
	 
	public function buscarEmpresas() {
		$this->empresadao = new EmpresaDao();
		$dataempresas = $this->empresadao->leerTodos("codemp",1);
		unset($this->empresadao);
		return $dataempresas;
	}
	
	public function verificarUsuario($codemp,$codusu,$pwdusu,$actsess=true){
		$this->usuariodao = new UsuarioDao();
		$resultado = $this->usuariodao->validarUsuario($codemp,$codusu,$pwdusu,$actsess);
		unset($this->usuariodao);
		return $resultado;
	}
	
	public function obtenerSistemasUsuario($codemp,$codusu){
		$this->sistemadao = new SistemaDao();
		$datasistemas = $this->sistemadao->getSistemasUsuario($codemp,$codusu);
		unset($this->sistemadao);
		return $datasistemas;
	}
	
	public function empresaCambiobd($arrdatcon){
		$this->empresadao = new EmpresaDao();
		$dataempresas = $this->empresadao->getEmpresas($arrdatcon);
		unset($this->empresadao);
		return $dataempresas;	
	}
	
	public function obtenerDerechosUsuarios($as_empresa, $as_usuario, $as_sistema, $as_ventana, $ls_enabled) {
		$this->derechousuariodao = new DerechoUsuario();
		return $this->derechousuariodao->obtenerDerechosUsuario($as_empresa, $as_usuario, $as_sistema, $as_ventana, $ls_enabled);
	}
	/***************************************/
	/* Fin Metodos Asociados al Servicio   */
	/***************************************/	

}

?>