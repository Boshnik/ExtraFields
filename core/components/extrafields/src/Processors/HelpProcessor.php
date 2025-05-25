<?php

namespace Boshnik\ExtraFields\Processors;

trait HelpProcessor
{
    public array $fieldmeta = [
        'textfield' => [
            'dbtype' => 'varchar',
            'precision' => 250,
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
        'listbox-int' => [
            'dbtype' => 'int',
            'precision' => 16,
            'phptype' => 'integer',
            'default' => 0,
        ],
        'listbox-multiple' => [
            'dbtype' => 'text',
            'phptype' => 'string',
        ],
        'enumfield' => [
            'dbtype' => 'enum',
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
    public function createTableColumn($object): void
    {
        if ($sql = $this->getSQL($object)) {
            $this->modx->exec($sql);
        }
    }


    /**
     * Update a column in a table
     * @param $object
     */
    public function updateTableColumn($object): void
    {
        if ($sql = $this->getSQL($object, 'update')) {
            $this->modx->exec($sql);
        }
    }


    /**
     * Remove a column in a table
     * @param $object
     */
    public function removeTableColumn($object): void
    {
        if ($sql = $this->getSQL($object, 'remove')) {
            $this->modx->exec($sql);
        }
    }

    public function updateIndex($object): void
    {
        $sql = $this->getSQL($object, 'check_index');
        $result = $this->modx->query($sql);

        if ($result && $result->fetch(\PDO::FETCH_ASSOC)) {
            if (!$object->field_index) {
                $sql = $this->getSQL($object, 'remove_index');
                $this->modx->exec($sql);
            }
        } else {
            if ($object->field_index) {
                $sql = $this->getSQL($object, 'add_index');
                $this->modx->exec($sql);
            }
        }
    }

    public function removeIndex($object): void
    {
        $sql = $this->getSQL($object, 'check_index');
        $result = $this->modx->query($sql);
        if ($result && $result->fetch(\PDO::FETCH_ASSOC)) {
            $sql = $this->getSQL($object, 'remove_index');
            $this->modx->exec($sql);
        }
    }

    /**
     * @param $object
     * @param string $mode
     * @return string
     */
    public function getSQL($object, $mode = 'create'): string
    {
        $table = $this->modx->getTableName($object->class_name);
        $name = $object->field_name;

        $meta = $this->fieldmeta[$object->field_type] ?? $this->fieldmeta['textfield'];

        $type = $meta['dbtype'];
        if (isset($meta['precision'])) {
            $type .= "({$meta['precision']})";
        }
        if ($object->field_type === 'enumfield') {
            $precision = explode(',', $object->precision);
            $enumValues = "'" . implode("', '", $precision) . "'";
            $type = "ENUM($enumValues)";
        }

        $null = $object->field_null ? 'NULL' : 'NOT NULL';
        $default = (empty($object->field_default) && $object->field_default !== '0') ? "" : "DEFAULT '$object->field_default'";

        if (!empty($object->old_name) && $object->field_name !== $object->old_name) {
            $mode = 'rename';
        }

        switch ($mode) {
            case 'create':
                $sql = "ALTER TABLE $table ADD `$name` $type $null $default;";
                break;
            case 'update':
                $sql = "ALTER TABLE $table MODIFY COLUMN `$name` $type $null $default;";
                break;
            case 'rename':
                $sql = "ALTER TABLE $table CHANGE COLUMN `$object->old_name` `$name` $type $null $default;";
                break;
            case 'remove':
                $sql = "ALTER TABLE $table DROP COLUMN`$name`;";
                break;
            case 'check_index':
                $sql = "SHOW INDEX FROM $table WHERE Key_name = 'idx_$name';";
                break;
            case 'add_index':
                $sql = "ALTER TABLE $table ADD INDEX `idx_$name` ($name);";
                break;
            case 'remove_index':
                $sql = "ALTER TABLE $table DROP INDEX `idx_$name`;";
                break;
        }

        return trim($sql ?? '');
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


    public function validFieldType($properties): void
    {
        [
            'field_type' => $field_type,
            'field_default' => $field_default,
            'precision' => $precision
        ] = $properties;

        switch($field_type) {
            case 'enumfield':
                if (empty($precision)) {
                    $this->modx->error->addField('precision', $this->modx->lexicon('ef_field_empty'));
                }
                if (!empty($field_default)) {
                    $values = explode(',', $precision);
                    if (!in_array($field_default, $values)) {
                        $this->modx->error->addField('field_default', $this->modx->lexicon('ef_field_default_error_enum'));
                    }
                }
                break;
            case 'xcheckbox':
            case 'combo-boolean':
                if (!in_array($field_default, ['0', '1'])) {
                    $this->modx->error->addField('field_default', $this->modx->lexicon('ef_field_default_error_combo'));
                }
                break;
            case 'numberfield':
                if (!is_numeric($field_default)) {
                    $this->modx->error->addField('field_default', $this->modx->lexicon('ef_field_default_error_numberfield'));
                }
                break;
            case 'xdatetime':
                if (!empty($field_default) && !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $field_default)) {
                    $this->modx->error->addField('field_default', $this->modx->lexicon('ef_field_default_error_date'));
                }
                break;
        }
    }
}