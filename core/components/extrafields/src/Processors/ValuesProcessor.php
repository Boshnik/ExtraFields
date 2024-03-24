<?php

namespace Boshnik\ExtraFields\Processors;

trait ValuesProcessor
{

    /**
     * Supported bindings for MODX
     * @var array $bindings
     */
    public $bindings = [
        'FILE',
        'SELECT',
    ];

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
        return ['cmd' => $cmd,'param' => $param];
    }


    /**
     * @param string $value
     * @param int $resourceId
     * @param bool $preProcess
     * @return mixed|string
     */
    public function processBindings($value = '', $resourceId = 0, $preProcess = true) {
        $bdata = $this->getBindingDataFromValue($value);
        if (empty($bdata['cmd'])) return $value;

        if (empty($this->modx->resource)) {
            if (!empty($resourceId)) {
                $this->modx->resource = $this->modx->getObject(\modResource::class,$resourceId);
            }
            if (empty($this->modx->resource) || empty($resourceId)) {
                $this->modx->resource = $this->modx->newObject(\modResource::class);
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

            case 'SELECT': /* selects a record from the cms database */
                if ($preProcess) {
                    $dbtags = [];
                    if ($this->modx->resource && $this->modx->resource instanceof \modResource) {
                        $dbtags = $this->modx->resource->toArray();
                    }
                    $dbtags['DBASE'] = $dbtags['+dbname'] = $this->modx->getOption('dbname');
                    $dbtags['PREFIX'] = $dbtags['+table_prefix'] = $this->modx->getOption('table_prefix');
                    foreach($dbtags as $key => $pValue) {
                        if (!is_scalar($pValue)) continue;
                        $param = str_replace('[[+'.$key.']]', (string)$pValue, $param);
                    }

                    $stmt = $this->modx->query('SELECT '.$param);

                    if ($stmt && $stmt instanceof \PDOStatement) {
                        $data = '';

                        while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
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
        $match = [];
        $binding_string= trim($binding_string);
        $regexp= '/@(' . implode('|', $this->bindings) . ')\s*(.*)/is'; /* Split binding on whitespace */
        if (preg_match($regexp, $binding_string, $match)) {
            /* We can't return the match array directly because the first element is the whole string */
            $binding_array = [
                strtoupper($match[1]),
                trim($match[2])
            ]; /* Make command uppercase */
            return $binding_array;
        }
    }

    /**
     * Special parsing for file bindings.
     *
     * @access public
     * @param string $file The absolute location of the file in the binding.
     * @return string The file buffer from the read file.
     */
    public function processFileBinding($file) {
        if (file_exists($file) && @ $handle= fopen($file,'r')) {
            $buffer= "";
            while (!feof($handle)) {
                $buffer .= fgets($handle, 4096);
            }
            fclose($handle);
        } else {
            $buffer= " Could not retrieve document '$file'.";
        }

        return $buffer;
    }

}