ExtraFields.window.CreateFieldAbs = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = Ext.id();
    }
    Ext.applyIf(config, {
        title: _('ef_row_create'),
        action: 'mgr/abs/create',
    });

    ExtraFields.window.CreateFieldAbs.superclass.constructor.call(this, config);

    this.on('afterrender', () => {
        this.statusFields(config);
    });
};
Ext.extend(ExtraFields.window.CreateFieldAbs, ExtraFields.window.Default, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'hidden',
            name: 'field_id',
            id: config.id + '-field_id',
        }, {
            layout: 'column',
            defaults: {msgTarget: 'under'},
            renderTo: Ext.getBody(),
            items: [{
                columnWidth: .3,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_caption'),
                    name: 'caption',
                    id: config.id + '-caption',
                    anchor: '99%',
                    allowBlank: false,
                }]
            }, {
                columnWidth: .7,
                layout: 'form',
                defaults: {msgTarget: 'under'},
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('ef_field_help'),
                    name: 'help',
                    id: config.id + '-help',
                    anchor: '99%',
                    allowBlank: true,
                }]
            }]
        }, {
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
                    hidden: true,
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
                    hidden: true,
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
                    hidden: true,
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
                    hidden: true,
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
                    hidden: true,
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
            text: _('ef_field_values_desc'),
            hidden: true,
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
            }, {
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
            anchor: '100%',
            width: 100,
            allowBlank: true,
            hidden: true,
            url: ExtraFields.config.pageblocks
                ? ExtraFields.config.pageblocks.connectorUrl
                : '',
            baseParams: {
                action: 'mgr/table/getlist',
                sort: 'menuindex',
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
            allowBlank: true,
            hidden: true,
        }, {
            xtype: 'label',
            id: config.id + '-where-desc',
            cls: 'desc-under',
            text: _('resourcelist_where_desc'),
            hidden: true,
        }, {
            xtype: 'textfield',
            fieldLabel: _('ef_field_xtype'),
            name: 'xtype',
            id: config.id + '-xtype',
            anchor: '100%',
            width: 100,
            allowBlank: true,
            hidden: true,
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
            allowBlank: false,
            hidden: true,
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
            text: _('checkbox_columns_desc'),
            hidden: true,
        }, {
            xtype: 'fieldset',
            title: _('ef_position'),
            id: Ext.id(),
            layout: 'form',
            columnWidth: 1,
            collapsible: true,
            items: [{
                layout: 'column',
                items: [{
                    columnWidth: .4,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    cls: 'x-superboxselect ef-superboxselect',
                    items: [{
                        xtype: 'ef-combo-getlist',
                        fieldLabel: _('ef_field_tab'),
                        name: 'tab_id',
                        id: config.id + '-tab-id',
                        allowBlank: true,
                        baseParams: {
                            action: 'mgr/tab/getlist',
                            class_name: config.class_name,
                            sort: 'menuindex',
                            dir: 'asc',
                            combo: 1,
                        },
                        triggerConfig: {
                            tag: 'div',
                            cls: 'x-superboxselect-btns',
                            cn: [
                                {tag: 'div', cls: 'x-superboxselect-btn-expand x-form-trigger'},
                                {tag: 'div', cls: 'x-superboxselect-btn-clear x-form-trigger'}
                            ]
                        },
                        onTriggerClick: function(event, btn){
                            if (btn && Ext.get(btn).hasClass('x-superboxselect-btn-clear')) {
                                Ext.getCmp(config.id + '-tab-id').setValue();
                                this.fireEvent('render', this);
                            } else {
                                MODx.combo.ComboBox.superclass.onTriggerClick.call(this);
                            }
                        },
                        listeners: {
                            render: {
                                fn: this.changeTabId,
                                scope: this,
                            },
                            select: {
                                fn: this.changeTabId,
                                scope: this,
                            }
                        }
                    }]
                }, {
                    columnWidth: .4,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [{
                        xtype: 'ef-combo-getlist',
                        fieldLabel: _('ef_field_category'),
                        name: 'category_id',
                        id: config.id + '-category-id',
                        allowBlank: true,
                        baseParams: {
                            action: 'mgr/category/getlist',
                            tab_id: config.record ? config.record.object.tab_id : 0,
                            sort: 'menuindex',
                            dir: 'asc',
                            combo: 1,
                        }
                    }]
                }, {
                    columnWidth: .2,
                    layout: 'form',
                    defaults: {msgTarget: 'under'},
                    items: [{
                        xtype: 'numberfield',
                        inputType: 'number',
                        cls: 'x-form-text',
                        fieldLabel: _('ef_field_index'),
                        name: 'index',
                        id: config.id + '-index',
                        allowBlank: true,
                        anchor: '100%',
                        width: 100,
                        listeners: {
                            render: function (el) {
                                if(Ext.isEmpty(el.value)) {
                                    el.setValue(0);
                                }
                            }
                        }
                    }]
                }]
            }]
        }, {
            xtype: 'fieldset',
            title: _('ef_accessibility'),
            id: Ext.id(),
            layout: 'form',
            columnWidth: 1,
            collapsible: true,
            items: ExtraFields.utils.getAbs(config, config.class_name)
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

    statusFields: function (config) {
        const { id, field_type } = config;

        let values = Ext.getCmp(id + '-values');
        let values_desc = Ext.getCmp(id + '-values-desc');
        let defaultfield = Ext.getCmp(id + '-default');
        let defaultfield_desc = Ext.getCmp(id + '-default-desc');
        let table_id = Ext.getCmp(id + '-table_id');
        let where = Ext.getCmp(id + '-where');
        let where_desc = Ext.getCmp(id + '-where-desc');
        let sort = Ext.getCmp(id + '-sort');
        let dir = Ext.getCmp(id + '-dir');
        let number_allownegative = Ext.getCmp(id + '-number_allownegative');
        let number_minvalue = Ext.getCmp(id + '-number_minvalue');
        let number_maxvalue = Ext.getCmp(id + '-number_maxvalue');
        let custom_xtype = Ext.getCmp(id + '-xtype');
        let custom_xtype_desc = Ext.getCmp(id + '-xtype-desc');
        let columns = Ext.getCmp(id + '-columns');
        let columns_desc = Ext.getCmp(id + '-columns-desc');
        let required = Ext.getCmp(id + '-required');
        let source = Ext.getCmp(id + '-source');
        let source_path = Ext.getCmp(id + '-source_path');
        let disabled_dates = Ext.getCmp(id + '-disabled-dates');
        let disabled_days = Ext.getCmp(id + '-disabled-days');
        let hide_time = Ext.getCmp(id + '-hide-time');

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

        switch (field_type) {
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
            case 'pb-gallery':
                source.show();
                source_path.show();
                break;
            case 'pb-table':
                table_id.show();
                break;
            case 'efxtype':
                custom_xtype.show();
                custom_xtype_desc.show();
                break;
        }

    },

    changeTabId: function (combo, row) {
        let category = Ext.getCmp(this.id + '-category-id');
        category.allowBlank = true;
        let categoryEl = document.getElementById(category.id);
        let store = category.getStore();
        store.baseParams.tab_id = combo.value;

        if (row) {
            category.setValue('');
            store.load();
            categoryEl.value = '';
        } else {
            if (!combo.value) {
                category.setValue('');
                store.load();
            }
        }
        setTimeout(() => {
            category.allowBlank = !store.totalLength;
        }, 300);
    }

});
Ext.reg('ef-abs-window-create', ExtraFields.window.CreateFieldAbs);


ExtraFields.window.UpdateFieldAbs = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = Ext.id();
    }
    Ext.applyIf(config, {
        title: _('ef_row_update') + ': ' + config.record.object.caption,
        action: 'mgr/abs/update',
    });

    ExtraFields.window.UpdateFieldAbs.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.window.UpdateFieldAbs, ExtraFields.window.CreateFieldAbs);
Ext.reg('ef-abs-window-update', ExtraFields.window.UpdateFieldAbs);