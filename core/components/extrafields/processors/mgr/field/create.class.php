<?php

class ExtraMetaFieldCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = ExtraMetaField::class;
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
            $this->modx->error->addField('name', $this->modx->lexicon('extrameta_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('extrameta_field_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'ExtraMetaFieldCreateProcessor';