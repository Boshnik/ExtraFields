<?php

namespace Boshnik\ExtraFields\Events;

/**
 * class OnManagerPageBeforeRender
 */
class OnManagerPageBeforeRender extends Event
{
    public function run()
    {
        if (!isset($this->extrafields->config['pageblocks'])) {
            $this->modx->controller->addHtml("<style> 
                #extrafields_pb_block, 
                #extrafields_pb_table {
                    display: none !important;
                } 
            </style>");
        }

    }
}