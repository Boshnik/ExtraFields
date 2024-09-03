ExtraFields.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'ef-panel-home',
            renderTo: 'ef-panel-home-div'
        }],
        buttons: [{
            xtype: 'button',
            text: '<i class="icon icon-large icon-book"></i> ' + _('ef_docs'),
            handler: () => {
                window.open('https://extrafields.boshnik.com/')
            }
        }]
    });
    ExtraFields.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.page.Home, MODx.Component);
Ext.reg('ef-page-home', ExtraFields.page.Home);