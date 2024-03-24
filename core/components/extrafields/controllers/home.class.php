<?php

/**
 * The home manager controller for ExtraFields.
 */
class ExtraFieldsHomeManagerController extends modExtraManagerController
{
    /** @var ExtraFields $extrafields */
    public $extrafields;

    public function initialize()
    {
        if ($this->modx->services instanceof Psr\Http\Client\ClientInterface) {
            $this->extrafields = $this->modx->services->get('extrafields');
        } else {
            $this->extrafields = $this->modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
        }
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['extrafields:default', 'core:tv_input_types', 'core:tv_widget'];
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
        $this->addJavascript($jsUrl . 'misc/default.grid.js');
        $this->addJavascript($jsUrl . 'misc/default.window.js');

        // Tab
        $this->addJavascript($jsUrl . 'widgets/tab/grid.js');
        $this->addJavascript($jsUrl . 'widgets/tab/windows.js');

        // Category
        $this->addJavascript($jsUrl . 'widgets/category/grid.js');
        $this->addJavascript($jsUrl . 'widgets/category/windows.js');

        // Field
        $this->addJavascript($jsUrl . 'widgets/field/grid.js');
        $this->addJavascript($jsUrl . 'widgets/field/windows.js');

        // Abs
        $this->addJavascript($jsUrl . 'widgets/abs/grid.js');
        $this->addJavascript($jsUrl . 'widgets/abs/windows.js');

        $this->addJavascript($jsUrl . 'panel/home.js');
        $this->addJavascript($jsUrl . 'page/home.js');

        $config = $this->extrafields->config;

        $this->addHtml('<script>
            Ext.onReady(() => {
                ExtraFields.config = ' . json_encode($config) . ';
                MODx.load({ xtype: "ef-page-home"});
            });
        </script>');

        $this->extrafields->loadRichTextEditor();
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="ef-panel-home-div"></div>';

        return '';
    }
}