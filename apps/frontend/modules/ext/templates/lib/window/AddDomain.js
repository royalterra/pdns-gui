function AddDomainWindow()
{
  var win_id = get_win_id();
  if (!win_id) return;
  
  var form = new Ext.form.FormPanel({
    bodyStyle: 'padding: 10px;',
    border: false,
    height: 80,
    labelWidth: 60,
    url: '<?php echo url_for('domain/add') ?>',
    defaults: { allowBlank: false },
    items: [
      {
        xtype: 'textfield',
        fieldLabel: 'Name',
        width: 170,
        name: 'name'
      },{
        xtype: 'combo',
        store: TemplateStore,
        fieldLabel: 'Template',
        width: 170,
        displayField: 'name',
        valueField: 'id',
        name: 'template_id',
        hiddenName: 'template_id',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        editable: false,
        emptyText: 'Please select...'
      }
    ]
  });
  
  var win = new Ext.ux.Window({
    id: win_id,
    title: 'Add domain',
    width: 300,
    doSubmit: function(){
      form.form.submit({
        success: function(form,action){
          win.close();
          Ext.Msg.alert('Info',action.result.info);
        }
      });
    },
    items: form,
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
