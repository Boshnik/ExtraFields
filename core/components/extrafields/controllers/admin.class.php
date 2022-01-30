<?php

/**
 * The admin manager controller for ExtraFields.
 *
 */
class ExtraFieldsAdminManagerController extends modExtraManagerController
{
    /** @var ExtraFields $extrafields */
    public $extrafields;

    public function initialize()
    {
        $this->extrafields = $this->modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['extrafields:default'];
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('extrafields');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $cssUrl = $this->extrafields->config['cssUrl'] . 'mgr/';
        $jsUrl = $this->extrafields->config['jsUrl'] . 'mgr/';

        $this->addCss($cssUrl . 'main.css');

        $this->addJavascript($jsUrl . 'extrafields.js');
        $this->addJavascript($jsUrl . 'misc/utils.js');
        $this->addJavascript($jsUrl . 'misc/combo.js');

        // Resource tab
        $this->addJavascript($jsUrl . 'widgets/resource/tab/grid.js');
        $this->addJavascript($jsUrl . 'widgets/resource/tab/windows.js');

        // Field
        $this->addJavascript($jsUrl . 'widgets/field/grid.js');
        $this->addJavascript($jsUrl . 'widgets/field/windows.js');

        $this->addJavascript($jsUrl . 'panel/admin.js');
        $this->addJavascript($jsUrl . 'page/admin.js');

        $this->addHtml('<script>
            Ext.onReady(() => {
                ExtraFields.config = ' . json_encode($this->extrafields->config) . ';
                MODx.load({ xtype: "extrafields-page-admin"});
            });
        </script>');

        $this->extrafields->loadRichTextEditor();
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="extrafields-panel-admin-div"></div>';

        return '';
    }
}