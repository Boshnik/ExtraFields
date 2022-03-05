<?php

require_once(dirname(__FILE__) . '/update.class.php');

class efFieldEnableProcessor extends efFieldUpdateProcessor
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

return 'efFieldEnableProcessor';
