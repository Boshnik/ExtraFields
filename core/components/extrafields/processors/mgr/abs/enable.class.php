<?php

require_once(dirname(__FILE__) . '/update.class.php');

class efFieldAbsEnableProcessor extends efFieldAbsUpdateProcessor
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

return 'efFieldAbsEnableProcessor';
