ExtraFields.window.CreateUserField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'extrauser-field-window-create';
    }
    Ext.applyIf(config, {
        title: _('extrauser_field_create'),
        width: 550,
        autoHeight: true,
        url: ExtraFields.config.connectorUrl,
        action: 'mgr/user/field/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit();
            }, scope: this
        }]
    });
    ExtraFields.window.CreateUserField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.CreateUserField, MODx.Window, {

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
                    fieldLabel: _('extrauser_field_name'),
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
                    fieldLabel: _('extrauser_field_label'),
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
                    xtype: 'extrauser-combo-position',
                    fieldLabel: _('extrauser_field_position'),
                    name: 'position',
                    hiddenName: 'position',
                    id: config.id + '-position',
                    anchor: '99%',
                    allowBlank: false,
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
                    fieldLabel: _('extrauser_field_index'),
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
                    fieldLabel: _('extrauser_field_fieldtype'),
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
            boxLabel: _('extrauser_field_required'),
            name: 'required',
            id: config.id + '-required',
            anchor: '99%',
            checked: false,
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('extrauser_field_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

});
Ext.reg('extrauser-field-window-create', ExtraFields.window.CreateUserField);


ExtraFields.window.UpdateUserField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'extrauser-field-window-update';
    }
    Ext.applyIf(config, {
        title: _('extrauser_field_update'),
        width: 550,
        autoHeight: true,
        url: ExtraFields.config.connectorUrl,
        action: 'mgr/user/field/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    ExtraFields.window.UpdateUserField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateUserField, MODx.Window, {

    getFields: function (config) {
        return ExtraFields.window.CreateUserField.prototype.getFields.call(this, config);
    },

});
Ext.reg('extrauser-field-window-update', ExtraFields.window.UpdateUserField);