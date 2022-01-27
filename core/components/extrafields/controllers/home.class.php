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

        // Item
        $this->addJavascript($jsUrl . 'widgets/field/grid.js');
        $this->addJavascript($jsUrl . 'widgets/field/windows.js');

        $this->addJavascript($jsUrl . 'panel/home.js');
        $this->addJavascript($jsUrl . 'page/home.js');

        $this->addHtml('<script>
            Ext.onReady(() => {
                ExtraFields.config = ' . json_encode($this->extrafields->config) . ';
                MODx.load({ xtype: "extrafields-page-home"});
            });
        </script>');

        $this->loadRichTextEditor();
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="extrafields-panel-home-div"></div>';

        return '';
    }


    /**
     * Load rich text editor.
     */
    public function loadRichTextEditor()
    {
        $useEditor = $this->modx->getOption('use_editor');
        $whichEditor = $this->modx->getOption('which_editor');
        if ($useEditor && !empty($whichEditor)) {
            $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit', [
                'editor' => $whichEditor
            ]);
            if (is_array($onRichTextEditorInit)) {
                $onRichTextEditorInit = implode('', $onRichTextEditorInit);
            }
            $this->addHtml($onRichTextEditorInit);
        }
    }
}