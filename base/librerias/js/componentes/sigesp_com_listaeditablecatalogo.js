/***********************************************************************************
* @Archivo JavaScript que incluye el componente lista editable catalogo 
*   
* @fecha de creacion: 07/09/2009
* @autor: Ing. Gerardo Cordero
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
Ext.namespace('com.sigesp.vista');
var copiadatastorecatalogo = '';

com.sigesp.vista.comListaEditableCatalogo = function(options){
	
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
	 		tbar: this.formBusquedaCat,
	 		autoScroll:true,
     		border:true,
     		ds: this.dataStoreCatalogo,
       		cm: options.colmodelocat,
			sm: new Ext.grid.CheckboxSelectionModel({}),
       		stripeRows: true,
      		viewConfig: {forceFit:true}
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
			copiadatastorecatalogo.loadData(objetodata);
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
	}
	
	this.setDataGrid = function(){
		var arregloreg =  this.gridcatalogo.getSelectionModel().getSelections();
		for (i=0; i<arregloreg.length; i++){
			var validareg = arregloreg[i];
			if(validarExistenciaRegistroStore(validareg,this.dataGridEditable.store,options.arrcampovalidaori,options.arrcampovalidades)){
				arregloreg[i].set('registrocat','1');
				this.dataGridEditable.store.insert(0,arregloreg[i]);
			}
			else{
				Ext.MessageBox.alert('Advertencia','El item seleccionado ya fue cargado');
			}
		}
		this.vencatalogo.hide();
	}
	//Fin de los eventos de la ventana catalogo
	
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
			        	handler: this.setDataGrid.createDelegate(this)
			       	},{
			      		text: 'Salir',
			        	handler:this.cerrarVentana.createDelegate(this)
                  	}]
      	});
	//Fin creando la instacia de la window para la ventana del catalogo
	
	//Creando grid de datos que se llenara con el catalgo
	this.dataGridEditable =new Ext.grid.EditorGridPanel({
        id: options.idgrid,
		width:options.ancho,
        height:options.alto,
       	style:options.posicion,
        title:options.titgrid,
	    ds: options.datosgrid,
       	cm: options.colmodelo,
       	sm: options.selmodelo,
        frame:true,
       	viewConfig: {forceFit:true},
        columnLines: true,
        tbar:[{
            text:'Agregar',
            tooltip:'Agregar un registro',
            iconCls:'agregar',
            id:'agregar',
			handler: this.mostrarVentana.createDelegate(this)
        	}, '-', {
            text:'Eliminar',
            tooltip:'Eliminar un registro',
            iconCls:'remover',
            id:'remover',
			handler: function(){
					   eliminarRegistro();
					}
			}]
	    
	});
	//Fin creando grid de datos que se llenara con el catalgo
	

}//Fin componente lista editable catalogo

