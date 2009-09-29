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
      html: '<img src="/images/logo.png" alt="PowerDNS GUI logo" />',
      width: 200,
      height: 60
    },{
      flex: 1,
      height: 60,
      bodyStyle: 'padding-top: 5px; padding-left: 15px;',
      items: messages
    },{
      xtype: 'button',
      scale: 'medium',
      text: 'Commit changes',
      width: 120,
      margins: '0 5 0 5'
    }
  ]
});
