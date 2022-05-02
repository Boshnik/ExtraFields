<?php

class efTabUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = efTab::class;
    public $objectType = 'ef_tab';
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
        $id = (int)$this->getProperty('id');
        $name = trim($this->getProperty('name'));
        if (empty($id)) {
            return $this->modx->lexicon('ef_tab_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_tab_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'id:!=' => $id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_tab_err_ae'));
        }

        if ($this->properties['ab_templates']) {
            $this->properties['ab_templates'] = implode('||', $this->properties['ab_templates']);
        }
        if ($this->properties['ab_user_group']) {
            $this->properties['ab_user_group'] = implode('||', $this->properties['ab_user_group']);
        }

        return parent::beforeSet();
    }
}

return 'efTabUpdateProcessor';
