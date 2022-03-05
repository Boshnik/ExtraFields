<?php

class efFieldAbsCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = efFieldAbs::class;
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
        $this->properties['ab_templates'] = implode('||', $this->getProperty('ab_templates'));
        $this->properties['ab_user_group'] = implode('||', $this->getProperty('ab_user_group'));

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->fromArray([
            'rank' => $this->modx->getCount($this->classKey, [
                'field_id' => $this->getProperty('field_id'),
            ]),
        ]);

        return true;
    }


}

return 'efFieldAbsCreateProcessor';