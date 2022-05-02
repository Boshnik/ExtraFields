ExtraFields.window.CreateField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ef-field-window-create';
    }
    Ext.applyIf(config, {
        title: _('ef_row_create'),
        action: 'mgr/field/create',
    });

    ExtraFields.window.CreateField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.CreateField, ExtraFields.window.Default, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'hidden',
            name: 'class_name',
            id: config.id + '-class_name',
        }, {
            layout: 'column',
            items: [{
                layout: 'form',
                columnWidth: .5,
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'ef-combo-field-types',
                    fieldLabel: _('ef_field_type'),
                    name: 'type',
                    hiddenName: 'type',
                    id: config.id + '-type',
                    combo: config.combo,
                    anchor: '100%',
                    width: 100,
                    allowBlank: false,
                    listeners: {
                        render: {
                            fn: this.changeFields,
                            scope: this,
                        },
                        select: {
                            fn: this.changeFields,
                            scope: this,
                        }
                    }
                }]
            }, {
                layout: 'form',
                columnWidth: .5,
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_name'),
                    name: 'name',
                    id: config.id + '-name',
                    anchor: '100%',
                    width: 100,
                    allowBlank: false,
                }, {
                    xtype: 'label',
                    cls: 'desc-under',
                    text: _('ef_field_name_desc')
                }]
            }]
        }, {
            xtype: 'fieldset',
            title: _('ef_settings'),
            id: config.id + '-settings',
            columnWidth: 1,
            collapsible: true,
            hidden: true,
            items: [{
                xtype: 'ef-grid-field-abs',
                field_id: config.record ? config.record.object.id : 0,
                config_id: config.id,
            }]
        },{
            xtype: 'xcheckbox',
            boxLabel: _('ef_row_active'),
            name: 'active',
            id: config.id + '-active',
            checked: config.record ? config.record.object['active'] : true,
        }];
    },

    changeFields: function (combo, row) {
        let settings = Ext.getCmp(this.id + '-settings');
        Ext.isEmpty(combo.value) ? settings.hide() : settings.show();
    },

});
Ext.reg('ef-field-window-create', ExtraFields.window.CreateField);


ExtraFields.window.UpdateField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ef-field-window-update';
    }
    Ext.applyIf(config, {
        title: _('ef_row_update') + ': ' + config.record.object.name,
        action: 'mgr/field/update',
    });

    ExtraFields.window.UpdateField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateField, ExtraFields.window.CreateField);
Ext.reg('ef-field-window-update', ExtraFields.window.UpdateField);