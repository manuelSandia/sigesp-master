<?php
$dirmodsssdaobarher = ""; 
$dirmodsssdaobarher = dirname(__FILE__);
$dirmodsssdaobarher = str_replace("\\","/",$dirmodsssdaobarher);
$dirmodsssdaobarher = str_replace("/modelo/sss/dao","",$dirmodsssdaobarher);
require_once ($dirmodsssdaobarher."/base/librerias/php/general/sigesp_lib_daogenerico.php");

class barraHerramientasDao extends DaoGenerico{
        
	function __construct() {
		parent::__construct ( 'sss_derechos_usuarios' );
	}
    
   	public function obtenerBarraHerramientaUsuario($codusu='',$codsis='',$nomven='',$codemp=''){
		//CONSULTA ADAPTADA PARA V1 OJO 
		
   		$candenasql = "SELECT sss_derechos_usuarios.visible, sss_derechos_usuarios.leer, sss_derechos_usuarios.incluir,
		sss_derechos_usuarios.cambiar, sss_derechos_usuarios.eliminar, sss_derechos_usuarios.imprimir,
		sss_derechos_usuarios.anular, sss_derechos_usuarios.ejecutar, sss_derechos_usuarios.administrativo
		FROM sss_derechos_usuarios
		WHERE sss_derechos_usuarios.codusu = '".$codusu."'
		AND sss_derechos_usuarios.enabled = '1'  
		AND sss_derechos_usuarios.codsis = '".$codsis."'
		AND sss_derechos_usuarios.nomven = '".$nomven."'
		AND sss_derechos_usuarios.codemp = '".$codemp."' 
		AND sss_derechos_usuarios.codintper = '---------------------------------'"; 
		
		
		return $arrsolicitud = $this->buscarSql($candenasql);
	}
    
}

?>
