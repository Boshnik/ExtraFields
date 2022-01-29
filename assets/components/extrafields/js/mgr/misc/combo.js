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

ExtraFields.combo.Fields = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        displayField: 'name',
        valueField: 'id',
        editable: true,
        fields: ['id', 'name'],
        pageSize: 10,
        emptyText: _('empty'),
        mode: 'remote',
        url: ExtraFields.config.connectorUrl,
        baseParams: {
            action: 'mgr/field/getlist',
            sort: 'rank',
            dir: 'asc',
            combo: 1,
        }
    });
    ExtraFields.combo.Fields.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.combo.Fields,MODx.combo.ComboBox);
Ext.reg('extrafields-combo-fields',ExtraFields.combo.Fields);