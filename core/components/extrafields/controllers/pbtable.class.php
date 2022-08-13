<?php

/**
 * The PbTable manager controller for ExtraFields.
 */
class ExtraFieldsPbTableManagerController extends modExtraManagerController
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
        $this->addJavascript($jsUrl . 'misc/default.grid.js');
        $this->addJavascript($jsUrl . 'misc/default.window.js');

        // Field
        $this->addJavascript($jsUrl . 'widgets/pageblocks/grid.js');
        $this->addJavascript($jsUrl . 'widgets/pageblocks/windows.js');

        $this->addJavascript($jsUrl . 'panel/pageblocks.js');
        $this->addJavascript($jsUrl . 'page/pageblocks.js');

        $config = $this->extrafields->config;
        $config['class_name'] = 'pbTableValue';

        if ($config['pageblocks']) {
            $this->addHtml('<script>
                Ext.onReady(() => {
                    ExtraFields.config = ' . json_encode($config) . ';
                    MODx.load({ xtype: "ef-page-pageblocks"});
                });
            </script>');
        }

    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="ef-panel-pageblocks-div"></div>';

        return '';
    }
}