/***********************************************************************************
* @Archivo JavaScript que genera un fieldset con las estructuras presupuestarias 
* @fecha de creacion: 08/10/2009
* @autor: Ing. Arnaldo Suarez
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
Ext.namespace('com.sigesp.vista'); 
 
//Objeto que construye el fieldset para seleccionar una estructura presupuestaria
com.sigesp.vista.comFieldSetEstructuraPresupuesto =  function(options){
	
	Ext.override(Ext.form.Field, {
		afterRender: Ext.form.Field.prototype.afterRender.createSequence(function(){
			if(this.qtip){
				var target = this.getTipTarget();
				if(typeof this.qtip == 'object'){
					Ext.QuickTips.register(Ext.apply({
						  target: target
					}, this.qtip));
				} else {
					target.dom.qtip = this.qtip;
				}
			}
		}),
		getTipTarget: function(){
			return this.el;
		}
	});
	this.operacion="";                  // Variable que controla la operacion para la carga de datos en el DataStore de los Catalogos
	this.widthColumnaCampo    = 0;      // Ancho de la Columna que contiene el Codigo de la Estructura
	this.widthColumnaBoton    = 0;      // Ancho de la Columna que contiene el Boton de Busqueda
	this.widthColumnaEtiqueta = 0;      // Ancho de la Columna que contiene la Etiqueta que describe la Estructura
	this.ocultarDenominacion  = false;  // Variable que controla si se oculta o no la denominacion de la Estrucutura
	
	this.obtenerHeightFieldSet=function() // Funcion que determina la altura del Fielset en función del número de niveles
	{
		var height = 120;
		
		switch(parseInt(empresa['numniv']))
		{
		
		case 1: height=40;
		break;
		
		case 2: height=80;
		break;
		
		case 3: height=130;
		break;
		
		case 4: height=160;
		break;
		
		case 5: height=200;
		break;
		
		}
		return height;
	}
	
	this.obtenerWidthFieldSet=function() // Funcion que determina el ancho del FieldSet en función si se muestra o no la denominación
	{
		var width = 500;
		if(!options.mostrarDenominacion)
		{
			width = 450;
			this.widthColumnaCampo    = 0.92; 
			this.widthColumnaBoton    = 0.08;
			this.widthColumnaEtiqueta = 0;
			this.ocultarDenominacion  = true;
		}
		else
		{
			//width = 650;
			width = 750;
			this.widthColumnaCampo    = 0.55; 
			this.widthColumnaBoton    = 0.05;
			this.widthColumnaEtiqueta = 0.40;
		}
		
		return width;
	}
	
	this.altura  = this.obtenerHeightFieldSet(); // Altura del FieldSet
	
	this.anchura = this.obtenerWidthFieldSet(); // Anchura del FieldSet
	
	this.mostrarEstatus=function(est){
		
		if (est=='P'){
				return 'Proyecto';
		}else if (est=='A'){
				return 'Acci&#243;n Centralizada';	
		}else if (est=='-'){
				return 'POR DEFECTO';	
		}
	}

	this.mostrarNumDigNiv1=function(estructura)
	{
	 var formatoEstructura="";
	 formatoEstructura = estructura.substr(-empresa['loncodestpro1'])
	 return formatoEstructura;
	}

	this.mostrarNumDigNiv2=function(estructura)
	{
	 var formatoEstructura="";
	 formatoEstructura = estructura.substr(-empresa['loncodestpro2'])
	 return formatoEstructura;
	}

	this.mostrarNumDigNiv3=function(estructura)
	{
	 var formatoEstructura="";
	 formatoEstructura = estructura.substr(-empresa['loncodestpro3'])
	 return formatoEstructura;
	}

	this.mostrarNumDigNiv4=function(estructura)
	{
	 var formatoEstructura="";
	 formatoEstructura = estructura.substr(-empresa['loncodestpro4'])
	 return formatoEstructura;
	}

	this.mostrarNumDigNiv5=function(estructura)
	{
	 var formatoEstructura="";
	 formatoEstructura = estructura.substr(-empresa['loncodestpro5'])
	 return formatoEstructura;
	}
	
	
	this.catalogoEstructuraNivel1=function(){
		this.crear_grid_catalogoestructura('nivel1');				   
	    ventana = new Ext.Window({
	    	title: 'Cat&#225;logo de '+empresa['nomestpro1'],
			autoScroll:true,
	        width:800,
	        height:475,
	        modal: true,
	        closable:false,
	        plain: false,
	        items:[this.gridestructuranivel1],
	        buttons: [{
						text:'Aceptar',  
				        handler: this.setDataEstructuraNivel1.createDelegate(this)
				       },
				       {
				      	text: 'Salir',
				        handler: this.cerrarVentanaEstructuraNivel1.createDelegate(this)
	                  }]
	      });
	      ventana.show();
	}
	
	this.catalogoEstructuraNivel2=function(){
		if((this.fieldSetEstPre.findById('codest'+options.idtxt+'0').getValue() =="")&&(parseInt(empresa['numniv']) != 2))
		{
			this.mensajeValidacionNivel(1)
		}
		else
		{	
			this.crear_grid_catalogoestructura('nivel2');				   
	    ventana = new Ext.Window({
	    	title: 'Cat&#225;logo de '+empresa['nomestpro2'],
			autoScroll:true,
	        width:800,
	        height:475,
	        modal: true,
	        closable:false,
	        plain: false,
	        items:[this.gridestructuranivel2],
	        buttons: [{
						text:'Aceptar',  
				        handler: this.setDataEstructuraNivel2.createDelegate(this)
				       },
				       {
				      	text: 'Salir',
				        handler: this.cerrarVentanaEstructuraNivel2.createDelegate(this)
	                  }]
	      });
	      ventana.show();
		}
	}
	
	this.catalogoEstructuraNivel3=function(){
		if((this.fieldSetEstPre.findById('codest'+options.idtxt+'1').getValue() =="")&&(parseInt(empresa['numniv']) != 3))
		{
			this.mensajeValidacionNivel(2)
		}
		else
		{	
			this.crear_grid_catalogoestructura('nivel3');				   
			ventana = new Ext.Window({
	    	title: 'Cat&#225;logo de '+empresa['nomestpro3'],
			autoScroll:true,
	        width:800,
	        height:475,
	        modal: true,
	        closable:false,
	        plain: false,
	        items:[this.gridestructuranivel3],
	        buttons: [{
						text:'Aceptar',  
				        handler: this.setDataEstructuraNivel3.createDelegate(this)
				       },
				       {
				      	text: 'Salir',
				        handler: this.cerrarVentanaEstructuraNivel3.createDelegate(this)
	                  }]
	      });
	      ventana.show();
		}
	}
	
	this.catalogoEstructuraNivel4=function(){
		if((this.fieldSetEstPre.findById('codest'+options.idtxt+'2').getValue() =="")&&(parseInt(empresa['numniv']) != 4))
		{
			this.mensajeValidacionNivel(3)
		}
		else
		{	
			this.crear_grid_catalogoestructura('nivel4');				   
			ventana = new Ext.Window({
	    	title: 'Cat&#225;logo de '+empresa['nomestpro4'],
			autoScroll:true,
	        width:800,
	        height:475,
	        modal: true,
	        closable:false,
	        plain: false,
	        items:[this.gridestructuranivel4],
	        buttons: [{
						text:'Aceptar',  
				        handler: this.setDataEstructuraNivel4.createDelegate(this)
				       },
				       {
				      	text: 'Salir',
				        handler: this.cerrarVentanaEstructuraNivel4.createDelegate(this)
	                  }]
	      });
	      ventana.show();
		}
	}
	
	this.catalogoEstructuraNivel5=function(){
		if((comestructura.fieldSetEstPre.getComponent('codest'+options.idtxt+'3').getValue() =="")&&(parseInt(empresa['numniv']) != 5))
		{
			mensajeValidacionNivel(4)
		}
		else
		{	
		 this.crear_grid_catalogoestructura('nivel5');				   
	    ventana = new Ext.Window({
	    	title: 'Cat&#225;logo de '+empresa['nomestpro5'],
			autoScroll:true,
	        width:800,
	        height:475,
	        modal: true,
	        closable:false,
	        plain: false,
	        items:[this.gridestructuranivel5],
	        buttons: [{
						text:'Aceptar',  
				        handler: this.setDataEstructuraNivel5.createDelegate(this)
				       },
				       {
				      	text: 'Salir',
				        handler: this.cerrarVentanaEstructuraNivel5.createDelegate(this)
	                  }]
	      });
	      ventana.show();
		}
	}
	
	this.setDataEstructuraNivelN=function()
	{
		estnivelN = this.gridestructuranivelN.getSelectionModel().getSelected();
		if(estnivelN != null)
		{
			for (var i = parseInt(empresa['numniv']) - 1 ; i >= 0; i--){
				var estructura = "";
				switch(i)
				{
					case 4: estructura=this.mostrarNumDigNiv5(estnivelN.get('codestpro'+(i+1)));
					break;
					
					case 3: estructura=this.mostrarNumDigNiv4(estnivelN.get('codestpro'+(i+1)));
					break;
					
					case 2: estructura=this.mostrarNumDigNiv3(estnivelN.get('codestpro'+(i+1)));
					break;
					
					case 1: estructura=this.mostrarNumDigNiv2(estnivelN.get('codestpro'+(i+1)));
					break;
					
					case 0: estructura=this.mostrarNumDigNiv1(estnivelN.get('codestpro'+(i+1)));
					break;
				}
				
				this.fieldSetEstPre.findById('codest'+options.idtxt+i).setValue(estructura);
				if(this.fieldSetEstPre.findById('denest'+options.idtxt+i) != null)
				{
					this.fieldSetEstPre.findById('denest'+options.idtxt+i).setText(estnivelN.get('denestpro'+(i+1)))
				}
			};
			this.fieldSetEstPre.findById('estcla'+options.idtxt).setValue(estnivelN.get('estcla'));
			this.gridestructuranivelN.destroy();
			ventana.destroy();
		}
		else
		{
			Ext.Msg.show({
			   	title:'Mensaje',
			   	msg: 'No ha seleccionado ninguna estructura, verifique por favor',
			   	buttons: Ext.Msg.OK,
			   	animEl: 'elId',
			   	icon: Ext.MessageBox.ERROR,
			   	closable:false
				});
		}
	}
	
	this.catalogoEstructuraNivelN=function(){
		if((this.fieldSetEstPre.findById('codest'+options.idtxt+'0').getValue()!="")&&(this.fieldSetEstPre.findById('codest'+options.idtxt+(parseInt(empresa['numniv'])-1)).getValue()=="")){
			var funcion = new Array();
			funcion[0]=this.catalogoEstructuraNivel1.createDelegate(this);
			funcion[1]=this.catalogoEstructuraNivel2.createDelegate(this);
			funcion[2]=this.catalogoEstructuraNivel3.createDelegate(this);
			funcion[3]=this.catalogoEstructuraNivel4.createDelegate(this);
			funcion[4]=this.catalogoEstructuraNivel5.createDelegate(this);
			funcion[parseInt(empresa['numniv'])-1]();
		}else{
			//var fncreargrid = this.crear_grid_catalogoestructura.createDelegate(this);
			//fncreargrid('nivelN');
			this.crear_grid_catalogoestructura('nivelN');
	    	ventana = new Ext.Window({
	    	title: 'Cat&#225;logo de Estructuras Presupuestarias',
			autoScroll:true,
	        width:800,
	        height:475,
	        modal: true,
	        closable:false,
	        plain: false,
	        items:[this.gridestructuranivelN],
	        buttons: [{
						text:'Aceptar',  
				        handler: this.setDataEstructuraNivelN.createDelegate(this)
				       },
				       {
				      	text: 'Salir',
				        handler: this.cerrarVentanaEstructuraNivelN.createDelegate(this)
	                  }]
	      	});
	      	ventana.show();
		}
	}
	
  this.obtenerFieldSetEstructura=function()
  {
   var fieldset = null;
   switch(parseInt(empresa['numniv']))
   {
	 case 1 : 
		  fieldset =new Ext.form.FieldSet({
											width: this.anchura,
											height: this.altura,
											title: options.titform,
											style: options.estilo,
											autoScroll:true,
											items: [{
														xtype: 'hidden',
														name: 'estcla'+options.idtxt,
														id: 'estcla'+options.idtxt
													},
													{ 
													   layout : "column", 
													   defaults : 
													   { 
														border : false,
														labelWidth: 200
													   }, 
													   items : [{ 
																 layout : "form", 
																 border : false, 
																 defaultType : "textfield", 
																 columnWidth : this.widthColumnaCampo,
																 items : [{ 
																		   fieldLabel: empresa['nomestpro1'], 
																		   name: 'codigo'+options.idtxt+'0', 
																		   id: 'codest'+options.idtxt+'0', 
																		   readOnly:true, 
																		   style:"text-align:right; margin-top: 5px;", 
																		   width: 185
																		  }
																		]
																 }, 
														{ 
														 defaultType : "button", 
														 columnWidth : this.widthColumnaBoton, 
														 items : [{ 
																  iconCls: "menubuscar",
																  style: "margin-top: 5px",
																  id:'btnest'+options.idtxt+'0',
																  handler:this.catalogoEstructuraNivelN.createDelegate(this)
																 }]
													   },
													   {
														columnWidth : this.widthColumnaEtiqueta, 
														defaultType: "label",
														items : [{ 
																  name: "denon"+options.idtxt+'0', 
																  id: "denest"+options.idtxt+'0',
																  style: "margin-top: 5px;",
																  hidden: this.ocultarDenominacion 
																 }] 
													   }]
													}]
											});
  break;	
  case 2 : 
		  fieldset =new Ext.form.FieldSet({
											width: this.anchura,
											height: this.altura,
											title: options.titform,
											style: options.estilo,
											autoScroll:true,
											items: [{
														xtype: 'hidden',
														name: 'estcla'+options.idtxt,
														id: 'estcla'+options.idtxt
													}
													,{ 
													   layout : "column", 
													   defaults : 
													   { 
														border : false,
														labelWidth: 200
													   }, 
													   items : [{ 
																 layout : "form", 
																 border : false, 
																 defaultType : "textfield", 
																 columnWidth : this.widthColumnaCampo,
																 items : [{ 
																		   fieldLabel: empresa['nomestpro1'], 
																		   name: 'codigo'+options.idtxt+'0', 
																		   id: 'codest'+options.idtxt+'0', 
																		   readOnly:true, 
																		   style:"text-align:right; margin-top: 5px;", 
																		   width: 185
																		  }
																		]
																 }, 
														{ 
														 defaultType : "button", 
														 columnWidth : this.widthColumnaBoton, 
														 items : [{ 
																  iconCls: "menubuscar",
																  style: "margin-top: 5px",
																  id:'btnest'+options.idtxt+'0',
																  handler:this.catalogoEstructuraNivel1.createDelegate(this)
																 }]
													   },
													   {
														columnWidth : this.widthColumnaEtiqueta, 
														defaultType: "label",
														items : [{ 
																  name: "denon"+options.idtxt+'0', 
																  id: "denest"+options.idtxt+'0',
																  style: "margin-top: 5px;",
																  hidden: this.ocultarDenominacion 
																 }] 
													   }]
													},{ 
														   layout : "column", 
														   defaults : 
														   { 
															border : false,
															labelWidth:200
														   }, 
														   items : [{ 
																	 layout : "form", 
																	 border : false, 
																	 defaultType : "textfield", 
																	 columnWidth : this.widthColumnaCampo,
																	 items : [{ 
																			   fieldLabel: empresa['nomestpro2'], 
																			   name: 'codigo'+options.idtxt+'1', 
																			   id: 'codest'+options.idtxt+'1', 
																			   readOnly:true, 
																			   style:"text-align:right; margin-top: 5px;", 
																			   width: 185 
																			  }
																			]
																	 }, 
															{ 
															 layout : "form", 
															 border : false, 
															 defaultType : "button", 
															 columnWidth : this.widthColumnaBoton, 
															 items : [{ 
																	  iconCls: "menubuscar",
																	  style: "margin-top: 5px",
																	  id:'btnest'+options.idtxt+'1',
																	  handler:this.catalogoEstructuraNivelN.createDelegate(this)
																	 }]
														   },
														   {
															layout : "form", 
															border : false,    
															columnWidth : this.widthColumnaEtiqueta, 
															defaultType: "label", 
															items : [{ 
																	  name: "denon"+options.idtxt+'1', 
																	  id: "denest"+options.idtxt+'1',
																	  style:"margin-top: 5px",
																	  width:200,
																	  hidden:this.ocultarDenominacion 
																	 }] 
														   }]
														}]
											});
											break;		   
  case 3 : 
		  fieldset =new Ext.form.FieldSet({
											width: this.anchura,
											height: this.altura,
											title: options.titform,
											style: options.estilo,
											autoScroll:true,
											items: [{
														xtype: 'hidden',
														name: 'estcla'+options.idtxt,
														id: 'estcla'+options.idtxt
													}
													,{ 
													   layout : "column", 
													   defaults : 
													   { 
														border : false,
														labelWidth: 200
													   }, 
													   items : [{ 
																 layout : "form", 
																 border : false,
																 defaultType : "textfield", 
																 columnWidth : this.widthColumnaCampo,
																 items : [{ 
																		   fieldLabel: empresa['nomestpro1'], 
																		   name: 'codigo'+options.idtxt+'0', 
																		   id: 'codest'+options.idtxt+'0', 
																		   readOnly:true, 
																		   style:"text-align:right; margin-top: 5px;", 
																		   width: 185,
																		   qtip:empresa['nomestpro1']
																		  }
																		]
																 }, 
														{ 
														 defaultType : "button", 
														 columnWidth : this.widthColumnaBoton, 
														 items : [{ 
																  iconCls: "menubuscar",
																  style: "margin-top: 5px",
																  id:'btnest'+options.idtxt+'0',
																  handler:this.catalogoEstructuraNivel1.createDelegate(this)
																 }]
													   },
													   {
														columnWidth : this.widthColumnaEtiqueta, 
														defaultType: "label",
														items : [{ 
																  name: "denon"+options.idtxt+'0', 
																  id: "denest"+options.idtxt+'0',
																  style: "margin-top: 5px;",
																  hidden: this.ocultarDenominacion 
																 }] 
													   }]
													},{ 
														   layout : "column", 
														   defaults : 
														   { 
															border : false,
															labelWidth:200
														   }, 
														   items : [{ 
																	 layout : "form", 
																	 border : false, 
																	 defaultType : "textfield", 
																	 columnWidth : this.widthColumnaCampo,
																	 items : [{ 
																			   fieldLabel: empresa['nomestpro2'], 
																			   name: 'codigo'+options.idtxt+'1', 
																			   id: 'codest'+options.idtxt+'1', 
																			   readOnly:true, 
																			   style:"text-align:right; margin-top: 5px;", 
																			   width: 185 
																			  }
																			]
																	 }, 
															{ 
															 layout : "form", 
															 border : false, 
															 defaultType : "button", 
															 columnWidth : this.widthColumnaBoton, 
															 items : [{ 
																	  iconCls: "menubuscar",
																	  style: "margin-top: 5px",
																	  id:'btnest'+options.idtxt+'1',
																	  handler:this.catalogoEstructuraNivel2.createDelegate(this)
																	 }]
														   },
														   {
															layout : "form", 
															border : false,    
															columnWidth : this.widthColumnaEtiqueta, 
															defaultType: "label", 
															items : [{ 
																	  name: "denon"+options.idtxt+'1', 
																	  id: "denest"+options.idtxt+'1',
																	  style:"margin-top: 5px",
																	  width:200,
																	  hidden:this.ocultarDenominacion 
																	 }] 
														   }]
														},{ 
															   layout : "column", 
															   defaults : 
															   { 
																border : false,
																labelWidth: 200
															   }, 
															   items : [{ 
																		 layout : "form", 
																		 border : false, 
																		 defaultType : "textfield", 
																		 columnWidth : this.widthColumnaCampo,
																		 items : [{ 
																				   fieldLabel: empresa['nomestpro3'], 
																				   name: 'codigo'+options.idtxt+'2', 
																				   id: 'codest'+options.idtxt+'2', 
																				   readOnly:true, 
																				   style:"text-align:right; margin-top: 5px", 
																				   width: 185 
																				  }
																				]
																		 }, 
																{ 
																 defaultType : "button", 
																 columnWidth : this.widthColumnaBoton,
																 items : [{ 
																		  iconCls: "menubuscar", 
																		  style: "margin-top: 5px",
																		  id:'btnest'+options.idtxt+'2',
																		  handler:this.catalogoEstructuraNivelN.createDelegate(this)
																		 }]
															   },
															   {
																columnWidth : this.widthColumnaEtiqueta, 
																defaultType: "label", 
																items : [{ 
																		  name: "denon"+options.idtxt+'2', 
																		  id: "denest"+options.idtxt+'2', 
																		  style: "margin-top: 5px",
																		  hidden:this.ocultarDenominacion
																		 }] 
															   }]
												 }]
											});
  break; 
  case 4 : 
		  fieldset =new Ext.form.FieldSet({
											width: this.anchura,
											height: this.altura,
											title: options.titform,
											style: options.estilo,
											autoScroll:true,
											items: [{
														xtype: 'hidden',
														name: 'estcla'+options.idtxt,
														id: 'estcla'+options.idtxt
													}
													,{ 
													   layout : "column", 
													   defaults : 
													   { 
														border : false,
														labelWidth: 200
													   }, 
													   items : [{ 
																 layout : "form", 
																 border : false, 
																 defaultType : "textfield", 
																 columnWidth : this.widthColumnaCampo,
																 items : [{ 
																		   fieldLabel: empresa['nomestpro1'], 
																		   name: 'codigo'+options.idtxt+'0', 
																		   id: 'codest'+options.idtxt+'0', 
																		   readOnly:true, 
																		   style:"text-align:right; margin-top: 5px;", 
																		   width: 185
																		  }
																		]
																 }, 
														{ 
														 defaultType : "button", 
														 columnWidth : this.widthColumnaBoton, 
														 items : [{ 
																  iconCls: "menubuscar",
																  style: "margin-top: 5px",
																  id:'btnest'+options.idtxt+'0',
																  handler:this.catalogoEstructuraNivel1.createDelegate(this)
																 }]
													   },
													   {
														columnWidth : this.widthColumnaEtiqueta, 
														defaultType: "label",
														items : [{ 
																  name: "denon"+options.idtxt+'0', 
																  id: "denest"+options.idtxt+'0',
																  style: "margin-top: 5px;",
																  hidden: this.ocultarDenominacion 
																 }] 
													   }]
													},{ 
														   layout : "column", 
														   defaults : 
														   { 
															border : false,
															labelWidth:200
														   }, 
														   items : [{ 
																	 layout : "form", 
																	 border : false, 
																	 defaultType : "textfield", 
																	 columnWidth : this.widthColumnaCampo,
																	 items : [{ 
																			   fieldLabel: empresa['nomestpro2'], 
																			   name: 'codigo'+options.idtxt+'1', 
																			   id: 'codest'+options.idtxt+'1', 
																			   readOnly:true, 
																			   style:"text-align:right; margin-top: 5px;", 
																			   width: 185 
																			  }
																			]
																	 }, 
															{ 
															 layout : "form", 
															 border : false, 
															 defaultType : "button", 
															 columnWidth : this.widthColumnaBoton, 
															 items : [{ 
																	  iconCls: "menubuscar",
																	  style: "margin-top: 5px",
																	  id:'btnest'+options.idtxt+'1',
																	  handler:this.catalogoEstructuraNivel2.createDelegate(this)
																	 }]
														   },
														   {
															layout : "form", 
															border : false,    
															columnWidth : this.widthColumnaEtiqueta, 
															defaultType: "label", 
															items : [{ 
																	  name: "denon"+options.idtxt+'1', 
																	  id: "denest"+options.idtxt+'1',
																	  style:"margin-top: 5px",
																	  width:200,
																	  hidden:this.ocultarDenominacion 
																	 }] 
														   }]
														},
														{ 
															   layout : "column", 
															   defaults : 
															   { 
																border : false,
																labelWidth: 200
															   }, 
															   items : [{ 
																		 layout : "form", 
																		 border : false, 
																		 defaultType : "textfield", 
																		 columnWidth : this.widthColumnaCampo,
																		 items : [{ 
																				   fieldLabel: empresa['nomestpro3'], 
																				   name: 'codigo'+options.idtxt+'2', 
																				   id: 'codest'+options.idtxt+'2', 
																				   readOnly:true, 
																				   style:"text-align:right; margin-top: 5px", 
																				   width: 185 
																				  }
																				]
																		 }, 
																{ 
																 defaultType : "button", 
																 columnWidth : this.widthColumnaBoton,
																 items : [{ 
																		  iconCls: "menubuscar", 
																		  style: "margin-top: 5px",
																		  id:'btnest'+options.idtxt+'2',
																		  handler:this.catalogoEstructuraNivel3.createDelegate(this)
																		 }]
															   },
															   {
																columnWidth : this.widthColumnaEtiqueta, 
																defaultType: "label", 
																items : [{ 
																		  name: "denon"+options.idtxt+'2', 
																		  id: "denest"+options.idtxt+'2', 
																		  style: "margin-top: 5px",
																		  hidden:this.ocultarDenominacion
																		 }] 
															   }]
												  },
												  { 
															   layout : "column", 
															   defaults : 
															   { 
																border : false,
																labelWidth: 200
															   }, 
															   items : [{ 
																		 layout : "form", 
																		 border : false, 
																		 defaultType : "textfield", 
																		 columnWidth : this.widthColumnaCampo,
																		 items : [{ 
																				   fieldLabel: empresa['nomestpro4'], 
																				   name: 'codigo'+options.idtxt+'3', 
																				   id: 'codest'+options.idtxt+'3', 
																				   readOnly:true, 
																				   style:"text-align:right; margin-top: 5px", 
																				   width: 185 
																				  }
																				]
																		 }, 
																{ 
																 defaultType : "button", 
																 columnWidth : this.widthColumnaBoton,
																 items : [{ 
																		  iconCls: "menubuscar", 
																		  style: "margin-top: 5px",
																		  id:'btnest'+options.idtxt+'4',
																		  handler:this.catalogoEstructuraNivelN.createDelegate(this)
																		 }]
															   },
															   {
																columnWidth : this.widthColumnaEtiqueta, 
																defaultType: "label", 
																items : [{ 
																		  name: "denon"+options.idtxt+'3', 
																		  id: "denest"+options.idtxt+'3', 
																		  style: "margin-top: 5px",
																		  hidden:this.ocultarDenominacion
																		 }] 
															   }]
												 }]
											});
		  break;
  case 5 : 
		  fieldset =new Ext.form.FieldSet({
											width: this.anchura,
											height: this.altura,
											title: options.titform,
											style: options.estilo,
											autoScroll:true,
											items: [{
														xtype: 'hidden',
														name: 'estcla'+options.idtxt,
														id: 'estcla'+options.idtxt
													}
													,{ 
													   layout : "column", 
													   defaults : 
													   { 
														border : false,
														labelWidth: 200
													   }, 
													   items : [{ 
																 layout : "form", 
																 border : false, 
																 defaultType : "textfield", 
																 columnWidth : this.widthColumnaCampo,
																 items : [{ 
																		   fieldLabel: empresa['nomestpro1'], 
																		   name: 'codigo'+options.idtxt+'0', 
																		   id: 'codest'+options.idtxt+'0', 
																		   readOnly:true, 
																		   style:"text-align:right; margin-top: 5px;", 
																		   width: 185
																		  }
																		]
																 }, 
														{ 
														 defaultType : "button", 
														 columnWidth : this.widthColumnaBoton, 
														 items : [{ 
																  iconCls: "menubuscar",
																  style: "margin-top: 5px",
																  id:'btnest'+options.idtxt+'0',
																  handler:this.catalogoEstructuraNivel1.createDelegate(this)
																 }]
													   },
													   {
														columnWidth : this.widthColumnaEtiqueta, 
														defaultType: "label",
														items : [{ 
																  name: "denon"+options.idtxt+'0', 
																  id: "denest"+options.idtxt+'0',
																  style: "margin-top: 5px;",
																  hidden: this.ocultarDenominacion 
																 }] 
													   }]
													},{ 
														   layout : "column", 
														   defaults : 
														   { 
															border : false,
															labelWidth:200
														   }, 
														   items : [{ 
																	 layout : "form", 
																	 border : false, 
																	 defaultType : "textfield", 
																	 columnWidth : this.widthColumnaCampo,
																	 items : [{ 
																			   fieldLabel: empresa['nomestpro2'], 
																			   name: 'codigo'+options.idtxt+'1', 
																			   id: 'codest'+options.idtxt+'1', 
																			   readOnly:true, 
																			   style:"text-align:right; margin-top: 5px;", 
																			   width: 185 
																			  }
																			]
																	 }, 
															{ 
															 layout : "form", 
															 border : false, 
															 defaultType : "button", 
															 columnWidth : this.widthColumnaBoton, 
															 items : [{ 
																	  iconCls: "menubuscar",
																	  style: "margin-top: 5px",
																	  id:'btnest'+options.idtxt+'1',
																	  handler:this.catalogoEstructuraNivel2.createDelegate(this)
																	 }]
														   },
														   {
															layout : "form", 
															border : false,    
															columnWidth : this.widthColumnaEtiqueta, 
															defaultType: "label", 
															items : [{ 
																	  name: "denon"+options.idtxt+'1', 
																	  id: "denest"+options.idtxt+'1',
																	  style:"margin-top: 5px",
																	  width:200,
																	  hidden:this.ocultarDenominacion 
																	 }] 
														   }]
														},
														{ 
															   layout : "column", 
															   defaults : 
															   { 
																border : false,
																labelWidth: 200
															   }, 
															   items : [{ 
																		 layout : "form", 
																		 border : false, 
																		 defaultType : "textfield", 
																		 columnWidth : this.widthColumnaCampo,
																		 items : [{ 
																				   fieldLabel: empresa['nomestpro3'], 
																				   name: 'codigo'+options.idtxt+'2', 
																				   id: 'codest'+options.idtxt+'2', 
																				   readOnly:true, 
																				   style:"text-align:right; margin-top: 5px", 
																				   width: 185 
																				  }
																				]
																		 }, 
																{ 
																 defaultType : "button", 
																 columnWidth : this.widthColumnaBoton,
																 items : [{ 
																		  iconCls: "menubuscar", 
																		  style: "margin-top: 5px",
																		  id:'btnest'+options.idtxt+'2',
																		  handler:this.catalogoEstructuraNivel3.createDelegate(this)
																		 }]
															   },
															   {
																columnWidth : this.widthColumnaEtiqueta, 
																defaultType: "label", 
																items : [{ 
																		  name: "denon"+options.idtxt+'2', 
																		  id: "denest"+options.idtxt+'2', 
																		  style: "margin-top: 5px",
																		  hidden:this.ocultarDenominacion
																		 }] 
															   }]
												  },
												  { 
															   layout : "column", 
															   defaults : 
															   { 
																border : false,
																labelWidth: 200
															   }, 
															   items : [{ 
																		 layout : "form", 
																		 border : false, 
																		 defaultType : "textfield", 
																		 columnWidth : this.widthColumnaCampo,
																		 items : [{ 
																				   fieldLabel: empresa['nomestpro4'], 
																				   name: 'codigo'+options.idtxt+'3', 
																				   id: 'codest'+options.idtxt+'3', 
																				   readOnly:true, 
																				   style:"text-align:right; margin-top: 5px", 
																				   width: 185 
																				  }
																				]
																		 }, 
																{ 
																 defaultType : "button", 
																 columnWidth : this.widthColumnaBoton,
																 items : [{ 
																		  iconCls: "menubuscar", 
																		  style: "margin-top: 5px",
																		  id:'btnest'+options.idtxt+'3',
																		  handler:this.catalogoEstructuraNivel4.createDelegate(this)
																		 }]
															   },
															   {
																columnWidth : this.widthColumnaEtiqueta, 
																defaultType: "label", 
																items : [{ 
																		  name: "denon"+options.idtxt+'3', 
																		  id: "denest"+options.idtxt+'3', 
																		  style: "margin-top: 5px",
																		  hidden:this.ocultarDenominacion
																		 }] 
															   }]
												 },
												 { 
															   layout : "column", 
															   defaults : 
															   { 
																border : false,
																labelWidth: 200
															   }, 
															   items : [{ 
																		 layout : "form", 
																		 border : false, 
																		 defaultType : "textfield", 
																		 columnWidth : this.widthColumnaCampo,
																		 items : [{ 
																				   fieldLabel: empresa['nomestpro4'], 
																				   name: 'codigo'+options.idtxt+'4', 
																				   id: 'codest'+options.idtxt+'4', 
																				   readOnly:true, 
																				   style:"text-align:right; margin-top: 5px", 
																				   width: 185 
																				  }
																				]
																		 }, 
																{ 
																 defaultType : "button", 
																 columnWidth : this.widthColumnaBoton,
																 items : [{ 
																		  iconCls: "menubuscar", 
																		  style: "margin-top: 5px",
																		  id:'btnest'+options.idtxt+'4',
																		  handler:this.catalogoEstructuraNivelN.createDelegate(this)
																		 }]
															   },
															   {
																columnWidth : this.widthColumnaEtiqueta, 
																defaultType: "label", 
																items : [{ 
																		  name: "denon"+options.idtxt+'4', 
																		  id: "denest"+options.idtxt+'4', 
																		  style: "margin-top: 5px",
																		  hidden:this.ocultarDenominacion
																		 }] 
															   }]
												 }]
											});
		 break;
	 }
	 //this.fieldSetEstPre.doLayout();
	 return fieldset;
	}
	
	//funcion que usa a la funcion agregarCampo para colocar los campos en el formulario segun los parametros
	this.setCampoEstructura=function(){
		this.fieldSetEstPre=this.obtenerFieldSetEstructura();
		this.fieldSetEstPre.doLayout();
	}
	
	this.fieldSetEstPre=this.obtenerFieldSetEstructura();
	
	/************************************************************/
	/************CATALOGO DE ESTRUCTURA NIVEL 1******************/
	/************************************************************/
	//variables usadas en la creacion del catalogo de estructuras nivel 1
	this.dsestructuranivel1="";
	this.objetoestnivel1="";
	this.formbusquedaestructuranivel1="";
	this.gridestructuranivel1="";//esta variable sera usada en la funcion que crea los grid

	//funciones que crean y manejan los objetos que manejaran la data
	this.crearDatastoreEstructuraNivel1=function(){
		var registroestnivel1 = Ext.data.Record.create([
								{name: 'codestpro1'},    
								{name: 'denestpro1'},
								{name: 'estcla'}
							]);
		
		this.objetoestnivel1={"raiz":[{"codestpro1":'',"denestpro1":'',"estcla":''}]};
			
		this.dsestructuranivel1 =  new Ext.data.Store({
									proxy: new Ext.data.MemoryProxy(this.objetoestnivel1),
									reader: new Ext.data.JsonReader({
													root: 'raiz',             
													id: "id"   
												},registroestnivel1),
									data: this.objetoestnivel1
		  						})	;
	}

	this.actualizaDatastoreEstructuraNivel1 = function(cadena,valor)
	{
		this.dsestructuranivel1.filter(cadena,valor,true);
	}
	//fin funciones que crean y manejan los objetos que manejaran la data
	
	this.actualizarGridCodigoNivel1=function()
	{
		var v =this.formbusquedaestructuranivel1.getComponent('codestniv'+options.idtxt+'1').getValue();
		this.actualizaDatastoreEstructuraNivel1('codestpro1',v);
		if(String(v) !== String(this.formbusquedaestructuranivel1.getComponent('codestniv'+options.idtxt+'1').startValue))
		{
			this.formbusquedaestructuranivel1.getComponent('codestniv'+options.idtxt+'1').fireEvent('change', this.formbusquedaestructuranivel1.getComponent('codestniv'+options.idtxt+'1'), v, this.formbusquedaestructuranivel1.getComponent('codestniv'+options.idtxt+'1').startValue);
		}
		
	}
	
	this.actualizarGridDenominacionNivel1=function()
	{
		var v =this.formbusquedaestructuranivel1.getComponent('denestniv1').getValue();
		this.actualizaDatastoreEstructuraNivel1('denestpro1',v);
		if(String(v) !== String(this.formbusquedaestructuranivel1.getComponent('denestniv1').startValue))
		{
			this.formbusquedaestructuranivel1.getComponent('denestniv'+options.idtxt+'1').fireEvent('change', this.formbusquedaestructuranivel1.getComponent('denestniv'+options.idtxt+'1'), v, this.formbusquedaestructuranivel1.getComponent('denestniv'+options.idtxt+'1').startValue);
		}
		
	}
	
	
	//funcion para crear el formulario de busqueda 
	this.crearFormBusquedaEstructuraNivel1=function(){
			this.formbusquedaestructuranivel1 = new Ext.FormPanel({
	        labelWidth: 80, // label settings here cascade unless overridden
	        frame:true,
	        title: 'Busqueda',
	        bodyStyle:'padding:5px 5px 0',
	        width: 630,
			height:100,
	        defaults: {width: 230},
	        defaultType: 'textfield',
			items: [{
		                fieldLabel: 'Codigo',
		                name: 'C&#243;digo',
						id:'codestniv'+options.idtxt+'1',
						changeCheck: this.actualizarGridCodigoNivel1.createDelegate(this),							 
						initEvents : function()
						{
							AgregarKeyPress(this);
						}               
	      			},
	      			{
		                fieldLabel: 'Denominaci&#243;n',
		                name: 'denominacion',
		                id:'denestniv1',
		                width:500,
						changeCheck: this.actualizarGridDenominacionNivel1.createDelegate(this),							 
						initEvents : function()
						{
							AgregarKeyPress(this);
						}
				   }]
				});				  

	}
	//FIN CATALOGO DE ESTRUCTURA NIVEL 1

	/************************************************************/
	/************CATALOGO DE ESTRUCTURA NIVEL 2******************/
	/************************************************************/
	//variables usadas en la creacion del catalogo de estructuras nivel 2
	this.dsestructuranivel2="";
	this.objetoestnivel2="";
	this.formbusquedaestructuranivel2="";
	this.gridestructuranivel2="";//esta variable sera usada en la funcion que crea los grid

	//funciones que crean y manejan los objetos que manejaran la data
	this.crearDatastoreEstructuraNivel2 =function(){
		var registroestnivel2 = Ext.data.Record.create([
								{name: 'codestpro1'},
								{name: 'codestpro2'},    
								{name: 'denestpro2'}
							]);
		
		this.objetoestnivel2 = {"raiz":[{"codestpro1":'',"codestpro2":'',"denestpro2":''}]};
			
		this.dsestructuranivel2 =  new Ext.data.Store({
									proxy: new Ext.data.MemoryProxy(this.objetoestnivel2),
									reader: new Ext.data.JsonReader({
													root: 'raiz',             
													id: "id"   
												},registroestnivel2),
									data: this.objetoestnivel2
		  						});	
	}

	this.actualizaDatastoreEstructuraNivel2=function(criterio,cadena)
	{
		this.dsestructuranivel2.filter(criterio,cadena,true);
	}
	//fin funciones que crean y manejan los objetos que manejaran la data
	
	this.actualizarGridCodigoNivel2=function()
	{
		var v =this.formbusquedaestructuranivel2.getComponent('codestniv'+options.idtxt+'2').getValue();
		this.actualizaDatastoreEstructuraNivel2('codestpro2',v);
		if(String(v) !== String(this.formbusquedaestructuranivel2.getComponent('codestniv'+options.idtxt+'2').startValue))
		{
			this.formbusquedaestructuranivel2.getComponent('codestniv'+options.idtxt+'2').fireEvent('change', this.formbusquedaestructuranivel2.getComponent('codestniv'+options.idtxt+'2'), v, this.formbusquedaestructuranivel2.getComponent('codestniv'+options.idtxt+'2').startValue);
		}
		
	}
	
	this.actualizarGridDenominacionNivel2=function()
	{
		var v =this.formbusquedaestructuranivel2.getComponent('denestniv'+options.idtxt+'2').getValue();
		this.actualizaDatastoreEstructuraNivel2('denestpro2',v);
		if(String(v) !== String(this.formbusquedaestructuranivel2.getComponent('denestniv'+options.idtxt+'2').startValue))
		{
			this.formbusquedaestructuranivel2.getComponent('denestniv'+options.idtxt+'2').fireEvent('change', this.formbusquedaestructuranivel2.getComponent('denestniv'+options.idtxt+'2'), v, this.formbusquedaestructuranivel2.getComponent('denestniv'+options.idtxt+'2').startValue);
		}
		
	}

	//funcion para crear el formulario de busqueda 
	this.crearFormBusquedaEstructuraNivel2=function(){
			this.formbusquedaestructuranivel2 = new Ext.FormPanel({
	        labelWidth: 80, // label settings here cascade unless overridden
	        frame:true,
	        title: 'Busqueda',
	        bodyStyle:'padding:5px 5px 0',
	        width: 630,
			height:100,
	        defaults: {width: 230},
	        defaultType: 'textfield',
			items: [{
	                fieldLabel: 'Codigo',
	                name: 'C&#243;digo',
					id:'codestniv2',
					changeCheck: this.actualizarGridCodigoNivel2.createDelegate(this),							 
								initEvents : function()
								{
									AgregarKeyPress(this);
								}               
	      			},{
				                fieldLabel: 'Denominaci&#243;n',
				                name: 'denominacion',
				                id:'denestniv2',
				                width:500,
								changeCheck: this.actualizarGridDenominacionNivel2.createDelegate(this),							 
											initEvents : function()
											{
												AgregarKeyPress(this);
											}
				            }]
						});				  

	}
	//FIN CATALOGO DE ESTRUCTURA NIVEL 2

	/************************************************************/
	/************CATALOGO DE ESTRUCTURA NIVEL 3******************/
	/************************************************************/
	//variables usadas en la creacion del catalogo de estructuras nivel 3
	this.dsestructuranivel3="";
	this.objetoestnivel3="";
	this.formbusquedaestructuranivel3="";
	this.gridestructuranivel3="";//esta variable sera usada en la funcion que crea los grid

	//funciones que crean y manejan los objetos que manejaran la data
	this.crearDatastoreEstructuraNivel3=function(){
		var registroestnivel3 = Ext.data.Record.create([
								{name: 'codestpro1'},
								{name: 'codestpro2'},
								{name: 'codestpro3'},    
								{name: 'denestpro3'}
							]);
		
		this.objetoestnivel3 = {"raiz":[{"codestpro1":'',"codestpro2":'',"codestpro3":'',"denestpro3":''}]};
			
		this.dsestructuranivel3 =  new Ext.data.Store({
									proxy: new Ext.data.MemoryProxy(this.objetoestnivel3),
									reader: new Ext.data.JsonReader({
													root: 'raiz',             
													id: "id"   
												},registroestnivel3),
									data: this.objetoestnivel3
		  						});	
	}

	this.actualizaDatastoreEstructuraNivel3=function(criterio,cadena)
	{
		this.dsestructuranivel3.filter(criterio,cadena,true);
	}
	//fin funciones que crean y manejan los objetos que manejaran la data
	
	
	this.actualizarGridCodigoNivel3=function()
	{
		var v =this.formbusquedaestructuranivel3.getComponent('codestniv'+options.idtxt+'3').getValue();
		this.actualizaDatastoreEstructuraNivel3('codestpro3',v);
		if(String(v) !== String(this.formbusquedaestructuranivel3.getComponent('codestniv'+options.idtxt+'3').startValue))
		{
			this.formbusquedaestructuranivel3.getComponent('codestniv'+options.idtxt+'3').fireEvent('change', this.formbusquedaestructuranivel3.getComponent('codestniv'+options.idtxt+'3'), v, this.formbusquedaestructuranivel3.getComponent('codestniv'+options.idtxt+'3').startValue);
		}
		
	}
	
	this.actualizarGridDenominacionNivel3=function()
	{
		var v =this.formbusquedaestructuranivel3.getComponent('denestniv'+options.idtxt+'3').getValue();
		this.actualizaDatastoreEstructuraNivel3('denestpro3',v);
		if(String(v) !== String(this.formbusquedaestructuranivel3.getComponent('denestniv'+options.idtxt+'3').startValue))
		{
			this.formbusquedaestructuranivel3.getComponent('denestniv'+options.idtxt+'3').fireEvent('change', this.formbusquedaestructuranivel3.getComponent('denestniv'+options.idtxt+'3'), v, this.formbusquedaestructuranivel3.getComponent('denestniv'+options.idtxt+'3').startValue);
		}
		
	}

	//funcion para crear el formulario de busqueda 
	this.crearFormBusquedaEstructuraNivel3=function(){
			this.formbusquedaestructuranivel3 = new Ext.FormPanel({
	        labelWidth: 80, // label settings here cascade unless overridden
	        frame:true,
	        title: 'Busqueda',
	        bodyStyle:'padding:5px 5px 0',
	        width: 630,
			height:100,
	        defaults: {width: 230},
	        defaultType: 'textfield',
			items: [{
		                fieldLabel: 'Codigo',
		                name: 'C&#243;digo',
						id:'codestniv3',
						changeCheck: this.actualizarGridCodigoNivel3.createDelegate(this),							 
									initEvents : function()
									{
										AgregarKeyPress(this);
									}               
	      			},{
		                fieldLabel: 'Denominaci&#243;n',
		                name: 'denominacion',
		                id:'denestniv3',
		                width:500,
						changeCheck: this.actualizarGridDenominacionNivel3.createDelegate(this),							 
									initEvents : function()
									{
										AgregarKeyPress(this);
									}
				            }]
						});				  

	}
	//FIN CATALOGO DE ESTRUCTURA NIVEL 3

	/************************************************************/
	/************CATALOGO DE ESTRUCTURA NIVEL 4******************/
	/************************************************************/
	//variables usadas en la creacion del catalogo de estructuras nivel 4
	this.dsestructuranivel4="";
	this.objetoestnivel4="";
	this.formbusquedaestructuranivel4="";
	this.gridestructuranivel4="";//esta variable sera usada en la funcion que crea los grid

	//funciones que crean y manejan los objetos que manejaran la data
	function crearDatastoreEstructuraNivel4(){
		var registroestnivel4 = Ext.data.Record.create([
								{name: 'codestpro1'},
								{name: 'codestpro2'},
								{name: 'codestpro3'},
								{name: 'codestpro4'},    
								{name: 'denestpro4'}
							]);
		
		this.objetoestnivel4 = {"raiz":[{"codestpro1":'',"codestpro2":'',"codestpro3":'',"codestpro4":'',"denestpro4":''}]};
			
		this.dsestructuranivel4 =  new Ext.data.Store({
									proxy: new Ext.data.MemoryProxy(this.objetoestnivel4),
									reader: new Ext.data.JsonReader({
													root: 'raiz',             
													id: "id"   
												},registroestnivel4),
									data: this.objetoestnivel4
		  						})	
	}

	this.actualizaDatastoreEstructuraNivel4=function(criterio,cadena)
	{
		this.dsestructuranivel4.filter(criterio,cadena,true);
	}
	//fin funciones que crean y manejan los objetos que manejaran la data

	this.actualizarGridCodigoNivel4=function()
	{
		var v =this.formbusquedaestructuranivel4.getComponent('codestniv4').getValue();
		this.actualizaDatastoreEstructuraNivel4('codestpro4',v);
		if(String(v) !== String(this.formbusquedaestructuranivel4.getComponent('codestniv4').startValue))
		{
			this.formbusquedaestructuranivel4.getComponent('codestniv'+options.idtxt+'4').fireEvent('change', this.formbusquedaestructuranivel4.getComponent('codestniv'+options.idtxt+'4'), v, this.formbusquedaestructuranivel4.getComponent('codestniv'+options.idtxt+'4').startValue);
		}
		
	}
	
	this.actualizarGridDenominacionNivel4=function()
	{
		var v =this.formbusquedaestructuranivel4.getComponent('denestniv'+options.idtxt+'4').getValue();
		this.actualizaDatastoreEstructuraNivel4('denestpro4',v);
		if(String(v) !== String(this.formbusquedaestructuranivel4.getComponent('denestniv'+options.idtxt+'4').startValue))
		{
			this.formbusquedaestructuranivel4.getComponent('denestniv'+options.idtxt+'4').fireEvent('change', this.formbusquedaestructuranivel4.getComponent('denestniv'+options.idtxt+'4'), v, this.formbusquedaestructuranivel4.getComponent('denestniv'+options.idtxt+'4').startValue);
		}
		
	}
	
	//funcion para crear el formulario de busqueda 
	this.crearFormBusquedaEstructuraNivel4=function(){
			this.formbusquedaestructuranivel4 = new Ext.FormPanel({
	        labelWidth: 80, 
	        frame:true,
	        title: 'Busqueda',
	        bodyStyle:'padding:5px 5px 0',
	        width: 630,
			height:100,
	        defaults: {width: 230},
	        defaultType: 'textfield',
			items: [{
	                fieldLabel: 'Codigo',
	                name: 'C&#243;digo',
					id:'codestniv4',
					changeCheck: this.actualizarGridCodigoNivel4.createDelegate(this),							 
								initEvents : function()
								{
									AgregarKeyPress(this);
								}               
	      			},{
				                fieldLabel: 'Denominaci&#243;n',
				                name: 'denominacion',
				                id:'denestniv4',
				                width:500,
								changeCheck:this.actualizarGridDenominacionNivel4.createDelegate(this),							 
											initEvents : function()
											{
												AgregarKeyPress(this);
											}
				            }]
						});				  

	}
	//FIN CATALOGO DE ESTRUCTURA NIVEL 4

	/************************************************************/
	/************CATALOGO DE ESTRUCTURA NIVEL 5******************/
	/************************************************************/
	//variables usadas en la creacion del catalogo de estructuras nivel 5
	this.dsestructuranivel5="";
	this.objetoestnivel5="";
	this.formbusquedaestructuranivel5="";
	this.gridestructuranivel5="";//esta variable sera usada en la funcion que crea los grid

	//funciones que crean y manejan los objetos que manejaran la data
	this.crearDatastoreEstructuraNivel5=function(){
		var registroestnivel5 = Ext.data.Record.create([
								{name: 'codestpro1'},
								{name: 'codestpro2'},
								{name: 'codestpro3'},
								{name: 'codestpro4'},
								{name: 'codestpro5'},    
								{name: 'denestpro5'}
							]);
		
		this.objetoestnivel5 = {"raiz":[{"codestpro1":'',"codestpro2":'',"codestpro3":'',"codestpro4":'',"codestpro5":'',"denestpro5":''}]};
			
		this.dsestructuranivel5 =  new Ext.data.Store({
									proxy: new Ext.data.MemoryProxy(this.objetoestnivel5),
									reader: new Ext.data.JsonReader({
													root: 'raiz',             
													id: "id"   
												},registroestnivel5),
									data: this.objetoestnivel5
		  						});	
	}

	this.actualizaDatastoreEstructuraNivel5=function(criterio,cadena)
	{
		this.dsestructuranivel5.filter(criterio,cadena);
	}
	//fin funciones que crean y manejan los objetos que manejaran la data

	this.actualizarGridCodigoNivel5=function()
	{
		var v =this.formbusquedaestructuranivel5.getComponent('codestniv'+options.idtxt+'5').getValue();
		this.actualizaDatastoreEstructuraNivel5('codestpro5',v);
		if(String(v) !== String(this.formbusquedaestructuranivel5.getComponent('codestniv'+options.idtxt+'5').startValue))
		{
			this.formbusquedaestructuranivel5.getComponent('codestniv'+options.idtxt+'5').fireEvent('change', this.formbusquedaestructuranivel5.getComponent('codestniv'+options.idtxt+'5'), v, this.formbusquedaestructuranivel5.getComponent('codestniv'+options.idtxt+'5').startValue);
		}
		
	}
	
	this.actualizarGridDenominacionNivel5=function()
	{
		var v =this.formbusquedaestructuranivel5.getComponent('denestniv'+options.idtxt+'5').getValue();
		this.actualizaDatastoreEstructuraNivel5('denestpro5',v);
		if(String(v) !== String(this.formbusquedaestructuranivel5.getComponent('denestniv'+options.idtxt+'5').startValue))
		{
			this.formbusquedaestructuranivel5.getComponent('denestniv'+options.idtxt+'5').fireEvent('change', this.formbusquedaestructuranivel5.getComponent('denestniv'+options.idtxt+'5'), v, this.formbusquedaestructuranivel5.getComponent('denestniv'+options.idtxt+'5').startValue);
		}
		
	}
	
	//funcion para crear el formulario de busqueda 
	this.crearFormBusquedaEstructuraNivel5=function(){
			this.formbusquedaestructuranivel5 = new Ext.FormPanel({
	        labelWidth: 80, // label settings here cascade unless overridden
	        frame:true,
	        title: 'Busqueda',
	        bodyStyle:'padding:5px 5px 0',
	        width: 630,
			height:100,
	        defaults: {width: 230},
	        defaultType: 'textfield',
			items: [{
	                fieldLabel: 'Codigo',
	                name: 'C&#243;digo',
					id:'codestniv5',
					changeCheck: this.actualizarGridCodigoNivel5.createDelegate(this),							 
								initEvents : function()
								{
									AgregarKeyPress(this);
								}               
	      			},{
				                fieldLabel: 'Denominaci&#243;n',
				                name: 'denominacion',
				                id:'denestniv5',
				                width:500,
								changeCheck: this.actualizarGridDenominacionNivel5.createDelegate(this),							 
											initEvents : function()
											{
												AgregarKeyPress(this);
											}
				            }]
						});				  

	}
	//FIN CATALOGO DE ESTRUCTURA NIVEL 5

	/************************************************************/
	/************CATALOGO DE ESTRUCTURA NIVEL N******************/
	/************************************************************/
	//variables usadas en la creacion del catalogo de estructuras nivel 5
	this.dsestructuranivelN="";
	this.objetoestnivelN="";
	this.formbusquedaestructuranivelN="";
	this.gridestructuranivelN="";//esta variable sera usada en la funcion que crea los grid

	//funciones que crean y manejan los objetos que manejaran la data
	this.crearDatastoreEstructuraNivelN=function(){
		var registroestnivelN="";
		switch(parseInt(empresa['numniv'])) {
			case 1:
				registroestnivelN = Ext.data.Record.create([
								{name: 'codestpro1'},    
								{name: 'denestpro1'},
								{name: 'estcla'}
							]);
		
				this.objetoestnivelN={"raiz":[{"codestpro1":'',"denestpro1":'',"estcla":''}]};
				break;
			case 2:
				registroestnivelN = Ext.data.Record.create([
								{name: 'codestpro1'},
								{name: 'denestpro1'},
								{name: 'codestpro2'},    
								{name: 'denestpro2'},
								{name: 'estcla'}
							]);
		
				this.objetoestnivelN = {"raiz":[{"codestpro1":'',"codestpro2":'',"denestpro2":''}]};
				break;
			case 3:
				registroestnivelN = Ext.data.Record.create([
								{name: 'codestpro1'},
								{name: 'denestpro1'},
								{name: 'codestpro2'},
								{name: 'denestpro2'},
								{name: 'codestpro3'},    
								{name: 'denestpro3'},
								{name: 'estcla'}
							]);
		
				this.objetoestnivelN = {"raiz":[{"codestpro1":'',"codestpro2":'',"codestpro3":'',"denestpro3":''}]};
				break;
			case 4:
				registroestnivelN = Ext.data.Record.create([
								{name: 'codestpro1'},
								{name: 'denestpro1'},
								{name: 'codestpro2'},
								{name: 'denestpro2'},
								{name: 'codestpro3'},
								{name: 'denestpro3'},
								{name: 'codestpro4'},    
								{name: 'denestpro4'},
								{name: 'estcla'}
							]);
		
				this.objetoestnivelN = {"raiz":[{"codestpro1":'',"codestpro2":'',"codestpro3":'',"codestpro4":'',"denestpro4":''}]};
				break;
			case 5:
		    	registroestnivelN = Ext.data.Record.create([
								{name: 'codestpro1'},
								{name: 'denestpro1'},
								{name: 'codestpro2'},
								{name: 'denestpro2'},
								{name: 'codestpro3'},
								{name: 'denestpro3'},
								{name: 'codestpro4'},
								{name: 'denestpro4'},
								{name: 'codestpro5'},    
								{name: 'denestpro5'},
								{name: 'estcla'}
							]);
		
		    	this.objetoestnivelN = {"raiz":[{"codestpro1":'',"codestpro2":'',"codestpro3":'',"codestpro4":'',"codestpro5":'',"denestpro5":''}]};
				break;
		}
		
		this.dsestructuranivelN =  new Ext.data.Store({
									proxy: new Ext.data.MemoryProxy(this.objetoestnivelN),
									reader: new Ext.data.JsonReader({
													root: 'raiz',             
													id: "id"   
												},registroestnivelN),
									data: this.objetoestnivelN
		  						})
	}

	this.actualizaDatastoreEstructuraNivelN=function(criterio,cadena)
	{
		this.dsestructuranivelN.filter(criterio,cadena,true);
	}
	//fin funciones que crean y manejan los objetos que manejaran la data
	
	this.actualizarGridCodigoNivelN=function()
	{
		var v =this.formbusquedaestructuranivelN.getComponent('codestniv'+options.idtxt+'N').getValue();
		this.actualizaDatastoreEstructuraNivelN('codestpro'+empresa['numniv'],v);
		if(String(v) !== String(this.formbusquedaestructuranivelN.getComponent('codestnivN').startValue))
		{
			this.formbusquedaestructuranivelN.getComponent('codestniv'+options.idtxt+'N').fireEvent('change', this.formbusquedaestructuranivelN.getComponent('codestniv'+options.idtxt+'N'), v, this.formbusquedaestructuranivelN.getComponent('codestniv'+options.idtxt+'N').startValue);
		}
		
	}
	
	this.actualizarGridDenominacionNivelN=function()
	{
		var v =this.formbusquedaestructuranivelN.getComponent('denestniv'+options.idtxt+'N').getValue();
		this.actualizaDatastoreEstructuraNivelN('denestpro'+empresa['numniv'],v);
		if(String(v) !== String(this.formbusquedaestructuranivelN.getComponent('denestniv'+options.idtxt+'N').startValue))
		{
			this.formbusquedaestructuranivelN.getComponent('denestnivN').fireEvent('change', this.formbusquedaestructuranivelN.getComponent('denestniv'+options.idtxt+'N'), v, this.formbusquedaestructuranivelN.getComponent('denestniv'+options.idtxt+'N').startValue);
		}
		
	}

	//funcion para crear el formulario de busqueda 
	this.crearFormBusquedaEstructuraNivelN=function(){
			this.formbusquedaestructuranivelN = new Ext.FormPanel({
	        labelWidth: 80,
	        frame:true,
	        title: 'Busqueda',
	        bodyStyle:'padding:5px 5px 0',
	        width: 630,
			height:100,
	        defaults: {width: 230},
	        defaultType: 'textfield',
			items: [{
	                fieldLabel: 'C&#243;digo',
	                name: 'Codigo',
					id:'codestniv'+options.idtxt+'N',
					changeCheck: this.actualizarGridCodigoNivelN.createDelegate(this),							 
								initEvents : function()
								{
									AgregarKeyPress(this);
								}               
	      			},{
				                fieldLabel: 'Denominaci&#243;n',
				                name: 'denominacion',
				                id:'denestnivN',
				                width:500,
								changeCheck: this.actualizarGridDenominacionNivelN.createDelegate(this),							 
											initEvents : function()
											{
												AgregarKeyPress(this);
											}
				            }]
						});				  

	}
	//FIN CATALOGO DE ESTRUCTURA NIVEL N

	
	this.cargarDataStoreNivel= function()
	{
		var datos = arguments[0].responseText;
		//segun el nivel cargamos los disferente resultados en los datastore correspondientes
		switch(this.operacion) {
			case 'nivel1':
				this.objetoestnivel1 = eval('(' + datos + ')');//objeto nivel 1
				if(this.objetoestnivel1!=''){
					this.dsestructuranivel1.loadData(this.objetoestnivel1);//ds nivel 1
				}
			break;
			
			case 'nivel2':
				this.objetoestnivel2 = eval('(' + datos + ')');//objeto nivel 2
				if(this.objetoestnivel2!=''){
					this.dsestructuranivel2.loadData(this.objetoestnivel2);//ds nivel 2
				}
			break;
			
			case 'nivel3':
				this.objetoestnivel3 = eval('(' + datos + ')');//objeto nivel 3
				if(this.objetoestnivel3!=''){
					this.dsestructuranivel3.loadData(this.objetoestnivel3);//ds nivel 3
				}
			break;
			
			case 'nivel4':
				this.objetoestnivel4 = eval('(' + datos + ')');//objeto nivel 4
				if(this.objetoestnivel4!=''){
					this.dsestructuranivel4.loadData(this.objetoestnivel4);//ds nivel 4
				}
			break;
			
			case 'nivel5':
				this.objetoestnivel5 = eval('(' + datos + ')');//objeto nivel 5
				if(this.objetoestnivel5!=''){
					this.dsestructuranivel5.loadData(this.objetoestnivel5);//ds nivel 5
				}
			break;
			
			case 'nivelN':
				this.objetoestnivelN = eval('(' + datos + ')');//objeto nivel N
				if(this.objetoestnivelN!=''){
					this.dsestructuranivelN.loadData(this.objetoestnivelN);//ds nivel N
				}
			break;
		}
	}
	//Aqui funcion para el request al controlador y capturar los datos del mismo
	this.enviarOperacion=function(operacion){
		//alert(this.fieldSetEstPre.findByType('textfield')[0].getValue());
		var cadenaJson="{'operacion':'" + operacion + "','cantnivel':'" + parseInt(empresa['numniv']) + "',";
		for (var i = 0;i<parseInt(empresa['numniv']);i++){
			if(i==parseInt(empresa['numniv'])-1){
				cadenaJson= cadenaJson + "'codest"+i+"':'" + String.leftPad(this.fieldSetEstPre.findById('codest'+options.idtxt+i).getValue(),25,'0') + "'}";//cambiar
			}else{
				cadenaJson= cadenaJson + "'codest"+i+"':'" + String.leftPad(this.fieldSetEstPre.findById('codest'+options.idtxt+i).getValue(),25,'0') + "',";//cambiar
			}
		}
		this.operacion=operacion;
		parametros = 'ObjSon='+cadenaJson; 
		Ext.Ajax.request({
			url : '../../controlador/spg/sigesp_ctr_spg_catestpresupuestaria.php',
			params : parametros,
			method: 'POST',
			success: this.cargarDataStoreNivel.createDelegate(this, arguments, 2)
		});
		
	}
	//fin funcion enviar operacion.....
	this.dobleclickgridNivel1=function() // Funcion para realizar el Set de los Datos de la Estructura de Nivel 1
	{
		this.setDataEstructuraNivel1();	
	}
	this.dobleclickgridNivel2=function() // Funcion para realizar el Set de los Datos de la Estructura de Nivel 2
	{
		this.setDataEstructuraNivel2();	
	}
	this.dobleclickgridNivel3=function() // Funcion para realizar el Set de los Datos de la Estructura de Nivel 3
	{
		this.setDataEstructuraNivel3();	
	}
	this.dobleclickgridNivel4=function() // Funcion para realizar el Set de los Datos de la Estructura de Nivel 4
	{
		this.setDataEstructuraNivel4();	
	}
	this.dobleclickgridNivel5=function() // Funcion para realizar el Set de los Datos de la Estructura de Nivel 5
	{
		this.setDataEstructuraNivel5();	
	}
	this.dobleclickgridNivelN=function() // Funcion para realizar el Set de los Datos de la Estructura de Nivel N
	{
		this.setDataEstructuraNivelN();		
	}
	//Aqui creaciones de las grid...
	this.crear_grid_catalogoestructura=function(operacion){
		//aqui creamos los grid....
		switch(operacion) {
			case 'nivel1':
				this.crearDatastoreEstructuraNivel1();
				this.enviarOperacion(operacion);	
		    	this.crearFormBusquedaEstructuraNivel1();//invocando el crear form de busqueda
				this.gridestructuranivel1 = new Ext.grid.GridPanel({
		 								width:770,
		 								height:400,
		 								tbar: this.formbusquedaestructuranivel1,
		 								autoScroll:true,
	     								border:true,
	     								ds: this.dsestructuranivel1,
	     								cm: new Ext.grid.ColumnModel([
	          								{header: empresa['nomestpro1'], width: 30, sortable: true,   dataIndex: 'codestpro1', renderer: this.mostrarNumDigNiv1, align:'center'},
	          								{header: "Denominaci&#243;n", width: 50, sortable: true, dataIndex: 'denestpro1'},
											{header: "Tipo", width: 50, sortable: true, dataIndex: 'estcla',renderer:this.mostrarEstatus}
	       								]),
	       								stripeRows: true,
	      								viewConfig: {forceFit:true},
	      								listeners:{'celldblclick' : this.dobleclickgridNivel1.createDelegate(this)}
									});
				break;
				
			case 'nivel2':
				this.crearDatastoreEstructuraNivel2()
				this.enviarOperacion(operacion)	
		    	this.crearFormBusquedaEstructuraNivel2();//invocando el crear form de busqueda
				this.gridestructuranivel2 = new Ext.grid.GridPanel({
		 								width:770,
		 								height:400,
		 								tbar: this.formbusquedaestructuranivel2,
		 								autoScroll:true,
	     								border:true,
	     								ds: this.dsestructuranivel2,
	     								cm: new Ext.grid.ColumnModel([
	          								{header: empresa['nomestpro1'], width: 30, sortable: true,   dataIndex: 'codestpro1', renderer: this.mostrarNumDigNiv1, align:'center'},
											{header: empresa['nomestpro2'], width: 30, sortable: true,   dataIndex: 'codestpro2', renderer: this.mostrarNumDigNiv2, align:'center'},
	          								{header: "Denominaci&#243;n", width: 50, sortable: true, dataIndex: 'denestpro2'}
										]),
	       								stripeRows: true,
	      								viewConfig: {forceFit:true},
	      								listeners:{'celldblclick' : this.dobleclickgridNivel2.createDelegate(this)}
									});
				break;
			
			case 'nivel3':
				this.crearDatastoreEstructuraNivel3()
				this.enviarOperacion(operacion)	
		    	this.crearFormBusquedaEstructuraNivel3();//invocando el crear form de busqueda
				this.gridestructuranivel3 = new Ext.grid.GridPanel({
		 								width:770,
		 								height:400,
		 								tbar: this.formbusquedaestructuranivel3,
		 								autoScroll:true,
	     								border:true,
	     								ds: this.dsestructuranivel3,
	     								cm: new Ext.grid.ColumnModel([
	          								{header: empresa['nomestpro1'], width: 30, sortable: true,   dataIndex: 'codestpro1', renderer: this.mostrarNumDigNiv1, align:'center'},
											{header: empresa['nomestpro2'], width: 30, sortable: true,   dataIndex: 'codestpro2', renderer: this.mostrarNumDigNiv2, align:'center'},
											{header: empresa['nomestpro3'], width: 30, sortable: true,   dataIndex: 'codestpro3', renderer: this.mostrarNumDigNiv3, align:'center'},
	          								{header: "Denominaci&#243;n", width: 50, sortable: true, dataIndex: 'denestpro3'}
										]),
	       								stripeRows: true,
	      								viewConfig: {forceFit:true},
	      								listeners:{'celldblclick' : this.dobleclickgridNivel3.createDelegate(this)}
									});
				break;
			case 'nivel4':
				this.crearDatastoreEstructuraNivel4()
				this.enviarOperacion(operacion)	
		    	this.crearFormBusquedaEstructuraNivel4();//invocando el crear form de busqueda
				this.gridestructuranivel4 = new Ext.grid.GridPanel({
		 								width:770,
		 								height:400,
		 								tbar: this.formbusquedaestructuranivel4,
		 								autoScroll:true,
	     								border:true,
	     								ds: this.dsestructuranivel4,
	     								cm: new Ext.grid.ColumnModel([
	          								{header: empresa['nomestpro1'], width: 30, sortable: true,   dataIndex: 'codestpro1', renderer: this.mostrarNumDigNiv1, align:'center'},
											{header: empresa['nomestpro2'], width: 30, sortable: true,   dataIndex: 'codestpro2', renderer: this.mostrarNumDigNiv2, align:'center'},
											{header: empresa['nomestpro3'], width: 30, sortable: true,   dataIndex: 'codestpro3', renderer: this.mostrarNumDigNiv3, align:'center'},
											{header: empresa['nomestpro4'], width: 30, sortable: true,   dataIndex: 'codestpro4', renderer: this.mostrarNumDigNiv4, align:'center'},
	          								{header: "Denominaci&#243;n", width: 50, sortable: true, dataIndex: 'denestpro4'}
										]),
	       								stripeRows: true,
	      								viewConfig: {forceFit:true},
	      								listeners:{'celldblclick' : this.dobleclickgridNivel4.createDelegate(this)}
									});
				break;
			case 'nivel5':
				this.crearDatastoreEstructuraNivel5()
				this.enviarOperacion(operacion)	
		    	this.crearFormBusquedaEstructuraNivel5();//invocando el crear form de busqueda
				this.gridestructuranivel5 = new Ext.grid.GridPanel({
		 								width:770,
		 								height:400,
		 								tbar: this.formbusquedaestructuranivel5,
		 								autoScroll:true,
	     								border:true,
	     								ds: this.dsestructuranivel5,
	     								cm: new Ext.grid.ColumnModel([
	          								{header: empresa['nomestpro1'], width: 30, sortable: true,   dataIndex: 'codestpro1', renderer: this.mostrarNumDigNiv1, align:'center'},
											{header: empresa['nomestpro2'], width: 30, sortable: true,   dataIndex: 'codestpro2', renderer: this.mostrarNumDigNiv2, align:'center'},
											{header: empresa['nomestpro3'], width: 30, sortable: true,   dataIndex: 'codestpro3', renderer: this.mostrarNumDigNiv3, align:'center'},
											{header: empresa['nomestpro4'], width: 30, sortable: true,   dataIndex: 'codestpro4', renderer: this.mostrarNumDigNiv4, align:'center'},
											{header: empresa['nomestpro5'], width: 30, sortable: true,   dataIndex: 'codestpro5', renderer: this.mostrarNumDigNiv5, align:'center'},
	          								{header: "Denominaci&#243;n", width: 50, sortable: true, dataIndex: 'denestpro5'}
										]),
	       								stripeRows: true,
	      								viewConfig: {forceFit:true},
	      								listeners:{'celldblclick' : this.dobleclickgridNivel5.createDelegate(this)}
									});
				break;
			case 'nivelN':
				var nniv = parseInt(empresa['numniv'])
				this.crearDatastoreEstructuraNivelN();
				this.enviarOperacion(operacion);	
		    	this.crearFormBusquedaEstructuraNivelN();//invocando el crear form de busqueda
				modelogridN="[";
				for(var x=1;x<=nniv;x++){
					if(x==nniv){
						modelogridN = modelogridN + "{header: '"+empresa['nomestpro'+x]+"', width: 45, sortable: true,   dataIndex: 'codestpro"+x+"', align:'center'},"+
													"{header: 'Denominaci&#243;n', width: 45, sortable: true,   dataIndex: 'denestpro"+x+"'},"+
													"{header: 'Tipo', width: 30, sortable: true, dataIndex: 'estcla'}";
					}else{
						modelogridN = modelogridN + "{header: '"+empresa['nomestpro'+x]+"', width: 30, sortable: true,   dataIndex: 'codestpro"+x+"', renderer: this.mostrarNumDigNiv"+x+", align:'center'},";
					}	
				}
				modelogridN = modelogridN + "]";
				objetomodelo = Ext.util.JSON.decode(modelogridN);
				this.gridestructuranivelN = new Ext.grid.GridPanel({
		 								width:770,
		 								height:400,
		 								tbar: this.formbusquedaestructuranivelN,
		 								autoScroll:true,
	     								border:true,
	     								ds: this.dsestructuranivelN,
	     								cm: new Ext.grid.ColumnModel(objetomodelo),
	       								stripeRows: true,
	      								viewConfig: {forceFit:true},
	      								listeners:{'celldblclick' : this.dobleclickgridNivelN.createDelegate(this)}
									});
				
				switch (nniv) {
					case 1:
						this.gridestructuranivelN.getColumnModel().setRenderer(0,this.mostrarNumDigNiv1);
						this.gridestructuranivelN.getColumnModel().setRenderer(2,this.mostrarEstatus);
						break;
						
					case 2:
						this.gridestructuranivelN.getColumnModel().setRenderer(0,this.mostrarNumDigNiv1);
						this.gridestructuranivelN.getColumnModel().setRenderer(1,this.mostrarNumDigNiv2);
						this.gridestructuranivelN.getColumnModel().setRenderer(3,this.mostrarEstatus);
						break;
					
					case 3:
						this.gridestructuranivelN.getColumnModel().setRenderer(0,this.mostrarNumDigNiv1);
						this.gridestructuranivelN.getColumnModel().setRenderer(1,this.mostrarNumDigNiv2);
						this.gridestructuranivelN.getColumnModel().setRenderer(2,this.mostrarNumDigNiv3);
						this.gridestructuranivelN.getColumnModel().setRenderer(4,this.mostrarEstatus);
						break;
					
					case 4:
						this.gridestructuranivelN.getColumnModel().setRenderer(0,this.mostrarNumDigNiv1);
						this.gridestructuranivelN.getColumnModel().setRenderer(1,this.mostrarNumDigNiv2);
						this.gridestructuranivelN.getColumnModel().setRenderer(2,this.mostrarNumDigNiv3);
						this.gridestructuranivelN.getColumnModel().setRenderer(3,this.mostrarNumDigNiv4);
						this.gridestructuranivelN.getColumnModel().setRenderer(5,this.mostrarEstatus);
						break;
					
					case 5:
						this.gridestructuranivelN.getColumnModel().setRenderer(0,this.mostrarNumDigNiv1);
						this.gridestructuranivelN.getColumnModel().setRenderer(1,this.mostrarNumDigNiv2);
						this.gridestructuranivelN.getColumnModel().setRenderer(2,this.mostrarNumDigNiv3);
						this.gridestructuranivelN.getColumnModel().setRenderer(3,this.mostrarNumDigNiv4);
						this.gridestructuranivelN.getColumnModel().setRenderer(4,this.mostrarNumDigNiv5);
						this.gridestructuranivelN.getColumnModel().setRenderer(6,this.mostrarEstatus);
						break;
				}
				break; 
		}
	} 
	//fin crear grid..........
	
	
	this.setDataEstructuraNivel1=function()
	{
		estnivel1 = this.gridestructuranivel1.getSelectionModel().getSelected();
		if(estnivel1!=null)
		{
			this.fieldSetEstPre.findById('codest'+options.idtxt+'0').setValue(this.mostrarNumDigNiv1(estnivel1.get('codestpro1')));
			if(this.fieldSetEstPre.findById('denest'+options.idtxt+'0') != null)
			{
				this.fieldSetEstPre.findById('denest'+options.idtxt+'0').setText(estnivel1.get('denestpro1'));	
				//this.fieldSetEstPre.findById('denest'+options.idtxt+'0').setValue(estnivel1.get('denestpro1'));
			}
			this.fieldSetEstPre.findById('estcla'+options.idtxt).setValue(estnivel1.get('estcla'));
			this.limpiarEstructuras(0);
			this.gridestructuranivel1.destroy();
			ventana.destroy();
		}
		else
		{
			this.mensajeValidacionNivel(1);
		}
		
	}
	
	this.cerrarVentanaEstructuraNivel1=function()
	{
		this.gridestructuranivel1.destroy();
		ventana.destroy();
	}

	//funciones para llamar a los catalogos....
	/*this.catalogoEstructuraNivel1=function(){
		this.crear_grid_catalogoestructura('nivel1');				   
	    ventana = new Ext.Window({
	    	title: 'Cat&#225;logo de '+empresa['nomestpro1'],
			autoScroll:true,
	        width:800,
	        height:475,
	        modal: true,
	        closable:false,
	        plain: false,
	        items:[this.gridestructuranivel1],
	        buttons: [{
						text:'Aceptar',  
				        handler: this.setDataEstructuraNivel1.createDelegate(this)
				       },
				       {
				      	text: 'Salir',
				        handler: this.cerrarVentanaEstructuraNivel1.createDelegate(this)
	                  }]
	      });
	      ventana.show();
	}*/

	this.setDataEstructuraNivel2=function()
	{
		estnivel2 = this.gridestructuranivel2.getSelectionModel().getSelected();
		if(estnivel2 != null)
		{
			this.fieldSetEstPre.findById('codest'+options.idtxt+'1').setValue(this.mostrarNumDigNiv2(estnivel2.get('codestpro2')));
			if(this.fieldSetEstPre.findById('denest'+options.idtxt+'1') != null)
			{
			 this.fieldSetEstPre.findById('denest'+options.idtxt+'1').setText(estnivel2.get('denestpro2'))
			}
			this.gridestructuranivel2.destroy();
			ventana.destroy();
		}
		else
		{
			this.mensajeValidacionNivel(2);
		}
		
	}
	
	this.cerrarVentanaEstructuraNivel2=function()
	{
		this.gridestructuranivel2.destroy();
		ventana.destroy();	
	}
	
	this.setDataEstructuraNivel3=function()
	{
		estnivel3 = this.gridestructuranivel3.getSelectionModel().getSelected();
		if(estnivel3 != null)
		{
			this.fieldSetEstPre.findById('codest'+options.idtxt+'2').setValue(this.mostrarNumDigNiv3(estnivel3.get('codestpro3')));
			if(this.fieldSetEstPre.findById('denest'+options.idtxt+'2') != null)
			{
				this.fieldSetEstPre.findById('denest'+options.idtxt+'2').setText(estnivel3.get('denestpro3'))
			}
			this.gridestructuranivel3.destroy();
			ventana.destroy();
		}
		else
		{
			this.mensajeValidacionNivel(3);
		}
	}
	
	this.cerrarVentanaEstructuraNivel3=function()
	{
		this.gridestructuranivel3.destroy();
		ventana.destroy();
	}
	
	this.setDataEstructuraNivel4=function()
	{
		estnivel4 = this.gridestructuranivel4.getSelectionModel().getSelected();
		if(estnivel4 != null)
		{
		this.fieldSetEstPre.findById('codest'+options.idtxt+'3').setValue(this.mostrarNumDigNiv4(estnivel4.get('codestpro4')));
		if(this.fieldSetEstPre.findById('denest'+options.idtxt+'3') != null)
		{
			this.fieldSetEstPre.findById('denest'+options.idtxt+'3').setText(estnivel4.get('denestpro4'))
		}
		this.gridestructuranivel4.destroy();
		ventana.destroy();
		}
		else
		{
			this.mensajeValidacionNivel(4);
		}
	}
	
	this.cerrarVentanaEstructuraNivel4=function()
	{
		this.gridestructuranivel4.destroy();
		ventana.destroy();
	}

	this.setDataEstructuraNivel5=function()
	{
		estnivel5 = this.gridestructuranivel5.getSelectionModel().getSelected();
		if(estnivel5 != null)
		{
			this.fieldSetEstPre.findById('codest'+options.idtxt+'4').setValue(this.mostrarNumDigNiv5(estnivel5.get('codestpro5')));
			if(comestructura.fieldSetEstPre.getComponent('denest'+options.idtxt+'4') != null)
			{
				this.fieldSetEstPre.findById('denest'+options.idtxt+'4').setText(estnivel5.get('denestpro5'))
			}
			this.gridestructuranivel5.destroy();
			ventana.destroy();
		}
		else
		{
			this.mensajeValidacionNivel(5);
		}
	}
	
	this.cerrarVentanaEstructuraNivel5=function()
	{
		this.gridestructuranivel5.destroy();
		ventana.destroy();
	}
	
	this.cerrarVentanaEstructuraNivelN=function()
	{
		this.gridestructuranivelN.destroy();
		ventana.destroy();
	}

	this.mensajeValidacionNivel=function(nivel)
	{
		Ext.Msg.show({
		   	title:'Mensaje',
		   	msg: 'No ha seleccionado ningun(a) '+empresa['nomestpro'+nivel]+', verifique por favor',
		   	buttons: Ext.Msg.OK,
		   	animEl: 'elId',
		   	icon: Ext.MessageBox.ERROR,
		   	closable:false
			});
	}

	this.limpiarEstructuras=function(nivel)
	{
		var contador = nivel +1;
		for(i=contador; i<parseInt(empresa['numniv']); i++)
		{
			this.fieldSetEstPre.findById('codest'+options.idtxt+i).setValue("");
			if(this.fieldSetEstPre.findById('denest'+options.idtxt+i) != null)
			{
				this.fieldSetEstPre.findById('denest'+options.idtxt+i).setText("");
			}
		}
	}
	
	this.obtenerCodigoEstructuraNivel=function(nivel)
	{
		var codigo="";
		switch(nivel)
		{
			case 1: codigo = String.leftPad(this.fieldSetEstPre.findById('codest'+options.idtxt+'0').getValue(),25,'0');
			break;
			
			case 2:  if(this.fieldSetEstPre.findById('codest'+options.idtxt+'1') != null)
			         {
				      codigo = String.leftPad(this.fieldSetEstPre.findById('codest'+options.idtxt+'1').getValue(),25,'0');
			         }
			         else
			         {
			          codigo = String.leftPad("",25,'0'); 
			         }
				     
			break;
			
			case 3: 
					if(this.fieldSetEstPre.findById('codest'+options.idtxt+'2') != null)
			         {
						codigo = String.leftPad(this.fieldSetEstPre.findById('codest'+options.idtxt+'2').getValue(),25,'0');
			         }
			         else
			         {
			            codigo = String.leftPad("",25,'0'); 
			         }
				     
				    
			break;
			
			case 4: 
					if(this.fieldSetEstPre.findById('codest'+options.idtxt+'3') != null)
			         {
						codigo = String.leftPad(this.fieldSetEstPre.findById('codest'+options.idtxt+'3').getValue(),25,'0');
			         }
			         else
			         {
			            codigo = String.leftPad("",25,'0'); 
			         }
				   
			break;
			
			case 5: 
					if(this.fieldSetEstPre.findById('codest'+options.idtxt+'4') != null)
			         {
						codigo = String.leftPad(this.fieldSetEstPre.findById('codest'+options.idtxt+'4').getValue(),25,'0');
			         }
			         else
			         {
			            codigo = String.leftPad("",25,'0'); 
			         }
			break;
			
		}
		
		return codigo;
	}
	
	this.obtenerEstClaEstructura=function()
	{
		
		return this.fieldSetEstPre.findById('estcla'+options.idtxt).getValue();
	}
	
	this.obtenerValorNivel=function(nivel)
	{
		return this.fieldSetEstPre.findById('codest'+options.idtxt+(nivel-1)).getValue();
	}
	
    this.obtenerArrayEstructura=function()
    {
    	var codestpro = new Array(5);
    	var j = 0;
    	for(i = 0; i<5; i++)
    	{
    		j++;
    		codestpro[i] = this.obtenerCodigoEstructuraNivel(j);
    	}
    	codestpro[5] = this.obtenerEstClaEstructura();	
    	
    	return codestpro;
    }
	
	this.obtenerEstructucturaFormato=function()
	{
		var codigo="";
		for(i=0; i<parseInt(empresa['numniv']); i++)
		{
			if(i==0)
			{
				codigo = this.fieldSetEstPre.findById('codest'+options.idtxt+i).getValue();
			}
			else
			{
				codigo += this.fieldSetEstPre.findById('codest'+options.idtxt+i).getValue();
			}
			
		}
		return codigo;
	}
	
	this.agregarListenerBoton=function(nivel,funcion)
	{
		if(typeof(funcion) == "function")
		{
			this.fieldSetEstPre.findById('btnest'+options.idtxt+(nivel-1)).addListener('click',funcion);
		}
	}
	
};
