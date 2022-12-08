/***********************************************************************************
* @Archivo JavaScript que incluye tanto los componentes como los eventos asociados 
* a  
* @fecha de creacion: 00/00/2009
* @autor: Ing. Gerardo Cordero
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
var gridresultado ="";

//manejo de la session
var rutaSesion    = '../../controlador/sss/sigesp_ctr_sss_sesion.php';
validarSesion(rutaSesion,vista);
mostrarInformacionUsuario();
//fin manejo de la session

Ext.onReady(function(){
	Ext.BLANK_IMAGE_URL = '../../base/librerias/js/ext/resources/images/default/s.gif';
	    
	//creando datastore y columnmodel para la grid de movimientos del tramite
	var registro_dttramite = Ext.data.Record.create([
						{name: 'codunienv'},
						{name: 'denunienv'},
						{name: 'codusuenv'},
						{name: 'coddocenv'},
						{name: 'tipdocenv'},
						{name: 'fecenv'},
						{name: 'codunirec'},
						{name: 'denunirec'},
						{name: 'codusurec'},
						{name: 'fecrec'}
					]);
	
	var dsdttramite = new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},registro_dttramite)
	  	});
						
	var colmodeldttramite = new Ext.grid.ColumnModel([
						{header: "Unidad Emisora", width: 70, sortable: true, dataIndex: 'denunienv'},
          				{header: "Enviado por", width: 40, sortable: true, dataIndex: 'codusuenv'},
						{header: "Documento Enviado", width: 45, sortable: true, dataIndex: 'coddocenv'},
						{header: "Tipo", width: 20, sortable: true, dataIndex: 'tipdocenv'},
						{header: "Fecha Envio", width: 35, sortable: true, dataIndex: 'fecenv',renderer:formatoFechaHoraGrid},
						{header: "Unidad Receptora", width: 70, sortable: true, dataIndex: 'denunirec'},
						{header: "Enviado a ", width: 40, sortable: true, dataIndex: 'codusurec'},
						{header: "Fecha Recepci&#243;n ", width: 40, sortable: true, dataIndex: 'fecrec',renderer:formatoFechaHoraGrid}
				]);
	//fin creando datastore y columnmodel para la grid de movimientos del tramite
	
	//creando grid para los resultados
	gridresultado = new Ext.grid.GridPanel({
	 		width:885,
	 		height:150,
	 		autoScroll:true,
			title:'Detalle del Tramite',
			frame:true,
			style:'margin-left:0px;margin-top:250px',
			ds: dsdttramite,
       		cm: colmodeldttramite,
			stripeRows: true,
      		viewConfig: {forceFit:true}
	});
	//fin creando grid para los resultados
	
	//funcion para la construcion del formulario de la solicitud
	function getFormRegistroSolicitud(){
		Ext.QuickTips.init();
		var Xpos = ((screen.width/2)-(920/2));
		var formconsultratramite = new Ext.FormPanel({
						width: 920,
						height: 445,
						applyTo: 'formulario_consultratramite',
						title: 'Consultar Estado Tramite',
						frame:true,
						autoScroll:true,
						style:'position:absolute;margin-left:'+Xpos+'px;margin-top:65px;',
						items:[{
								layout: "column",
								defaults: {border: false},
								style: 'position:absolute;left:15px;top:0px',
								width:400,
								items: [{
										layout: "form",
										border: false,
										labelWidth: 130,
										columnWidth: 0.7,
										items: [{
											xtype: 'textfield',
											fieldLabel: 'Numero Tramite',
											labelSeparator: '',
											id: 'numtramite',
											autoCreate: {tag: 'input',type: 'text',size: '100',autocomplete: 'off',maxlength: '100'},
											width: 130,
											disabled:true
										}]
										}, {
										layout: "form",
										border: false,
										labelWidth: 0,
										columnWidth: 0.3,
										items: [{
												xtype: 'button',
												iconCls: 'menubuscar',
												handler: function(){
													catalogoTramiteCon(formconsultratramite);
												}
											}]
										}]
								},{
									layout: "column",
									defaults: {border: false},
									labelWidth: 130,
									style:'position:absolute;left:15px;top:30px',
									items: [{
										layout: "form",
										border: false,
										items: [{
													xtype: 'textfield',
													fieldLabel: 'Proveedor/Beneficiario',
													labelSeparator: '',
													id: 'codprobene',
													width: 300,
													disabled:true
												}]
									}]
								},{
									layout: "column",
									defaults: {border: false},
									labelWidth: 130,
									style:'position:absolute;left:15px;top:60px',
									items: [{
										layout: "form",
										border: false,
										items: [{
													xtype: 'textfield',
													fieldLabel: 'Usuario Actual',
													labelSeparator: '',
													id: 'codusuact',
													width: 130,
													disabled:true
												}]
									}]
								},{
									layout: "column",
									defaults: {border: false},
									style:'position:absolute;left:5px;top:95px',
									items: [{
										layout: "form",
										border: false,
										columnWidth: 0.5,
										items: [{
											xtype: 'fieldset',
											width: 350,
											height: 150,
											title: 'Datos Inicio',
											border: true,
											items: [{
												xtype: 'textfield',
												fieldLabel: 'Usuario Inicial',
												labelSeparator: '',
												id: 'codusuini',
												autoCreate: {
													tag: 'input',
													type: 'text',
													size: '100',
													autocomplete: 'off',
													maxlength: '100'
												},
												width: 130,
												disabled:true
											},{
												xtype: 'datefield',
												fieldLabel: 'Fecha Inicio',
												labelSeparator: '',
												id: 'fecini',
												width: 130,
												disabled:true
											}, {
												xtype: 'textfield',
												fieldLabel: 'Documento',
												labelSeparator: '',
												id: 'coddocini',
												autoCreate: {
													tag: 'input',
													type: 'text',
													size: '100',
													autocomplete: 'off',
													maxlength: '100'
												},
												width: 130,
												disabled:true
											}, {
												xtype: 'textfield',
												fieldLabel: 'Tipo Documento',
												labelSeparator: '',
												id: 'tipdocini',
												autoCreate: {
													tag: 'input',
													type: 'text',
													size: '100',
													autocomplete: 'off',
													maxlength: '100'
												},
												width: 130,
												disabled:true
											}]
										}]
									},{
										layout: "form",
										border: false,
										columnWidth: 0.5,
										items: [{
											xtype: 'fieldset',
											width: 350,
											height: 125,
											title: 'Datos Fin',
											style:'margin-left:10px',
											border: true,
											items: [{
												xtype: 'datefield',
												fieldLabel: 'Fecha Fin',
												labelSeparator: '',
												id: 'fecfin',
												width: 130,
												disabled:true
											}, {
												xtype: 'textfield',
												fieldLabel: 'Documento',
												labelSeparator: '',
												id: 'coddocfin',
												autoCreate: {
													tag: 'input',
													type: 'text',
													size: '100',
													autocomplete: 'off',
													maxlength: '100'
												},
												width: 130,
												disabled:true
											}, {
												xtype: 'textfield',
												fieldLabel: 'Tipo Documento',
												labelSeparator: '',
												id: 'tipdocfin',
												autoCreate: {
													tag: 'input',
													type: 'text',
													size: '100',
													autocomplete: 'off',
													maxlength: '100'
												},
												width: 130,
												disabled:true
											}]
										}]
									}]
								},
								gridresultado
								]
		});
	}
	//fin funcion para la construcion del formulario de la solicitud
	
	//llamado a funciones.........
	getFormRegistroSolicitud();
});



function buscarTramite(){
	var cadenajson = "{'operacion':'consultar','numtramite':'"+Ext.getCmp('numtramite').getValue()+"'}";
	parametros = 'ObjSon='+cadenajson;
	
	Ext.Ajax.request({
		url : '../../controlador/sst/sigesp_ctr_sst_catalogosst.php',
		params : parametros,
		method: 'POST',
		success: function ( resultad, request ){ 
	        datos = resultad.responseText;
			 if (datos != '') {
			 	var DatosNuevo = eval('(' + datos + ')');
			 	if (DatosNuevo.raiz == null) {
			 		Ext.MessageBox.alert('Informaci&#243;n','No se encontraron datos')
			 	}
				gridresultado.store.loadData(DatosNuevo);
			 }
	    },
		failure: function ( result, request){ 
			Ext.MessageBox.alert('Error','Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo'); 
		} 
	});
}

var banderaCatalogo = 'generica';
var buscarGenerica = buscarTramite;
var banderaNuevo = false;

function imprimirReporte(){
	var urlreporte = "reporte/sigesp_sst_rfs_tramite.php?numtramite="+Ext.getCmp('numtramite').getValue();
	window.open(urlreporte,"reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
}

var banderaImprimir = true;
var imprimirPersonalizado = imprimirReporte;