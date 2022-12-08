<?php
$gi_total=15;//Incrementado por OFIMATICA DE VENEZUELA, C.A. el 31-05-2011
$arbol["sistema"][1]="INS";
$arbol["nivel"][1]=0;
$arbol["nombre_logico"][1]="Procesos";
$arbol["nombre_fisico"][1]="";
$arbol["id"][1]="001";
$arbol["padre"][1]="000";
$arbol["numero_hijos"][1]=1;

$arbol["sistema"][2]="INS";
$arbol["nivel"][2]=1;
$arbol["nombre_logico"][2]="Mantenimiento";
$arbol["nombre_fisico"][2]="";
$arbol["id"][2]="002";
$arbol["padre"][2]="001";
$arbol["numero_hijos"][2]=4;

$arbol["sistema"][3]="INS";
$arbol["nivel"][3]=2;
$arbol["nombre_logico"][3]="Contabilidad";
$arbol["nombre_fisico"][3]="sigesp_ins_p_contabilidad.php";
$arbol["id"][3]="003";
$arbol["padre"][3]="002";
$arbol["numero_hijos"][3]=0;

$arbol["sistema"][4]="INS";
$arbol["nivel"][4]=2;
$arbol["nombre_logico"][4]="Presupuesto de Gasto";
$arbol["nombre_fisico"][4]="sigesp_ins_p_presupuesto_gasto.php";
$arbol["id"][4]="004";
$arbol["padre"][4]="002";
$arbol["numero_hijos"][4]=0;

$arbol["sistema"][5]="INS";
$arbol["nivel"][5]=2;
$arbol["nombre_logico"][5]="Release";
$arbol["nombre_fisico"][5]="sigesp_ins_p_release.php";
$arbol["id"][5]="005";
$arbol["padre"][5]="002";
$arbol["numero_hijos"][5]=0;

$arbol["sistema"][6]="INS";
$arbol["nivel"][6]=2;
$arbol["nombre_logico"][6]="Reprocesar Comprobantes Descuadrados";
$arbol["nombre_fisico"][6]="sigesp_ins_p_reprocesar_comprobantes.php";
$arbol["id"][6]="006";
$arbol["padre"][6]="002";
$arbol["numero_hijos"][6]=0;

$arbol["sistema"][7]="INS";
$arbol["nivel"][7]=2;
$arbol["nombre_logico"][7]="Presupuesto de Ingreso";
$arbol["nombre_fisico"][7]="sigesp_ins_p_presupuesto_ingreso.php";
$arbol["id"][7]="007";
$arbol["padre"][7]="002";
$arbol["numero_hijos"][7]=0;

$arbol["sistema"][8]="INS";
$arbol["nivel"][8]=2;
$arbol["nombre_logico"][8]="Inventario";
$arbol["nombre_fisico"][8]="sigesp_ins_p_reprocesar_existencias.php";
$arbol["id"][8]="008";
$arbol["padre"][8]="002";
$arbol["numero_hijos"][8]=0;

$arbol["sistema"][9]="INS";
$arbol["nivel"][9]=2;
$arbol["nombre_logico"][9]="Reprocesar Fecha de comprobantes";
$arbol["nombre_fisico"][9]="sigesp_ins_p_reprocesar_fechacomprobantes.php";
$arbol["id"][9]="009";
$arbol["padre"][9]="002";
$arbol["numero_hijos"][9]=0;

$arbol["sistema"][10]="INS";
$arbol["nivel"][10]=2;
$arbol["nombre_logico"][10]="Solicitudes de Pago sin Detalle";
$arbol["nombre_fisico"][10]="sigesp_ins_r_solicitudpago.php";
$arbol["id"][10]="010";
$arbol["padre"][10]="002";
$arbol["numero_hijos"][10]=0;

$arbol["sistema"][11]="INS";
$arbol["nivel"][11]=2;
$arbol["nombre_logico"][11]="Cambios de Alicuota del IVA ";
$arbol["nombre_fisico"][11]="sigesp_ins_p_cambioiva.php";
$arbol["id"][11]="011";
$arbol["padre"][11]="002";
$arbol["numero_hijos"][11]=0;

$arbol["sistema"][12]="INS";
$arbol["nivel"][12]=2;
$arbol["nombre_logico"][12]="Consolidación Contable";
$arbol["nombre_fisico"][12]="sigesp_ins_p_consolidacion_contable.php";
$arbol["id"][12]="012";
$arbol["padre"][12]="002";
$arbol["numero_hijos"][12]=0;

$arbol["sistema"][13]="INS";
$arbol["nivel"][13]=2;
$arbol["nombre_logico"][13]="Traspaso de Conceptos y Aportes";
$arbol["nombre_fisico"][13]="sigesp_ins_p_traspaso_conceptos_aportes.php";
$arbol["id"][13]="013";
$arbol["padre"][13]="002";
$arbol["numero_hijos"][13]=0;

$arbol["sistema"][14]="INS";
$arbol["nivel"][14]=2;
$arbol["nombre_logico"][14]="Traspaso de Movimientos Bancarios";
$arbol["nombre_fisico"][14]="sigesp_ins_p_traspaso_movbancarios.php";
$arbol["id"][14]="014";
$arbol["padre"][14]="002";
$arbol["numero_hijos"][14]=0;

/*AGREGADO POR OFIMATICA DE VENEZUELA, C.A.31-05-2011-PROCESO QUE PERMITE LA INCLUSIÓN O RETIRO DE DOCUMENTOS DEL LIBRO DE COMPRA*/
$arbol["sistema"][15]="INS";
$arbol["nivel"][15]=2;
$arbol["nombre_logico"][15]="Mantenimiento de Documentos a Libro de Compra";
$arbol["nombre_fisico"][15]="sigesp_ins_p_docs_a_libcompra.php";
$arbol["id"][15]="015";
$arbol["padre"][15]="002";
$arbol["numero_hijos"][15]=0;
/******************************************* OFIMATICA DE VENEZUELA, C.A.- *********************************************/
?>