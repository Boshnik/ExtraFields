<?php

class ExtraResourceTabGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = ExtraResourceTab::class;
    public $objectType = 'extrafields';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    //public $permission = 'list';


    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->where([
                'name:LIKE' => "%{$query}%",
                'OR:description:LIKE' => "%{$query}%",
            ]);
        }

        if ($this->getProperty('combo')) {
            $c->select('id,name');
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
        if ($this->getProperty('combo')) {
            $array = array(
                'id' => $object->get('id'),
                'name' => $object->get('name'),
            );

            return $array;
        }

        $array = $object->toArray();
        $array['actions'] = [];

        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('extraresource_tab_update'),
            //'multiple' => $this->modx->lexicon('extraresource_tabs_update'),
            'action' => 'updateResourceTab',
            'button' => true,
            'menu' => true,
        ];

        if (!$array['active']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('extraresource_tab_enable'),
                'multiple' => $this->modx->lexicon('extraresource_tabs_enable'),
                'action' => 'enableItem',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('extraresource_tab_disable'),
                'multiple' => $this->modx->lexicon('extraresource_tabs_disable'),
                'action' => 'disableItem',
                'button' => true,
                'menu' => true,
            ];
        }

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('extraresource_tab_remove'),
            'multiple' => $this->modx->lexicon('extraresource_tabs_remove'),
            'action' => 'removeItem',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }


    public function outputArray(array $array, $count = false)
    {
        if ($this->getProperty('combo')) {
            $array = array_merge([
                [
                    'id' => 0,
                    'name' => 'modx-resource-main-left'
                ], [
                    'id' => 255,
                    'name' => 'modx-resource-main-right'
                ]
            ], $array);
        }

        return parent::outputArray($array, $count);
    }

}

return 'ExtraResourceTabGetListProcessor';