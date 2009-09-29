function DomainWindow(domain)
{
  var win_id = get_win_id(domain);
  if (!win_id) return;
  
  var win = new Ext.ux.Window({
    id: win_id,
    title: domain.name,
    width: 300,
    items: {
      xtype: 'form',
      url: '<?php echo url_for('domain/edit') ?>',
      defaults: { allowBlank: false },
      items: [
        {
          xtype: 'textfield',
          name: 'name'
        }
      ]
    },
    buttons: [
      {
        text: 'Submit',
        handler: function() { win.doSubmit() }
      },{
        text: 'Close',
        handler: function() { win.close() }
      }
    ]
  });
  
  win.show();
}
