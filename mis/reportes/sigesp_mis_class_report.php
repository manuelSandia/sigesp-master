<?php 
/**
 * @author gerardoc
 * @desc Clase que se encarga de buscar la informacion de los reportes del modulo integrador
 */
class sigesp_mis_class_report{
	
	/**
	 * @author Ing. Gerardo Cordero
	 * @desc Funcion constructora de la clase
	 */
	public function sigesp_mis_class_report(){
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
    }
    
	
	/**
	 * @author Ing. Gerardo Cordero
	 * @desc Funcion que busca en la base de datos los documentod contabilizados
	 *       por un usuario, segun los parametros espesificados
	 * @param string $modulo - modulo por el cual se desea filtrar los documentos
	 * @param string $concepto - concepto por el cual se desea filtrar los documentos
	 * @param string $order - orden en el cual se presentara la informacion
	 * @return resulset - arreglo con los datos obtenidos en el sql
	 */
	public function uf_select_documentos_contabilizados($codusu,$fecdes,$fechas,$modulo,$concepto,$order){
		$ls_filtro = "AND cmp.fecha  between '".$this->io_funciones->uf_convertirdatetobd($fecdes)."' AND '".$this->io_funciones->uf_convertirdatetobd($fechas)."' ";
		
		if ($codusu!='') {
			$ls_filtro .= "AND cmp.codusu like '%".$codusu."%' ";
		}
		
		if($modulo!="NSD"){
			$ls_filtro .= "AND cmp.procede like '".$modulo."%' ";	
		}
		
		if($concepto!=""){
			$ls_filtro .= "AND cmp.descripcion like '%".$concepto."%'";
		}
	
		$ls_sql="SELECT cmp.comprobante AS numdoc,cmp.total as monto,cmp.fecha,cmp.procede,pro.desproc,cmp.codusu
				 FROM sigesp_cmp cmp
				 INNER JOIN
				 sigesp_procedencias pro ON cmp.procede=pro.procede
				 WHERE cmp.codemp = '".$_SESSION["la_empresa"]["codemp"]."' 
				       ".$ls_filtro."  
				 ORDER BY ".$order; 
				
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false){
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_documentos_contabilizados ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
				
		return $rs_data;
	}
}
?>