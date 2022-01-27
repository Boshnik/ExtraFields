ExtraFields.window.CreateField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'extrameta-field-window-create';
    }
    Ext.applyIf(config, {
        title: _('extrameta_field_create'),
        width: 550,
        autoHeight: true,
        url: ExtraFields.config.connectorUrl,
        action: 'mgr/field/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit();
            }, scope: this
        }]
    });
    ExtraFields.window.CreateField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.CreateField, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        },{
            layout: 'column',
            items:[{
                columnWidth: .5,
                layout: 'form',
                width:'100%',
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'textfield',
                    fieldLabel: _('extrameta_field_name'),
                    name: 'name',
                    id: config.id + '-name',
                    anchor: '99%',
                    allowBlank: false,
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('extrameta_field_precision'),
                    name: 'precision',
                    id: config.id + '-precision',
                    anchor: '99%',
                    allowBlank: false,
                }, {
                    xtype: 'xcheckbox',
                    fieldLabel: _('extrameta_field_null'),
                    name: 'null',
                    id: config.id + '-null',
                    anchor: '99%',
                    checked: config.record ? config.record.object['null'] : true,
                }]
            },{
                columnWidth: .5,
                layout: 'form',
                width:'100%',
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'textfield',
                    fieldLabel: _('extrameta_field_dbtype'),
                    name: 'dbtype',
                    id: config.id + '-dbtype',
                    anchor: '99%'
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('extrameta_field_phptype'),
                    name: 'phptype',
                    id: config.id + '-phptype',
                    anchor: '99%',
                    allowBlank: false,
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('extrameta_field_default'),
                    name: 'default',
                    id: config.id + '-default',
                    anchor: '99%',
                    allowBlank: true,
                }]
            }]
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('extrameta_field_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('extrameta-field-window-create', ExtraFields.window.CreateField);


ExtraFields.window.UpdateField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'extrameta-field-window-update';
    }
    Ext.applyIf(config, {
        title: _('extrameta_field_update'),
        width: 550,
        autoHeight: true,
        url: ExtraFields.config.connectorUrl,
        action: 'mgr/field/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    ExtraFields.window.UpdateField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateField, MODx.Window, {

    getFields: function (config) {
        return ExtraFields.window.CreateField.prototype.getFields.call(this, config);
    },

    loadDropZones: function () {
    }

});
Ext.reg('extrameta-field-window-update', ExtraFields.window.UpdateField);