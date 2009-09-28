
<?php echo ext_log('Loading Viewport') ?>

/* Viewport */
viewport = new Ext.Viewport({
  layout: 'border',
  style:  'background: #FFFFFF;',
  items:[
    NorthRegion,
    {
      id:       'west-region',
      region:   'west',
      layout:   'accordion',
      animate:  true,
      style:    'border-top: 1px solid #99BBE8;',
      defaults: { border: true },
      margins:  '0 5 0 5',
      width:    200,
      border:   false,
      items: [ Domains, Settings ]
    },{
      id: 'center-region',
      region: 'center',
      border: false,
      margins: "0 5 0 0",
      bodyStyle: 'padding: 20px;',
      items: [ Start ]
    },
    SouthRegion
  ],
  listeners: {
    render: function(){
      loadStores();
    }
  }
});
