/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
    // basic tabs 1, built from existing content
    var tabs = new Ext.TabPanel({
        renderTo: 'tabs1',
        width:586,
        activeTab: 0,
		plain:true,
        frame:true,
        defaults:{autoHeight: true},
        items:[
            {contentEl:'caracteristicas', title: 'Caracteristicas'},
            {contentEl:'causas', title: 'Causas'},
			{contentEl:'efectos', title: 'Efectos'}
        ]
    });

    // second tabs built from JS
/*    var tabs2 = new Ext.TabPanel({
        renderTo: document.body,
        activeTab: 0,
        width:600,
        height:250,
        plain:true,
        defaults:{autoScroll: true},
        items:[{
                title: 'Normal Tab',
                html: "My content was added during construction.xxx"
            },{
                title: 'Ajax Tab 1',
                autoLoad:'ajax1.htm'
            },{
                title: 'Ajax Tab 2',
                autoLoad: {url: 'ajax2.htm', params: 'foo=bar&wtf=1'}
            },{
                title: 'Event Tab',
                listeners: {activate: handleActivate},
                html: "I am tab 4's content. I also have an event listener attached."
            },{
                title: 'Disabled Tab',
                disabled:true,
                html: "Can't see me cause I'm disabled"
            }
        ]
    });
*/
    function handleActivate(tab){
        alert(tab.title + ' was activated.');
    }
});