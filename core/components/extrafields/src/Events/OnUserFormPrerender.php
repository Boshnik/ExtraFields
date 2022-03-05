<?php

namespace Boshnik\ExtraFields\Events;

/**
 * class OnUserFormPrerender
 */
class OnUserFormPrerender extends Event
{
    public function run()
    {

        /** @var \modUser $user */
        $data = [];
        if ($user = $this->scriptProperties['user']) {
            $profile = $user->getOne('Profile')->toArray();
            $data = json_encode(array_merge($profile, [
                'id' => $user->id,
                'primary_group' => $user->primary_group,
                'sudo' => $user->sudo,
                'username' => $user->username,
            ]),1);
        }

        $this->extrafields->loadRichTextEditor();
        $tabs = json_encode($this->extrafields->getTabs('modUserProfile'),1);
        $fields = json_encode($this->extrafields->getFields('modUserProfile'),1);

        $config = $this->extrafields->config;
        $config['media_source'] = $this->extrafields->getMediaSources();

        $jsUrl = $this->extrafields->config['jsUrl'] . 'mgr/';
        $cssUrl = $this->extrafields->config['cssUrl'] . 'mgr/';

        $this->modx->controller->addCss($cssUrl . 'main.css');
        $this->modx->controller->addJavascript($jsUrl . 'extrafields.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/combo.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/utils.js');
        $this->modx->controller->addJavascript($jsUrl . 'misc/xtype.js');
        $this->modx->controller->addLastJavascript($jsUrl . 'inject/user.js');

        $config['class_name'] = 'modUserProfile';
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