<?php
require_once ("sigesp_lib_daogenerico.php");

class DaoGenericoPlus {
	private $objcabecera;
	private $arrnomtabdetalles;
	private $arrobjdetalles;
	
	public function getCabecera(){
		return $this->objcabecera;
	}
	
	public function getDetalles(){
		return $this->arrobjdetalles;
	}
	
	public function getInstaciaDetalle($tabdetalle){
		return $objdetalle = new DaoGenerico ( $tabdetalle );
	}
	
	public function DaoGenericoPlus($tabcabecera,$arrtabdetalles=null){
		$i=0;
		$this->objcabecera = new DaoGenerico ( $tabcabecera );
		if($arrtabdetalles!=null){
			foreach ($arrtabdetalles as $tabdetalle => $nomtabla) {
				$this->arrnomtabdetalles[$i]=$nomtabla;
				$i++;
			}	
		}
		
	}
	
	public function setData($arrjson,$codemp){
		$this->arrobjdetalles = array();		
		$this->objcabecera->codemp=$codemp;
		$this->setDataDao($this->objcabecera,$arrjson->datoscabecera[0]);
		foreach ($this->arrnomtabdetalles as $tabdetalle => $nomtabla) {
			$j=0;
			foreach ($arrjson->$nomtabla as $recdetalle) {
				$nombrereal=substr($nomtabla, 4,strlen($nomtabla));
				$this->arrobjdetalles[$nomtabla][$j] = new DaoGenerico ( $nombrereal );
				$this->arrobjdetalles[$nomtabla][$j]->codemp=$codemp;
				$this->setDataDao($this->arrobjdetalles[$nomtabla][$j], $recdetalle);
				$j++;
			}
		}
	}
		
	public function setDataDao($objdao,$ObJson){
		$arratributos = $objdao->getAttributeNames();
		foreach ( $arratributos as $IndiceDAO ) {
			foreach ( $ObJson as $IndiceJson => $valorJson ) {
				if ($IndiceJson == $IndiceDAO && $IndiceJson != "codemp") {
					$objdao->$IndiceJson = utf8_decode ( $valorJson );
				} 
			}
		}
	}
	
	public function incluirDto(){
		$resultado = array();
		DaoGenerico::iniciarTrans ();
		$resultado[0] = $this->objcabecera->modificar ();
		if(DaoGenerico::completarTrans ()){
			foreach ($this->arrobjdetalles as $nomtabla => $recdetalles) {
				switch (substr($nomtabla, 0,3)) {
					case 'pel'://para eliminar
						DaoGenerico::iniciarTrans ();
						foreach ($recdetalles as $detalle) {
							$detalle->eliminar ();
						}
						if(DaoGenerico::completarTrans ()){
							$resultado[1] =1;
						}
						else{
							return	$resultado[1] =0;
						}
						break;
					case 'ins'://solo inserta no modifica si existe
						DaoGenerico::iniciarTrans ();
						foreach ($recdetalles as $detalle) {
							$detalle->modificar (true);
						}
						if(DaoGenerico::completarTrans ()){
							$resultado[1] =1;
						}
						else{
							return	$resultado[1] =0;
						}
						break;
					case 'imo'://inserta y modifica en el caso que exista
						DaoGenerico::iniciarTrans ();
						foreach ($recdetalles as $detalle) {
							$detalle->modificar ();
						}
						if(DaoGenerico::completarTrans ()){
							$resultado[1] =1;
						}
						else{
							return	$resultado[1] =0;
						}
						break;
					case 'esp'://elimina lo existen y luego inserta
						DaoGenerico::iniciarTrans ();
						$this->objcabecera->deleteDetalle(substr($nomtabla, 4,strlen($nomtabla)));
						if(DaoGenerico::completarTrans ()){
							DaoGenerico::iniciarTrans ();
							foreach ($recdetalles as $detalle) {
								$detalle->modificar ();
							}
							if(DaoGenerico::completarTrans ()){
								$resultado[1] =1;
							}
							else{
								return	$resultado[1] =0;
							}
						}
						else{
							return	$resultado[1] =0;
						}
						
						break;
				}
			}
		}
		return $resultado;		
	}
	
	public function eliminarDto($validarelacion = false,$arrfk=array()){
		DaoGenerico::iniciarTrans ();
		if(!$validarelacion){
			$valido=true;
		}
		else{
			$valido=$this->objcabecera->validarRelaciones($arrfk);	
		}
		
		if($valido){
			foreach ($this->arrobjdetalles as $recdetalles) {
				foreach ($recdetalles as $detalle) {
					$detalle->eliminar ();
				}
			}
			
			$this->objcabecera->eliminar ($validarelacion,$arrfk);
			if(DaoGenerico::completarTrans ()){
				return 1;
			}
			else{
				return 2;
			}
		}
		else{
			return 0;
		}
	}
}
?>