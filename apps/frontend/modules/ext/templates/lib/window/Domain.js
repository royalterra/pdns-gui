function DomainWindow(domain)
{
  var win_id = get_win_id(domain);
  if (!win_id) return;
  
  if (domain)
  {
    var title = 'Edit domain';
  }
  else
  {
    var title = 'Add domain';
  }
  
  var win = new Ext.ux.Window({
    id: win_id,
    title: title,
    width: 300,
    items: {
      height: 200,
      html: 'test'
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
