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

stm_bm(["menu08dd",430,"","../../../shared/imagebank/blank.gif",0,"","",0,0,0,0,1000,1,0,0,"","100%",0],this);
stm_bp("p0",[0,4,0,0,1,3,0,0,100,"",-2,"",-2,90,0,0,"#000000","#e6e6e6","",3,0,0,"#000000"]);


stm_ai("p0i0",[0," Definiciones ","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#F7F7F7",0,"#f4f4f4",0,"","",3,3,0,0,"#fffff7","#000000","#909090","#909090","8pt 'Tahoma','Arial'","8pt 'Tahoma','Arial'",0,0]);
stm_bp("p1",[1,4,0,0,2,3,6,0,100,"progid:DXImageTransform.Microsoft.Fade(overlap=.5,enabled=0,Duration=0.10)",-2,"",-2,100,2,3,"#999999","#ffffff","",3,1,1,"#F7F7F7"]);
stm_aix("p1i0","p0i0",[0,"M?todo de Rotulaci?n    ","","",-1,-1,0,"sigesp_saf_d_rotulacion.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i2","p0i0",[0,"Condici?n del Activo    ","","",-1,-1,0,"sigesp_saf_d_condicion.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i4","p0i0",[0,"Causas de Movimiento    ","","",-1,-1,0,"sigesp_saf_d_movimientos.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p1i6","p0i0",[0,"Activos                 ","","",-1,-1,0,"sigesp_saf_d_activossigecof.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p1i6","p0i0",[0,"Activos SIGECOF         ","","",-1,-1,0,"sigesp_saf_d_activossigecof.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
//stm_aix("p1i8","p0i0",[0,"Cat?logos de Grupos, Subgrupos y Secciones    ","","",-1,-1,0,"sigesp_saf_d_grupo.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i8","p0i0",[0,"Cat?logo SIGECOF        ","","",-1,-1,0,"sigesp_saf_d_catalogo.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i8","p0i0",[0,"Categoria CGR        ","","",-1,-1,0,"sigesp_saf_d_grupo.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i8","p0i0",[0,"Configuracion de Activos        ","","",-1,-1,0,"sigesp_saf_d_configuracion.php","","","","","",0,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();



// Men? Principal - Procesos
stm_aix("p0i3","p0i0",[0," Procesos "]);
stm_bpx("p5","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p1i0","p1i0",[0,"Movimientos ","","",-1,-1,0,"","","","","","",6,0,0,"../../../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p1i0","p1i0",[0," Incorporaciones    ","","",-1,-1,0,"sigesp_saf_p_incorporaciones.php","_self"]);
stm_aix("p1i0","p1i0",[0," Desincorporaciones ","","",-1,-1,0,"sigesp_saf_p_desincorporaciones.php","_self"]);
stm_aix("p1i0","p1i0",[0," Reasignaciones     ","","",-1,-1,0,"sigesp_saf_p_reasignaciones.php","_self"]);
stm_aix("p1i0","p1i0",[0," Modificaciones     ","","",-1,-1,0,"sigesp_saf_p_modificaciones.php","_self"]);
stm_aix("p1i0","p1i0",[0," Incorporaciones por Lote ","","",-1,-1,0,"sigesp_saf_p_incorporacioneslote.php","_self"]);
stm_aix("p1i0","p1i0",[0," Incorporaciones General ","","",-1,-1,0,"sigesp_saf_p_incorporacioneslotegeneral.php","_self"]);
stm_ep();
//stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
//stm_aix("p1i2","p1i0",[0,"Traslado de Activos ","","",-1,-1,0,"sigesp_saf_p_traslado.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
//stm_aix("p1i4","p0i0",[0,"Cambio de Responsable","","",-1,-1,0,"sigesp_saf_p_cambioresponsable.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
//stm_aix("p1i6","p1i0",[0,"Entrega de Unidad ","","",-1,-1,0,"sigesp_saf_p_entregaunidad.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p1i6","p1i0",[0,"Depreciaci?n de Activos ","","",-1,-1,0,"sigesp_saf_p_depreciacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();

// Men? Principal - Reportes
stm_aix("p0i4","p0i0",[0," Reportes "]);
stm_bpx("p6","p1",[1,4,0,0,2,3,6,7]);
stm_aix("p6i0","p1i0",[0,"Activos                      ","","",-1,-1,0,"sigesp_saf_r_activo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p1i2",[0,"Incorporaci?n                ","","",-1,-1,0,"sigesp_saf_r_incorporacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p1i2",[0,"Desincorporaci?n             ","","",-1,-1,0,"sigesp_saf_r_desincorporacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p1i2",[0,"Reasignaci?n                 ","","",-1,-1,0,"sigesp_saf_r_reasignacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p1i2",[0,"Modificaci?n                 ","","",-1,-1,0,"sigesp_saf_r_modificacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p6i0","p1i2",[0,"Depreciaci?n                 ","","",-1,-1,0,"sigesp_saf_r_depreciacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p1i2",[0,"Depreciacion Mensual         ","","",-1,-1,0,"sigesp_saf_r_depmensual.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p6i0","p1i2",[0,"Listado Cat?logo SIGECOF     ","","",-1,-1,0,"sigesp_saf_r_sigecof.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p1i2",[0,"Listado de Activos           ","","",-1,-1,0,"sigesp_saf_r_defactivo.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ai("p1i1",[6,1,"#e6e6e6","",0,0,0]);
stm_aix("p6i0","p1i2",[0,"Comprobante de Incorporaci?n ","","",-1,-1,0,"sigesp_saf_r_compincorporacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p1i2",[0,"Comprobante de Desincorporaci?n ","","",-1,-1,0,"sigesp_saf_r_compdesincorporacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_aix("p6i0","p1i2",[0,"Comprobante de Reasignaci?n  ","","",-1,-1,0,"sigesp_saf_r_compreasignacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);

/*stm_aix("p1i0","p1i0",[0,"Categoria   CGR ","","",-1,-1,0,"","","","","","",6,0,0,"../shared/imagebank/arrow.gif","../shared/imagebank/arrow.gif",0,0,0,0,1,"#ffffff"]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
stm_aix("p6i0","p1i2",[0,"Acta de Donaci?n","","",-1,-1,0,"sigesp_saf_r_incorporacion.php","","","","","",6,0,0,"","",0,0,0,0,1,"#ffffff"]);
stm_ep();*/
stm_ep();

/*stm_aix("p6i1","p1i0",[0,"Reportes 2    "]);
stm_aix("p6i2","p1i0",[0,"Reportes 3    ","","",-1,-1,0,"","_self","","","","",6,0,0,"imagebank/arrow.gif","imagebank/arrow.gif",7,7]);
stm_bpx("p3","p1",[1,2,0,0,2,3,0]);
// Edici?n - Opciones de Tercer Nivel
stm_aix("p3i0","p1i0",[0,"  Menu Item 1  ","","",-1,-1,0,"","_self","","","","",0]);
stm_aix("p3i1","p3i0",[0,"  Menu Item 2  ","","",-1,-1,0,"http://www.google.com/"]);
stm_aix("p3i2","p3i0",[0,"  Menu Item 3  "]);
stm_aix("p3i3","p3i0",[0,"  Menu Item 4  "]);
stm_aix("p3i4","p3i0",[0,"  Menu Item 5  "]);
stm_ep();
stm_aix("p6i3","p1i0",[0,"Reportes 4    "]);
stm_aix("p6i4","p1i0",[0,"Reportes 5    "]);*/

// Men? Principal - Ir a M?dulo
stm_aix("p4i0","p1i0",[0," Ir a M?dulos  ","","",-1,-1,0,"../../../index_modules.php","","","","","",6,0,0,"","",0,0,0,0,1,"#F7F7F7"]);
stm_bpx("p10","p1",[]);
stm_ep();

/*stm_aix("p8i2","p1i0",[0,"Ventana 3    "]);
stm_aix("p8i3","p1i0",[0,"Ventana 4    "]);
stm_aix("p8i4","p1i0",[0,"Ventana 5    "]);*/
//stm_ep();

// Men? Principal - Exploraci?n
//stm_aix("p0i7","p0i0",[0," Exploraci?n "]);
//stm_bpx("p9","p1",[]);
/*stm_aix("p9i0","p1i0",[0,"Exploraci?n 1    "]);
stm_aix("p9i1","p1i0",[0,"Exploraci?n 1    "]);
stm_aix("p9i2","p1i0",[0,"Exploraci?n 1    "]);
stm_aix("p9i3","p1i0",[0,"Exploraci?n 1    "]);
stm_aix("p9i4","p1i0",[0,"Exploraci?n 1    "]);*/
stm_ep();

// Men? Principal - Ayuda
//stm_aix("p0i8","p0i0",[0," Ayuda "]);
//stm_bpx("p10","p1",[]);
/*stm_aix("p10i0","p1i0",[0,"Ayuda 1    "]);
stm_aix("p10i1","p1i0",[0,"Ayuda 2    "]);
stm_aix("p10i2","p1i0",[0,"Ayuda 3    "]);
stm_aix("p10i3","p1i0",[0,"Ayuda 4    "]);
stm_aix("p10i4","p1i0",[0,"Ayuda 5    "]);
stm_ep();*/
stm_ep();
stm_em();
function ue_abrir(ventana)
{
	window.open(ventana,"catalogo","menubar=no,toolbar=no,scrollbars=no,resizable=no,width=400,height=230,left=150,top=150,location=no,resizable=yes");
}

function ue_abrir_usuario(sistema)
{
	window.open("sigesp_c_seleccionar_usuario.php?sist="+sistema,"catalogo","menubar=no,toolbar=no,scrollbars=no,resizable=no,width=400,height=230,left=150,top=150,location=no,resizable=yes");
}

function ue_actulizar_ventana()
{
	window.open("sigesp_c_Actualizar_ventanas.php","catalogo","menubar=no,toolbar=no,scrollbars=no,resizable=no,width=400,height=230,left=150,top=150,location=no,resizable=yes");
}