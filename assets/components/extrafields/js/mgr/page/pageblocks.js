ExtraFields.page.PageBlocks = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'ef-panel-pageblocks',
            renderTo: 'ef-panel-pageblocks-div'
        }],
        buttons: [{
            xtype: 'boshnik',
            text: '<img src="'+ExtraFields.config.assetsUrl+'img/boshnik.jpg" width="50" height="50"/> BOSHNIK',
        }]
    });
    ExtraFields.page.PageBlocks.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.page.PageBlocks, MODx.Component);
Ext.reg('ef-page-pageblocks', ExtraFields.page.PageBlocks);