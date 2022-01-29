<?php

class ExtraUserFieldCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = ExtraUserField::class;
    public $objectType = 'extrafields';
    public $languageTopics = ['extrafields'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('extrauser_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('extrauser_field_err_ae'));
        }

        $extrafields = $this->modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
        $extrafields->validationUserField($name);

        return parent::beforeSet();
    }

    /**
     * Расширяем таблицу пользователей
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

            $sql = "ALTER TABLE {$table} ADD `{$name}` {$type} {$null}{$default};";
            $this->modx->exec($sql);
        }

        return true;
    }

}

return 'ExtraUserFieldCreateProcessor';