<?php

class ExtraUserFieldUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = ExtraUserField::class;
    public $objectType = 'extrafields';
    public $languageTopics = ['extrafields'];
    //public $permission = 'save';


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->getProperty('id');
        $name = trim($this->getProperty('name'));
        if (empty($id)) {
            return $this->modx->lexicon('extrauser_field_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('extrauser_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'id:!=' => $id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('extrauser_field_err_ae'));
        }

        return parent::beforeSet();
    }

    /**
     * Обновляем таблицу пользователей
     * @return boolean
     */
    public function afterSave()
    {
        if ($field = $this->object->getOne('Field')) {
            $table = $this->modx->getTableName(modUserProfile::class);
            $name = $this->object->get('name');
            $type = $field->get('dbtype');
            if ($field->get('precision')) {
                $type .= "({$field->get('precision')})";
            }
            $null = $field->get('null') ? 'NULL' : 'NOT NULL';
            $default = empty($field->get('default')) ? "" : " DEFAULT $field->get('default')";

            $sql = "ALTER TABLE {$table} MODIFY COLUMN `{$name}` {$type} {$null}{$default};";
            $this->modx->exec($sql);
        }

        return true;
    }
}

return 'ExtraUserFieldUpdateProcessor';
