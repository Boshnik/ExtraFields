<?php

namespace Boshnik\ExtraFields;

use modX;
use PDO;
use PDOStatement;
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
    public $version = '2.0.0';

    /**
     * The class config
     * @var array $config
     */
    public $config = [];

    /**
     * Supported bindings for MODX
     * @var array $bindings
     */
    public $bindings = [
        'FILE',
        'CHUNK',
        'DOCUMENT',
        'RESOURCE',
        'SELECT',
        'INHERIT',
        'DIRECTORY'
    ];

    const FIELDMETA = [
        'textfield' => [
            'dbtype' => 'varchar',
            'precision' => 255,
            'phptype' => 'string',
            'null' => true,
            'default' => null,
        ],
        'textarea' => [
            'dbtype' => 'text',
            'phptype' => 'string',
            'null' => true,
            'default' => null,
        ],
        'richtext' => [
            'dbtype' => 'mediumtext',
            'phptype' => 'string',
            'null' => true,
            'default' => null,
        ],
        'numberfield' => [
            'dbtype' => 'int',
            'precision' => 10,
            'attributes' => 'unsigned',
            'phptype' => 'integer',
            'null' => true,
            'default' => 0,
        ],
        'xcheckbox' => [
            'dbtype' => 'tinyint',
            'precision' => 1,
            'attributes' => 'unsigned',
            'phptype' => 'integer',
            'null' => true,
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
            'null' => true,
            'default' => 0,
        ],
    ];


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


    /**
     * @param $className
     * @param $class_name
     * @return mixed
     */
    public function getFetchAll($className, array $where = [])
    {
        $q = $this->modx->newQuery($className);
        $q->select($this->modx->getSelectColumns($className, $className, '', '', false));
        $q->where($where);
        $q->sortby('colrank', 'asc');
        $q->prepare();
        $q->stmt->execute();
        $results = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
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
            $tables = [modUser::class, modUserProfile::class];
        }

        if (!preg_match("/^[\w\d\_]*$/", $name)) {
            $this->modx->error->addField('name', $this->modx->lexicon("ef_field_err_name_cyrillic"));
        }

        $columns = [];
        foreach ($tables as $table) {
            $q = $this->modx->prepare("DESCRIBE " . $this->modx->getTableName($table));
            $q->execute();
            $columns += $q->fetchAll(PDO::FETCH_COLUMN);
        }

        if (in_array($name, $columns)) {
            $this->modx->error->addField('name', $this->modx->lexicon("ef_field_err_name_reserved"));
        }
    }


    /**
     * Getting fields from efTab table
     * @return array
     */
    public function getTabs($class_name = 'modResource')
    {
        $tabs = $this->getFetchAll(efTab::class, [
            'class_name' => $class_name,
            'active' => 1
        ]);

        foreach ($tabs as $idx => $tab) {
            $tabs[$idx]['categories'] = $this->getFetchAll(efCategory::class, [
                'tab_id' => $tab['id'],
                'active' => 1
            ]);
        }

        return $tabs;
    }


    /**
     * Get fields from efField table
     * @return array
     */
    public function getFields($class_name = 'modResource')
    {
         $fields = $this->getFetchAll(efField::class, [
            'class_name' => $class_name,
            'active' => 1
         ]);

         if (!count($fields)) return [];
         foreach ($fields as $idx => $field) {
             $fields[$idx]['abs'] = [];
             $abs = $this->getFetchAll(efFieldAbs::class, [
                 'field_id' => $field['id'],
                 'active' => 1
             ]);
             foreach ($abs as $item) {
                 $item['values'] = $this->processBindings($item['values']);
                 if (isset($this->config['pageblocks'])) {
                     $columns = $this->getFetchAll(pbTableColumn::class, [
                         'table_id' => $item['table_id'],
                     ]);
                     foreach ($columns as $column) {
                         $field = $this->modx->getObject(pbField::class, $column['field_id']);
                         if ($field) {
                             $column['name'] = $field->name;
                             $column['caption'] = $field->caption;
                             $item['table_columns'][] = $column;
                         }
                     }
                 }

                 $fields[$idx]['abs'][] = $item;
             }
         }
         return $fields;
    }


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
        $name = $object->name;

        $FIELDMETA = self::FIELDMETA;
        $meta = array_key_exists($object->type, $FIELDMETA) ? $FIELDMETA[$object->type] : $FIELDMETA['richtext'];

        $type = $meta['dbtype'];
        if (isset($meta['precision'])) {
            $type .= "({$meta['precision']})";
        }
        $null = $meta['null'] ? 'NULL' : 'NOT NULL';
        $default = empty($meta['default']) ? "" : " DEFAULT {$meta['default']}";

        if (!empty($object->old_name) && $object->name !== $object->old_name) {
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
                $sql = "ALTER TABLE {$table} DROP `{$name}`;";
                break;
        }

        return $sql;
    }

    /**
     * Parses the binding data from a value
     *
     * @param mixed $value The value to parse
     * @return array An array of cmd and param for the binding
     */
    public function getBindingDataFromValue($value) {
        $nvalue = trim($value);
        $cmd = false;
        $param = '';
        if (substr($nvalue,0,1) == '@') {
            list($cmd,$param) = $this->parseBinding($nvalue);
            $cmd = trim($cmd);
        }
        return array('cmd' => $cmd,'param' => $param);
    }


    public function processBindings($value= '', $resourceId= 0, $preProcess = true) {
        $bdata = $this->getBindingDataFromValue($value);
        if (empty($bdata['cmd'])) return $value;

        if (empty($this->modx->resource)) {
            if (!empty($resourceId)) {
                $this->modx->resource = $this->modx->getObject('modResource',$resourceId);
            }
            if (empty($this->modx->resource) || empty($resourceId)) {
                $this->modx->resource = $this->modx->newObject('modResource');
                $this->modx->resource->set('id',0);
            }
        }
        $cmd = $bdata['cmd'];
        $param = !empty($bdata['param']) ? $bdata['param'] : null;

        switch ($cmd) {
            case 'FILE':
                if ($preProcess) {
                    $output = $this->processFileBinding($param);
                }
                break;

            case 'CHUNK': /* retrieve a chunk and process it's content */
                if ($preProcess) {
                    $output = $this->modx->getChunk($param);
                }
                break;

            case 'RESOURCE':
            case 'DOCUMENT': /* retrieve a document and process it's content */
                if ($preProcess) {
                    $query = $this->modx->newQuery('modResource', array(
                        'id' => (integer) $param,
                        'deleted' => false
                    ));
                    $query->select('content');
                    if ($query->prepare() && $query->stmt->execute()) {
                        $output = $query->stmt->fetch(PDO::FETCH_COLUMN);
                    } else {
                        $output = 'Unable to locate resource '.$param;
                    }
                }
                break;

            case 'SELECT': /* selects a record from the cms database */
                if ($preProcess) {
                    $dbtags = [];
                    if ($this->modx->resource && $this->modx->resource instanceof modResource) {
                        $dbtags = $this->modx->resource->toArray();
                    }
                    $dbtags['DBASE'] = $dbtags['+dbname'] = $this->modx->getOption('dbname');
                    $dbtags['PREFIX'] = $dbtags['+table_prefix'] = $this->modx->getOption('table_prefix');
                    foreach($dbtags as $key => $pValue) {
                        if (!is_scalar($pValue)) continue;
                        $param = str_replace('[[+'.$key.']]', (string)$pValue, $param);
                    }

                    $stmt = $this->modx->query('SELECT '.$param);

                    if ($stmt && $stmt instanceof PDOStatement) {
                        $data = '';

                        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                            $col = '';
                            if (isset($row[1])) {
                                $col = $row[0].'=='.$row[1];
                            } else {
                                $col = $row[0];
                            }
                            $data .= (!empty($data) ? '||' : '').$col;
                        }
                        $stmt->closeCursor();
                    }
                    $output = $data;


                }
                break;

            case 'INHERIT':
                if ($preProcess) {
                    $output = $this->processInheritBinding($param,$resourceId);
                } else {
                    $output = $value;
                }
                break;

            case 'DIRECTORY':
                $path = $this->modx->getOption('base_path').$param;
                if (substr($path,-1,1) != '/') { $path .= '/'; }
                if (!is_dir($path)) { break; }

                $files = array();
                $invalid = array('.','..','.svn','.git','.DS_Store');
                foreach (new DirectoryIterator($path) as $file) {
                    if (!$file->isReadable()) continue;
                    $basename = $file->getFilename();
                    if(!in_array($basename,$invalid)) {
                        $files[] = "{$basename}=={$param}/{$basename}";
                    }
                }
                asort($files);
                $output = implode('||',$files);
                break;

            default:
                $output = $value;
                break;

        }
        /* support for nested bindings */
        return is_string($output) && ($output != $value) ? $this->processBindings($output) : $output;
    }


    /**
     * Parses bindings to an appropriate format.
     *
     * @access public
     * @param string $binding_string The binding to parse.
     * @return array The parsed binding, now in array format.
     */
    public function parseBinding($binding_string) {
        $match = array ();
        $binding_string= trim($binding_string);
        $regexp= '/@(' . implode('|', $this->bindings) . ')\s*(.*)/is'; /* Split binding on whitespace */
        if (preg_match($regexp, $binding_string, $match)) {
            /* We can't return the match array directly because the first element is the whole string */
            $binding_array= array (
                strtoupper($match[1]),
                trim($match[2])
            ); /* Make command uppercase */
            return $binding_array;
        }
    }

}