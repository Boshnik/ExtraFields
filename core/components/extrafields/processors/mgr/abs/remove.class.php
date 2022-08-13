<?php

class efFieldAbsRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = efFieldAbs::class;
    public $objectType = 'ef_field';
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

return 'efFieldAbsRemoveProcessor';