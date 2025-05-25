<?php

use Boshnik\ExtraFields\Processors\HelpProcessor;

class efFieldUpdateProcessor extends modObjectUpdateProcessor
{
    use HelpProcessor;

    public $classKey = efField::class;
    public $objectType = 'ef_field';
    public $languageTopics = ['extrafields'];

    public function beforeSet()
    {
        $this->object->set('old_name', $this->object->field_name);
        $this->object->set('old_precision', $this->object->precision);

        $id = (int) $this->properties['id'];
        $name = trim($this->properties['field_name']);
        if (empty($id)) {
            return $this->modx->lexicon('ef_field_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('field_name', $this->modx->lexicon('ef_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'field_name' => $name,
            'class_name' => $this->properties['class_name'],
            'id:!=' => $id
        ])) {
            $this->modx->error->addField('field_name', $this->modx->lexicon('ef_field_err_ae'));
        }

        $this->validFieldType($this->properties);

        return parent::beforeSet();
    }

    public function afterSave()
    {
        if (in_array($this->object->field_type, ['enumfield', 'setfield'])) {
            $oldEnum = explode(',', $this->object->old_precision);
            $newEnum = explode(',', $this->object->precision);
            $deletedValues = array_diff($oldEnum, $newEnum);
            $table = $this->modx->getTableName($this->object->class_name);
            $field_name = $this->object->field_name;
            $default = $this->object->field_null ? 'NULL' : "'{$this->object->field_default}'";
            foreach ($deletedValues as $value) {
                if ($this->object->field_type === 'enumfield') {
                    $sql = "UPDATE $table SET $field_name = $default WHERE $field_name = '$value';";
                } else {
                    $sql = "UPDATE $table SET $field_name = $default WHERE FIND_IN_SET('$value', $field_name) > 0;";
                }
                $this->modx->exec($sql);
            }
        }

        $this->updateTableColumn($this->object);
        $this->updateIndex($this->object);

        return true;
    }
}

return 'efFieldUpdateProcessor';
