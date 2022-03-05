<?php

class efFieldCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = efField::class;
    public $objectType = 'ef_field';
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
            $this->modx->error->addField('name', $this->modx->lexicon('ef_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'name' => $name,
            'class_name' => $this->getProperty('class_name'),
        ])) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_field_err_ae'));
        }

        $this->extrafields->validationField($name, 'modUserProfile');
        $this->setProperty('name', strtolower($name));

        return parent::beforeSet();
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->fromArray([
            'rank' => $this->modx->getCount($this->classKey, [
                'class_name' => $this->getProperty('class_name'),
            ]),
        ]);

        return true;
    }


    /**
     * @return bool
     */
    public function afterSave()
    {

        $abs = $this->modx->getiterator('efFieldAbs', ['field_id' => 0]);
        foreach ($abs as $item) {
            $item->set('field_id', $this->object->id);
            $item->save();
        }

        $this->extrafields->createTableColumn($this->object);

        return true;
    }

}

return 'efFieldCreateProcessor';