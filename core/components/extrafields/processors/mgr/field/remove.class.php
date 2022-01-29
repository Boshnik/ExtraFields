<?php

class ExtraMetaFieldRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = ExtraMetaField::class;
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

}

return 'ExtraMetaFieldRemoveProcessor';