<?php
require_once ("../../base/librerias/php/general/sigesp_lib_daogenerico.php");

class plancuentapatrimonialDao extends DaoGenerico
{
	
	var $_table='sigesp_plan_unico';
	//$this->_table='sigesp_plan_unico';
	
	public function guardar()
	{
		$lb_valido = $int_scg->uf_insert_plan_unico_cuenta($ls_cuenta,$ls_dencta,$ls_status);
		if ($lb_valido)
		    {
		   	  $lb_existe = $int_scg->uf_select_plan_unico_cuenta($ls_cuenta,$ls_dencta);
			  if ($lb_existe)
				 {
				   $io_msg->message("Registro Actualizado !!!");
				   $ls_evento="UPDATE";
				   $ls_descripcion="Actualizo la cuenta de plan unico $ls_cuenta, con denominacion $ls_dencta";
				 }
				else
				 {
				   $io_msg->message("Registro Incluido !!!");
				   $ls_evento="INSERT";
				   $ls_descripcion="Inserto la cuenta de plan unico $ls_cuenta, con denominacion $ls_dencta";
				 }
			  $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas,$ls_descripcion);
			  $ls_cuenta = $ls_dencta = "";
		    }
		 else
		    {
			  $io_msg->message("".$int_scg->is_msg_error);
		    }
	}
	
	
	
	
	public function Modificar()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->Replace();
		$conexionbd->CompleteTrans();
		return "1";
		
	}
	public function Incluir()
	{
		try
		{
			global $conexionbd;
			$conexionbd->StartTrans();
			$this->save();
			$conexionbd->CompleteTrans();
			return "1";
		}
		catch (Exception $e) 
		{
			//mandar a un archivo de logs con los eventos fallidos fallidos	
    		return "0";
		}


	}
	public function Eliminar()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->delete();
		$conexionbd->CompleteTrans();
		return "1";

	}
	
	public function BuscarCodigo()
	{
		global $conexionbd;
		$Rs = $conexionbd->Execute("select (sc_cuenta) from {$this->_table}"); 
		var_dump($Rs->fields['sc_cuenta']); 
		if($Rs->fields['sc_cuenta']=='')
		{
			return "0001"; 
		}
		else
		{	
			$dato = $Rs->fields['sc_cuenta'];
			return $dato;
		}
	}
	
	public function leerTodos($campoorden="",$tipoorden=0) {
		$cadena="";
		if($campoorden != "")
		{
			$cadena = " order by ".$campoorden;
			
			switch($tipoorden)
			{
				case 1: $cadena = $cadena." ASC";
						break;
						
				case 2: $cadena = $cadena." DESC";
						break;
						
				default: $cadena = $cadena." ASC";
			}
		}
		$resultado = $this->buscarSql("select * from {$this->_table} ".$cadena );
		return $resultado;
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $conexionbd;
		$Rs = $this->Find("{$cr} like  '%{$cad}%' ");
		return $Rs;
	}
	
	public function Buscar($ls_tipctares,$ls_resultado,$ls_scgcta,$ls_denscgcta)
	{
		$ls_sqlaux = "";
	 		if ($ls_tipctares==3 || $ls_tipctares==4)
	    	{
		  		$ls_sqlaux = " AND status = 'S'";
			}
	 		$ls_sql =" SELECT TRIM(sc_cuenta) as sc_cuenta,status,denominacion 
	              FROM scg_cuentas
		         WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."'
				   AND sc_cuenta like '".$ls_resultado."%'
				   AND sc_cuenta like '".$ls_scgcta."%'
				   AND UPPER(denominacion) like '%".strtoupper($ls_denscgcta)."%' $ls_sqlaux
				 ORDER BY sc_cuenta ASC";
	}
	
}
?>