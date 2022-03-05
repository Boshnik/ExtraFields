<?php

namespace Boshnik\ExtraFields\Events;

/**
 * class OnDocFormPrerender
 */
class OnDocFormPrerender extends Event
{
    public function run()
    {

        /** @var \modResource $resource */
        $data = [];
        if ($resource = $this->scriptProperties['resource']) {
            $data = json_encode($resource->toArray(),1);
        }

        $this->extrafields->loadRichTextEditor();
        $tabs = json_encode($this->extrafields->getTabs('modResource'),1);
        $fields = json_encode($this->extrafields->getFields('modResource'),1);

        $config = $this->extrafields->config;
        $config['media_source'] = $this->extrafields->getMediaSources();

        $jsUrl = $config['jsUrl'] . 'mgr/';
        $cssUrl = $config['cssUrl'] . 'mgr/';

        $this->modx->controller->addCss($cssUrl . 'main.css');
        $this->modx->controller->addJavascript($jsUrl . 'extrafields.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/combo.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/utils.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/xtype.js');
        $this->modx->controller->addLastJavascript($jsUrl . 'inject/resource.js');

        $config['class_name'] = 'modResource';
        $config = json_encode($config);
        $this->modx->controller->addHtml("<script>
                ExtraFields.config = $config;
                ExtraFields.tabs = $tabs;
                ExtraFields.fields = $fields;
                ExtraFields.modxversion = $this->modxversion;
                ExtraFields.object = $data;
            </script>");
    }
}