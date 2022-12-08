/***********************************************************************************
* @Archivo JavaScript que incluye tanto el componentes que construye el panel para 
* seleccionar las estructuras presupuestarias  
* @fecha de creacion: 12/08/2009
* @autor: Ing. Gerardo Cordero
************************************************************************************
* @fecha modificacion:
* @descripcion:
* @autor:
***********************************************************************************/
Ext.namespace('com.sigesp.vista'); 
 
//Objeto que construye el panel para seleccionar una estructura presupuestaria
com.sigesp.vista.comEstructuraPresupuesto =  function(options){
	
	//panel sobre el cual se colocan los txt y botones dinamicamente
	var Xpos = ((screen.width/2)-(740/2));
	this.formSelEstPre = new Ext.FormPanel({
			width: 740,
			height: 443,
			title: options.titform,
			frame: true,
			labelWidth: 200,
			style: 'position:absolute;margin-left:'+Xpos+'px;margin-top:65px;',
			items: [{
				xtype: 'hidden',
				name: 'estcla',
				id: 'estcla'
			}]
		
		});
	
	this.renderForm=function(){
		this.formSelEstPre.render(options.appto)
	}
	
	//funcion que agrega un campo de forma dinamica al formulario
	this.agregarCampo = function (titulo, indice, pxtop, funcion){
       	var campos = new Array();
		var etiqueta = new Array();
		var fnasignada  = new Array();
				
		if(options.titcampo.length - 1==indice){
			fnasignada[indice]=funcion[5]
		}else{
			fnasignada[indice]=funcion[indice]
		}
		
		
		campos[indice] = new Ext.form.TextField({
				xtype: 'textfield',
				fieldLabel: titulo,
				name: 'codigo'+indice,
				id: 'codest'+indice,
				width: 185
			});
		
		boton = new Ext.Button({
				xtype:'button',
				iconCls: 'menubuscar',
				style:'position:absolute;left:395px;top:'+pxtop+'px;',
				handler:fnasignada[indice]
			});
		
		etiqueta[indice] = new Ext.form.Label({
				name: 'denon'+indice,
				id: 'denest'+indice,
				style:'position:absolute;left:430px;top:'+pxtop+'px;'
			});
		
		this.formSelEstPre.add(campos[indice]);
		this.formSelEstPre.add(boton);
		this.formSelEstPre.add(etiqueta[indice]);
		this.formSelEstPre.doLayout();	
		
    }
	
	//funcion que usa a la funcion agregarCampo para colocar los campos en el formulario segun los parametros
	this.setCampoEstrutura=function(){
		var pxtop=0;
		
		for (var i = 0; i < options.titcampo.length; i++) {
        	this.agregarCampo(options.titcampo[i], i, pxtop, options.arrfuncion);
            pxtop=pxtop+27;
		}
	}
	
};
