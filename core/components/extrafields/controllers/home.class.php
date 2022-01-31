<?php


/**
 * The home manager controller for ExtraFields.
 *
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

        // Resource field
        $this->addJavascript($jsUrl . 'widgets/resource/field/grid.js');
        $this->addJavascript($jsUrl . 'widgets/resource/field/windows.js');

        // User
        $this->addJavascript($jsUrl . 'widgets/user/grid.js');
        $this->addJavascript($jsUrl . 'widgets/user/windows.js');

        $this->addJavascript($jsUrl . 'panel/home.js');
        $this->addJavascript($jsUrl . 'page/home.js');

        $this->addHtml('<script>
            Ext.onReady(() => {
                ExtraFields.config = ' . json_encode($this->extrafields->config) . ';
                MODx.load({ xtype: "extrafields-page-home"});
            });
        </script>');

        $this->extrafields->loadRichTextEditor();
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="extrafields-panel-home-div"></div>';

        return '';
    }
}