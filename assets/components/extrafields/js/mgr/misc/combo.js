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

    if (config.length != 0 && config.openTo != undefined) {
        if (!/^\//.test(config.openTo)) {
            config.openTo = '/' + config.openTo;
        }
        if (!/$\//.test(config.openTo)) {
            var tmp = config.openTo.split('/');
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

        var browser = MODx.load({
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
        if (n.id == '/') {
            return '';
        }

        return n.attributes.path + '/';
    }
});
Ext.reg('ef-combo-browser', ExtraFields.combo.Browser);

ExtraFields.combo.Types = function(config) {
    config = config || {};

    var data = [
        [_('textfield'),'textfield'],
        [_('textarea'),'textarea'],
        [_('listbox'),'listbox'],
        [_('listbox-multiple'),'listbox-multiple'],
        [_('resourcelist'),'resourcelist'],
        [_('list') + ' ' + _('yesno'),'combo-boolean'],
        [_('numberfield'),'numberfield'],
        [_('xcheckbox'),'xcheckbox'],
        [_('checkbox'),'checkboxgroup'],
        [_('option'),'radiogroup'],
        [_('image'),'image'],
        [_('file'),'file'],
        [_('date'),'xdatetime'],
        [_('readonly'),'readonly'],
        [_('hidden'),'hidden'],
        [_('efxtype'),'efxtype']
    ];

    if (MODx.loadRTE) data.splice(2,0,[_('richtext'),'richtext']);

    var ace = (typeof(MODx.ux) != 'undefined' && typeof(MODx.ux.Ace) == 'function') ? 1 : 0;
    if (ace) data.splice(2,0,[_('ace'), 'modx-texteditor']);

    if (typeof ColorPicker == 'object') {
        data.splice(-4,0,['ColorPicker','colorpicker']);
    }

    if (ExtraFields.config.pageblocks) {
        data.splice(-4,0,[_('pb-video-gallery'),'pb-video-gallery']);
        data.splice(-4,0,[_('pb-gallery'),'pb-gallery']);
        data.splice(-4,0,[_('pb-table'),'pb-table']);
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

ExtraFields.combo.FieldMeta = function(config) {
    config = config || {};

    var data = [
        ['varchar (string)','textfield'],
        ['text (string)','textarea'],
        ['mediumtext (string)','richtext'],
        ['int (integer)','numberfield'],
        ['int (timestamp)','xdatetime'],
        ['tinyint (integer)','xcheckbox'],
        ['tinyint (boolean)','combo-boolean'],
    ];

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
    ExtraFields.combo.FieldMeta.superclass.constructor.call(this,config);
};
Ext.extend(ExtraFields.combo.FieldMeta, MODx.combo.ComboBox);
Ext.reg('ef-combo-field-meta',ExtraFields.combo.FieldMeta);

ExtraFields.combo.Areas = function(config) {
    config = config || {};
    if (Ext.isEmpty(config.id)) {
        config.id = Ext.id();
    }
    let data = [];

    switch (ExtraFields.config.class_name) {
        case 'modResource':
            data = [
                [_('modx-resource-settings'),'modx-resource-settings'],
                [_('modx-resource-main-left'),'modx-resource-main-left'],
                [_('modx-resource-main-right'),'modx-resource-main-right'],

                [_('modx-page-settings'),'modx-page-settings'],
                [_('modx-page-settings-left'),'modx-page-settings-left'],
                [_('modx-page-settings-right'),'modx-page-settings-right'],
                [_('modx-page-settings-right-box-left'),'modx-page-settings-right-box-left'],
                [_('modx-page-settings-right-box-right'),'modx-page-settings-right-box-right'],

                [_('modx-resource-access-permissions'),'modx-resource-access-permissions'],
            ];
            break;

        case 'modUserProfile':
            data = [
                [_('user_tab_0'), '0'],
                [_('user_tab_0_1'), '0_0'],
                [_('user_tab_0_2'), '0_1'],
                [_('user_tab_1'), '1'],
                [_('user_tab_2'), '2'],
                [_('user_tab_3'), '3'],
            ];
            break;
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
        hiddenName: config.name,
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
                Ext.getCmp(config.id).setValue();
            } else {
                MODx.combo.ComboBox.superclass.onTriggerClick.call(this);
            }
        },
    });
    ExtraFields.combo.Areas.superclass.constructor.call(this,config);
};
Ext.extend(ExtraFields.combo.Areas, MODx.combo.ComboBox);
Ext.reg('ef-combo-areas',ExtraFields.combo.Areas);

ExtraFields.combo.Listbox = function(config) {

    var store = [];
    if (config.all) {
        store.push([
            _('all'), ''
        ])
    }
    if (!Ext.isEmpty(config.values)) {
        var all = Object.values(config.values);
        all.forEach(function (value){
            store.push([
                value['name'],
                value['id']
            ]);
        })
    } else if (!Ext.isEmpty(config.data)) {
        var all = config.data.split('||');
        all.forEach(function (value) {
            var val = value.split('==');
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

    var store = [];
    if (!Ext.isEmpty(config.data)) {
        var all = config.data.split('||');
        all.forEach(function (value) {
            var val = value.split('==');
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
        if (!config.hidden) {
            config.store.load();
        }
    }, 1000);
};
Ext.extend(ExtraFields.combo.GetList, MODx.combo.ComboBox);
Ext.reg('ef-combo-getlist',ExtraFields.combo.GetList);

ExtraFields.combo.GetListMulti = function (config) {
    config = config || {};

    let store = new Ext.data.JsonStore({
        fields: config.fields || ['name', 'id'],
        autoLoad: true,
        autoDestroy: false,
        root: 'results',
        url: config.url || PageBlocks.config['connector_url'],
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

    setTimeout(() => {
        store.load({'params': {'limit': 10}});
    },1000);
};
Ext.extend(ExtraFields.combo.GetListMulti, Ext.ux.form.SuperBoxSelect);
Ext.reg('ef-combo-getlist-multiple', ExtraFields.combo.GetListMulti);

ExtraFields.form.Boshnik = Ext.extend(Ext.Toolbar.TextItem, {
    baseCls: 'boshnik',
    listeners: {
        afterrender: function () {
            this.getEl().addClass(this.baseCls);
            var msg = '<div class="wrapper"><div class="col">';
            msg += '<img src="'+ExtraFields.config.assetsUrl+'img/boshnik.jpg" alt="Boshnik" width="60" height="60"/>'
            msg += '</div><div class="col">';
            msg += '<p><b>Email:</b> Superboshnik@ya.ru</p>';
            msg += '<p><b>Telegram:</b> <a href="https://t.me/Boshnik" target="_blank">@Boshnik</a></p>';
            msg += '<p><b>GitHub:</b> <a href="https://github.com/Boshnik" target="_blank">Boshnik</a></p>';
            msg += '</div>';
            msg += '<br><p class="col-12">&copy; '+ new Date().getFullYear() +' <a href="https://boshnik.com" target="_blank">boshnik.com</a></p>'
            msg += '</div>';

            this.el.on('click', () => {
                var btnOKText = Ext.Msg.buttonText.ok;
                Ext.Msg.buttonText.ok = '<i class="icon icon-coffee"></i> buy me a coffee';
                Ext.Msg.show({
                    title: _('extrafields') + ' ' + ExtraFields.config.version,
                    msg: msg,
                    buttons: Ext.Msg.OKCANCEL,
                    fn: function(btn) {
                        if (btn == 'ok') {
                            window.open('https://yoomoney.ru/to/410011655323883', '_blank');
                        }
                    },
                    cls: 'boshnik-window',
                    width: 300
                });
                Ext.Msg.buttonText.ok = btnOKText;
            })
        }
    },
});
Ext.reg("boshnik", ExtraFields.form.Boshnik);