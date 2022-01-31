<?php

class ExtraUserFieldRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = ExtraUserField::class;
    public $objectType = 'extrafields';
    public $languageTopics = ['extrafields'];
    //public $permission = 'remove';

    /** @var ExtraFields $extrafields */
    public $extrafields;


    /**
     * @return array|string
     */
    public function initialize()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
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
    public function afterRemove()
    {
        $this->extrafields->removeTableColumn($this->object, modUserProfile::class);

        return true;
    }

}

return 'ExtraUserFieldRemoveProcessor';