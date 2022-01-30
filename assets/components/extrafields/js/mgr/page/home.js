ExtraFields.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'extrafields-panel-home',
            renderTo: 'extrafields-panel-home-div'
        }],
        buttons: [{
            xtype: 'boshnik',
            text: '<img src="'+ExtraFields.config.assetsUrl+'img/boshnik.jpg" width="50" height="50"/> BOSHNIK',
        }, {
            xtype: 'button',
            text: '<i class="icon icon-cog"></i> ' + _('extrafields_btn_admin'),
            handler: function () {
                MODx.loadPage('admin', 'namespace=extrafields');
            }
        }]
    });
    ExtraFields.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.page.Home, MODx.Component);
Ext.reg('extrafields-page-home', ExtraFields.page.Home);