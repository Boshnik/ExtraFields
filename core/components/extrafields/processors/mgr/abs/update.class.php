<?php

class efFieldAbsUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = efFieldAbs::class;
    public $objectType = 'ef_field';
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

        $this->properties['ab_templates'] = implode('||', $this->getProperty('ab_templates'));
        $this->properties['ab_user_group'] = implode('||', $this->getProperty('ab_user_group'));

        return parent::beforeSet();
    }


}

return 'efFieldAbsUpdateProcessor';
