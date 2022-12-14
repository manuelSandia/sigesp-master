// stm_aix("p1i2","p1i0",[0,"Opci?n 2    ","","",-1,-1,0,""]);
// stm_aix("p1i0","p0i0",[0,"Opci?n 1    ","","",-1,-1,0,"tablas.htm","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

//-----------------------//
// L?nea de separaci?n
// Para inlcuir l?neas de separaci?n entre las opciones, incoporar la siguiente instrucci?n, entre las opciones a separar
// stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);

//-----------------------//
// Men?es de Tercer Nivel
// Para hacer submen?es, incluir las siguientes l?neas de c?digo
// stm_bpx("pn","p1",[1,4,0,0,2,3,6,7]);   debajo de la l?nea de c?digo de la opci?n principal stm_aix("p0in","p0i0",[0," Opci?n Men? "]);
// luego, buscar la opci?n del men? bajo la cual se abrir? el submen? y agregar al final de esa l?nea de c?digo, los siguientes atributos:
// ,"","",-1,-1,0,"","_self","","","","",6,0,0,"imagebank/arrow.gif","imagebank/arrow.gif",7,7]);
// y justo debajo de esa l?nea agregar las siguientes l?neas de c?digo.
// stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel
// stm_aix("p3i0","p1i0",[0,"  Menu Item 1  ","","",-1,-1,0,"","_self","","","","",0]);
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// stm_aix("p3i2","p3i0",[0,"  Menu Item 3  "]);
// stm_aix("p3i3","p3i0",[0,"  Menu Item 4  "]);
// stm_aix("p3i4","p3i0",[0,"  Menu Item 5  "]);
// stm_ep();
// Luego cambiar las opciones "Menu Item 5", por el nombre de la opci?n que corresponda en cada caso.

//-----------------------//
// Hiperv?nculos
// Para incluir los enlaces correspondientes a cada opci?n del men?, se procede de la siguiente manera:
// En aquellas intrucciones, cuyo c?digo es similare a esto:
// stm_aix("p1i0","p0i0",[0,"Opci?n 1    ","","",-1,-1,0,"","_self","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
// agregar el enlace dentro de las comillas, justo delante de "_self"
// En aquellas intrucciones, cuyo c?digo es similare a esto:
// stm_aix("p3i1","p3i0",[0,"  Menu Item 2  "]);
// agregar al final de esta l?nea de c?digo, los siguientes par?metros:
// ,"","",-1,-1,0,"","_self","","","","",0]);
// y luego incorporar el enlace en las comillas que est? justo antes de "_self"

stm_bm(["menu08dd",430,"","../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,1,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);

// Men? Principal- Archivo
stm_ai("p0i0",[0," Procesos ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,1,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
// Archivo - Opciones de Segundo Nivel
stm_aix("p1i0","p0i0",[0,"Movimiento de Banco    ","","",-1,-1,0,"sigesp_scb_p_movbanco.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p1i0","p0i0",[0,"Actualizaci?n de Movimiento de Banco    ","","",-1,-1,0,"sigesp_scb_p_modifmovbco.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0,"Actualizaci?n de Movimiento de Banco    ","","",-1,-1,0,"sigesp_scb_p_modifnumdoc.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0,"Cancelaciones ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p1","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i4","p1i0",[0,"Programaci?n de Pagos ","","",-1,-1,0,"sigesp_scb_p_progpago.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Desprogramaci?n de Pagos ","","",-1,-1,0,"sigesp_scb_p_desprogpago.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Emisi?n de Cheques  ","","",-1,-1,0,"sigesp_scb_p_emision_chq.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Eliminaci?n de Cheques no Contabilizados ","","",-1,-1,0,"sigesp_scb_p_elimin_chq.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
//stm_aix("p1i4","p1i0",[0,"Pago Directo                               ","","",-1,-1,0,"sigesp_scb_p_pago_directo.php","_self"]);
//stm_aix("p1i4","p1i0",[0,"Orden de Pago Directa                      ","","",-1,-1,0,"sigesp_scb_p_orden_pago_directo.php","_self"]);
//stm_aix("p1i4","p1i0",[0,"Orden de Pago Directa con Compromiso Previo","","",-1,-1,0,"sigesp_scb_p_opd_causapaga.php","_self"]);
//stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0,"Transferencia Bancaria ","","",-1,-1,0,"sigesp_scb_p_carta_orden_mnd.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Eliminaci?n de Transferencia Bancaria no Contabilizada ","","",-1,-1,0,"sigesp_scb_p_elimin_carta_orden.php","_self"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
//stm_aix("p1i4","p1i0",[0,"Cr?ditos - Programaci?n de Pagos      ","","",-1,-1,0,"sigesp_scb_p_progpago_creditos.php","_self"]);
//stm_aix("p1i4","p1i0",[0,"Cr?ditos - Emisi?n de Cheques         ","","",-1,-1,0,"","_self"]);
//stm_aix("p1i4","p1i0",[0,"Liquidaci?n de Cr?ditos               ","","",-1,-1,0,"sigesp_scb_p_liquidacion_creditos.php","_self"]);
//stm_aix("p1i4","p1i0",[0,"Cobranza - Recuperaci?n de Cr?ditos   ","","",-1,-1,0,"sigesp_scb_p_recuperacion_creditos.php","_self"]);
//stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
//stm_aix("p1i0","p0i0",[0,"Pago de Anticipos (Caso Vialidad y Construcci?n SUCRE)  ","","",-1,-1,0,"sigesp_scb_p_pago_anticipo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Conciliaci?n Bancaria    ","","",-1,-1,0,"sigesp_scb_p_conciliacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
//stm_aix("p1i4","p1i0",[0,"Colocaciones    ","","",-1,-1,0,"sigesp_scb_p_movcol.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p1i0",[0,"Retenciones Iva ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0,"Crear Comprobante ","","",-1,-1,0,"sigesp_scb_p_cmp_retencion.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Modificar Comprobante ","","",-1,-1,0,"sigesp_scb_p_modcmpret.php","_self"]);
stm_ep();
stm_aix("p1i2","p1i0",[0,"Retenciones Municipales ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0,"Crear Comprobante ","","",-1,-1,0,"sigesp_scb_p_cmp_ret_mcp.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Modificar Comprobante ","","",-1,-1,0,"sigesp_scb_p_cmp_ret_mod.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Otros Comprobantes ","","",-1,-1,0,"sigesp_scb_p_cmp_ret_mun_otros.php","_self"]);
stm_ep();
stm_aix("p1i2","p1i0",[0,"Retenciones 1x1000 ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0,"Crear Comprobante ","","",-1,-1,0,"sigesp_scb_p_cmp_ret_1x1000.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Modificar Comprobante ","","",-1,-1,0,"sigesp_scb_p_modcmpret_1x1000.php","_self"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Transferencia entre Cuentas Bancarias    ","","",-1,-1,0,"sigesp_scb_p_transferencias.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i4","p1i0",[0,"Entrega de Cheques    ","","",-1,-1,0,"sigesp_scb_p_entregach.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p1i0",[0,"Reverso de Entrega de Cheques    ","","",-1,-1,0,"sigesp_scb_p_reverso_entregach.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i0","p0i0",[0,"Eliminaci?n Anulados Monto 0   ","","",-1,-1,0,"sigesp_scb_p_elimin_anulado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i0","p0i0",[0,"Procesar Documentos No Contabilizables   ","","",-1,-1,0,"sigesp_scb_p_procesar_no_contabilizables.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i2","p0i0",[0,"Integraci?n Bancaria   ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i4","p1i0",[0,"Notas de D?bito o Cr?dito ","","",-1,-1,0,"sigesp_scb_p_int_creditodebito.php","_self"]);
stm_aix("p1i4","p1i0",[0,"Cheques de Gerencia ","","",-1,-1,0,"sigesp_scb_p_int_cheqgerencia.php","_self"]);
stm_ep();
stm_aix("p1i4","p1i0",[0,"Control de Documentos    ","","",-1,-1,0,"sigesp_scb_p_controldocumentos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Men? Principal - Procesos
stm_aix("p0i3","p0i0",[0," Reportes "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Disponibilidad Financiera    ","","",-1,-1,0,"sigesp_scb_r_disponibilidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0," Listado de Documentos ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0," Documentos ","","",-1,-1,0,"sigesp_scb_r_documentos.php","_self"]);
stm_aix("p1i4","p1i0",[0," Conciliados ","","",-1,-1,0,"sigesp_scb_r_list_doc_conciliados.php","_self"]);
stm_aix("p4i0","p1i0",[0," Documentos en Tr?nsito ","","",-1,-1,0,"sigesp_scb_r_list_doc_transito.php","_self"]);
stm_aix("p4i0","p1i0",[0," Relaci?n Selectiva de Cheques / Transferencias Bancarias ","","",-1,-1,0,"sigesp_scb_r_selectivo_cheques_co.php","_self"]);
stm_ep();
stm_aix("p4i0","p1i0",[0," Listado de Ordenes de Pago Directo  ","","",-1,-1,0,"sigesp_scb_r_ordenpago.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Pagos    ","","",-1,-1,0,"sigesp_scb_r_pagos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Registros Contables   ","","",-1,-1,0,"sigesp_scb_r_reg_contables.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Conciliaci?n Bancaria   ","","",-1,-1,0,"sigesp_scb_r_conciliacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0," Estado de Cuenta      ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0," Formato 1 ","","",-1,-1,0,"sigesp_scb_r_estado_cta.php","_self"]);
stm_aix("p1i4","p1i0",[0," Resumido ","","",-1,-1,0,"sigesp_scb_r_estado_ctares.php","_self"]);
stm_ep();
stm_aix("p1i2","p0i0",[0," Otros  ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel
stm_aix("p1i4","p1i0",[0," Movimientos Presupuestarios por Banco ","","",-1,-1,0,"sigesp_scb_r_spg_x_banco.php","_self"]);
stm_aix("p1i4","p1i0",[0," Listado de Chequeras ","","",-1,-1,0,"sigesp_scb_r_listadochequeras.php","_self"]);
stm_aix("p1i4","p1i0",[0," Relaci?n Selectiva de Cheques ","","",-1,-1,0,"sigesp_scb_r_relacion_sel_chq.php","_self"]);
stm_aix("p1i4","p1i0",[0," Relaci?n Selectiva de Documentos(No incluye Cheques) ","","",-1,-1,0,"sigesp_scb_r_relacion_sel_docs.php","_self"]);
stm_ep();
stm_aix("p4i0","p1i0",[0," Cheques en Custodia  ","","",-1,-1,0,"sigesp_scb_r_chq_custodia_entregados.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Libro de Banco   ","","",-1,-1,0,"sigesp_scb_r_libro_banco.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Comprobante Retenci?n Municipal   ","","",-1,-1,0,"sigesp_scb_r_comp_ret_mun.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//agregado el 25/08/08
stm_aix("p4i0","p1i0",[0," Listado Cheques Caducados   ","","",-1,-1,0,"sigesp_scb_r_chq_caducados.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado Cheques por Anticipos o Amortizados  ","","",-1,-1,0,"sigesp_scb_r_pagos_anticipos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0," Comprobante de Retenci?n  ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel
stm_aix("p4i0","p0i0",[0," IVA","","",-1,-1,0,"sigesp_scb_r_retencionesiva.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0," I.S.R.L","","",-1,-1,0,"sigesp_scb_r_retencionesislr.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0," 1x1000 ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p4","p1",[1,2,0,0,2,3,0]);
stm_aix("p3i0","p1i0",[0," Relaci?n Mensual Impuesto 1x1000-Entes P?blicos","","",-1,-1,0,"sigesp_scb_r_retenciones_pub1x1000.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i0","p0i0",[0," Retenci?n 1x1000","","",-1,-1,0,"sigesp_scb_r_retenciones_1x1000.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();
/*stm_ep();
stm_aix("p1i2","p0i0",[0," Fondos en Avance  ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p4i0","p0i0",[0," Movimientos de Fondos Por Resumen de Pago","","",-1,-1,0,"sigesp_scb_r_fondos_resumen_pago.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p0i0",[0," Movimientos de Fondos de Orden de Pago   ","","",-1,-1,0,"sigesp_scb_r_fondos_orden_pago.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);*/
stm_ep();
stm_aix("p4i0","p1i0",[0," Declaracion de Salario y Otras Remuneraciones    ","","",-1,-1,0,"sigesp_scb_r_declaracionxml.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Colocaciones    ","","",-1,-1,0,"sigesp_scb_r_colocaciones.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p4i0","p1i0",[0," Listado de Ubicacion de Documentos    ","","",-1,-1,0,"sigesp_scb_r_listadoubicaciondocumentos.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();



stm_aix("p0i3","p0i0",[0," Configuraci?n "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Formato N? Orden de Pago    ","","",-1,-1,0,"sigesp_scb_config.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0," Medidas del Cheque Voucher ","","",-1,-1,0,"sigesp_scb_p_conf_voucher.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

stm_aix("p1i2","p0i0",[0," Otras Configuraciones del Cheque Voucher ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel

stm_aix("p1i4","p1i0",[0,"   Banco Banesco       ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_banesco.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco BOD           ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_bod.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Bancoro       ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_bancoro.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Banfoandes    ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_banfoandes.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Caron?        ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_caroni.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Bicentenario  ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_central.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Del Tesoro    ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_tesoro.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Federal       ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_federal.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Industrial    ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_industrial.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Mercantil     ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_mercantil.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Provincial    ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_provincial.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Venezuela     ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_venezuela.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Sofitasa      ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_sofitasa.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Corbanca      ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_corbanca.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Banorte      ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_banorte.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Casa Propia      ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_casa_propia.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Banplus       ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_banplus.php","_self"]);
stm_aix("p1i4","p1i0",[0,"   Banco Agricola  ","","",-1,-1,0,"sigesp_scb_p_conf_voucher_agricola.php","_self"]);
stm_ep();


stm_aix("p1i2","p0i0",[0," Selecci?n Formato de Transferencia Bancaria ","","",-1,-1,0,"sigesp_scb_p_conf_select_cartaorden.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0," Configuraci?n Formato de Transferencia Bancaria ","","",-1,-1,0,"sigesp_scb_p_conf_cartaorden.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i2","p0i0",[0," Validaci?n Disponibilidad Financiera ","","",-1,-1,0,"sigesp_scb_p_conf_disponibilidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_ep();
stm_ep();

stm_aix("p0i3","p0i0",[0," Mantenimiento "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p4i0","p1i0",[0," Movimientos Descuadrados    ","","",-1,-1,0,"sigesp_scb_p_mant_descuadrados.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_ep();
stm_ep();

stm_aix("p4i0","p1i0",[0," Ir a M?dulos  ","","",-1,-1,0,"../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

stm_em();
