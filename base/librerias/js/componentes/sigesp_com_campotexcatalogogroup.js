/***********************************************************************************
* @Archivo JavaScript que incluye la implementacion del componente campo catalogo  
* con la variante de que la grid del catalgo permite agrupar los datos 
* @fecha de creacion: 00/00/2009
* @autor: Ing. Gerardo Cordero
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
/***********************************************************************************
* @Archivo JavaScript que incluye un componentes que construye un campo de texto con 
* un catalgo asociado que llena dicho campo  
* @fecha de creacion: 21/08/2009
* @autor: Ing. Gerardo Cordero
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
Ext.namespace('com.sigesp.vista');
var copiadatastorecatalogo = '';

com.sigesp.vista.comCampoCatalogoAgrupado = function(options){
	
	this.dataStoreCatalogo = options.datosgridcat;
	copiadatastorecatalogo = options.datosgridcat;
		
	//Creando el Json para la configuracion de los items del formulario de busqueda
	var cadenafiltro="[";
	for (var i = 0; i < options.arrfiltro.length; i++) {
       	if(i==options.arrfiltro.length-1){
			cadenafiltro =  cadenafiltro + "{fieldLabel:'"+options.arrfiltro[i].etiqueta+"',id:'"+options.arrfiltro[i].id+"',"+
							"changeCheck: function(){"+
							"var valor = this.getValue();"+
							"copiadatastorecatalogo.filter('"+options.arrfiltro[i].valor+"',valor);"+
							"if(String(valor) !== String(this.startValue)){"+
								"this.fireEvent('change', this, valor, this.startValue);"+
							"}"+ 
							"},"+								 
							"initEvents : function(){"+
								"AgregarKeyPress(this);"+
							"}"+              
    						"}";
		}else{
			cadenafiltro =  cadenafiltro + "{fieldLabel:'"+options.arrfiltro[i].etiqueta+"',id:'"+options.arrfiltro[i].id+"',"+
							"changeCheck: function(){"+
							"var valor = this.getValue();"+
							"copiadatastorecatalogo.filter('"+options.arrfiltro[i].valor+"',valor);"+
							"if(String(valor) !== String(this.startValue)){"+
								"this.fireEvent('change', this, valor, this.startValue);"+
							"}"+ 
							"},"+							 
							"initEvents : function(){"+
								"AgregarKeyPress(this);"+
							"}"+               
     						"},";
		}
	}
	cadenafiltro=  cadenafiltro + "]";
	var objetofiltro = Ext.util.JSON.decode(cadenafiltro);
	//Fin creando el Json para la configuracion de los items del formulario de busqueda	
		
	//Creando formulario de busqueda del catalogo
	this.formBusquedaCat = new Ext.FormPanel({
        	labelWidth: 80, 
			frame:true,
        	title: 'Busqueda',
        	bodyStyle:'padding:5px 5px 0',
        	width: 630,
			height:100,
        	defaults: {width: 230},
        	defaultType: 'textfield',
			items: objetofiltro
		});
	//Fin creando formulario de busqueda del catalogo
		
	//Creando la instacia de la grid del catalogo
	this.gridcatalogo = new Ext.grid.GridPanel({
	 		width:options.ancho,
	 		height:options.alto,
			autoExpandColumn:options.colexpandible,
			tbar: this.formBusquedaCat,
			bbar: new Ext.PagingToolbar({store: this.dataStoreCatalogo,pageSize: 10,displayInfo: 'Topics {0} - {1} of {2}',emptyMsg: 'No topics to display'}),
			ds: this.dataStoreCatalogo,
       		cm: options.colmodelocat,
       		stripeRows: true,
      		view: new Ext.grid.GroupingView({startCollapsed:true,forceFit: true,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "'+options.labelitem+'" : "'+options.labelitem+'"]})'})
		});
	//Fin Creando la instacia de la grid del catalogo
	
	//Eventos de la ventana catalogo
	this.cerrarVentana = function(){
		this.vencatalogo.hide();
	}
	
	this.cargarDatosCat = function (){
		var datos = arguments[0].responseText;
		var objetodata = eval('(' + datos + ')');
		if(objetodata!=''){
			this.dataStoreCatalogo.loadData(objetodata);
		}
	}
	
	this.mostrarVentana = function(){
		Ext.Ajax.request({
			url : options.rutacontrolador,
			params : options.parametros,
			method: 'POST',
			success: this.cargarDatosCat.createDelegate(this, arguments, 2)
		});
		this.vencatalogo.show();
		
		if(copiadatastorecatalogo==''){
			copiadatastorecatalogo = this.dataStoreCatalogo;
		}
	}
	
	this.setDataCampo = function(){
		var registrocat = this.gridcatalogo.getSelectionModel().getSelected();
		this.campo.setValue(registrocat.get(options.campovalue));
		if(options.labelvalue!=''){
			this.etiqueta.setValue(registrocat.get(options.labelvalue));	
		}
		
		if(options.datosocultos==1){
			for (var i = options.camposocultos.length - 1; i >= 0; i--){
				Ext.getCmp(options.camposocultos[i]).setValue(registrocat.get(options.camposocultos[i]));
			};
		}
		this.dataStoreCatalogo.removeAll();
		copiadatastorecatalogo = '';
		this.vencatalogo.hide();
	}
	//Fin de los eventos de la ventana catalogo
	
	//agregadon listener a la grid del catalogo para que cuando de dobleclick sobre el registro este se pase al formulario
	this.gridcatalogo.on({
		'celldblclick': {
			fn: this.setDataCampo.createDelegate(this)
		}
	});
	//fin agregadon listener a la grid del catalogo para que cuando de dobleclick sobre el registro este se pase al formulario
	
	//Creando la instacia de la window para la ventana del catalogo
	this.vencatalogo = new Ext.Window({
    		title: options.titvencat,
			autoScroll:true,
        	width:options.ancho,
        	height:options.alto,
        	modal: true,
        	closable:false,
        	plain: false,
			items:[this.gridcatalogo],
			buttons: [{
						text:'Aceptar',  
			        	handler: this.setDataCampo.createDelegate(this)
			       	},{
			      		text: 'Salir',
			        	handler:this.cerrarVentana.createDelegate(this)
                  	}]
      	});
	//Fin creando la instacia de la window para la ventana del catalogo
	
	this.campo = new Ext.form.TextField({
					xtype: 'textfield',
					fieldLabel: options.tittxt,
					id: options.idtxt,
					width: options.anchotext,
					readOnly: true
					});
	
	this.boton = new Ext.Button({
					xtype:'button',
					iconCls: 'menubuscar',
					handler:this.mostrarVentana.createDelegate(this)
					});
					
					
					
	this.etiqueta = new Ext.form.TextField({
					xtype: 'textfield',
  					labelSeparator :'',
  					style:'border:none;background:#DFE8F6;',
  					id: options.idlabel,
  					disabled:true,  
  					width: options.anchoetiqueta
					});
		
	this.fieldsetCatalogo = new Ext.form.FieldSet({
					height:390,
					width:725,
					border:false,
					style:options.posicion,
			    	items:[{
							layout : "column",
					        defaults : {border : false},
							items : [{layout : "form",
					        					border : false,
												labelWidth: options.anchoetiquetatext,
					        					columnWidth : options.anchocoltext,
					        					items : [this.campo]
						   			},{layout : "form",
					        					border : false,
												columnWidth : 0.07,
					        					items : [this.boton]
						   			},{layout : "form",
					        					border : false,
												columnWidth : options.anchocoletiqueta,
					        					items : [this.etiqueta]
						   			}]
							}]
					});

}//Fin componente campo catalogo

