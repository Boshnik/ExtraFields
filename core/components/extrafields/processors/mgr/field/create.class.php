<?php

use Boshnik\ExtraFields\Processors\HelpProcessor;

class efFieldCreateProcessor extends modObjectCreateProcessor
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
        $name = str_replace('-', '_', trim($this->properties['field_name']));
        if (empty($name)) {
            $this->modx->error->addField('field_name', $this->modx->lexicon('ef_field_err_name'));
        } elseif ($this->modx->getCount($this->classKey, [
            'class_name' => $this->properties['class_name'],
            'field_name' => $name,
        ])) {
            $this->modx->error->addField('field_name', $this->modx->lexicon('ef_field_err_ae'));
        }

        $this->validationField($name, $this->properties['class_name']);
        $this->properties['field_name'] = strtolower($name);

        $this->validFieldType($this->properties);

        return parent::beforeSet();
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->fromArray([
            'menuindex' => $this->modx->getCount($this->classKey),
        ]);

        return true;
    }


    /**
     * @return bool
     */
    public function afterSave()
    {
        if (!in_array($this->object->class_name, ['pbBlockValue', 'pbTableValue'])) {
            $abs = $this->modx->getCollection('efFieldAbs', ['field_id' => 0]);
            foreach ($abs as $item) {
                $item->set('field_id', $this->object->id);
                $item->save();
            }
        }

        $this->createTableColumn($this->object);

        return true;
    }

}

return 'efFieldCreateProcessor';