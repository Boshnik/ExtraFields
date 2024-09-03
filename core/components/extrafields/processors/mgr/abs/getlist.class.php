<?php

class efFieldAbsGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = efFieldAbs::class;
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
        $c->where([
            'field_id' => trim($this->properties['field_id'])
        ]);

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        $array['actions'] = [];

//        if (empty($object->areas)) {
//            if ($tab = $object->getOne('Tab')) {
//                $array['tab'] = $tab->get('name');
//            }
//
//            if ($category = $object->getOne('Category')) {
//                $array['category'] = $category->get('name');
//            }
//        } else {
//            $array['tab'] = $this->modx->lexicon($object->areas);
//            $array['category'] = $object->areas;
//        }


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

return 'efFieldAbsGetListProcessor';