/*******************************************************************************
 * @Archivo JavaScript que incluye tanto los componentes como los eventos
 *          asociados al catalogo de documentos
 * @fecha de creacion: 04/09/2009
 * @autor: Ing. Gerardo Cordero
 *         ***********************************************************************************
 * @fecha modificacion:
 * @descripcion:
 * @autor:
 ******************************************************************************/
var dsdocumento="";
var grid_documento="";
var form_busqueda_documento="";

function buscarDatos(btn){
	var fecha  = new Date();
	var cadenajson = "";
	var tipoprop = Ext.getCmp('tipprop').getValue();
	var tipodoc  = Ext.getCmp('tipodoc').getValue()
	Ext.getCmp('tipodoc').disable();
	var codprop  = '';
	if(tipoprop=='P'||tipoprop=='B'){
		codprop=Ext.getCmp('codprop').getValue();	
	}
	
	if(btn=="yes"){
		if(Ext.getCmp('bandera').getValue()==0){
			cadenajson = "{'operacion':'catalogodocumentos',"+
				     	 "'numdoc':'"+Ext.getCmp('numero').getValue()+"',"+
				     	 "'fecdes':'"+fecha.getFirstDateOfMonth().format(Date.patterns.bdfecha)+"',"+
				     	 "'fechas':'"+Ext.getCmp('fechascat').getValue().format(Date.patterns.bdfecha)+"',"+
				     	 "'tipodoc':'"+tipodoc+"',"+
	                 	 "'codprop':'"+codprop+"','tipprop':'"+tipoprop+"'}";
		}
		else{
			cadenajson = "{'operacion':'catalogodocumentosvalidar',"+
				     	 "'numdoc':'"+Ext.getCmp('numero').getValue()+"',"+
				     	 "'fecdes':'"+fecha.getFirstDateOfMonth().format(Date.patterns.bdfecha)+"',"+
				     	 "'fechas':'"+Ext.getCmp('fechascat').getValue().format(Date.patterns.bdfecha)+"',"+
				     	 "'tipodoc':'"+tipodoc+"',"+
	                 	 "'codprop':'"+codprop+"','tipprop':'"+tipoprop+"'}";
		
		}
	}
	else if(btn=="normal"){
		if(Ext.getCmp('bandera').getValue()==0){
			cadenajson = "{'operacion':'catalogodocumentos',"+
				     	 "'numdoc':'"+Ext.getCmp('numero').getValue()+"',"+
				     	 "'fecdes':'"+Ext.getCmp('fecdescat').getValue().format(Date.patterns.bdfecha)+"',"+
				     	 "'fechas':'"+Ext.getCmp('fechascat').getValue().format(Date.patterns.bdfecha)+"',"+
				     	 "'tipodoc':'"+tipodoc+"',"+
	                 	 "'codprop':'"+codprop+"','tipprop':'"+tipoprop+"'}";
		}
		else{
			cadenajson = "{'operacion':'catalogodocumentosvalidar',"+
				     	 "'numdoc':'"+Ext.getCmp('numero').getValue()+"',"+
				     	 "'fecdes':'"+Ext.getCmp('fecdescat').getValue().format(Date.patterns.bdfecha)+"',"+
				     	 "'fechas':'"+Ext.getCmp('fechascat').getValue().format(Date.patterns.bdfecha)+"',"+
				     	 "'tipodoc':'"+tipodoc+"',"+
	                 	 "'codprop':'"+codprop+"','tipprop':'"+tipoprop+"'}";
		
		}
	}
	
	if(cadenajson!=""){
		var parametros = 'ObjSon='+cadenajson; 
		Ext.Ajax.request({
			url : '../../controlador/sst/sigesp_ctr_sst_catalogosst.php',
			params : parametros,
			method: 'POST',
			success: function ( resultado, request) { 
						var datos = resultado.responseText;
						var dataobjeto = eval('(' + datos + ')');
						if(dataobjeto!=''){
							if(dataobjeto.raiz == null){
								Ext.MessageBox.alert('Informaci&#243;n','No se encontraron datos');
							}else{
								dsdocumento.loadData(dataobjeto);
							}
						}
			}	
		});
	}
}


function validarDatos(){
	
	if(Ext.getCmp('tipodoc').getValue()!=""){
		if (Ext.getCmp('fecdescat').getValue() == "") {
			Ext.Msg.show({
				title: 'Advertencia',
				msg: 'Usted no indico la fecha de inicio para la busqueda. El sistema tomara por defecto el primer dia del mes en curso para la busqueda desea proseguir?',
				buttons: Ext.Msg.YESNO,
				fn: buscarDatos,
				animEl: 'elId',
				icon: Ext.MessageBox.QUESTION
			});
		}
		else{
			buscarDatos("normal");
		}
	}
	else{
		Ext.MessageBox.alert('Advertencia','Debe indicar el tipo de documento');
	}
}

function crear_dsdocumento(){
	
	var registro_documento = Ext.data.Record.create([
						{name: 'numdoc'},    
						{name: 'fecha'},
						{name: 'codunienv'},
						{name: 'denunienv'}
		]);
	
	dsdocumento =  new Ext.data.Store({
			reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},registro_documento)
		});	
}

function actualizar_dsdocumento(criterio,cadena)
{
	dsdocumento.filter(criterio,cadena);
}


function crear_form_busqueda_documento(bandera){
	var fecha  = new Date();
	
	// creando store para el combo tipo de propietario
	var tipodocumento = [['Solicitud Ejecucion Presupuestaria', 'SEP'], 
						['Orden de Compra(Bienes)', 'SOCB'],
						['Orden de Compra(Servicios)', 'SOCS'],
						['Solicitud de Pago', 'CXPSP'],
						['Cheque', 'SCBCH']];
	var storetipodocumento = new Ext.data.SimpleStore({
            fields: ['col', 'tipo'],
            data: tipodocumento
	});
	// fin creando store para el combo tipo de propietario
	
	// creando objeto combo tipo de propietario
	var combotipodocumento = new Ext.form.ComboBox({
				store: storetipodocumento,
				fieldLabel: 'Tipo Documento',
				labelSeparator :'',
            	editable: false,
            	displayField: 'col',
            	valueField: 'tipo',
            	name: 'tipodocu',
            	id: 'tipodoc',
            	typeAhead: true,
            	triggerAction: 'all',
            	mode: 'local',
            	width: 210
	});
	// fin creando objeto combo tipo de propietario
	
	combotipodocumento.addListener('select',desHabilitarCombo);
	
	function desHabilitarCombo(objcombo,registro){
		objcombo.disable();
	}
	
	function habilitarCombo(){
		Ext.getCmp('tipodoc').enable();
		dsdocumento.removeAll();
	}
		
	form_busqueda_documento = new Ext.FormPanel({
        width: 600,
		height:165,
        frame:true,
        title: 'Busqueda',
        items: [{
				layout: "column",
				style: 'position:absolute;left:15px;top:5px',
				border: false,
				items: [{
						layout: "form",
						border: false,
						labelWidth:130,
						columnWidth: 1,
						items: [{
        							xtype:'textfield',
									fieldLabel: 'Numero Documento',
									id:'numero',
									changeCheck: function(){
													var v = this.getValue();
													actualizar_dsdocumento('numdoc',v);
													if(String(v) !== String(this.startValue)){
														this.fireEvent('change', this, v, this.startValue);
													} 
									},							 
									initEvents : function(){
													AgregarKeyPress(this);
									}               
      							}]
      					}]
      			},{
				layout: "column",
				style: 'position:absolute;left:15px;top:35px',
				border: false,
				items: [{
						layout: "form",
						border: false,
						labelWidth:130,
						columnWidth: 1,
						items: [combotipodocumento]
						}]
				},{
				layout: "column",
				style: 'position:absolute;left:15px;top:65px',
				border: false,
				width:500,
				items: [{
						layout: "form",
						border: false,
						labelWidth:130,
						columnWidth: 0.5,
						items: [{
									xtype: "datefield",
									fieldLabel: "Fecha de Inicio",
									labelSeparator: '',
									width: 100,
									id: "fecdescat",
									endDateField: 'fechascat',
									readOnly: true,
									vtype: 'daterange'
								}]
						},{
						layout: "form",
						border: false,
						labelWidth: 50,
						columnWidth: 0.5,
						items: [{
									xtype: "datefield",
									fieldLabel: "Hasta",
									labelSeparator: '',
									width: 100,
									id: "fechascat",
									startDateField: 'fecdescat',
									readOnly: true,
									vtype: 'daterange',
									value:fecha.format(Date.patterns.fechacorta)
								}]
						}]
				},{
				layout: "column",
				style:'position:absolute;left:500px;top:95px',
				border: false,
				items: [{
						layout: "form",
						border: false,
						labelWidth:130,
						columnWidth: 1,
						items: [{
									xtype:'button',
									id:'buscar',
									iconCls: 'menubuscar',
									handler: function (){
												validarDatos();
									}
								}]
						}]
				},{
				layout: "column",
				style:'position:absolute;left:530px;top:95px',
				border: false,
				items: [{
						layout: "form",
						border: false,
						labelWidth:130,
						columnWidth: 1,
						items: [{
									xtype:'button',
									id:'nuevo',
									iconCls: 'menunuevo',
									handler: function (){
												habilitarCombo();
									}
								}]
						}]
				},{
				xtype: 'hidden',
				id: 'bandera',
				value:bandera
				}]
	});
	
	
}


function crearGridDocumento(bandera){
	crear_form_busqueda_documento(bandera);
	crear_dsdocumento();
		 
	grid_documento = new Ext.grid.GridPanel({
			width: 600,
			height:300,
			autoScroll:true,
			border:true,
			ds: dsdocumento,
			cm: new Ext.grid.ColumnModel([
						{header: "Numero Documento", width: 30, sortable: true,   dataIndex: 'numdoc'},
						{header: "Fecha de Creaci&#243;n", width: 50, sortable: true, dataIndex: 'fecha',renderer:formatoFechaGrid}
        	]),
       		stripeRows: true,
      		viewConfig: {forceFit:true}
	});            
} 

function mostrarCatalogoDocumento(){
	crearGridDocumento(1);
	var ventanacatdocumento = new Ext.Window({
		title: 'Cat&#225;logo de Documentos',
		autoScroll:true,
        width:700,
        height:400,
        modal: true,
        closable:false,
        plain: false,
        items:[form_busqueda_documento,grid_documento],
        buttons: [{
						text:'Aceptar',
						handler: function(){
									var registrocat = grid_documento.getSelectionModel().getSelected();
									Ext.getCmp('coddocini').setValue(registrocat.get('numdoc'));
									if(Ext.getCmp('tipdocini')!=null){
										Ext.getCmp('tipdocini').setValue(Ext.getCmp('tipodoc').getValue());	
									}
									
									if(registrocat.get('codunienv')!=""){
										Ext.getCmp('codunienv').setValue(registrocat.get('codunienv'))
									}
									
									if(registrocat.get('denunienv')!=""){
										Ext.getCmp('denunienv').setValue(registrocat.get('denunienv'))
									}
									grid_documento.destroy();
									ventanacatdocumento.destroy();
						}
					},
					{
						text: 'Salir',
                     	handler: function(){
										grid_documento.destroy();
										ventanacatdocumento.destroy();
                     	}
				 }]
	});
    ventanacatdocumento.show();       
 }
 
 function mostrarCatalogoDocumento2(idcodigo,idtipo){
	crearGridDocumento(0);
	var ventanacatdocumento = new Ext.Window({
		title: 'Cat&#225;logo de Documentos',
		autoScroll:true,
        width:700,
        height:400,
        modal: true,
        closable:false,
        plain: false,
        items:[form_busqueda_documento,grid_documento],
        buttons: [{
						text:'Aceptar',
						handler: function(){
									var registrocat = grid_documento.getSelectionModel().getSelected();
									Ext.getCmp(idcodigo).setValue(registrocat.get('numdoc'));
									if(idtipo!=""){
										Ext.getCmp(idtipo).setValue(Ext.getCmp('tipodoc').getValue());	
									}
									grid_documento.destroy();
									ventanacatdocumento.destroy();
						}
					},
					{
						text: 'Salir',
                     	handler: function(){
										grid_documento.destroy();
										ventanacatdocumento.destroy();
                     	}
				 }]
	});
    ventanacatdocumento.show();       
 }