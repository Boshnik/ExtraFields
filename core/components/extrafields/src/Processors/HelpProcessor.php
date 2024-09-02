<?php

namespace Boshnik\ExtraFields\Processors;

trait HelpProcessor
{

    public $fieldmeta = [
        'textfield' => [
            'dbtype' => 'varchar',
            'precision' => 255,
            'phptype' => 'string',
        ],
        'textarea' => [
            'dbtype' => 'text',
            'phptype' => 'string',
        ],
        'pb-panel-video' => [
            'dbtype' => 'text',
            'phptype' => 'string',
        ],
        'richtext' => [
            'dbtype' => 'mediumtext',
            'phptype' => 'string',
        ],
        'pb-gallery' => [
            'dbtype' => 'mediumtext',
            'phptype' => 'string',
        ],
        'modx-texteditor' => [
            'dbtype' => 'mediumtext',
            'phptype' => 'string',
        ],
        'listbox-multiple' => [
            'dbtype' => 'text',
            'phptype' => 'string',
        ],
        'numberfield' => [
            'dbtype' => 'int',
            'precision' => '1',
            'phptype' => 'integer',
            'default' => 0,
        ],
        'price' => [
            'dbtype' => 'decimal',
            'precision' => '12,2',
            'phptype' => 'float',
            'default' => 0,
        ],
        'xcheckbox' => [
            'dbtype' => 'tinyint',
            'precision' => 1,
            'attributes' => 'unsigned',
            'phptype' => 'integer',
            'default' => 0,
        ],
        'combo-boolean' => [
            'dbtype' => 'tinyint',
            'precision' => 1,
            'attributes' => 'unsigned',
            'phptype' => 'boolean',
            'null' => false,
            'default' => 0,
        ],
        'xdatetime' => [
            'dbtype' => 'int',
            'precision' => 20,
            'phptype' => 'timestamp',
        ],
    ];


    /**
     * Create a column in a table
     * @param $object
     * @param $className
     */
    public function createTableColumn($object)
    {
        if ($sql = $this->getSQL($object)) {
            $this->modx->exec($sql);
        }
    }


    /**
     * Update a column in a table
     * @param $object
     */
    public function updateTableColumn($object)
    {
        if ($sql = $this->getSQL($object, 'update')) {
            $this->modx->exec($sql);
        }
    }


    /**
     * Remove a column in a table
     * @param $object
     */
    public function removeTableColumn($object)
    {
        if ($sql = $this->getSQL($object, 'remove')) {
            $this->modx->exec($sql);
        }
    }

    /**
     * @param $object
     * @param string $mode
     * @return false|string
     */
    public function getSQL($object, $mode = 'create')
    {
        $table = $this->modx->getTableName($object->class_name);
        $name = $object->field_name;

        $meta = $this->fieldmeta[$object->field_type] ?? $this->fieldmeta['textfield'];

        $type = $meta['dbtype'];
        if (isset($meta['precision'])) {
            $type .= "({$meta['precision']})";
        }
        $null = $object->field_null ? 'NULL' : 'NOT NULL';
        $default = (empty($object->field_default) && $object->field_default !== '0') ? "" : " DEFAULT '$object->field_default'";

        if (!empty($object->old_name) && $object->field_name !== $object->old_name) {
            $mode = 'rename';
        }

        switch ($mode) {
            case 'create':
                $sql = "ALTER TABLE {$table} ADD `{$name}` {$type} {$null}{$default};";
                break;
            case 'update':
                $sql = "ALTER TABLE {$table} MODIFY COLUMN `{$name}` {$type} {$null}{$default};";
                break;
            case 'rename':
                $sql = "ALTER TABLE {$table} CHANGE COLUMN `{$object->old_name}` `{$name}` {$type} {$null}{$default};";
                break;
            case 'remove':
                $sql = "ALTER TABLE {$table} DROP COLUMN`{$name}`;";
                break;
        }

        return $sql;
    }


    /**
     * @return array
     */
    public function getMediaSources()
    {
        $all = [];
        $sources = $this->modx->getCollection('sources.modMediaSource');
        foreach ($sources as $source) {
            $all[$source->id] = $source->getPropertyList();
        }
        return $all;
    }


    /**
     * Check field name
     * @param $name
     */
    public function validationField($name, $class_name = 'modResource')
    {
        $tables = [$class_name];
        if ($class_name == 'modUserProfile') {
            $tables = [\modUser::class, \modUserProfile::class];
        }

        if (!preg_match("/^[\w\d\_]*$/", $name)) {
            $this->modx->error->addField('name', $this->modx->lexicon("ef_field_err_name_cyrillic"));
        }

        $columns = [];
        foreach ($tables as $table) {
            $q = $this->modx->prepare("DESCRIBE " . $this->modx->getTableName($table));
            $q->execute();
            $columns += $q->fetchAll(\PDO::FETCH_COLUMN);
        }

        if (in_array($name, $columns)) {
            $this->modx->error->addField('name', $this->modx->lexicon("ef_field_err_name_reserved"));
        }
    }


    /**
     * @param $packageName
     * @return mixed
     */
    public function getPackage($packageName)
    {
        $namespace = strtolower($packageName);
        $model = '/model/';
        if ($packageName == 'ClientConfig') {
            $model = '/model/clientconfig/';
        }
        $file = MODX_CORE_PATH . "components/{$namespace}{$model}{$namespace}.class.php";
        if (!file_exists($file)) {
            return false;
        }

        if ($this->modx->services instanceof Psr\Http\Client\ClientInterface) {
            $package = $this->modx->services->get($namespace);
        } else {
            $package = $this->modx->getService($namespace, $packageName, MODX_CORE_PATH . "components/{$namespace}{$model}");
        }
        return $package;
    }


    public function validFieldType($properties)
    {
        [
            'field_type' => $field_type,
            'field_default' => $field_default
        ] = $properties;
        if (in_array($field_type, ['xcheckbox', 'combo-boolean'])) {
            if (!in_array($field_default, ['0', '1'])) {
                $this->modx->error->addField('field_default', $this->modx->lexicon('ef_field_default_error_combo'));
            }
        }

        if (in_array($field_type, ['numberfield'])) {
            if (!is_numeric($field_default)) {
                $this->modx->error->addField('field_default', $this->modx->lexicon('ef_field_default_error_numberfield'));
            }
        }

        if (in_array($field_type, ['xdatetime']) && !empty($field_default)) {
            if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $field_default)) {
                $this->modx->error->addField('field_default', $this->modx->lexicon('ef_field_default_error_date'));
            }
        }

    }
}