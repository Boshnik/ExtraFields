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
Ext.reg('extrafields-combo-search', ExtraFields.combo.Search);
Ext.reg('extrafields-field-search', ExtraFields.combo.Search);

ExtraFields.combo.FieldPositions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            id: 'value',
            fields: ['display','value'],
            data: [
                [_('extrauser_field_position_left'),0],
                [_('extrauser_field_position_right'),1],
            ]
        }),
        mode: 'local',
        displayField: 'display',
        valueField: 'value',
    });
    ExtraFields.combo.FieldPositions.superclass.constructor.call(this,config);
};
Ext.extend(ExtraFields.combo.FieldPositions, MODx.combo.ComboBox);
Ext.reg('extrafields-combo-positions', ExtraFields.combo.FieldPositions);

ExtraFields.combo.GetList = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        hiddenName: config.name,
        displayField: 'name',
        valueField: 'id',
        editable: true,
        fields: ['id', 'name'],
        pageSize: 10,
        emptyText: _('empty'),
        mode: 'remote',
        url: ExtraFields.config.connectorUrl,
    });
    ExtraFields.combo.GetList.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.combo.GetList,MODx.combo.ComboBox);
Ext.reg('extrafields-combo-getlist',ExtraFields.combo.GetList);

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
                Ext.Msg.buttonText.ok = 'DONATE';
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