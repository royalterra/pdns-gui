var messages = new Ext.Panel({
  height: 55,
  border: false,
  defaults: { 
    border: false, 
    bodyStyle: 'background: red; color: white; font-weight: bold; padding: 3px;', 
    style: 'margin-bottom: 2px; margin-right: 5px;'
  },
  autoScroll: true
});

var toolsMenu = new Ext.menu.Menu({
  items: [
    {
      text: 'Search and replace',
      handler: function(){ ReplaceWindow() }
    }
  ]
});

var NorthRegion = new Ext.Panel({
  region:'north',
  border: false,
  layout: 'hbox',
  defaults: { border: false },
  layoutConfig: {
    align: 'middle'
  },
  height: 60,
  items: [
    {
      style: 'margin-left: 5px;',
      html: '<?php echo image_tag('logo.png') ?>',
      width: 200,
      height: 60
    },{
      flex: 1,
      height: 60,
      bodyStyle: 'padding-top: 5px; padding-left: 15px;',
      items: messages
    },{
      xtype: 'button',
      text: 'Tools',
      menu: toolsMenu,
      margins: '0 5 0 5'
    },{
      xtype: 'button',
      scale: 'medium',
      text: 'Commit changes',
      width: 120,
      margins: '0 5 0 5',
      handler: function(){
        Ext.Ajax.request({
          url: '<?php echo url_for('domain/commit') ?>',
          success: function(action){
            
            var info = Ext.decode(action.responseText).info;
            
            Ext.Msg.alert('Error',info);
            
            DomainStore.load();
          }
        });

      }
    }
  ]
});
