<?php

require_once(dirname(__FILE__) . '/update.class.php');

class efCategoryEnableProcessor extends efCategoryUpdateProcessor
{
    
    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = [
            'active' => 1,
        ];

        return true;
    }

}

return 'efCategoryEnableProcessor';
