<?php

class ExtraResourceFieldUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = ExtraResourceField::class;
    public $objectType = 'extrafields';
    public $languageTopics = ['extrafields'];
    //public $permission = 'save';

    /** @var ExtraFields $extrafields */
    public $extrafields;


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }
        $this->extrafields = $this->modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');

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
            return $this->modx->lexicon('extraresource_field_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('extraresource_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'id:!=' => $id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('extraresource_field_err_ae'));
        }

        return parent::beforeSet();
    }


    /**
     * @return boolean
     */
    public function afterSave()
    {
        $this->extrafields->updateTableColumn($this->object, modResource::class);

        return true;
    }
}

return 'ExtraResourceFieldUpdateProcessor';
