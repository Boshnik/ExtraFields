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
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_caption'),
                    name: 'caption',
                    id: config.id + '-caption',
                    anchor: '100%',
                    width: 100,
                    allowBlank: true,
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
            layout: 'column',
            items: [{
                columnWidth: .4,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
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
                columnWidth: .6,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_default'),
                    name: 'default',
                    id: config.id + '-default',
                    anchor: '100%',
                    width: 100,
                    allowBlank: true,
                },{
                    xtype: 'label',
                    id: config.id + '-default-desc',
                    cls: 'desc-under',
                    text: _('tv_default_desc'),
                }]
            }]
        }, {
            layout: 'column',
            items: [{
                columnWidth: .35,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('disabled_dates'),
                    name: 'disabled_dates',
                    id: config.id + '-disabled-dates',
                    allowBlank: true,
                    anchor: '100%',
                    width: 100,
                    hidden: true,
                }]
            }, {
                columnWidth: .35,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('disabled_days'),
                    name: 'disabled_days',
                    id: config.id + '-disabled-days',
                    allowBlank: true,
                    anchor: '100%',
                    width: 100,
                    hidden: true,
                }]
            }, {
                columnWidth: .3,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'combo-boolean',
                    store:  new Ext.data.SimpleStore({
                        fields: ["d", "v"],
                        data: [[_("yes"), 1], [_("no"), 0]]
                    }),
                    fieldLabel: _('hide_time'),
                    name: 'hide_time',
                    hiddenName: 'hide_time',
                    id: config.id + '-hide-time',
                    allowBlank: true,
                    anchor: '100%',
                    width: 100,
                    hidden: true,
                }]
            }]
        }, {
            layout: 'column',
            items: [{
                columnWidth: .3,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'combo-boolean',
                    store: new Ext.data.SimpleStore({
                        fields: ["d", "v"],
                        data: [[_("yes"), 1], [_("no"), 0]]
                    }),
                    fieldLabel: _('number_allownegative'),
                    name: 'number_allownegative',
                    hiddenName: 'number_allownegative',
                    id: config.id + '-number_allownegative',
                    anchor: '100%',
                    allowBlank: true,
                }],
            },{
                columnWidth: .35,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'numberfield',
                    inputType: 'number',
                    cls: 'x-form-text',
                    fieldLabel: _('number_minvalue'),
                    name: 'number_minvalue',
                    id: config.id + '-number_minvalue',
                    anchor: '100%',
                    width: 100,
                    allowBlank: true,
                }],
            },{
                columnWidth: .35,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'numberfield',
                    inputType: 'number',
                    cls: 'x-form-text',
                    fieldLabel: _('number_maxvalue'),
                    name: 'number_maxvalue',
                    id: config.id + '-number_maxvalue',
                    anchor: '100%',
                    width: 100,
                    allowBlank: true,
                    listeners: {
                        render: function (el) {
                            if(Ext.isEmpty(el.value)) {
                                el.setValue(2147483647);
                            }
                        }
                    }
                }],
            }]
        }, {
            layout: 'column',
            items: [{
                columnWidth: .5,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'modx-combo-source',
                    fieldLabel: _('source'),
                    id: config.id + '-source',
                    name: 'source',
                    hiddenName: 'source',
                    anchor: '100%',
                    width: 100,
                    listeners: {
                        render: function (el) {
                            if(Ext.isEmpty(el.value) || el.value == 0) {
                                el.setValue(MODx.config.default_media_source);
                            }
                        }
                    }
                }]
            },{
                columnWidth: .5,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_source_path'),
                    id: config.id + '-source_path',
                    name: 'source_path',
                    anchor: '100%',
                    width: 100,
                    allowBlank: false,
                    listeners: {
                        render: function (el) {
                            if(Ext.isEmpty(el.value)) {
                                el.setValue('/');
                            }
                        }
                    }
                }]
            }]
        }, {
            xtype: 'textarea',
            fieldLabel: _('ef_field_values'),
            name: 'values',
            height: 100,
            id: config.id + '-values',
            anchor: '100%',
            width: 100,
            hidden: true,
            allowBlank: true,
        }, {
            xtype: 'label',
            id: config.id + '-values-desc',
            cls: 'desc-under',
            text: _('ef_field_values_desc')
        }, {
            layout: 'column',
            items: [{
                columnWidth: .5,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_sort'),
                    id: config.id + '-sort',
                    name: 'sort',
                    anchor: '100%',
                    width: 100,
                    hidden: true,
                    listeners: {
                        render: function (el) {
                            if(Ext.isEmpty(el.value)) {
                                el.setValue('id');
                            }
                        }
                    }
                }]
            },{
                columnWidth: .5,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items:[{
                    xtype: 'ef-combo-dir',
                    fieldLabel: _('ef_field_dir'),
                    id: config.id + '-dir',
                    name: 'dir',
                    anchor: '100%',
                    width: 100,
                    hidden: true,
                    allowBlank: false,
                    listeners: {
                        render: function (el) {
                            if(Ext.isEmpty(el.value)) {
                                el.setValue('asc');
                            }
                        }
                    }
                }]
            }]
        }, {
            xtype: 'ef-combo-getlist',
            fieldLabel: _('ef_table'),
            name: 'table_id',
            id: config.id + '-table_id',
            hidden: true,
            allowBlank: true,
            url: ExtraFields.config.pageblocks.connectorUrl,
            baseParams: {
                action: 'mgr/table/getlist',
                sort: 'rank',
                dir: 'asc',
                combo: true,
            }
        }, {
            xtype: 'textfield',
            fieldLabel: _('resourcelist_where'),
            name: 'where',
            id: config.id + '-where',
            anchor: '100%',
            width: 100,
            hidden: true,
            allowBlank: true,
        }, {
            xtype: 'label',
            id: config.id + '-where-desc',
            cls: 'desc-under',
            text: _('resourcelist_where_desc')
        }, {
            xtype: 'textfield',
            fieldLabel: _('ef_field_xtype'),
            name: 'xtype',
            id: config.id + '-xtype',
            anchor: '100%',
            width: 100,
            hidden: true,
            allowBlank: true,
        }, {
            xtype: 'label',
            id: config.id + '-xtype-desc',
            cls: 'desc-under',
            text: _('ef_field_xtype_desc'),
            hidden: true,
        }, {
            xtype: 'numberfield',
            inputType: 'number',
            cls: 'x-form-text',
            fieldLabel: _('checkbox_columns'),
            name: 'columns',
            id: config.id + '-columns',
            anchor: '100%',
            width: 100,
            minValue:1,
            maxValue:10,
            hidden: true,
            allowBlank: false,
            listeners: {
                render: function (columns) {
                    if(Ext.isEmpty(columns.value)) {
                        columns.setValue(3);
                    }
                }
            }
        }, {
            xtype: 'label',
            id: config.id + '-columns-desc',
            cls: 'desc-under',
            text: _('checkbox_columns_desc')
        }, {
            xtype: 'textarea',
            fieldLabel: _('ef_field_help'),
            name: 'help',
            height: 50,
            id: config.id + '-help',
            anchor: '100%',
            width: 100,
            allowBlank: true,
        }, {
            xtype: 'fieldset',
            title: _('ef_accessibility'),
            columnWidth: 1,
            collapsible: true,
            items: [{
                xtype: 'ef-grid-field-abs',
                field_id: config.record ? config.record.object.id : 0,
                field_caption: config.record ? config.record.object.caption : '',
            }]
        },{
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
            }, {
                xtype: 'xcheckbox',
                boxLabel: _('ef_field_required'),
                name: 'required',
                id: config.id + '-required',
                checked: config.record ? config.record.object['required'] : false,
                hidden: true,
            }]
        }];
    },

    changeFields: function (combo, row) {
        let values = Ext.getCmp(this.id + '-values');
        let values_desc = Ext.getCmp(this.id + '-values-desc');
        let defaultfield = Ext.getCmp(this.id + '-default');
        let defaultfield_desc = Ext.getCmp(this.id + '-default-desc');
        let table_id = Ext.getCmp(this.id + '-table_id');
        let where = Ext.getCmp(this.id + '-where');
        let where_desc = Ext.getCmp(this.id + '-where-desc');
        let sort = Ext.getCmp(this.id + '-sort');
        let dir = Ext.getCmp(this.id + '-dir');
        let number_allownegative = Ext.getCmp(this.id + '-number_allownegative');
        let number_minvalue = Ext.getCmp(this.id + '-number_minvalue');
        let number_maxvalue = Ext.getCmp(this.id + '-number_maxvalue');
        let custom_xtype = Ext.getCmp(this.id + '-xtype');
        let custom_xtype_desc = Ext.getCmp(this.id + '-xtype-desc');
        let columns = Ext.getCmp(this.id + '-columns');
        let columns_desc = Ext.getCmp(this.id + '-columns-desc');
        let required = Ext.getCmp(this.id + '-required');
        let source = Ext.getCmp(this.id + '-source');
        let source_path = Ext.getCmp(this.id + '-source_path');
        let disabled_dates = Ext.getCmp(this.id + '-disabled-dates');
        let disabled_days = Ext.getCmp(this.id + '-disabled-days');
        let hide_time = Ext.getCmp(this.id + '-hide-time');

        values.hide();
        values_desc.hide();
        table_id.hide();
        where.hide();
        where_desc.hide();
        sort.hide();
        dir.hide();
        number_allownegative.hide();
        number_minvalue.hide();
        number_maxvalue.hide();
        custom_xtype.hide();
        custom_xtype_desc.hide();
        columns.hide();
        columns_desc.hide();
        source.hide();
        source_path.hide();
        disabled_dates.hide();
        disabled_days.hide();
        hide_time.hide();

        defaultfield.show();
        defaultfield_desc.show();
        required.show();

        switch (combo.value) {
            case 'listbox':
            case 'listbox-multiple':
                values.show();
                values_desc.show();
                break;
            case 'resourcelist':
                sort.show();
                dir.show();
                where.show();
                where_desc.show();
                break;
            case 'numberfield':
                number_allownegative.show();
                number_minvalue.show();
                number_maxvalue.show();
                break;
            case 'xcheckbox':
                required.hide();
                break;
            case 'checkboxgroup':
                values.show();
                values_desc.show();
                columns.show();
                columns_desc.show().setText(_('checkbox_columns_desc'));
                break;
            case 'radiogroup':
                values.show();
                values_desc.show();
                columns.show();
                columns_desc.show();
                columns_desc.setText(_('radio_columns_desc'));
                break;
            case 'image':
            case 'file':
                source.show().setValue(MODx.config.default_media_source);
                source_path.show();
                break;
            case 'xdatetime':
                disabled_dates.show();
                disabled_days.show();
                hide_time.show();
                break;
            case 'pageblocks':
                table_id.show();
                break;
            case 'efxtype':
                custom_xtype.show();
                custom_xtype_desc.show();
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
        title: _('ef_row_update') + ': ' + config.record.object.name,
        action: 'mgr/field/update',
    });

    ExtraFields.window.UpdateField.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateField, ExtraFields.window.CreateField);
Ext.reg('ef-field-window-update', ExtraFields.window.UpdateField);