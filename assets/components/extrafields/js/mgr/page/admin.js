ExtraFields.page.Admin = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'extrafields-panel-admin',
            renderTo: 'extrafields-panel-admin-div'
        }],
        buttons: [{
            xtype: 'boshnik',
            text: '<img src="'+ExtraFields.config.assetsUrl+'img/boshnik.jpg" width="50" height="50"/> BOSHNIK',
        }, {
            xtype: 'button',
            text: '<i class="icon icon-home"></i> ' + _('extrafields_btn_home'),
            handler: function () {
                MODx.loadPage('home', 'namespace=extrafields');
            }
        }]
    });
    ExtraFields.page.Admin.superclass.constructor.call(this, config);
};
Ext.extend(ExtraFields.page.Admin, MODx.Component);
Ext.reg('extrafields-page-admin', ExtraFields.page.Admin);