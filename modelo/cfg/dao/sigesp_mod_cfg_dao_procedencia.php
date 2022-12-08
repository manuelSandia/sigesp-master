<?php
require_once("../../base/librerias/php/general/sigesp_lib_daogenerico.php");

class procedenciaDAO
{
	private $daogenerico;
	private $tabla;
	
	public function getTabla(){
        return $this->tabla;
    }
    
    
	public function setTabla($tabla){
        $this->tabla = $tabla;
		$this->daogenerico= new DaoGenerico($this->tabla);
    }
	
	public function incluirDto($dto){
		
		$this->pasarDatos($dto);		
		return $this->daogenerico->incluir();
	}
	
	public function modificarDto($dto){
		
		$this->pasarDatos($dto);		
		return $this->daogenerico->modificar();		
	}
	
	public function eliminarDto($dto){
		
		$this->pasarDatos($dto);		
		return $this->daogenerico->eliminar();			
	}
	
	public function buscarTodos(){
		
		return $this->daogenerico->leerTodos();			
	}
	
	function pasarDatos($ObJson)
	{
		foreach($this->daogenerico as $IndiceDAO =>$valorDAO)
		{
			foreach($ObJson as $IndiceJson =>$valorJson)
			{
				if($IndiceJson==$IndiceDAO && $IndiceJson!="ano_presupuesto" && $IndiceJson!="codemp")
				{
					$this->daogenerico->$IndiceJson = utf8_decode($valorJson);					
				}
				else
				{
					$GLOBALS[$IndiceJson] = $valorJson;
				}
			}
		}
	}
	
}

?>