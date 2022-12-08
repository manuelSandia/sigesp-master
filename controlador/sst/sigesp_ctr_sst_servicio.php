<?php
$dirctrsstser = "";
$dirctrsstser = dirname(__FILE__);
$dirctrsstser = str_replace("\\","/",$dirctrsstser);
$dirctrsstser = str_replace("/controlador/sst","",$dirctrsstser);
require_once ($dirctrsstser."/base/librerias/php/general/sigesp_lib_serviciogenerico.php");
require_once ($dirctrsstser."/shared/class_folder/sigesp_release.php");
require_once ($dirctrsstser."/modelo/sst/dao/sigesp_mod_sst_dao_tramite.php");
require_once ($dirctrsstser."/modelo/sst/dao/sigesp_mod_sst_dao_asignaciontramite.php");

class ServicioSst extends ServicioGenerico{
	private $tramitedao;
	private $asignaciontramitedao;
	
	function __construct($tabla='') {
		parent::__construct ( $tabla );
	}
	
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Registro de Tramites(INICIO)  
	* @Descripcion: Grupos de metodos implementados para gestionar el registro,modificacion
	* y eliminacion de los tramistes
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	public function buscarNumtramite($codemp) {
		$this->tramitedao = new TramiteDao();
		$datatramite = $this->tramitedao->getNumeroTramite($codemp);
		unset($this->tramitedao);
		return $datatramite;
	}
	
	public function buscarTramiteCatalogo($codemp,$codusu,$numtramite,$tipoprop,$codprovbene,$numdoc,$fecdescat,$fechascat){
		$this->tramitedao = new TramiteDao();
		$datatramite = $this->tramitedao->getTramitesCatalogo($codemp,$codusu,$numtramite,$tipoprop,$codprovbene,$numdoc,$fecdescat,$fechascat);
		unset($this->tramitedao);
		return $datatramite;
	}
	
	public function buscarTramiteCatalogoCon($codemp,$numtramite,$tipoprop,$codprovbene,$numdoc,$fecdescat,$fechascat){
		$this->tramitedao = new TramiteDao();
		$datatramite = $this->tramitedao->getTramitesCatalogoCon($codemp,$numtramite,$tipoprop,$codprovbene,$numdoc,$fecdescat,$fechascat);
		unset($this->tramitedao);
		return $datatramite;
	}
	
	public function registrarTramite($codemp,$codusu,$arrjson) {
		$this->tramitedao            = new TramiteDao();
		$this->asignaciontramitedao  = new AsignacionTramiteDao();
 		$resultado = $this->tramitedao->guardarTramite($codemp,$codusu,$arrjson);
		$arrcadres = explode(",",$resultado);
		if($arrcadres[0]!=0){
			ServicioSst::iniTransaccion ();
			$resultado = $this->asignaciontramitedao->guardarAsignacionInicial($codemp,$codusu,"ASIGNACION INICIAL",$arrjson);
			if (ServicioSst::comTransaccion ()) {
				$numrespuesta=count($arrcadres);
				if($numrespuesta==2){
					$resultado=$arrcadres[0]."|".$arrcadres[1];
				}
				else{
					$resultado=$arrcadres[0];
				}
			}
			else{
				$resultado=0;
			}
		}
				
		unset($this->asignaciontramitedao);
		unset($this->tramitedao);
		return $resultado;
	}
	
	public function modificarTramite($codemp,$arrjson) {
		$this->tramitedao = new TramiteDao();
		ServicioSst::iniTransaccion ();
		$resultado=$this->tramitedao->modificarTramite($codemp,$arrjson);
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->tramitedao);
		return $resultado;
	}
	
	public function eliminarTramite($codemp,$arrjson){
		$this->tramitedao = new TramiteDao();
		$this->asignaciontramitedao  = new AsignacionTramiteDao();
		ServicioSst::iniTransaccion ();
		if($this->tramitedao->verificarUltimo($codemp,$arrjson->numtramite)){
			if($this->tramitedao->validarEliminarTramite($codemp,$arrjson->numtramite)){
				$resultado = $this->asignaciontramitedao->eliminarAsignacion($codemp,$arrjson->numtramite);
				if($resultado){
					$resultado = $this->tramitedao->eliminarRegistroTramite($codemp,$arrjson->numtramite);
					if($resultado){
						$resultado = 4;
					}
					else{
						$resultado = 3;
					}
				}
				else{
					$resultado = 3;
				}
			}
			else{
				$resultado = 3;
			}	
		}
		else{
			$resultado = 5;
		}
		
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->asignaciontramitedao);
		unset($this->tramitedao);
		return $resultado;
	}
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Registro de Tramites(FIN)  
	* @Descripcion: Grupos de metodos implementados para gestionar el registro,modificacion
	* y eliminacion de los tramistes
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Consulta de Tramites(INICIO)  
	* @Descripcion: Grupos de metodos de apoyo a la consulta de tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	function obtenerCaberceraTramite($codemp,$numtramite){
		$this->tramitedao = new TramiteDao();
		$datacabecera = $this->tramitedao->getCabeceraTramite($codemp,$numtramite);
		unset($this->tramitedao);
		return $datacabecera;
	}
	
	function obtenerDetalleTramite($codemp,$numtramite){
		$this->tramitedao = new TramiteDao();
		$datadetalle = $this->tramitedao->getDetalleTramite($codemp,$numtramite);
		unset($this->tramitedao);
		return $datadetalle;
	}
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Consulta de Tramites(INICIO)  
	* @Descripcion: Grupos de metodos de apoyo a la consulta de tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	
	
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Recepcion de Tramites(INICIO)  
	* @Descripcion: Grupos de metodos implementados para gestionar la recepcion de 
	* los tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	public function recibirTramite($codemp,$arrjson){
		$this->asignaciontramitedao  = new AsignacionTramiteDao();
		ServicioSst::iniTransaccion ();
		$resultado = $this->asignaciontramitedao->recibirAsignacion($codemp,$arrjson);
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->asignaciontramitedao);
		return $resultado;                   
	}
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Recepcion de Tramites(FIN)  
	* @Descripcion: Grupos de metodos implementados para gestionar la recepcion de 
	* los tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	
	
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Asignacion de Tramites(INICIO)  
	* @Descripcion: Grupos de metodos implementados para gestionar la asigancion de 
	* los tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	public function asignarTramite($codemp,$codusu,$arrjson){
		$this->tramitedao               = new TramiteDao();
		$this->asignaciontramitedao     = new AsignacionTramiteDao();
				
		ServicioSst::iniTransaccion ();
		//guardando asigancion
		$resultado=$this->asignaciontramitedao->guardarAsigancion($codemp,$codusu,$arrjson);
		if($resultado!=0){
			//actualizando el usuario del tramite
			$resultado=$this->tramitedao->actualizarUsuarioTramite($codemp,$arrjson->codusurec,$arrjson);
			if($resultado!=0){
				//actualizando el estado a la asigancion anterior se coloca procesada
				$resultado=$this->asignaciontramitedao->procesarAsignacion($codemp,$arrjson);
			}
		}
		
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		
		unset($this->asignaciontramitedao);
		unset($this->tramitedao);
		return $resultado;
	}
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Asigancion de Tramites(FIN)  
	* @Descripcion: Grupos de metodos implementados para gestionar la asignacion de 
	* los tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	
	
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Cierre de Tramites(INICIO)  
	* @Descripcion: Grupos de metodos implementados para gestionar el cierre de 
	* los tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	public function cerrarTramite($codemp,$codusu,$arrjson){
		$this->tramitedao = new TramiteDao();
		ServicioSst::iniTransaccion ();
		$resultado=$this->tramitedao->registrarCierreTramite($codemp,$codusu,$arrjson);
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->tramitedao);
		return $resultado;
	}
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Cierre de Tramites(FIN)  
	* @Descripcion: Grupos de metodos implementados para gestionar el cierre de 
	* los tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Reverso de Operaciones(INICIO)  
	* @Descripcion: Grupos de metodos implementados para gestionar el reverso de recepciones 
	* asignaciones y cierres de tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	public function buscarRecepciones($codemp,$codusu,$arrjson){
		$this->asignaciontramitedao = new AsignacionTramiteDao();
		
		ServicioSst::iniTransaccion ();
		$resultado = $this->asignaciontramitedao->buscarAsigancionRecibida($codemp,$codusu,$arrjson);
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->asignaciontramitedao);
		return $resultado;
	}
	
	public function buscarAsignaciones($codemp,$codusu,$arrjson){
		$this->asignaciontramitedao = new AsignacionTramiteDao();
		
		ServicioSst::iniTransaccion ();
		$resultado = $this->asignaciontramitedao->buscarAsigancionEnviada($codemp,$codusu,$arrjson);
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->asignaciontramitedao);
		return $resultado;
	}
	
	public function buscarCierres($codemp,$codusu,$arrjson){
		$this->tramitedao = new TramiteDao();
		
		ServicioSst::iniTransaccion ();
		$resultado = $this->tramitedao->buscarTramitesCerrados($codemp,$codusu,$arrjson);
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->tramitedao);
		return $resultado;
	}
	
	public function reversarRecepcion($codemp,$arrjson){
		$this->asignaciontramitedao = new AsignacionTramiteDao();
		ServicioSst::iniTransaccion ();
		foreach ($arrjson->asignacion as $asignacion) {
			$resultado = $this->asignaciontramitedao->reiniciarRecepcion($codemp,$asignacion);
			if($resultado==0){
				break;
			}
		}
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->asignaciontramitedao);
		return $resultado;
	}
	
	public function reversarAsignacion($codemp,$arrjson){
		$this->asignaciontramitedao = new AsignacionTramiteDao();
		ServicioSst::iniTransaccion ();
		foreach ($arrjson->asignacion as $asignacion) {
			$numasi = QuitarUnoZ($asignacion->numasi,15);
			if($numasi!="000000000000000"){
				$resultado = $this->asignaciontramitedao->actualizarEstadoAsignacion($codemp,$asignacion->numtramite,$numasi,"S");
				if($resultado!=0){
					$resultado = $this->asignaciontramitedao->eliminarAsignacion($codemp,$asignacion->numtramite,$asignacion->numasi);
					if($resultado){
						$resultado = 1;
					}
					else{
						$resultado = 0;
						break;
					}
				}
			}
		}
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->asignaciontramitedao);
		return $resultado;
	}
	
	public function reversarCierre($codemp,$arrjson){
		$this->tramitedao = new TramiteDao();
		ServicioSst::iniTransaccion ();
		foreach ($arrjson->asignacion as $asignacion) {
			$resultado = $this->tramitedao->reversarCierreTramite($codemp,$asignacion);
			if($resultado!=0){
				break;
			}
		}
		if (!(ServicioSst::comTransaccion ())) {
			$resultado=0;
		}
		unset($this->tramitedao);
		return $resultado;
	}
	/***********************************************************************************
	* @Seccion: Metodos del Proceso Reverso de Operaciones(FIN)  
	* @Descripcion: Grupos de metodos implementados para gestionar el reverso de recepciones 
	* asignaciones y cierres de tramites
	* @Autor: Ing. Gerardo Cordero
	************************************************************************************/
	
	public function buscarTramites($codemp,$codusu){
		$this->asignaciontramitedao = new AsignacionTramiteDao();
		$resultado = $this->asignaciontramitedao->buscarAsignacionesUsuario($codemp,$codusu);
		unset($this->asignaciontramitedao);
		return $resultado;
	}
	
	
	
	/***********************************************************************************
	* @Seccion: Metodo para el Control del Release de SST
	* @Descripcion: Control de Release de SST
	* @Autor: Ing. Arnaldo Suárez
	************************************************************************************/
	
	/*******************************************************************************************************
	 * Funcion: verificarReleaseSst
	 * Descripcion:  Funcion que se encarga de verificar si se necesita ejecutar algun release antes de 
	 *               utilizar el modulo de SEP.
	 * Argumentos: $gestorbd   : Nombre del Gestor de la Base de Datos
	 *             $desrelease : Nombre del Release a ejecutar, en caso de que devuelva false 
	 * Fecha de Creacion : 02/11/2009
	 * Return : Boolean
	 * *****************************************************************************************************/
	public function verificarReleaseSst($gestorbd,&$desrelease){
		$release= new sigesp_release();
		$valido = true;
		$control=0;
		$i=0;
		do{
			if($valido){
				$i++; // 1
				//aqui se implementara el release....
				//$valido=$release->io_function_db->uf_select_column('sigesp_empresa','estparsindis');
				//$desrelease="Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_61";
			}
		}
		while(($valido)&&($i<$control));
		unset($release);
		return $valido;
	}	

}

?>