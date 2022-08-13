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
        }, {
            xtype: 'hidden',
            name: 'class_name',
        }, {
            xtype: 'hidden',
            name: 'active',
        }, {
            layout: 'column',
            items: [{
                layout: 'form',
                columnWidth: .5,
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'ef-combo-field-meta',
                    fieldLabel: _('ef_field_type'),
                    name: 'type',
                    hiddenName: 'type',
                    id: config.id + '-type',
                    combo: config.combo,
                    anchor: '100%',
                    width: 100,
                    allowBlank: false,
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
        }];
    }

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