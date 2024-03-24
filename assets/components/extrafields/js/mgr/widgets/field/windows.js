ExtraFields.window.CreateField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ef-field-window-create';
    }
    Ext.applyIf(config, {
        title: _('ef_row_create'),
        action: 'mgr/field/create',
        width: 650,
    });

    ExtraFields.window.CreateField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.CreateField, ExtraFields.window.Default, {

    getFields: function (config) {

        let field_null = true;
        let field_null_disabled = false;
        if (config.record && config.record.object) {
            field_null = config.record.object['field_null'];
            if (config.record.object.field_type === 'combo-boolean') {
                field_null = false;
                field_null_disabled = true;
            }
        }

        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            layout: 'column',
            items: [{
                layout: 'form',
                columnWidth: .5,
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'ef-combo-table-class',
                    fieldLabel: _('ef_class_name'),
                    name: 'class_name',
                    hiddenName: 'class_name',
                    id: config.id + '-class_name',
                    combo: config.combo,
                    create_field: 1,
                    anchor: '100%',
                    width: 100,
                    allowBlank: false,
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_name'),
                    name: 'field_name',
                    id: config.id + '-field_name',
                    anchor: '100%',
                    width: 100,
                    allowBlank: false,
                }]
            },{
                layout: 'form',
                columnWidth: .5,
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'ef-combo-field-types',
                    fieldLabel: _('ef_field_type'),
                    name: 'field_type',
                    hiddenName: 'field_type',
                    id: config.id + '-field_type',
                    combo: config.combo,
                    anchor: '100%',
                    width: 100,
                    allowBlank: false,
                    listeners: {
                        afterrender: {
                            fn: this.changeFields,
                            scope: this,
                        },
                        select: {
                            fn: this.changeFields,
                            scope: this,
                        }
                    }
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_default'),
                    name: 'field_default',
                    id: config.id + '-field_default',
                    anchor: '100%',
                    width: 100,
                    allowBlank: true,
                }]
            }]
        }, {
            xtype: 'checkboxgroup',
            hideLabel: true,
            name: 'checkboxgroup',
            columns: 3,
            items: [{
                xtype: 'xcheckbox',
                boxLabel: _('ef_field_null'),
                name: 'field_null',
                id: config.id + '-field_null',
                checked: field_null,
                disabled: field_null_disabled
            }]
        }, {
            xtype: 'fieldset',
            title: _('ef_settings'),
            id: config.id + '-settings',
            columnWidth: 1,
            collapsible: true,
            hidden: false,
            items: [{
                xtype: 'ef-grid-field-abs',
                field_id: config.record ? config.record.object.id : 0,
                config_id: config.id,
                cls: '',
            }]
        }, {
            xtype: 'checkboxgroup',
            hideLabel: true,
            name: 'checkboxgroup',
            columns: 3,
            items: [{
                xtype: 'xcheckbox',
                boxLabel: _('ef_row_active'),
                name: 'active',
                id: config.id + '-active',
                checked: config.record ? config.record.object['active'] : true,
            }]
        }];
    },

    changeFields: function (combo, row) {
        let field_null = Ext.getCmp(this.id + '-field_null');
        let field_default = Ext.getCmp(this.id + '-field_default');

        field_default.setDisabled(false);
        if (field_null) {
            field_null.setDisabled(false).setValue(1);
        }

        switch (combo.value) {
            case 'textarea':
            case 'richtext':
            case 'modx-texteditor':
                field_default.setValue('').setDisabled(true);
                break;
            case 'combo-boolean':
            case 'xcheckbox':
                if (field_null) {
                    field_null.setDisabled(true).setValue(0);
                }
        }
    },

});
Ext.reg('ef-field-window-create', ExtraFields.window.CreateField);


ExtraFields.window.UpdateField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ef-field-window-update';
    }
    Ext.applyIf(config, {
        title: _('ef_row_update') + ': ' + config.record.object.field_name,
        action: 'mgr/field/update',
    });

    ExtraFields.window.UpdateField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateField, ExtraFields.window.CreateField);
Ext.reg('ef-field-window-update', ExtraFields.window.UpdateField);