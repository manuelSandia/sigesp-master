<?php
class sigesp_scg_c_cierresemestral{
	var $io_sql;
	var $io_msg;
	var $io_fun;
	var $ls_codemp;
	
	function sigesp_scg_c_cierresemestral(){
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		
		$io_siginc    		= new sigesp_include();
		$io_con       		= $io_siginc->uf_conectar();
		$this->io_sql       = new class_sql($io_con);
		$this->io_fun       = new class_funciones();
        $this->ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		$this->io_seguridad = new sigesp_c_seguridad($ai_semestre);
	}
	
	function uf_update_estatus_comprobante($ai_semestre,$ai_reverso,$aa_seguridad){
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_estatus_comprobante
		//		   Access: private
		//	    Arguments: as_numrecdoc // Número de Recepcion de Documentos
		//                 as_codtipdoc // Codigo de Tipo de Documento
		//				   as_cedbene   // Cedula de Beneficiario
		//				   as_codpro    // Código Proveedor
		//				   ls_estatus   // Estatus en que se desea colocar la R.D.
		//                 aa_seguridad // Arreglo que contiene informacion de seguridad
		// 	      Returns: lb_existe True si existe ó False si no existe
		//	  Description: Funcion que actualiza el estatus de la Recepcion de Documentos
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 25/04/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if ($ai_reverso!=1) {
			if($ai_semestre==1){
				$ls_sql="UPDATE sigesp_empresa ".
					"   SET m01 = 0,m02 = 0,m03 = 0,m04 = 0,m05 = 0,m06 = 0,ciesem1='1' ".
					" WHERE codemp = '".$this->ls_codemp."'";
			}
			else{
				$ls_sql="UPDATE sigesp_empresa ".
					"   SET m07 = 0,m08 = 0,m09 = 0,m10 = 0,m11 = 0,m12 = 0,ciesem2='1' ".
					" WHERE codemp = '".$this->ls_codemp."'";
			}
		}
		else{
			if($ai_semestre==1){
				$ls_sql="UPDATE sigesp_empresa ".
					"   SET ciesem1='0' ".
					" WHERE codemp = '".$this->ls_codemp."'";
			}
			else{
				$ls_sql="UPDATE sigesp_empresa ".
					"   SET ciesem2='0' ".
					" WHERE codemp = '".$this->ls_codemp."'";
			}
		}
		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Interfase MÉTODO->uf_update_estatus_comprobante ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$this->io_sql->rollback();
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			if ($ai_semestre==1) {
				if ($ai_reverso!=1) {
					$ls_descripcion ="Realizo cierre semestral, actualizando los estatus de los meses de enero a junio a cerrado";;
				}
				else{
					$ls_descripcion ="Realizo reverso de cierre semestral, actualizando los estatus de los meses de enero a junio a abierto";
				}
			}
			else{
				if ($ai_reverso!=1) {
					$ls_descripcion ="Realizo segundo cierre semestral";
				}
				else{
					$ls_descripcion ="Realizo reverso de segundo cierre semestral";
				}
			}
			
			
			$ls_descripcion .="</b> Asociado a la Empresa <b>".$this->ls_codemp."<b>";
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}
}
?>