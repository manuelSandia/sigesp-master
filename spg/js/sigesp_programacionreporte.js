/**
 * 
 */
var formagencia         = null;  //instancia del formulario de agencia
var gridProgramacion    = null;
Ext.onReady(function(){
	
	function redondear(numero){
		numero=Math.ceil(numero*10)/10
		AuxString = numero.toString();
		if(AuxString.indexOf('.')>=0)
		{
			AuxArr=AuxString.split('.');
			if(AuxArr[1]>=5)
			{
				numero=Math.ceil(numero);
			}
			else
			{ 
				numero=Math.floor(numero);
			}
		} 
	    return numero;
	}
	
	function mostrarFormatoNumero(valor){
		return formatoNumericoMostrar(valor,2,'.',',','','','-','');
	}
	
	
	function mostrarEtiqueta(valor){
		
		if (valor=='1'){
				return 'Automatica';
		}else if (valor=='0'){
				return 'Manual';	
		}
	}
	
	function validarFormato(formato){
		var strFormato = new String(formato);
		var coma = strFormato.indexOf(',');
		var punto = strFormato.indexOf('.');
		if(coma!=-1 && punto!=-1){
			return true;
		}
		else{
			return false;
		}
	}
	
	function calcularTotal(panel){
		var total  = 0;
		var enero  = 0; var febrero  = 0; var marzo  = 0;
		var abril  = 0; var mayo  = 0; var junio  = 0;
		var julio  = 0; var agosto  = 0; var septiembre  = 0;
		var octubre  = 0; var noviembre  = 0; var diciembre  = 0;
		
		if(validarFormato(panel.findById('enero').getValue())){
			enero = parseFloat(ue_formato_operaciones(panel.findById('enero').getValue()));
		}
		else{
			if(panel.findById('enero').getValue()!=''){
				enero = parseFloat(panel.findById('enero').getValue());
			}
			else{
				enero = 0;
			}
			
		}
		
		if(validarFormato(panel.findById('febrero').getValue())){
			febrero = parseFloat(ue_formato_operaciones(panel.findById('febrero').getValue()));
		}
		else{
			if(panel.findById('febrero').getValue()!=''){
				febrero = parseFloat(panel.findById('febrero').getValue());
			}
			else{
				febrero = 0;
			}
			
		}
		
		if(validarFormato(panel.findById('marzo').getValue())){
			marzo = parseFloat(ue_formato_operaciones(panel.findById('marzo').getValue()));
		}
		else{
			if(panel.findById('marzo').getValue()!=''){
				marzo = parseFloat(panel.findById('marzo').getValue());
			}
			else{
				marzo = 0;
			}
			
		}
		
		if(validarFormato(panel.findById('abril').getValue())){
			abril = parseFloat(ue_formato_operaciones(panel.findById('abril').getValue()));
		}
		else{
			if(panel.findById('abril').getValue()!=''){
				abril = parseFloat(panel.findById('abril').getValue());
			}
			else{
				abril = 0;
			}
		}
		
		if(validarFormato(panel.findById('mayo').getValue())){
			mayo = parseFloat(ue_formato_operaciones(panel.findById('mayo').getValue()));
		}
		else{
			if(panel.findById('mayo').getValue()!=''){
				mayo = parseFloat(panel.findById('mayo').getValue());
			}
			else{
				mayo = 0;
			}
		}
		
		if(validarFormato(panel.findById('junio').getValue())){
			junio = parseFloat(ue_formato_operaciones(panel.findById('junio').getValue()));
		}
		else{
			if(panel.findById('junio').getValue()!=''){
				junio = parseFloat(panel.findById('junio').getValue());
			}
			else{
				junio = 0;
			}
		}
		
		if(validarFormato(panel.findById('julio').getValue())){
			julio = parseFloat(ue_formato_operaciones(panel.findById('julio').getValue()));
		}
		else{
			if(panel.findById('julio').getValue()!=''){
				julio = parseFloat(panel.findById('julio').getValue());
			}
			else{
				julio = 0;
			}
		}
		
		if(validarFormato(panel.findById('agosto').getValue())){
			agosto = parseFloat(ue_formato_operaciones(panel.findById('agosto').getValue()));
		}
		else{
			if(panel.findById('agosto').getValue()!=''){
				agosto = parseFloat(panel.findById('agosto').getValue());
			}
			else{
				agosto = 0;
			}
		}
		
		if(validarFormato(panel.findById('septiembre').getValue())){
			septiembre = parseFloat(ue_formato_operaciones(panel.findById('septiembre').getValue()));
		}
		else{
			if(panel.findById('septiembre').getValue()!=''){
				septiembre = parseFloat(panel.findById('septiembre').getValue());
			}
			else{
				septiembre = 0;
			}
		}
		
		if(validarFormato(panel.findById('octubre').getValue())){
			octubre = parseFloat(ue_formato_operaciones(panel.findById('octubre').getValue()));
		}
		else{
			if(panel.findById('octubre').getValue()!=''){
				octubre = parseFloat(panel.findById('octubre').getValue());
			}
			else{
				octubre = 0;
			}
		}
		
		if(validarFormato(panel.findById('noviembre').getValue())){
			noviembre = parseFloat(ue_formato_operaciones(panel.findById('noviembre').getValue()));
		}
		else{
			if(panel.findById('noviembre').getValue()!=''){
				noviembre = parseFloat(panel.findById('noviembre').getValue());
			}
			else{
				noviembre = 0;
			}
		}
		
		if(validarFormato(panel.findById('diciembre').getValue())){
			diciembre = parseFloat(ue_formato_operaciones(panel.findById('diciembre').getValue()));
		}
		else{
			if(panel.findById('diciembre').getValue()!=''){
				diciembre = parseFloat(panel.findById('diciembre').getValue());
			}
			else{
				diciembre = 0;
			}
		}
		
		total = enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre;
		
		return total;
	}
	
	var plDistribucion = new Ext.FormPanel({
		id:'plDistribucion',
		width:600,
    	height:300,
		frame: true,
		items:[{
			layout: "column",
			defaults: {border: false},
			items: [{
						layout: "form",
						border: false,
						labelWidth: 130,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Enero',
									labelSeparator :'',
									id: 'enero',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                        				if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
								}]
					},{
						layout: "form",
						border: false,
						labelWidth: 70,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Febrero',
									labelSeparator :'',
									id: 'febrero',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
		           		}]
			}]
					
		},{
			layout: "column",
			defaults: {border: false},
			items: [{
						layout: "form",
						border: false,
						labelWidth: 130,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Marzo',
									labelSeparator :'',
									id: 'marzo',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
								}]
					},{
						layout: "form",
						border: false,
						labelWidth: 70,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Abril',
									labelSeparator :'',
									id: 'abril',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
		           		}]
			}]
					
		},{
			layout: "column",
			defaults: {border: false},
			items: [{
						layout: "form",
						border: false,
						labelWidth: 130,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Mayo',
									labelSeparator :'',
									id: 'mayo',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
								}]
					},{
						layout: "form",
						border: false,
						labelWidth: 70,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Junio',
									labelSeparator :'',
									id: 'junio',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
		           		}]
			}]
					
		},{
			layout: "column",
			defaults: {border: false},
			items: [{
						layout: "form",
						border: false,
						labelWidth: 130,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Julio',
									labelSeparator :'',
									id: 'julio',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
								}]
					},{
						layout: "form",
						border: false,
						labelWidth: 70,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Agosto',
									labelSeparator :'',
									id: 'agosto',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
		           		}]
			}]
					
		},{
			layout: "column",
			defaults: {border: false},
			items: [{
						layout: "form",
						border: false,
						labelWidth: 130,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Septiembre',
									labelSeparator :'',
									id: 'septiembre',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
								}]
					},{
						layout: "form",
						border: false,
						labelWidth: 70,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Octubre',
									labelSeparator :'',
									id: 'octubre',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
		           		}]
			}]
					
		},{
			layout: "column",
			defaults: {border: false},
			items: [{
						layout: "form",
						border: false,
						labelWidth: 130,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Noviembre',
									labelSeparator :'',
									id: 'noviembre',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
								}]
					},{
						layout: "form",
						border: false,
						labelWidth: 70,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: 'Diciembre',
									labelSeparator :'',
									id: 'diciembre',
									width: 100,
									listeners: {
	                                	'blur': function(){
	                                		if(!validarFormato(this.getValue())){
	                        					var formatonumero = formatoNumericoMostrar(this.getValue(),2,'.',',','','','-','');
												this.setValue(formatonumero);
											}
	                        					                        		        
											var txtasignado = plDistribucion.findById('asignado');
											var txttotal = plDistribucion.findById('total');
											var montoTotal = calcularTotal(plDistribucion);
																						
											var montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
											if(montoTotal>montoAsigando){
												var formatonumero = formatoNumericoMostrar('',2,'.',',','','','-','');
												this.setValue(formatonumero);
												Ext.Msg.alert('Advertencia','El monto que introdujo es mayor al monto asignado');
											}
											else{
												txttotal.setValue(formatoNumericoMostrar(montoTotal,2,'.',',','','','-',''));
											}
										}
									},
									value: 0
		           		}]
			}]
					
		},{
			layout: "column",
			defaults: {border: false},
			items: [{
						layout: "form",
						border: false,
						labelWidth: 130,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: '<b>Asignado</b>',
									labelSeparator :'',
									id: 'asignado',
									width: 100,
									readonly: true
	                            }]
					},{
						layout: "form",
						border: false,
						labelWidth: 70,
						columnWidth: 0.5,
						items: [{
									xtype: 'textfield',
									fieldLabel: '<b>Total</b>',
									labelSeparator :'',
									id: 'total',
									width: 100,
									readonly: true
		           		}]
			}]
					
		}]
	});
	
	var wiDistribucion =  new Ext.Window({
		title: 'Distribucion Manual',
		width:600,
    	height:300,
    	modal: true,
    	closable:false,
    	plain: false,
		items:[plDistribucion],
		buttons: [{
					text:'Aceptar',  
		        	handler: function(){
		        		var txtasignado = plDistribucion.findById('asignado');
						var txttotal = plDistribucion.findById('total');
						montoAsigando = parseFloat(ue_formato_operaciones(txtasignado.getValue()));
						montoTotal    = parseFloat(ue_formato_operaciones(txttotal.getValue()));
						
		        		if(montoAsigando!=montoTotal){
		        			Ext.Msg.alert('Advertencia','El monto total debe ser igual al monto asignado');
		        		}
		        		else {
		        			registro = gridProgramacion.getSelectionModel().getSelected();
		        			registro.set('enero',parseFloat(ue_formato_operaciones(plDistribucion.findById('enero').getValue())));
		        			registro.set('febrero',parseFloat(ue_formato_operaciones(plDistribucion.findById('febrero').getValue())));
		        			registro.set('marzo',parseFloat(ue_formato_operaciones(plDistribucion.findById('marzo').getValue())));
		        			registro.set('abril',parseFloat(ue_formato_operaciones(plDistribucion.findById('abril').getValue())));
		        			registro.set('mayo',parseFloat(ue_formato_operaciones(plDistribucion.findById('mayo').getValue())));
		        			registro.set('junio',parseFloat(ue_formato_operaciones(plDistribucion.findById('junio').getValue())));
		        			registro.set('julio',parseFloat(ue_formato_operaciones(plDistribucion.findById('julio').getValue())));
		        			registro.set('agosto',parseFloat(ue_formato_operaciones(plDistribucion.findById('agosto').getValue())));
		        			registro.set('septiembre',parseFloat(ue_formato_operaciones(plDistribucion.findById('septiembre').getValue())));
		        			registro.set('octubre',parseFloat(ue_formato_operaciones(plDistribucion.findById('octubre').getValue())));
		        			registro.set('noviembre',parseFloat(ue_formato_operaciones(plDistribucion.findById('noviembre').getValue())));
		        			registro.set('diciembre',parseFloat(ue_formato_operaciones(plDistribucion.findById('diciembre').getValue())));
		        			Ext.Msg.alert('Informacion','Las distribucion manual fue realizada con exito');
		        			wiDistribucion.hide();
		        		}
		        	}
		       	},{
		      		text: 'Salir',
		        	handler:function(){
		        		wiDistribucion.hide();
		        	}
              	}]
  	});
	
	var txtasignado = new Ext.form.TextField({
				allowBlank: false,
				listeners: {
					'blur': function(){
						registro = gridProgramacion.getSelectionModel().getSelected();
						if(registro.get('distribuir')=='0'){
							var form = wiDistribucion.findById('plDistribucion');
		        			form.findById('asignado').setValue(formatoNumericoMostrar(this.getValue(),2,'.',',','','','-',''));
		        			form.findById('enero').setValue(formatoNumericoMostrar(registro.get('enero'),2,'.',',','','','-',''));
		        			form.findById('febrero').setValue(formatoNumericoMostrar(registro.get('febrero'),2,'.',',','','','-',''));
		        			form.findById('marzo').setValue(formatoNumericoMostrar(registro.get('marzo'),2,'.',',','','','-',''));
		        			form.findById('abril').setValue(formatoNumericoMostrar(registro.get('abril'),2,'.',',','','','-',''));
		        			form.findById('mayo').setValue(formatoNumericoMostrar(registro.get('mayo'),2,'.',',','','','-',''));
		        			form.findById('junio').setValue(formatoNumericoMostrar(registro.get('junio'),2,'.',',','','','-',''));
		        			form.findById('julio').setValue(formatoNumericoMostrar(registro.get('julio'),2,'.',',','','','-',''));
		        			form.findById('agosto').setValue(formatoNumericoMostrar(registro.get('agosto'),2,'.',',','','','-',''));
		        			form.findById('septiembre').setValue(formatoNumericoMostrar(registro.get('septiembre'),2,'.',',','','','-',''));
		        			form.findById('octubre').setValue(formatoNumericoMostrar(registro.get('octubre'),2,'.',',','','','-',''));
		        			form.findById('noviembre').setValue(formatoNumericoMostrar(registro.get('noviembre'),2,'.',',','','','-',''));
		        			form.findById('diciembre').setValue(formatoNumericoMostrar(registro.get('diciembre'),2,'.',',','','','-',''));
		        			wiDistribucion.show()
						}
						else{
							asignado = parseFloat(this.getValue());
		        			mes = redondear(asignado/12);
		        			diciembre = redondear(asignado-mes*11);
		        			total = (mes*11)+diciembre;
		        			registro.set('enero',mes);
		        			registro.set('febrero',mes);
		        			registro.set('marzo',mes);
		        			registro.set('abril',mes);
		        			registro.set('mayo',mes);
		        			registro.set('junio',mes);
		        			registro.set('julio',mes);
		        			registro.set('agosto',mes);
		        			registro.set('septiembre',mes);
		        			registro.set('octubre',mes);
		        			registro.set('noviembre',mes);
		        			registro.set('diciembre',diciembre);
		        			  
		        			Ext.Msg.alert('Informacion','Las distribucion automatica fue realizada con exito');
						}
	        		}
				}
	});
	
	//combo distribucion
	var reOpciones = [['1', 'Automatica'], ['0', 'Manual']];
    var dsDistribucion = new Ext.data.SimpleStore({
        					fields: ['valor', 'etiqueta'],
        					data: reOpciones 
    });
    
    var cmbDistribucion = new Ext.form.ComboBox({
        store: dsDistribucion,
        editable: false,
        displayField: 'etiqueta',
        valueField: 'valor',
        name: 'distribucion',
        id: 'distribucion',
        typeAhead: true,
        triggerAction: 'all',
        mode: 'local',
        listeners: {
        	 'blur': function(Obj, e){
        		 if(Obj.value=='0'){
        			 registro = gridProgramacion.getSelectionModel().getSelected();
        			 var form = wiDistribucion.findById('plDistribucion');
        			 form.findById('asignado').setValue(formatoNumericoMostrar(registro.get('asignado'),2,'.',',','','','-',''));
        			 form.findById('enero').setValue(formatoNumericoMostrar(registro.get('enero'),2,'.',',','','','-',''));
        			 form.findById('febrero').setValue(formatoNumericoMostrar(registro.get('febrero'),2,'.',',','','','-',''));
        			 form.findById('marzo').setValue(formatoNumericoMostrar(registro.get('marzo'),2,'.',',','','','-',''));
        			 form.findById('abril').setValue(formatoNumericoMostrar(registro.get('abril'),2,'.',',','','','-',''));
        			 form.findById('mayo').setValue(formatoNumericoMostrar(registro.get('mayo'),2,'.',',','','','-',''));
        			 form.findById('junio').setValue(formatoNumericoMostrar(registro.get('junio'),2,'.',',','','','-',''));
        			 form.findById('julio').setValue(formatoNumericoMostrar(registro.get('julio'),2,'.',',','','','-',''));
        			 form.findById('agosto').setValue(formatoNumericoMostrar(registro.get('agosto'),2,'.',',','','','-',''));
        			 form.findById('septiembre').setValue(formatoNumericoMostrar(registro.get('septiembre'),2,'.',',','','','-',''));
        			 form.findById('octubre').setValue(formatoNumericoMostrar(registro.get('octubre'),2,'.',',','','','-',''));
        			 form.findById('noviembre').setValue(formatoNumericoMostrar(registro.get('noviembre'),2,'.',',','','','-',''));
        			 form.findById('diciembre').setValue(formatoNumericoMostrar(registro.get('diciembre'),2,'.',',','','','-',''));
        			 wiDistribucion.show()
        		 }
        		 else{
        			  registro = gridProgramacion.getSelectionModel().getSelected();
        			  asignado = parseFloat(ue_formato_operaciones(registro.get('asignado')));
        			  mes = redondear(asignado/12);
        			  diciembre = redondear(asignado-mes*11);
        			  total = (mes*11)+diciembre;
        			  registro.set('enero',mes);
        			  registro.set('febrero',mes);
        			  registro.set('marzo',mes);
        			  registro.set('abril',mes);
        			  registro.set('mayo',mes);
        			  registro.set('junio',mes);
        			  registro.set('julio',mes);
        			  registro.set('agosto',mes);
        			  registro.set('septiembre',mes);
        			  registro.set('octubre',mes);
        			  registro.set('noviembre',mes);
        			  registro.set('diciembre',diciembre);
        			  
        			  Ext.Msg.alert('Informacion','Las distribucion automatica fue realizada con exito');
        		 }
        	 }
        }
    })
	
	//creando datastore y columnmodel para la grid de reintegros
	var reProgramacion = new  Ext.data.Record.create([
	                            {name: 'spg_cuenta'},
	                            {name: 'denominacion'},
	                            {name: 'status'},
	                            {name: 'sc_cuenta'},
	                            {name: 'asignado'},
	                            {name: 'distribuir'},
	                            {name: 'enero'},
	                            {name: 'febrero'},
	                            {name: 'marzo'},
	                            {name: 'abril'},
	                            {name: 'mayo'},
	                            {name: 'junio'},
	                            {name: 'julio'},
	                            {name: 'agosto'},
	                            {name: 'septiembre'},
	                            {name: 'octubre'},
	                            {name: 'noviembre'},
	                            {name: 'diciembre'},
	                            {name: 'nivel'},
	                            {name: 'referencia'}
	]);
	
	var dsProgramacion =  new Ext.data.Store({
								reader: new Ext.data.JsonReader({root: 'raiz',id: "id"},reProgramacion)
	});
						
	var cmProgramacion = new Ext.grid.ColumnModel([
          				{header: 'Cuenta', width: 80, sortable: true, dataIndex: 'spg_cuenta'},
          				{header: 'Denominacion', width: 300, sortable: true, dataIndex: 'denominacion'},
          				{header: 'Asignado', width: 100, sortable: true, dataIndex: 'asignado',editor:txtasignado,renderer:mostrarFormatoNumero},
          				{header: 'Distribucion', width: 100, sortable: true, dataIndex: 'distribuir',editor:cmbDistribucion,renderer:mostrarEtiqueta},
    ]);
	//creando datastore y columnmodel para la grid de reintegros
	
	//creando grid para los reintegros
	gridProgramacion = new Ext.grid.EditorGridPanel({
			applyTo: 'formulario',
			width:580,
	 		height:150,
			title:'Programacion',
			autoScroll:true,
     		border:true,
     		ds: dsProgramacion,
       		cm: cmProgramacion,
       		sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
       		stripeRows: true,
      		viewConfig: {forceFit:true}
	});
	//fin creando grid para los reintegros
	
});

