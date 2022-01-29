<?php

class ExtraUserFieldRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = ExtraUserField::class;
    public $objectType = 'extrafields';
    public $languageTopics = ['extrafields'];
    //public $permission = 'remove';


    /**
     * @return array|string
     */
    public function initialize()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::initialize();
    }

    /**
     * @return bool
     */
    public function afterRemove()
    {
        $table = $this->modx->getTableName(modUserProfile::class);
        $name = $this->object->get('name');

        $sql = "ALTER TABLE {$table} DROP COLUMN `{$name}`;";
        $this->modx->exec($sql);

        return true;
    }

}

return 'ExtraUserFieldRemoveProcessor';