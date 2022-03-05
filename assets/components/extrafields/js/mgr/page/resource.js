ExtraFields.page.Resource = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'ef-panel-resource',
            renderTo: 'ef-panel-resource-div'
        }],
        buttons: [{
            xtype: 'boshnik',
            text: '<img src="'+ExtraFields.config.assetsUrl+'img/boshnik.jpg" width="50" height="50"/> BOSHNIK',
        }]
    });
    ExtraFields.page.Resource.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.page.Resource, MODx.Component);
Ext.reg('ef-page-resource', ExtraFields.page.Resource);