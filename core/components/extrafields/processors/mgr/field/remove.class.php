<?php

class efFieldRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = efField::class;
    public $objectType = 'ef_field';
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
        $this->extrafields->removeTableColumn($this->object);

        return true;
    }

}

return 'efFieldRemoveProcessor';