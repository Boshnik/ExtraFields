ExtraFields.utils.getXtype = function (field) {
    let values = ExtraFields.object ? ExtraFields.object[field.name] : field.values;
    let xtype = {
        xtype: field.type,
        fieldLabel: field.caption,
        name: field.name,
        id: Ext.id(),
        anchor: '100%',
        width: '100%',
        allowBlank: !+field.required,
        description: '<b>[[*' + field.name + ']]</b>',
        listeners: {
            afterrender: function(el) {
                ExtraFields.utils.setDefaultValue(el, field.default);
            }
        }
    };

    if (['image', 'file'].includes(field.type)) {
        field.source = field.source || MODx.config['default_media_source'];
        var media_source = ExtraFields.config.media_source[field.source];
        var openTo = field.source_path;
        if (!Ext.isEmpty(values)) {
            let a = values.split('/');
            a.pop();
            openTo = a.join('/') + '/';
        }
    }

    switch (field.type) {
        case 'richtext':
            xtype.xtype = 'textarea';
            xtype.listeners.render = function (el) {
                ExtraFields.utils.renderRTE(el);
            }
            break;

        case 'modx-texteditor':
            xtype.height = 200;
            xtype.mimeType = 'text/x-smarty';
            xtype.modxTags = true;
            xtype.listeners = {
                render: function (el) {
                    if(el.getValue() == 'undefined') {
                        el.setValue('');
                    }
                }
            };
            break;

        case 'listbox':
            xtype.xtype = 'ef-combo-listbox';
            xtype.data = field.values;
            break;

        case 'listbox-multiple':
            xtype.xtype = 'ef-combo-listbox-multiple';
            xtype.data = field.values;
            xtype.listeners.render = function (el) {
                if (!Ext.isEmpty(values)) {
                    el.setValue(values.split('||'));
                }
            }
            break;

        case 'resourcelist':
            xtype.xtype = 'ef-combo-getlist';
            xtype.fields = ['id', 'pagetitle'];
            xtype.displayField = 'pagetitle';
            xtype.valueField = 'id';
            xtype.baseParams = {
                action: 'mgr/combo/resourcelist',
                where: field.where,
                sort: field.sort,
                dir: field.dir,
                limit: 5,
                combo: 1
            };
            break;

        case 'combo-boolean':
            xtype.hiddenName = field.name;
            xtype.store =  new Ext.data.SimpleStore({
                fields: ["d", "v"],
                data: [[_("yes"), 1], [_("no"), 0]]
            });
            break;

        case 'numberfield':
            xtype.inputType = 'number';
            xtype.cls = 'x-form-text';
            if(!field.number_allownegative) {
                xtype.minValue = 0;
            }
            if(!Ext.isEmpty(field.number_minvalue)) {
                xtype.minValue = field.number_minvalue;
            }
            if(!Ext.isEmpty(field.number_maxvalue)) {
                xtype.maxValue = field.number_maxvalue;
            }
            break;

        case 'xcheckbox':
            xtype.hideLabel = true;
            xtype.boxLabel = field.caption;
            xtype.inputValue = +field.default || 0;
            xtype.checked = +values;
            break;

        case 'checkboxgroup':
            xtype.columns = +field.columns || 1;
            xtype.items = [];
            xtype.name = '';
            field.values.split('||').forEach(function (value){
                var val = value.split('==');
                xtype.items.push({
                    xtype: 'checkbox',
                    boxLabel: val[0],
                    inputValue: val[1] || val[0],
                    name: field.name + '[]',
                    id: Ext.id(),
                    checked: values ? values.split('||').includes(val[1] || val[0]) : false,
                    listeners: {
                        afterrender: function(el) {
                            ExtraFields.utils.setDefaultValue(el, field.default);
                        }
                    }
                });
            });
            break;

        case 'radiogroup':
            xtype.columns = +field.columns || 1;
            xtype.items = [];
            xtype.name = '';
            xtype.listeners = {};
            field.values.split('||').forEach(function (value){
                var val = value.split('==');
                xtype.items.push({
                    boxLabel: val[0],
                    inputValue: val[1] || val[0],
                    name: field.name,
                    id: Ext.id(),
                    checked: values ? values.includes(val[1] || val[0]) : (field.default == (val[1] || val[0]) ? true : false),
                    listeners: {
                        render: function(el) {
                            if(!Ext.isEmpty(field.default) && Ext.isEmpty(el.inputValue)) {
                                el.setValue(field.default);
                            }
                        }
                    }
                });
            });
            break;

        case 'image':
            xtype.xtype = 'ef-combo-browser';
            xtype.triggerConfig = {
                tag: 'div',
                cls: 'ef-combo-btns',
                cn: [
                    {tag: 'div', cls: 'x-form-trigger x-field-combo-list icon icon-image', trigger: 'image'},
                ]
            };

            xtype.source = field.source;
            xtype.allowedFileTypes = media_source.allowedFileTypes;
            xtype.openTo = openTo.replace(media_source.basePath, '');
            xtype.listeners = {
                afterrender: (el) => {
                    setTimeout(() => {
                        ExtraFields.utils.updateImage(el.id, el.value);
                    },0);
                },
                select: function (data) {
                    ExtraFields.utils.updateImage(this.id, data.fullRelativeUrl);
                },
                change: (el, f) => {
                    ExtraFields.utils.updateImage(el.id, el.getValue());
                }
            }
            break;

        case 'file':
            xtype.xtype = 'ef-combo-browser';
            xtype.source = field.source;
            xtype.allowedFileTypes = media_source.allowedFileTypes;
            xtype.openTo = openTo.replace(media_source.basePath, '');
            break;

        case 'xdatetime':
            xtype.dateFormat =  MODx.config.manager_date_format;
            xtype.timeFormat =  MODx.config.manager_time_format;
            xtype.dateWidth = field.hide_time ? '100%' : '70%';
            xtype.timeWidth = field.hide_time ? 0 : '30%';
            if (field.disabled_dates) {
                xtype.disabledDates = field.disabled_dates.split(',');
            }
            if (xtype.disabledDays) {
                xtype.disabledDays = field.disabled_days.split(',');
            }
            break;

        case 'colorpicker':
            xtype.xtype = 'textfield';
            xtype.cls = 'coloris';
            xtype.anchor = '20%';
            xtype.listeners = {
                change: {
                    fn: MODx.fireResourceFormChange,
                    scope: this
                },
                afterrender: function (el) {
                    Coloris({
                        el: '.coloris',
                        wrap: true,
                        theme: 'modx' + ColorPicker.config.modxversion,
                        themeMode: 'light',
                        margin: 5,
                        format: 'hex',
                        formatToggle: true,
                        alpha: true,
                        swatchesOnly: false,
                        focusInput: false,
                        autoClose: true,
                        clearButton: {
                            show: true,
                            label: _('delete')
                        },
                        swatches: []
                    });
                    setTimeout(() => {
                        el.container.dom.querySelector('.clr-field').style.color = el.value;
                    },500);
                }
            }
            break;

        case 'pb-gallery':
        case 'pb-video-gallery':
            xtype.parent_id = 0;
            xtype.table_id = field.table_id;
            xtype.ef_field_id = field.field_id;
            xtype.source = field.source;
            xtype.source_path = field.source_path;
            xtype.width = 100;
            xtype.gallery_help = field.help;
            delete xtype.help;
            delete xtype.listeners;
            break;

        case 'pb-table':
            xtype.parent_id = 0;
            xtype.table_id = field.table_id;
            xtype.ef_field_id = field.field_id;
            xtype.table_columns = field.table_columns;
            delete xtype.listeners;
            break;

        case 'readonly':
            xtype.xtype = 'textfield';
            xtype.readOnly = true;
            break;

        case 'efxtype':
            xtype.xtype = field.xtype;
            xtype.hiddenName = field.name;

            if(field.xtype == 'modx-description') {
                xtype.listeners = {};
                xtype.html = field.default;
            }
            break;

    }

    let items = [xtype];

    if (field.type == 'image') {
        items.push({
            anchor: '100%',
            html: '',
            bodyCssClass: '',
            id: xtype.id + '-preview',
        });
    }

    if (field.help) {
        items.push({
            xtype: 'label',
            id: Ext.id(),
            cls: 'desc-under',
            text: field.help
        });
    }

    let classes = [field.cls];
    if (field.hide_time == 1) {
        classes.push('ef-hidden-time');
    }

    xtype = {
        layout: 'form',
        defaults: {msgTarget: 'under'},
        columnWidth: 1,
        labelAlign: 'top',
        width:'100%',
        items: items,
        cls: classes.join(' '),
    }

    if (ExtraFields.config.modxversion == 3) {
        xtype = {
            layout: 'form',
            width:'100%',
            cls: 'x-panel modx3-resource-panel x-form-label-top',
            items: [xtype]
        };
    }

    return xtype;
};