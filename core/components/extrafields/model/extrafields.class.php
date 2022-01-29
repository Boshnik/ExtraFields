<?php

class ExtraFields
{
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
    public $version = '1.0.0';

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
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',

            'modxversion' => $modxversion['version'],
            'is_admin' => $this->modx->user->isMember('Administrator'),
        ], $config);

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

        return $this->modx->runProcessor($action, $data, array(
            'processors_path' => $processorsPath,
        ));
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


    /**
     * Проверяем название поля для пользователя
     * @param $name
     */
    public function validationUserField($name)
    {
        if (!preg_match("/^[a-z]/", $name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('extrauser_field_err_name_cyrillic'));
        }

        $columns = [];
        $tables = [modUser::class, modUserProfile::class];
        foreach ($tables as $table) {
            $q = $this->modx->prepare("DESCRIBE " . $this->modx->getTableName($table));
            $q->execute();
            $columns += $q->fetchAll(PDO::FETCH_COLUMN);
        }

        if (in_array($name, $columns)) {
            $this->modx->error->addField('name', $this->modx->lexicon('extrauser_field_err_name_reserved'));
        }
    }

    /**
     * Получаем поля с таблиц ExtraMetaField и ExtraUserField
     * @return array
     */
    public function getUserColumns()
    {
        $usercolumns = [];
        $userFields = $this->modx->getIterator(ExtraUserField::class, ['active' => 1]);
        foreach ($userFields as $userfield) {
            $field = $userfield->getOne('Field');
            $usercolumns[] = array_merge($field->toArray(), $userfield->toArray(),[
                'xtype' => $field->get('name')
            ]);
        }

        return $usercolumns;
    }




}