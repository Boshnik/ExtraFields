<?php

class efCategoryCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = efCategory::class;
    public $objectType = 'ef_category';
    public $languageTopics = ['extrafields'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_category_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'name' => $name,
            'tab_id' => (int) $this->getProperty('tab_id'),
        ])) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_category_err_ae'));
        }

        $this->properties['ab_templates'] = implode('||', $this->getProperty('ab_templates'));
        $this->properties['ab_user_group'] = implode('||', $this->getProperty('ab_user_group'));

        return parent::beforeSet();
    }


    public function beforeSave()
    {
        $this->object->fromArray([
            'rank' => $this->modx->getCount($this->classKey, [
                'tab_id' => (int) $this->getProperty('tab_id'),
            ]),
        ]);

        return true;
    }

}

return 'efCategoryCreateProcessor';