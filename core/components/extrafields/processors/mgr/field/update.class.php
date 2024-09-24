<?php

use Boshnik\ExtraFields\Processors\HelpProcessor;

class efFieldUpdateProcessor extends modObjectUpdateProcessor
{
    use HelpProcessor;

    public $classKey = efField::class;
    public $objectType = 'ef_field';
    public $languageTopics = ['extrafields'];


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->object->set('old_name', $this->object->field_name);

        $id = (int) $this->properties['id'];
        $name = trim($this->properties['field_name']);
        if (empty($id)) {
            return $this->modx->lexicon('ef_field_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('field_name', $this->modx->lexicon('ef_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'field_name' => $name,
            'class_name' => $this->properties['class_name'],
            'id:!=' => $id
        ])) {
            $this->modx->error->addField('field_name', $this->modx->lexicon('ef_field_err_ae'));
        }

        $this->validFieldType($this->properties);

        return parent::beforeSet();
    }


    /**
     * @return bool
     */
    public function afterSave()
    {
        $this->updateTableColumn($this->object);
        $this->updateIndex($this->object);

        return true;
    }
}

return 'efFieldUpdateProcessor';
