/*******************************************************************
* @Funci�n que valida un dato de acuerdo a 
* varios tipos de validaci�n.
* @Par�metros: id: propiedad id del objeto del formulario a validar. 
* long longitud del campo, tipoVal: tipo de validaci�n.
* @Valor de Retorno: 0 o 1 si fue correcto o no.
* @Autor: Johny Porras. 
* @Fecha de Creaci�n: 15/05/2008
***************************************************************
* @fecha modificaci�n: 16/05/2008  
* @Descripci�n: Agregar casos para validar nombres,telefono y correo.
* @autor: Gusmary Balza.                 
*********************************************************************/
function validarObjetos(id,long,tipoVal)
{
	obj   = document.getElementById(id);
	arVal = tipoVal.split('|');
	for (i=0;i<arVal.length;i++)
	{
		switch(arVal[i])
		{
			case 'novacio':
				if ((obj.value=='') ||  (obj.value=='Seleccione'))
				{
					Ext.MessageBox.alert('Campos Vacios', 'Debe llenar el campo '+obj.name);
					return '0';
				}
			break;
			
			case 'nombre': //solo letras
				val = obj.value;
				longitud = val.length;
				validos='ABCDEFGHIJKLMN�OPQRSTUVWXYZ�����abcdefghijklmn�opqrstuvwxyz�����'+' ';
				if (longitud<3)
				{
				 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' no tiene la longitud correcta');
				 	return '0';
				}
				else
				{
				 	for(r=0;r<longitud;r++)
				 	{
			      		ch=val.charAt(r);					  
				  		if(validos.search(ch) == -1) //busca en la cadena validos el caracter ch
				  		{
				   			Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' debe contener solo letras');
				   			return '0';
				  		}			
			     	}
				}
			break;
			
			case 'longexacta':
				val = obj.value;
				longitud = val.length;
				validos='ABCDEFGHIJKLMN�OPQRSTUVWXYZ�����abcdefghijklmn�opqrstuvwxyz�����'+' ';
				if ((longitud<long) || (longitud>long))
				{
				 	Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+ ' no tiene la longitud correcta');
				 	return '0';
				}
				else
				{
				 	for(r=0;r<longitud;r++)
				 	{
			      		ch=val.charAt(r);					  
				  		if (validos.search(ch) == -1) //busca en la cadena validos el caracter ch
				  		{
				   			Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' debe contener solo letras');
				   			return '0';
				  		}			
			     	}
				}				
			break;
			
			case 'telefono':
				val = obj.value;	
			 	var er_tlf = /^\d{4}-\d{7}$/; //expresi�n regular para telefono con formato ejm: 0251-5555555
				if(!er_tlf.test(val))
				{
       			 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
				  	return '0';
            	}
			break;
			
			case 'vaciotelefono':
				val = obj.value;
				longitud = val.length;
				if ((longitud <= long) && longitud>0)
				{			
					var er_tlf = /^\d{4}-\d{7}$/; //expresi�n regular para telefono con formato ejm: 0251-5555555
					if (!er_tlf.test(val))
					{
						Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
						return '0';
					}
				}
			break;
			
			case 'email':
			   	val = obj.value;
			break;
			
			case 'vacioemail':
			   	val = obj.value;
				longitud = val.length;
				if ((longitud <= long) && longitud>0)
				{			
					var filtro=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; //expresi�n regular para emails
					if (!filtro.test(val)) //test compara la cadena val con la de la expresi�n regular
					{
						Ext.MessageBox.alert('Campos Incorrectos', 'El campo '+obj.name+' es incorrecto');
						return '0';	
					}
				}
			break;
			
			case 'numero': //para solo numeros
				val = obj.value;
				longitud = val.length;
				if (longitud <= long)
				{			
					var er_numero=/^\d+$/; //expresi�n regular para solo digitos
					if (!er_numero.test(val))
					{
						Ext.MessageBox.alert('Tipo de Dato incorrecto', 'El campo '+obj.name+' es incorrecto');
						return '0';	
					}
				}
				else
				{					
					Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+' no tiene la longitud correcta');
					return '0';	
				}
			break;
			
			case 'login':
				val = obj.value;
				var er_login = /^[a-zd_]{4,20}$/i; 
				if(!er_login.test(val))
				{
       			 	Ext.MessageBox.alert('Campo Incorrecto', 'El campo '+obj.name+ ' es incorrecto');
				  	return '0';
            	}			
			break;
			
			case 'alfanumerico':  //solo numeros o letras, guiones y espacios
				val = obj.value;
				longitud = val.length;
				if (longitud <= long)
				{
				//	var er_validos = /^[a-zA-Z0-9\s.\-]+$/;
					validos='ABCDEFGHIJKLMN�OPQRSTUVWXYZ�����abcdefghijklmn�opqrstuvwxyz�����0123456789'+'-'+' ';

					for(r=0;r<longitud;r++)
				//	if (!er_validos.test(val))
					{
						ch=val.charAt(r);			  
						if(validos.search(ch) == -1)
						{
							Ext.MessageBox.alert('Tipo de Dato Incorrecto', 'El campo '+obj.name+ ' no debe contener caracteres especiales');
							return '0';
						}
					}
				}
				else
				{
					Ext.MessageBox.alert('Longitud Incorrecta', 'El campo '+obj.name+' no tiene la longitud correcta');
					return '0';	
				}
		}
	}
	return '1';
}


/********************************************************************************
* @Funci�n para sacar una ventana emergente
* @Par�metros: --> pagina 
********************************************************************************/

function Abrir_ventana (pagina)
{
	var opciones='toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=1000, height=800, top=10, left=10';
	window.open(pagina,'',opciones);
}



