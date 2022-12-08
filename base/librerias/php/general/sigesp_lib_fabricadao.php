<?php
require_once ("sigesp_lib_daogenerico.php");
require_once ("sigesp_lib_daogenericoplus.php");
require_once ("sigesp_lib_daoregistroevento.php");

abstract class FabricaDao{
	
	public static function CrearDAO($tipodao,$tabla=null,$arrtabla=null) {
		$objeto = null;
		
		switch ($tipodao) {
			case 'N':
				$objeto = new DaoGenerico($tabla);
				break;
			
			case 'P':
				$objeto = new DaoGenericoPlus($tabla,$arrtabla);
				break;
			
			case 'L':
				$objeto = new daoRegistroEvento('sss_registro_eventos');
				break;
			
			case 'F':
				$objeto = new daoRegistroEvento('sss_registro_fallas');
				break;
		}
		
		return $objeto;
	}
	
}
?>