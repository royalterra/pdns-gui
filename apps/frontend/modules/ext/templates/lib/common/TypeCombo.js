/* Type Combo */
Ext.ux.TypeCombo = function(cfg){

  if (!cfg) var cfg = {};

  var defaultCfg = {
    store: [
      ["SOA","SOA"],
      ["NS","NS"],
      ["MX","MX"],
      ["A","A"],
      ["CNAME","CNAME"]
    ],
    displayField: 'field2',
    valueField: 'field1',
    width: 120,
    name: 'type',
    hiddenName: 'type',
    mode: 'local',
    triggerAction: 'all',
    forceSelection: true,
    editable: false,
    emptyText: 'Select...'
  };

  Ext.applyIf(cfg, defaultCfg);
  
  return new Ext.form.ComboBox(cfg);
}
