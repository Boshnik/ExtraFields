<?php

class efCategoryUpdateProcessor extends modObjectUpdateProcessor
{
    public $classKey = efCategory::class;
    public $objectType = 'ef_category';
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
        $id = (int) $this->getProperty('id');
        $name = trim($this->getProperty('name'));
        if (empty($id)) {
            return $this->modx->lexicon('ef_category_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_category_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'name' => $name,
            'tab_id' => (int) $this->getProperty('tab_id'),
            'id:!=' => $id,
        ])) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_category_err_ae'));
        }

        $this->properties['ab_templates'] = implode('||', $this->getProperty('ab_templates'));
        $this->properties['ab_user_group'] = implode('||', $this->getProperty('ab_user_group'));

        return parent::beforeSet();
    }
}

return 'efCategoryUpdateProcessor';
