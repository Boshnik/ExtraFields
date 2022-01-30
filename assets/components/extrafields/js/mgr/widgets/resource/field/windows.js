ExtraFields.window.CreateResourceField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'extraresource-field-window-create';
    }
    Ext.applyIf(config, {
        title: _('extraresource_field_create'),
        width: 550,
        autoHeight: true,
        url: ExtraFields.config.connectorUrl,
        action: 'mgr/resource/field/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit();
            }, scope: this
        }]
    });
    ExtraFields.window.CreateResourceField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.CreateResourceField, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            layout: 'column',
            items:[{
                columnWidth: .5,
                layout: 'form',
                width:'100%',
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'textfield',
                    readOnly: !!config.record,
                    fieldLabel: _('extraresource_field_name'),
                    name: 'name',
                    id: config.id + '-name',
                    anchor: '99%',
                    allowBlank: false,
                }]
            },{
                columnWidth: .5,
                layout: 'form',
                width:'100%',
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'textfield',
                    fieldLabel: _('extraresource_field_label'),
                    name: 'label',
                    id: config.id + '-label',
                    anchor: '99%',
                    allowBlank: false,
                }]
            }]
        }, {
            layout: 'column',
            items: [{
                columnWidth: .4,
                layout: 'form',
                width: '100%',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'extrafields-combo-getlist',
                    fieldLabel: _('extraresource_field_position'),
                    name: 'position',
                    id: config.id + '-position',
                    anchor: '99%',
                    allowBlank: false,
                    baseParams: {
                        action: 'mgr/resource/tab/getlist',
                        sort: 'rank',
                        dir: 'asc',
                        combo: 1,
                    }
                }]
            }, {
                columnWidth: .2,
                layout: 'form',
                width: '100%',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'numberfield',
                    inputType: 'number',
                    cls: 'x-form-text',
                    fieldLabel: _('extraresource_field_index'),
                    name: 'index',
                    id: config.id + '-index',
                    anchor: '99%',
                    value: 99,
                    maxValue: 99,
                    minValue: 0
                }]
            }, {
                columnWidth: .4,
                layout: 'form',
                width: '100%',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'extrafields-combo-getlist',
                    fieldLabel: _('extraresource_field_fieldtype'),
                    name: 'fieldtype',
                    id: config.id + '-fieldtype',
                    anchor: '99%',
                    allowBlank: false,
                    baseParams: {
                        action: 'mgr/field/getlist',
                        sort: 'rank',
                        dir: 'asc',
                        combo: 1,
                    }
                }]
            }]
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('extraresource_field_required'),
            name: 'required',
            id: config.id + '-required',
            anchor: '99%',
            checked: false,
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('extraresource_field_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

});
Ext.reg('extraresource-field-window-create', ExtraFields.window.CreateResourceField);


ExtraFields.window.UpdateResourceField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'extraresource-field-window-update';
    }
    Ext.applyIf(config, {
        title: _('extraresource_field_update'),
        width: 550,
        autoHeight: true,
        url: ExtraFields.config.connectorUrl,
        action: 'mgr/resource/field/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    ExtraFields.window.UpdateResourceField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateResourceField, MODx.Window, {

    getFields: function (config) {
        return ExtraFields.window.CreateResourceField.prototype.getFields.call(this, config);
    },

});
Ext.reg('extraresource-field-window-update', ExtraFields.window.UpdateResourceField);