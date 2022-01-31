<?php

class ExtraUserFieldCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = ExtraUserField::class;
    public $objectType = 'extrafields';
    public $languageTopics = ['extrafields'];
    //public $permission = 'create';

    /** @var ExtraFields $extrafields */
    public $extrafields;


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if ($this->modx->services instanceof Psr\Http\Client\ClientInterface) {
            $this->extrafields = $this->modx->services->get('extrafields');
        } else {
            $this->extrafields = $this->modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
        }

        return parent::initialize();
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = str_replace('-', '_', trim($this->getProperty('name')));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('extrauser_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('extrauser_field_err_ae'));
        }

        $this->extrafields->validationField($name, 'user');

        return parent::beforeSet();
    }


    /**
     * @return boolean
     */
    public function afterSave()
    {
        $this->extrafields->createTableColumn($this->object, modUserProfile::class);

        return true;
    }

}

return 'ExtraUserFieldCreateProcessor';