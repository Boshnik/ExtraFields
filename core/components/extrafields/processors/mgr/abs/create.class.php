<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

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
        if (!empty($this->properties['ab_templates'])) {
            $this->properties['ab_templates'] = implode('||', $this->properties['ab_templates']);
        }
        if (!empty($this->properties['ab_user_group'])) {
            $this->properties['ab_user_group'] = implode('||', $this->properties['ab_user_group']);
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->fromArray([
            'colrank' => $this->modx->getCount($this->classKey, [
                'field_id' => $this->getProperty('field_id'),
            ]),
        ]);

        return true;
    }

}

return 'efFieldAbsCreateProcessor';