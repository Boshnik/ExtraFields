ExtraFields.window.Default = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        title: '',
        url: ExtraFields.config.connectorUrl,
        cls: 'modx-window ef-window',
        width: 800,
        autoHeight: true,
        maxHeight: 600,
        autoScroll: true,
        fields: this.getFields(config),
        keys: this.getKeys(config),
        buttons: this.getButtons(config),
        listeners: this.getListeners(config),
    });
    ExtraFields.window.Default.superclass.constructor.call(this, config);

    this.on('afterrender', function() {
        if (this.getHeight() > this.maxHeight) {
            this.setHeight(this.maxHeight);
        }
        this.center();
    }, this);

    this.on('hide', function() {
        var w = this;
        window.setTimeout(() => {
            w.close();
        }, 200);
    });
};
Ext.extend(ExtraFields.window.Default, MODx.Window, {

    getFields: function() {
        return [];
    },

    getButtons: function(config) {
        return [{
            text: config.cancelBtnText || _('cancel'),
            scope: this,
            handler: function() {
                config.closeAction !== 'close'
                    ? this.hide()
                    : this.close();
            }
        }, {
            text: config.saveBtnText || _('save'),
            cls: 'primary-button',
            scope: this,
            handler: this.submit,
        }];
    },

    getKeys: function() {
        return [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: function() {
                this.submit();
            }, scope: this
        }];
    },

    getListeners: function() {
        return {};
    },

});
Ext.reg('ef-window-default', ExtraFields.window.Default);