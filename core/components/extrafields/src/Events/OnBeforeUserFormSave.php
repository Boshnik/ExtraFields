<?php

namespace Boshnik\ExtraFields\Events;

/**
 * class OnBeforeUserFormSave
 */
class OnBeforeUserFormSave extends Event
{
    public function run()
    {
        /** @var \modUser $user */
        $user = $this->scriptProperties['user'];
        $profile = $user->getOne('Profile');
        $fields = $this->extrafields->getFields('modUserProfile');
        foreach ($fields as $field) {
            switch ($field['type']) {
                case 'listbox-multiple':
                case 'checkboxgroup':
                    $value = implode('||', $profile->get($field['name']));
                    $profile->set($field['name'], $value);
                    break;
            }
        }

    }
}