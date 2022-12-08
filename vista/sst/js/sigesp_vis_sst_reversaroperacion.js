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
var formreversaroperacion = null;
var gridoperaciones       = null;

//manejo de la session
var rutaSesion    = '../../controlador/sss/sigesp_ctr_sss_sesion.php';
validarSesion(rutaSesion,vista);
mostrarInformacionUsuario();
//fin manejo de la session

Ext.onReady(function(){
	Ext.BLANK_IMAGE_URL = '../../base/librerias/js/ext/resources/images/default/s.gif';
	
	//creando el datastore para el combo de tipo operacion
	var tipooperacion = [['Reversar Recepci\u00F3n', 'R'], ['Reversar Asginaci\u00F3n', 'A'], ['Reversar Cierre', 'C']]
	var storetipooperacion = new Ext.data.SimpleStore({
            fields: ['col', 'tipo'],
            data: tipooperacion
	});
	//fin creando store para el combo tipo operacion	
	

	//creando objeto combo tipo operacion
	var combotipooperacion = new Ext.form.ComboBox({
				store: storetipooperacion,
				labelSeparator :'',
				fieldLabel:'Tipo Operaci&#243;n',
            	editable: false,
				displayField: 'col',
            	valueField: 'tipo',
            	id: 'codtipope',
            	typeAhead: true,
           		triggerAction: 'all',
            	mode: 'local',
				width: 200,
				binding:true,
				hiddenvalue:'',
				defaultvalue:'',
				allowBlank:false
	});
	//fin creando objeto combo tipo operacion
	
	
	
	//creando datastore y columnmodel para la grid de resultado(tramites)
	var registro_tramite = Ext.data.Record.create([
			{name: 'numtramite'},
			{name: 'numasi'},
			{name: 'coddocenv'},
			{name: 'tipdocenv'},
			{name: 'fecenv'}
		]);
	
	 var dsTramite=   new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "coditem"},registro_tramite)
		});
	
	var colmodTramite = new Ext.grid.ColumnModel([new Ext.grid.CheckboxSelectionModel({}),
          			{header: "Numero Tramite", width: 50, sortable: true,   dataIndex: 'numtramite'},
          			{header: "Documento", width: 50, sortable: true,   dataIndex: 'coddocenv'},
          			{header: "Tipo", width: 30, sortable: true,   dataIndex: 'tipdocenv'},
          			{header: "Fecha", width:30, sortable: true, dataIndex: 'fecenv',renderer:formatoFechaHoraGrid}
    	]);
	//fin creando datastore y columnmodel para la grid de resultado(tramites)
	
	//creando grid de resultado(tramites)
	gridoperaciones = new Ext.grid.GridPanel({
	 		title:'Tramites',
			width:600,
	 		height:150,
	 		autoScroll:true,
			border:true,
     		ds: dsTramite,
       		cm: colmodTramite,
			sm: new Ext.grid.CheckboxSelectionModel({}),
       		stripeRows: true,
      		viewConfig: {forceFit:true}
		});
	//fin creando grid de resultado(tramites)
	
	//funcion para la construcion del formulario de reverso de operaciones
	var Xpos = ((screen.width/2)-(750/2));
	function getFormReversarOperacion(){
		var fecha = new Date();
		Ext.QuickTips.init();
		formreversaroperacion = new Ext.FormPanel({
						width: 750,
						height: 350,
						autoScroll: true,
						applyTo: 'formulario_reversaroperacion',
						title: 'Reverso de Operaciones',
						frame:true,
						style:'position:absolute;margin-left:'+Xpos+'px;margin-top:80px;',
						items:[{
								layout : "column",
					        	defaults : {border : false},
								style:'position:absolute;left:17px;top:5px',
								items : [{
											layout : "form",
					        				border : false,
											labelWidth: 130,
					        				columnWidth : 1,
					        				items : [combotipooperacion]
						   				}]
								},{
								layout: "column",
								defaults: {border: false},
								style:'position:absolute;left:15px;top:40px',
								width:405,
								items: [{
										layout : "form",
					        			border : false,
										labelWidth: 130,
					        			columnWidth : 0.7,
					        			items : [{
													xtype: 'textfield',
													fieldLabel: 'Documento',
													labelSeparator: '',
													id: 'coddocenv',
													autoCreate: {tag: 'input',type: 'text',size: '100',autocomplete: 'off',maxlength: '100'},
													width: 130,
													binding:true,
												   	hiddenvalue:'',
												   	defaultvalue:'',
												   	allowBlank:true
												}]
										},{
										layout : "form",
					        			border : false,
										labelWidth: 0,
					        			columnWidth :0.3,
					        			items : [{
													xtype:'button',
													iconCls: 'menubuscar',
													handler: function (){
																mostrarCatalogoDocumento2('coddocenv','tipdocenv');
													}
												}]
											}]
								},{
								layout: "column",
								defaults: {border: false},
								style: 'position:absolute;left:17px;top:75px',
								width: 700,
								items: [{
										layout: "form",
										border: false,
										labelWidth:130,
										columnWidth: 0.5,
										items: [{
													xtype: "datefield",
													fieldLabel: "Fecha Desde",
													labelSeparator: '',
													width: 100,
													id: "fecdes",
													format:'d/m/Y',
													endDateField: 'fechas',
													readOnly: true,
													vtype: 'daterange',
													binding: true,
													hiddenvalue: '',
													defaultvalue: fecha.getFirstDateOfMonth().format(Date.patterns.bdfecha),
													allowBlank: true
												}]
										}, {
										layout: "form",
										border: false,
										labelWidth: 70,
										columnWidth: 0.5,
										items: [{
													xtype: "datefield",
													fieldLabel: "Fecha Hasta",
													labelSeparator: '',
													width: 100,
													id: "fechas",
													altFormats:'d/m/Y H:i',
													format:'d/m/Y',
													startDateField: 'fecdes',
													value:fecha.format(Date.patterns.fechahoracorta),
													readOnly: true,
													vtype: 'daterange',
													binding: true,
													hiddenvalue: '',
													defaultvalue: '',
													allowBlank: true
												}]
										}]
								},{
								layout : "column",
					        	defaults : {border : false},
								style:'position:absolute;left:20px;top:150px',
								items : [{
												layout : "form",
					        					border : false,
												labelWidth: 130,
					        					columnWidth : 1,
					        					items : [gridoperaciones]
											}]
								},{
									xtype: 'hidden',
									id: 'tipdocenv',
									binding:true,
									defaultvalue:''
								},{
									xtype: 'hidden',
									id: 'tipprop'
								},{
									xtype: 'hidden',
									id: 'codprop'
								}]
		});
	}
	//fin funcion para la construcion del formulario de reverso de operaciones
	
	//llamado a funciones.........
	getFormReversarOperacion();
	
})

function buscarSolicitud(){
	gridoperaciones.store.removeAll();
	if(formreversaroperacion.findById('fecdes').getValue() == ""){
		Ext.Msg.show({
			title: 'Advertencia',
			msg: 'Usted no indico la fecha desde para la busqueda. El sistema tomara por defecto el primer dia del mes en curso para la busqueda desea proseguir?',
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if(btn=="yes"){
					var cadjson = getItems(formreversaroperacion,"buscar","N",null,null);
					try {
							var objjson = Ext.util.JSON.decode(cadjson);
							if (typeof(objjson) == 'object') {
								var parametros = 'ObjSon='+cadjson;
								Ext.Ajax.request({
									url : '../../controlador/sst/sigesp_ctr_sst_reversaroperacion.php',
									params : parametros,
									method: 'POST',
									success: function ( resultado, request){ 
												var datos = resultado.responseText;
												var objetodata = eval('(' + datos + ')');
												if(objetodata != ''){
													if(objetodata.raiz == null){
														Ext.MessageBox.alert('Informaci&#243;n','No se encontraron datos')
													}
													else{
														gridoperaciones.getStore().loadData(objetodata);
													}
												}
									}	
								});
							}
					}
					catch(e){
						//alert('error'+e);
					}
				}
			},
			animEl: 'elId',
			icon: Ext.MessageBox.QUESTION
		});
	}
	else{
		var cadjson = getItems(formreversaroperacion,"buscar","N",null,null);
		try {
				var objjson = Ext.util.JSON.decode(cadjson);
				if (typeof(objjson) == 'object') {
					var parametros = 'ObjSon='+cadjson;
					Ext.Ajax.request({
						url : '../../controlador/sst/sigesp_ctr_sst_reversaroperacion.php',
						params : parametros,
						method: 'POST',
						success: function ( resultado, request){ 
									var datos = resultado.responseText;
									var objetodata = eval('(' + datos + ')');
									if(objetodata != ''){
										if(objetodata.raiz == null){
											Ext.MessageBox.alert('Informaci&#243;n','No se encontraron datos')
										}
										else{
											gridoperaciones.getStore().loadData(objetodata);
										}
									}
						}	
					});
				}
		}
		catch(e){
			//alert('error'+e);
		}
	}
}

var banderaCatalogo = 'generica';
var buscarGenerica = buscarSolicitud;

function grabarReversoOperacion(){
	var tramites   = gridoperaciones.getSelectionModel().getSelections();
	var operacion  = Ext.getCmp('codtipope').getValue();
	var cadenajson = "";
	switch(operacion){
		case "R":
			cadenajson = "{'operacion':'recepcion'";
			break;
		case "A":
			cadenajson = "{'operacion':'asignacion'";
			break;
		case "C":
			cadenajson = "{'operacion':'cierre'";
			break;
	}
	
	
	cadenajson = cadenajson +",'asignacion':[";
	for (var i = 0; i <= tramites.length-1; i++) {
		if(i==0){
			cadenajson = cadenajson + "{'numtramite':'"+tramites[i].get('numtramite')+"','numasi':'"+tramites[i].get('numasi')+"'}";
		}
		else{
			cadenajson = cadenajson + ",{'numtramite':'"+tramites[i].get('numtramite')+"','numasi':'"+tramites[i].get('numasi')+"'}";
		}
	}
	cadenajson = cadenajson +"]}";
	var parametros = 'ObjSon='+cadenajson; 
	Ext.Ajax.request({
		url : '../../controlador/sst/sigesp_ctr_sst_reversaroperacion.php',
		params : parametros,
		method: 'POST',
		success: function ( resultad, request ){ 
	        datos = resultad.responseText;
	       	resultado = datos.split("|");
			if(resultado[1]=="1"){
				Ext.MessageBox.alert('Mensaje','El reverso de la(s) operacion(es) fue realizado de manera exitosa');
			}
			else{
				Ext.MessageBox.alert('Error','Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo');
			}
			limpiarFormulario(formreversaroperacion);
			gridoperaciones.getStore().removeAll();
		},
		failure: function ( result, request){ 
			Ext.MessageBox.alert('Error','Ha ocurrido un error en la operaci&#243;n, por favor intente de nuevo'); 
		} 
	});
}

var banderaGrabar = true;
var grabarPersonalizado = grabarReversoOperacion;

function nuevoReversoOperacion(){
	limpiarFormulario(formreversaroperacion);
	gridoperaciones.getStore().removeAll();
}

var banderaNuevo=true;
var nuevoPersonalizado = nuevoReversoOperacion;