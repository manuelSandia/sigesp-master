<?php
require_once ("sigesp_lib_funciones.php");
require_once ("sigesp_lib_conexion.php");

/**
 * @desc Clase encargada de crear las instacias active record de la tabla que se le indique
 *       cuenta con metodos genericos que proveen conexion a base de datos y las operciones
 *       de insercion, modificacion y consulta de datos
 * @author Ing. Gerardo Cordero
 */
class DaoGenerico extends ADOdb_Active_Record {
	
	
	
	/**
	 * @desc Metodo constructor de la clase, llama al metodo estatico conectar para crear
	 *       el objeto de conexion el cual es seteado en el atributo privado conexionbd
	 * @param string $table - nombre de la tabla a instaciar con active record
	 * @author Ing. Gerardo Cordero
	 */
	function __construct($table = false) {
		ConexionBaseDatos::getInstanciaConexion(); 
		parent::__construct ( $table );
	}
	
	
	/**
	 * @desc Metodo que inicia una transaccion de base de datos
	 * @author Ing. Gerardo Cordero
	 */
	public static function iniciarTrans() {
		ConexionBaseDatos::getInstanciaConexion(); 
		ConexionBaseDatos::$conexionbd->StartTrans();
	}
	
	/**
	 * @desc Metodo que finaliza una transaccion de base de datos
	 * @return boolean - true si el commit se realizo satisfactoriamente
	 * @author Ing. Gerardo Cordero
	 */
	public static function completarTrans() {
		ConexionBaseDatos::getInstanciaConexion(); 
		if (ConexionBaseDatos::$conexionbd->CompleteTrans()) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * @desc  Este metodo invoca al metodo statico para establecer una conexion alterna
	 *        a una base de datos
	 * @param string  $servidor - ip del servidor de datos
	 * @param string  $usuario - cuenta de usuario servidor de datos
	 * @param strinf  $clave - clave cuenta de usuario servidor de datos
	 * @param string  $basedatos - nombre de la base de datos
	 * @param string  $gestor - identificador del gestor, ver documentacion adodb
	 * @param string  $puerto -  numero del puerto del servidor de datos
	 * @param boolean $flagactiverecord - true activa la capacidad active record de adodb
	 * @return conexionAlternabd - instacia de conexion alterna
	 * @author Ing. Gerardo Cordero
	 */
	public function obtenerConexionAlterna($servidor, $usuario, $clave, $basedatos, $gestor, $puerto,$flagactiverecord=false){
		return ConexionBaseDatos::conectarAlternaBD($servidor, $usuario, $clave, $basedatos, $gestor, $puerto,$flagactiverecord);
	}
	
	
	/**
	 * @desc  Metodo que instacia la clase de conecion para crea un objeto de conecion
	 * @param array $arrdatcon - parametros de coneccion
	 * @return objlibcom - objeto de conexion
	 * @author Ing. Gerardo Cordero
	 */
	public function getObjetoConexion($arrdatcon){
		$objlibcom = new ConexionBaseDatos();
		return $objlibcom->conectarBD($arrdatcon['host'],$arrdatcon['login'],$arrdatcon['password'],$arrdatcon['basedatos'],$arrdatcon['gestor']);
	}
	
	
	/**
	 * Enter description here ...
	 * @param unknown_type $multiusuario
	 * @param unknown_type $consecutivo
	 * @param unknown_type $validarempresa
	 * @return boolean|string
	 * @author Ing. Gerardo Cordero
	 */
	public function incluir($multiusuario=false,$consecutivo="",$validarempresa=true,$longitud=0) {
		if(!$multiusuario){
			return $this->save();
		}
		else{
			$resultado=$this->save();
			if($resultado){
				return "1";
			}
			else{
				if($this->ErrorNo()==-5 || $this->ErrorNo()==-1 || $this->ErrorNo()==-239 || $this->ErrorNo()==1062 || $this->ErrorNo()==23505){
					$numero=$this->buscarCodigo($consecutivo,$validarempresa,$longitud);
					$resultado=$this->incluir(true,$consecutivo,$validarempresa,$longitud);
					if($resultado){
						return "-1,".$this->$consecutivo;
					}
					else{
						return "0";
					}
				}
			}
		}
		
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $validaexiste
	 * @return Ambigous <boolean, unknown>|number
	 * @author Ing. Gerardo Cordero
	 */
	public function modificar($validaexiste = false) {
		if(!$validaexiste){
			$this->setDataNull();
			return $this->replace ();
		}
		else{
			$resultado = $this->buscarPk();
			$cantcampo = $resultado->_numOfRows;
			if($cantcampo>0){
				return 1;
			}else{
				$this->setDataNull();
				return $this->replace ();
			}
			
		}
	}
	
	/**
	 * Enter description here ...
	 * @return boolean
	 * @author Ing. Gerardo Cordero
	 */
	public function eliminar() {
		return $this->delete ();
	}
	
	/**
	 * Enter description here ...
	 * @param json $ObJson
	 * @author Ing. Gerardo Cordero
	 */
	public function setData($ObJson) {
		$arratributos = $this->GetAttributeNames();
		foreach ( $arratributos as $IndiceDAO ) {
			foreach ( $ObJson as $IndiceJson => $valorJson ) {
				if ($IndiceJson == $IndiceDAO) {
					$this->$IndiceJson = utf8_decode ( trim($valorJson) );
				}
			}
		}
	}
	
	/**
	 * Enter description here ...
	 * @param array $arrfk
	 * @return boolean
	 * @author Ing. Gerardo Cordero
	 */
	public function validarRelaciones($arrfk){
		$existerelacion=true;
		foreach ($arrfk as $nomtabenlace => $tablaenlace) {
			$cantcampo = ((count($tablaenlace))-1);
			$restriccion = array();
			$cantfk=0;
			foreach ($tablaenlace as $campoenlace => $valorenlace) {
				if($cantcampo==$cantfk){
					$restriccion[$cantfk][0] = $campoenlace;
            		$restriccion[$cantfk][1] = '=';
                    $restriccion[$cantfk][2] = $valorenlace;
                    $restriccion[$cantfk][3] = 2;
				}else{
					$restriccion[$cantfk][0] = $campoenlace;
                    $restriccion[$cantfk][1] = '=';
                    $restriccion[$cantfk][2] = $valorenlace;
                    $restriccion[$cantfk][3] = 0;
				}
				$cantfk++;
			}
			$resulatado=$this->buscarCampoRestriccion($restriccion,true,$nomtabenlace);
			if (($resultado->_numOfRows)>0){
				return false;
			}
		}
		
		return $existerelacion;
	}
	
	/**
	 * Enter description here ...
	 * @author Ing. Gerardo Cordero
	 */
	public function buscarPk(){
		$restriccion = array();
		$arrpk       = $this->obtenerArregloPk();
		$arrcampos   = $this->getAttributeNames();
		$cantcampo   = count($arrpk);
		$cantpk      = 0;
		foreach ($arrpk as $campopk =>$indicepk) {
			$cantpk++;
			foreach ($arrcampos as $regcampo => $indicecampo) {
				if($indicepk==$indicecampo){
					if($cantcampo==$cantpk){
						$restriccion[$cantpk][0] = $indicepk;
                    	$restriccion[$cantpk][1] = '=';
                    	$restriccion[$cantpk][2] = $this->$indicepk;
                    	$restriccion[$cantpk][3] = 2;
					}else{
						$restriccion[$cantpk][0] = $indicepk;
                    	$restriccion[$cantpk][1] = '=';
                    	$restriccion[$cantpk][2] = $this->$indicepk;
                    	$restriccion[$cantpk][3] = 0;
					}
				}
			}
		}
		
		return $this->buscarCampoRestriccion($restriccion);
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $cadenafiltro
	 * @return DaoGenerico|boolean
	 * @author Ing. Gerardo Cordero
	 */
	public function getObjetoDto($cadenafiltro){
		$resultado=$this->load($cadenafiltro);
		if($resultado){
			return $this;
		}
		else{
			return $resultado;
		}
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $campoorden
	 * @param unknown_type $tipoorden
	 * @return null
	 * @author Ing. Gerardo Cordero
	 */
	public function leerTodos($campoorden="",$tipoorden=0,$empresa="") {
		$cadena="";
		
		if($empresa != ""){
			$cadena = " where codemp='".$empresa."'"; 
		}
		
		if($campoorden != "")
		{
			$cadena .= " order by ".$campoorden;
			
			switch($tipoorden)
			{
				case 1: $cadena = $cadena." ASC";
						break;
						
				case 2: $cadena = $cadena." DESC";
						break;
						
				default: $cadena = $cadena." ASC";
			}
		}
		$resultado = ConexionBaseDatos::$conexionbd->Execute ( "select * from {$this->_table} ".$cadena );
		return $resultado;
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $campo
	 * @param unknown_type $valor
	 * @return Ambigous <boolean, unknown>
	 * @author Ing. Gerardo Cordero
	 */
	public function buscarCampo($campo, $valor) {
		$resultado = $this->Find ( "{$campo} like  '%{$valor}%' " );
		return $resultado;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $restricciones
	 * @param unknown_type $banderatabla
	 * @param unknown_type $tabla
	 * @author Ing. Gerardo Cordero
	 */
	public function buscarCampoRestriccion($restricciones,$banderatabla=false,$tabla='') {
		$modelo = "";
		
		//$conexionbd->debug=true; //Descomentar para que retorne el sql que se esta ejecutando
		foreach ( $restricciones as $restriccion ) {
			$campo = $restriccion [0];
			$evaluador = $restriccion [1];
			$valor = $restriccion [2];
			$andor = $restriccion [3];
						
			if($evaluador == 'ORDER BY'){
				$modelo .= $evaluador . " " . $campo . "  " . $valor;				
			}else{
				$modelo .= $campo . " " . $evaluador . " '" . $valor . "'";	
			}
			
			if ($andor == 0) {
				$modelo .= " AND ";
			} elseif ($andor == 1) {
				$modelo .= " OR ";
			} elseif ($andor == 2) {
				$modelo .= " ";
			}
		}
        if(!$banderatabla){
        	return $resultado = ConexionBaseDatos::$conexionbd->Execute ( "select * from {$this->_table} where " . $modelo );	
        }
		else{
			return $resultado = ConexionBaseDatos::$conexionbd->Execute ( "select * from {$tabla} where " . $modelo );
		}
		
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $codigo
	 * @param unknown_type $validarempresa
	 * @return number
	 * @author Ing. Gerardo Cordero
	 */
	public function buscarCodigo($codigo,$validarempresa=true,$longitud=0) {
		if($validarempresa){		
			$resultado = ConexionBaseDatos::$conexionbd->Execute ( "select max(" . $codigo . ")  as codigo from {$this->_table} where codemp='".$this->codemp."'");
		}
		else{
			$resultado = ConexionBaseDatos::$conexionbd->Execute ( "select max(" . $codigo . ")  as codigo from {$this->_table} ");
		}
		
		if ($resultado->fields ['codigo'] == '') {
			if ($longitud!=0) {
				return agregarUno(0, $longitud);
			}
			else{
				return 0;
			}
		} else {
			if ($longitud!=0) {
				return agregarUno($resultado->fields ['codigo'], $longitud);
			}
			else{
				return $resultado->fields ['codigo'];
			}
		}
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $cadenasql
	 * @author Ing. Gerardo Cordero
	 */
	public function buscarSql($cadenasql) {
		return $resultado = ConexionBaseDatos::$conexionbd->Execute ( $cadenasql );
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $cadenasql
	 * @author Ing. Gerardo Cordero
	 */
	public function ejecutarSql($cadenasql) {
		return $resultado = ConexionBaseDatos::$conexionbd->Execute ( $cadenasql );
	}
	
	/**
	 * Enter description here ...
	 * @author Ing. Gerardo Cordero
	 */
	public function obtenerArregloPk() {
		return ConexionBaseDatos::$conexionbd->MetaPrimaryKeys ($this->_table);
	}
	
	public function obtenerConexionBd() {
		return ConexionBaseDatos::$conexionbd;
	}
		
	/**
	 * Enter description here ...
	 * @param unknown_type $cadena
	 * @author Ing. Gerardo Cordero
	 */
	public function concatenarCadena($cadena) {
		$longitud = count($cadena);
		$tira = "";
		$i=0;
		$j=1;
		while($j < $longitud)
		{
			
			$tiraaux = ConexionBaseDatos::$conexionbd->Concat($cadena[$i],$cadena[$j]);
			if($tira != "")
			{
				$tira 	= ConexionBaseDatos::$conexionbd->Concat($tira,$tiraaux);
			}
			else
			{
				$tira = $tiraaux;
			}
			$i=$j+1;
			$j=$i+1;
		}
		if(($longitud%2)!=0) 
		{
			$tira 	= ConexionBaseDatos::$conexionbd->Concat($tira,$cadena[$i]);	
		}	
		return ConexionBaseDatos::$conexionbd->Concat($tira);
	}
	
	/****************************************************************************************************
	 *Funcion: buscarSqlLimitado
	 *Argumentos: $restricciones // Arreglo que contiene restricciones para los registros a devolver
	 *            $arrcampos     // Arreglo que contiene los campos a devolver durante la consulta
	 *            $numregsitros  // Cantidad que indica enl numero de registros a devolver
	 *            $reginicio     // Registro desde el que se comenzara a hacer la busqueda
	 *            $arreglo       // Arreglo para el control interno de la funcion
	 *            $tabla		 // Tabla auxiliar para realizar la busqueda en una distinta a la instanciada
	 * Descripcion: Funcion que se encarga de realizar un busqueda filtrada de registros, indicando el
	 *              numero de registros a devolver y desde cual se tomara en cuenta.
	 ****************************************************************************************************/
	public function buscarSqlLimitado($restricciones,$arrcampos,$numregistros,$reginicio,$arreglo=false,$tabla="")
	{
		$modelo = "";
		$campos = implode(',',$arrcampos);
		foreach ( $restricciones as $restriccion ) {
				$campo = $restriccion [0];
				$evaluador = $restriccion [1];
				$valor = $restriccion [2];
				$andor = $restriccion [3];
							
				if($evaluador == 'ORDER BY'){
					$modelo .= $evaluador . " " . $campo . "  " . $valor;				
				}else{
					$modelo .= $campo . " " . $evaluador . " '" . $valor . "'";	
				}
				
				if ($andor == 0) {
					$modelo .= " AND ";
				} elseif ($andor == 1) {
					$modelo .= " OR ";
				} elseif ($andor == 2) {
					$modelo .= " ";
				}
			}
		if(!empty($tabla))
		{
			$sentencia = "SELECT ".$campos." FROM {$tabla}";	
		}
		else
		{
			$sentencia = "SELECT ".$campos." FROM {$this->_table}";
		}	
		
			
		if(!empty($modelo))
		{
			$sentencia .= " WHERE ".$modelo;
		}
		return ConexionBaseDatos::$conexionbd->SelectLimit($sentencia,$numregistros,$reginicio,$arreglo);
	}
	
	/**
	 * Enter description here ...
	 * @param unknown_type $restricciones
	 * @param unknown_type $banderatabla
	 * @param unknown_type $tabla
	 * @author Ing. Gerardo Cordero
	 */
	public function borrarCampoRestriccion($restricciones,$banderatabla=false,$tabla='') {
		$modelo = "";
				
		foreach ( $restricciones as $restriccion ) {
			$campo = $restriccion [0];
			$evaluador = $restriccion [1];
			$valor = $restriccion [2];
			$andor = $restriccion [3];
						
			$modelo .= $campo . " " . $evaluador . " '" . $valor . "'";	
			
			if ($andor == 0) {
				$modelo .= " AND ";
			} elseif ($andor == 1) {
				$modelo .= " OR ";
			} elseif ($andor == 2) {
				$modelo .= " ";
			}
		}
		
        if(!$banderatabla){
        	return $resultado = ConexionBaseDatos::$conexionbd->Execute ( "delete from {$this->_table} where " . $modelo );
				
        }
		else{
			$resultado = ConexionBaseDatos::$conexionbd->Execute ( "delete from {$tabla} where " . $modelo );
		}
		
	}
	
	public function deleteDetalle($tabdetalle){
		$restriccion = array();
		$arrpk       = $this->obtenerArregloPk();
		$arrcampos   = $this->getAttributeNames();
		$cantcampo   = count($arrpk);
		$cantpk      = 0;
		foreach ($arrpk as $campopk =>$indicepk) {
			$cantpk++;
			foreach ($arrcampos as $regcampo => $indicecampo) {
				if($indicepk==$indicecampo){
					if($cantcampo==$cantpk){
						$restriccion[$cantpk][0] = $indicepk;
                    	$restriccion[$cantpk][1] = '=';
                    	$restriccion[$cantpk][2] = $this->$indicepk;
                    	$restriccion[$cantpk][3] = 2;
					}else{
						$restriccion[$cantpk][0] = $indicepk;
                    	$restriccion[$cantpk][1] = '=';
                    	$restriccion[$cantpk][2] = $this->$indicepk;
                    	$restriccion[$cantpk][3] = 0;
					}
				}
			}
		}
		
		return $this->borrarCampoRestriccion($restriccion,true,$tabdetalle);
		
	}

	public function setDataNull() {
		$arratributos = $this->GetAttributeNames();
		foreach ( $arratributos as $IndiceDAO ) {
			if ($this->$IndiceDAO === null || $this->$IndiceDAO === '') {
					$this->$IndiceDAO = null;
			}
		}
	}
}
?>