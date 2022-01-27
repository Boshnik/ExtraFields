ExtraFields.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'extrafields-panel-home',
            renderTo: 'extrafields-panel-home-div'
        }]
    });
    ExtraFields.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.page.Home, MODx.Component);
Ext.reg('extrafields-page-home', ExtraFields.page.Home);