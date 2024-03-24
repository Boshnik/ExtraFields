<?php

class efCategoryCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = efCategory::class;
    public $objectType = 'ef_category';
    public $languageTopics = ['extrafields'];


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->properties['name']);
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_category_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'name' => $name,
            'tab_id' => (int) $this->properties['tab_id'],
        ])) {
            $this->modx->error->addField('name', $this->modx->lexicon('ef_category_err_ae'));
        }

        if ($this->properties['ab_templates']) {
            $this->properties['ab_templates'] = implode('||', $this->properties['ab_templates']);
        }

        if ($this->properties['ab_user_group']) {
            $this->properties['ab_user_group'] = implode('||', $this->properties['ab_user_group']);
        }

        return parent::beforeSet();
    }


    public function beforeSave()
    {
        $this->object->fromArray([
            'menuindex' => $this->modx->getCount($this->classKey, [
                'tab_id' => (int) $this->getProperty('tab_id'),
            ]),
        ]);

        return true;
    }

}

return 'efCategoryCreateProcessor';