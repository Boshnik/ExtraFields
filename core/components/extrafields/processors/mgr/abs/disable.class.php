<?php

require_once(dirname(__FILE__) . '/update.class.php');

class efFieldAbsDisableProcessor extends efFieldAbsUpdateProcessor
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

return 'efFieldAbsDisableProcessor';
