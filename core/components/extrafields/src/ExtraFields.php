<?php

namespace Boshnik\ExtraFields;

use modX;
use PDO;
use modResource;
use modUserProfile;
use ExtraMetaField;
use ExtraUserField;
use ExtraResourceTab;
use ExtraResourceField;

/**
 * class ExtraFields
 */
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
    public $version = '1.1.0';

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
     * Проверяем название поля
     * @param $name
     */
    public function validationField($name, $type = 'resource')
    {
        $tables = [modResource::class];
        if ($type == 'user') {
            $tables = [modUser::class, modUserProfile::class];
        }

        if (!preg_match("/^[\w\d\s.,-]*$/", $name)) {
            $this->modx->error->addField('name', $this->modx->lexicon("extra{$type}_field_err_name_cyrillic"));
        }

        $columns = [];
        foreach ($tables as $table) {
            $q = $this->modx->prepare("DESCRIBE " . $this->modx->getTableName($table));
            $q->execute();
            $columns += $q->fetchAll(PDO::FETCH_COLUMN);
        }

        if (in_array($name, $columns)) {
            $this->modx->error->addField('name', $this->modx->lexicon("extra{$type}_field_err_name_reserved"));
        }
    }


    /**
     * Get table fields
     * @return array
     */
    public function getTableFields($className)
    {
        $fields = [];
        if (empty($className)) return $fields;

        $rows = $this->modx->getIterator($className, ['active' => 1]);
        foreach ($rows as $row) {
            $field = $row->getOne('Field');
            $fields[] = array_merge($field->toArray(), $row->toArray(),[
                'xtype' => $field->get('name')
            ]);
        }

        return $fields;
    }


    /**
     * Getting fields from ExtraResourceTab table
     * @return array
     */
    public function getResourceTabs()
    {
        $resourcetabs = [];
        $rows = $this->modx->getIterator(ExtraResourceTab::class, ['active' => 1]);
        foreach ($rows as $row) {
            $resourcetabs[] = $row->toArray();
        }

        return $resourcetabs;
    }


    /**
     * Create a column in a table
     * @param $object
     * @param $className
     */
    public function createTableColumn($object, $className)
    {
        if ($sql = $this->getSQL($object, $className)) {
            $this->modx->exec($sql);
        }
    }


    /**
     * Update a column in a table
     * @param $object
     * @param $className
     */
    public function updateTableColumn($object, $className)
    {
        if ($sql = $this->getSQL($object, $className, 'update')) {
            $this->modx->log(1, 'SQL:' . $sql);
            $this->modx->exec($sql);
        }
    }


    /**
     * Remove a column in a table
     * @param $object
     * @param $className
     */
    public function removeTableColumn($object, $className)
    {
        if ($sql = $this->getSQL($object, $className, 'remove')) {
            $this->modx->exec($sql);
        }
    }


    /**
     * @param $object
     * @param $className
     * @param string $mode
     * @return false|string
     */
    public function getSQL($object, $className, $mode = 'create')
    {
        $table = $this->modx->getTableName($className);
        $name = $object->get('name');

        if (!$field = $object->getOne('Field')) {
            return false;
        }

        $type = $field->get('dbtype');
        if ($field->get('precision')) {
            $type .= "({$field->get('precision')})";
        }
        $null = $field->get('null') ? 'NULL' : 'NOT NULL';
        $default = empty($field->get('default')) ? "" : " DEFAULT $field->get('default')";

        switch ($mode) {
            case 'create':
                $sql = "ALTER TABLE {$table} ADD `{$name}` {$type} {$null}{$default};";
                break;
            case 'update':
                $sql = "ALTER TABLE {$table} MODIFY COLUMN `{$name}` {$type} {$null}{$default};";
                break;
            case 'remove':
                $sql = "ALTER TABLE {$table} DROP COLUMN `{$name}`;";
                break;
        }

        return $sql;
    }


}