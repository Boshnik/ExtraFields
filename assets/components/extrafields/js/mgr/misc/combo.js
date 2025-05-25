ExtraFields.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    ExtraFields.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
    });
    this.addEvents('clear', 'search');
};
Ext.extend(ExtraFields.combo.Search, Ext.form.TwinTriggerField, {
    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },

    _triggerSearch: function () {
        this.fireEvent('search', this);
    },

    _triggerClear: function () {
        this.fireEvent('clear', this);
    },

});
Ext.reg('ef-combo-search', ExtraFields.combo.Search);
Ext.reg('ef-field-search', ExtraFields.combo.Search);

ExtraFields.combo.SortDir = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            id: 'value',
            fields: ['display','value'],
            data: [
                ['ASC','asc'],
                ['DESC','desc'],
            ]
        }),
        mode: 'local',
        displayField: 'display',
        valueField: 'value',
    });
    ExtraFields.combo.SortDir.superclass.constructor.call(this,config);
};
Ext.extend(ExtraFields.combo.SortDir, MODx.combo.ComboBox);
Ext.reg('ef-combo-dir',ExtraFields.combo.SortDir);

ExtraFields.combo.Browser = function (config) {
    config = config || {};

    if (config.length !== 0 && config.openTo !== undefined) {
        if (!/^\//.test(config.openTo)) {
            config.openTo = '/' + config.openTo;
        }
        if (!/$\//.test(config.openTo)) {
            let tmp = config.openTo.split('/');
            delete tmp[tmp.length - 1];
            tmp = tmp.join('/');
            config.openTo = tmp.substr(1)
        }
    }

    Ext.applyIf(config, {
        width: 300,
        triggerAction: 'all'
    });
    ExtraFields.combo.Browser.superclass.constructor.call(this, config);
    this.config = config;
};
Ext.extend(ExtraFields.combo.Browser, Ext.form.TriggerField, {
    browser: null,

    onTriggerClick: function () {
        if (this.disabled) {
            return false;
        }

        let browser = MODx.load({
            xtype: 'modx-browser',
            id: Ext.id(),
            multiple: true,
            source: this.config.source || MODx.config['default_media_source'],
            rootVisible: this.config.rootVisible || false,
            allowedFileTypes: this.config.allowedFileTypes || '',
            wctx: this.config.wctx || 'web',
            openTo: this.config.openTo || '',
            rootId: this.config.rootId || '/',
            hideSourceCombo: this.config.hideSourceCombo || false,
            hideFiles: this.config.hideFiles || true,
            listeners: {
                select: {
                    fn: function (data) {
                        this.setValue(data.fullRelativeUrl);
                        this.fireEvent('select', data);
                    }, scope: this
                }
            },
        });
        browser.win.buttons[0].on('disable', function () {
            this.enable()
        });
        browser.win.tree.on('click', function (n) {
            this.setValue(this.getPath(n));
        }, this);
        browser.win.tree.on('dblclick', function (n) {
            this.setValue(this.getPath(n));
            browser.hide()
        }, this);
        browser.show();
    },

    getPath: function (n) {
        if (n.id === '/') {
            return '';
        }

        return n.attributes.path + '/';
    }
});
Ext.reg('ef-combo-browser', ExtraFields.combo.Browser);

ExtraFields.combo.TableClass = function(config) {
    config = config || {};

    let data = [
        [_('ef_class_name_modResource'),'modResource'],
        [_('ef_class_name_modUserProfile'),'modUserProfile'],
    ];

    if (config.create_field) {
        data.push(
            [_('ef_class_name_pbBlockValue'),'pbBlockValue'],
            [_('ef_class_name_pbTableValue'),'pbTableValue']
        );
    }

    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            id: 'value',
            fields: ['display','value'],
            data: data
        }),
        mode: 'local',
        displayField: 'display',
        valueField: 'value',
    });
    ExtraFields.combo.TableClass.superclass.constructor.call(this,config);
};
Ext.extend(ExtraFields.combo.TableClass, MODx.combo.ComboBox);
Ext.reg('ef-combo-table-class',ExtraFields.combo.TableClass);

ExtraFields.combo.Types = function(config) {
    config = config || {};

    let data = [
        [_('ef_field_type_textfield'),'textfield'],
        [_('ef_field_type_textarea'),'textarea'],
        [_('ef_field_type_listbox'),'listbox'],
        [_('ef_field_type_listbox_int'),'listbox-int'],
        [_('ef_field_type_listbox_multiple'),'listbox-multiple'],
        [_('ef_field_type_enumfield'),'enumfield'],
        [_('ef_field_type_resourcelist'),'resourcelist'],
        [_('ef_field_type_combo_boolean'),'combo-boolean'],
        [_('ef_field_type_numberfield'),'numberfield'],
        [_('ef_field_type_price'),'price'],
        [_('ef_field_type_xcheckbox'),'xcheckbox'],
        [_('ef_field_type_checkboxgroup'),'checkboxgroup'],
        [_('ef_field_type_radiogroup'),'radiogroup'],
        [_('ef_field_type_image'),'image'],
        [_('ef_field_type_file'),'file'],
        [_('ef_field_type_xdatetime'),'xdatetime'],
        [_('ef_field_type_readonly'),'readonly'],
        [_('ef_field_type_hidden'),'hidden'],
        [_('ef_field_type_xtype'),'ef-xtype']
    ];

    if (MODx.loadRTE) data.splice(2,0,[_('ef_field_type_richtext'),'richtext']);

    const ace = (typeof(MODx.ux) != 'undefined' && typeof(MODx.ux.Ace) == 'function') ? 1 : 0;
    if (ace) data.splice((MODx.loadRTE ? 3 : 2),0,[_('ef_field_type_texteditor'), 'modx-texteditor']);

    // TODO
    // if (typeof ColorPicker == 'object') {
    //     data.splice(-4,0,['ColorPicker','colorpicker']);
    // }

    if (!Ext.isEmpty(ExtraFields.config.pageblocks?.apiKey)) {
        data.splice(-4,0,[_('ef_field_type_gallery'),'pb-gallery']);
        data.splice(-4,0,[_('ef_field_type_video'),'pb-panel-video']);
        // data.splice(-4,0,[_('ef_field_type_table'),'pb-table']);
    }

    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            id: 'value',
            fields: ['display','value'],
            data: data
        }),
        mode: 'local',
        displayField: 'display',
        valueField: 'value',
    });
    ExtraFields.combo.Types.superclass.constructor.call(this,config);
};
Ext.extend(ExtraFields.combo.Types, MODx.combo.ComboBox);
Ext.reg('ef-combo-field-types',ExtraFields.combo.Types);

ExtraFields.combo.Listbox = function(config) {
    let store = [];
    if (config.all) {
        store.push([
            _('all'), ''
        ])
    }
    if (!Ext.isEmpty(config.values)) {
        let all = Object.values(config.values);
        all.forEach(function (value){
            store.push([
                value['name'],
                value['id']
            ]);
        })
    } else if (!Ext.isEmpty(config.data)) {
        let all = config.data.split('||');
        all.forEach(function (value) {
            let val = value.split('==');
            store.push([
                val[0],
                val[1] || val[0]
            ]);
        });
    }

    config = config || {};
    Ext.applyIf(config,{
        hiddenName: config.name,
        store: new Ext.data.ArrayStore({
            id: 0,
            fields: ['display','value'],
            data: store,
        }),
        mode: 'local',
        displayField: 'display',
        valueField: 'value',
    });

    ExtraFields.combo.Listbox.superclass.constructor.call(this,config);
};
Ext.extend(ExtraFields.combo.Listbox,MODx.combo.ComboBox);
Ext.reg('ef-combo-listbox',ExtraFields.combo.Listbox);

ExtraFields.combo.ListboxMulti = function (config) {
    config = config || {};

    let store = [];
    if (!Ext.isEmpty(config.data)) {
        let all = config.data.split('||');
        all.forEach(function (value) {
            let val = value.split('==');
            store.push([
                val[0],
                val[1] || val[0]
            ]);
        });
    }

    Ext.applyIf(config, {
        xtype:'superboxselect',
        store: new Ext.data.ArrayStore({
            id: 0,
            fields: ['display','value'],
            data: store
        }),
        mode: 'local',
        displayField: 'display',
        valueField: 'value',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        renderTo: Ext.getBody(),
    });
    config.name += '[]';

    ExtraFields.combo.ListboxMulti.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.combo.ListboxMulti, Ext.ux.form.SuperBoxSelect);
Ext.reg('ef-combo-listbox-multiple', ExtraFields.combo.ListboxMulti);

ExtraFields.combo.GetList = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        url: ExtraFields.config.connectorUrl,
        hiddenName: config.name,
        fields: ['id', 'name'],
        displayField: 'name',
        valueField: 'id',
        editable: true,
        emptyText: _('ef_combo_empty'),
        mode: 'remote',
        anchor: '100%',
        width: 100,
        pageSize: 10,
        allowBlank: 1,
        autoLoad: 1,
    });

    ExtraFields.combo.GetList.superclass.constructor.call(this, config);

    setTimeout(() => {
        config.store.load({'params': {'limit': config.pageSize }});
    },1000);
};
Ext.extend(ExtraFields.combo.GetList, MODx.combo.ComboBox);
Ext.reg('ef-combo-getlist',ExtraFields.combo.GetList);

ExtraFields.combo.GetListMulti = function (config) {
    config = config || {};

    let store = new Ext.data.JsonStore({
        fields: config.fields || ['name', 'id'],
        autoLoad: true,
        autoDestroy: true,
        root: 'results',
        url: config.url || ExtraFields.config['connector_url'],
        baseParams: config.baseParams || {},
    });

    Ext.applyIf(config, {
        xtype:'superboxselect',
        store: store,
        displayField: 'name',
        valueField: 'id',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        renderTo: Ext.getBody(),
        // emptyText: _('ef_combo_empty'),
        mode: 'remote',
        pageSize: 10,
    });
    config.name += '[]';
    config.hiddenName = config.name;

    ExtraFields.combo.GetListMulti.superclass.constructor.call(this, config);

    this.on('afterrender', function() {
        store.load({'params': {'limit': 10}});
    });
};
Ext.extend(ExtraFields.combo.GetListMulti, Ext.ux.form.SuperBoxSelect);
Ext.reg('ef-combo-getlist-multiple', ExtraFields.combo.GetListMulti);