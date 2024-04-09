<?php

namespace Boshnik\ExtraFields;

use modX;
use modResource;
use modUser;
use modUserProfile;
use efTab;
use efCategory;
use efField;
use efFieldAbs;
use pbTableValue;
use pbTableColumn;
use pbField;

use Boshnik\ExtraFields\Processors\HelpProcessor;
use Boshnik\ExtraFields\Processors\QueryProcessor;

/**
 * class ExtraFields
 */
class ExtraFields
{
    use HelpProcessor;
    use QueryProcessor;

    /** @var modX $modx */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'extrafields';

    /**
     * The package name
     * @var string $packageName
     */
    public $packageName = 'ExtraFields';

    /**
     * The version
     * @var string $version
     */
    public $version = '2.0.1';

    /**
     * The class config
     * @var array $config
     */
    public $config = [];


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath = MODX_CORE_PATH . 'components/extrafields/';
        $assetsUrl = MODX_ASSETS_URL . 'components/extrafields/';

        $modxversion = $this->modx->getVersionData();

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
                'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',

            'namespace' => $this->namespace,
            'version' => $this->version,
            'modxversion' => $modxversion['version'],
            'is_admin' => $this->modx->user->isMember('Administrator'),
        ], $config);

        if ($pageblocks = $this->getPackage('PageBlocks')) {
            $this->config['pageblocks'] = $pageblocks->config;
        }

        $this->modx->addPackage($this->namespace, $this->config['modelPath']);
        $this->modx->lexicon->load("$this->namespace:default");
    }

    /**
     * @param string $action
     * @param array $data
     * @return false
     */
    public function runProcessor($action = '', $data = [])
    {
        if (empty($action)) {
            return false;
        }
        $this->modx->error->reset();
        $processorsPath = !empty($this->config['processorsPath'])
            ? $this->config['processorsPath']
            : MODX_CORE_PATH . 'components/extrafields/processors/';

        return $this->modx->runProcessor($action, $data, [
            'processors_path' => $processorsPath,
        ]);
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
            $this->modx->controller->addHtml($onRichTextEditorInit);
        }
    }

}