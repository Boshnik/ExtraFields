<?php

class efFieldUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = efField::class;
    public $objectType = 'ef_field';
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

        // old_name
        $this->object->set('old_name', $this->object->name);


        $id = (int) $this->getProperty('id');
        $name = trim($this->getProperty('name'));
        if (empty($id)) {
            return $this->modx->lexicon('ef_field_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'name' => $name,
            'class_name' => $this->getProperty('class_name'),
            'id:!=' => $id
        ])) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_field_err_ae'));
        }

        return parent::beforeSet();
    }


    /**
     * @return bool
     */
    public function afterSave()
    {
        $this->extrafields->updateTableColumn($this->object);

        return true;
    }
}

return 'efFieldUpdateProcessor';
