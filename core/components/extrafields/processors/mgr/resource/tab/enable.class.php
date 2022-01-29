<?php

require_once(dirname(__FILE__) . '/update.class.php');

class ExtraResourceTabEnableProcessor extends ExtraResourceTabUpdateProcessor
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

return 'ExtraResourceTabEnableProcessor';
