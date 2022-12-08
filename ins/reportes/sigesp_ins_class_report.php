<?php

class sigesp_ins_class_report
{
	var $obj="";
	var $io_sql;
	var $ds;
	var $siginc;
	var $con;

	function sigesp_ins_class_report()
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
		$this->ds=new class_datastore();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->rs_data="";
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////      Funciones del reporte de solicitudes de pago sin detalle asociado       ///////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function uf_select_solicitudpago()	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudpago
		//	           Access: public
		//  		Arguments: 
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que se obtiene las solicitudes de pago que no tienen detalle asociado
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 27/06/2007							Fecha de Ultima Modificaci�n:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		switch ($_SESSION["ls_gestor"])
		{
			case "MYSQL":
				$ls_cadena="CONCAT(rpc_beneficiario.nombene,' ',rpc_beneficiario.apebene)";
				break;
			case "POSTGRE":
				$ls_cadena="rpc_beneficiario.nombene||' '||rpc_beneficiario.apebene";
				break;
		}
		$ls_sql="SELECT cxp_solicitudes.numsol,cxp_solicitudes.fecemisol,cxp_solicitudes.monsol, ".
				"       (CASE tipproben WHEN 'P' THEN (SELECT rpc_proveedor.nompro ".
				"                                        FROM rpc_proveedor ".
				"                                       WHERE rpc_proveedor.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_proveedor.cod_pro=cxp_solicitudes.cod_pro) ".
				"                       WHEN 'B' THEN (SELECT ".$ls_cadena." ".
				"                                        FROM rpc_beneficiario ".
				"                                       WHERE rpc_beneficiario.codemp=cxp_solicitudes.codemp ".
				"                                         AND rpc_beneficiario.ced_bene=cxp_solicitudes.ced_bene) ". 
				"                       ELSE 'NINGUNO' END ) AS nombre ".
				"  FROM cxp_solicitudes ".	
				" WHERE cxp_solicitudes.codemp='".$this->ls_codemp."' ".
				"   AND cxp_solicitudes.numsol NOT IN (SELECT cxp_dt_solicitudes.numsol".
				"									     FROM cxp_dt_solicitudes".
				"										WHERE cxp_dt_solicitudes.codemp='".$this->ls_codemp."')";
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_solicitudpago ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} //fin  function uf_select_solicitudpago

	function uf_select_comprobantes($as_procede)	
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	         Function: uf_select_solicitudpago
		//	           Access: public
		//  		Arguments: as_procede  // Procedencia del documento
		//	         Returns : $lb_valido True si se creo el Data stored correctamente � False si no se creo
		//	      Description: Funci�n que obtiene los comprobantes dado el procede indicado
		//         Creado por: Ing. Luis Anibal Lang           
		//   Fecha de Cracion: 25/04/2008							Fecha de Ultima Modificaci�n:   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codemp,procede,comprobante,fecha,codban,ctaban,debhab,SUM(monto) AS monto".
				"  FROM scg_dt_cmp".
				" WHERE codemp='".$this->ls_codemp."'".
				" GROUP BY codemp,procede,comprobante,fecha,codban,ctaban,debhab ".
				" ORDER BY codemp,procede,comprobante,fecha,codban,ctaban,debhab ";
	    $this->rs_data=$this->io_sql->select($ls_sql);
		if($this->rs_data===false)
		{
			$this->io_msg->message("CLASE->Report M�TODO->uf_select_comprobantes ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if ($this->rs_data->EOF)
			{
				$lb_valido=false;
			}
		}
		return $lb_valido; 
	} //fin  function uf_select_comprobantes
} //fin  class sigesp_ins_class_report
?>
