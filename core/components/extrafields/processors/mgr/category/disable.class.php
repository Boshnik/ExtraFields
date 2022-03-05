<?php

require_once(dirname(__FILE__) . '/update.class.php');

class efCategoryDisableProcessor extends efCategoryUpdateProcessor
{

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = [
            'active' => 0,
        ];

        return true;
    }
}

return 'efCategoryDisableProcessor';
