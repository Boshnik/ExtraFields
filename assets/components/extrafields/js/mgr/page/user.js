ExtraFields.page.User = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'ef-panel-user',
            renderTo: 'ef-panel-user-div'
        }],
        buttons: [{
            xtype: 'boshnik',
            text: '<img src="'+ExtraFields.config.assetsUrl+'img/boshnik.jpg" width="50" height="50"/> BOSHNIK',
        }]
    });
    ExtraFields.page.User.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.page.User, MODx.Component);
Ext.reg('ef-page-user', ExtraFields.page.User);