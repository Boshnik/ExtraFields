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
                    id: config.id + '-field-default',
                    anchor: '100%',
                    width: 100,
                    allowBlank: true,
                }]
            }]
        }, {
            xtype: 'textarea',
            fieldLabel: _('ef_field_values'),
            name: 'precision',
            id: config.id + '-precision',
            anchor: '100%',
            width: 100,
            hidden: true,
            allowBlank: false,
        }, {
            xtype: 'label',
            id: config.id + '-precision-desc',
            cls: 'desc-under',
            text: _('ef_field_precision_desc'),
            hidden: true,
        }, {
            xtype: 'checkboxgroup',
            columns: 2,
            items: [{
                xtype: 'xcheckbox',
                boxLabel: _('ef_field_null'),
                name: 'field_null',
                id: config.id + '-field-null',
                checked: config.record?.field_null ?? true,
                disabled: (config.record?.field_type === 'combo-boolean') || false
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('ef_field_index'),
                name: 'field_index',
                id: config.id + '-field-index',
                checked: config.record?.field_index ?? false,
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
                field_id: config.record?.id || 0,
                config_id: config.id,
                cls: '',
            }]
        }, {
            xtype: 'checkboxgroup',
            columns: 3,
            items: [{
                xtype: 'xcheckbox',
                boxLabel: _('ef_row_active'),
                name: 'active',
                id: Ext.id(),
                checked: config.record?.active ?? true
            }]
        }];
    },

    changeFields: function (combo) {
        let field_null = Ext.getCmp(this.id + '-field-null');
        let field_default = Ext.getCmp(this.id + '-field-default');
        let precision = Ext.getCmp(this.id + '-precision');
        let precision_desc = Ext.getCmp(this.id + '-precision-desc');

        field_default.setDisabled(false);
        if (field_null) {
            field_null.setDisabled(false).setValue(1);
        }

        precision.hide().setDisabled(true);
        precision_desc.hide();

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
                break;
            case 'enumfield':
            case 'setfield':
                precision.show().setDisabled(false);
                precision_desc.show();
                break;
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
        title: _('ef_row_update') + ': ' + config.record.field_name,
        action: 'mgr/field/update',
    });

    ExtraFields.window.UpdateField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateField, ExtraFields.window.CreateField);
Ext.reg('ef-field-window-update', ExtraFields.window.UpdateField);