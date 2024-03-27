<?php

class efFieldGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = efField::class;
    public $objectType = 'ef_field';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        if (isset($this->properties['class_name'])) {
            $c->where([
                'class_name' => $this->properties['class_name']
            ]);
        }

        $query = trim($this->properties['query']);
        if ($query) {
            $c->where([
                'field_name:LIKE' => "%{$query}%",
            ]);
        }

        if ($this->properties['combo']) {
            $c->where(['active' => 1]);
            $c->select('id, field_name');
        }

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        if ($this->properties['combo']) {
            $array = [
                'id' => $object->id,
                'name' => $object->field_name,
            ];

            return $array;
        }

        $array = $object->toArray();
        $array['actions'] = [];

        $fieldType = str_replace(['pb-panel-', 'pb-', 'modx-', 'ef-'], '', $array['field_type']);
        $fieldType = str_replace('-', '_', $fieldType);
        $array['type_lexicon'] = $this->modx->lexicon('ef_field_type_' . $fieldType);

        $array['class_name_lexicon'] = $this->modx->lexicon('ef_class_name_' . $array['class_name']);

        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('ef_row_update'),
            'action' => 'updateObject',
            'button' => true,
            'menu' => true,
        ];

        if (!$array['active']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('ef_row_enable'),
                'multiple' => $this->modx->lexicon('ef_rows_enable'),
                'action' => 'enableObject',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('ef_row_disable'),
                'multiple' => $this->modx->lexicon('ef_rows_disable'),
                'action' => 'disableObject',
                'button' => true,
                'menu' => true,
            ];
        }

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('ef_row_remove'),
            'multiple' => $this->modx->lexicon('ef_rows_remove'),
            'action' => 'removeObject',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'efFieldGetListProcessor';