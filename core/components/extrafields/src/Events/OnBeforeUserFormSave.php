<?php

namespace Boshnik\ExtraFields\Events;

use Boshnik\ExtraFields\Processors\QueryProcessor;

/**
 * class OnBeforeUserFormSave
 */
class OnBeforeUserFormSave extends Event
{
    use QueryProcessor;

    public function run()
    {
        if ($this->scriptProperties['mode'] == 'new') return;

        /** @var \modUser $user */
        $user = $this->scriptProperties['user'];
        $profile = $user->getOne('Profile');
        $fields = $this->getFields('modUserProfile');

        foreach ($fields as $field) {
            switch ($field['field_type']) {
                case 'listbox-multiple':
                case 'checkboxgroup':
                    $value = implode('||', $profile->get($field['field_name']));
                    $profile->set($field['field_name'], $value);
                    break;
//                case 'pb-table':
//                    $results = [];
//                    foreach ($field['abs'] as $abs) {
//                        // user group
//                        if (!empty($abs['ab_user_group']) && !in_array($user->primary_group, explode(',', $abs['ab_user_group']))) {
//                            continue;
//                        }
//
//                        // users
//                        if (!empty($abs['ab_users']) && !in_array($user->id, explode(',', $abs['ab_users']))) {
//                            continue;
//                        }
//                        if (count($results)) continue;
//                        $results = $this->getFetchAll('pbTableValue', [
//                            'model_type' => $profile->_class,
//                            'model_id' => $user->id,
//                            'constructor_id' => 0,
//                            'table_id' => $abs['table_id'],
//                            'field_id' => 0,
//                            'ef_field_id' => $field['id'],
//                            'published' => 1
//                        ]);
//                    }
//
//                    $values = [];
//                    foreach ($results as $result) {
//                        $values[] = json_decode($result['values'], 1);
//                    }
//                    $profile->set($field['field_name'], json_encode($values, JSON_UNESCAPED_UNICODE));
//                    break;

                case 'pb-gallery':
                    $results = [];
                    foreach ($field['abs'] as $abs) {
                        // user group
                        if (!empty($abs['ab_user_group']) && !in_array($user->primary_group, explode(',', $abs['ab_user_group']))) {
                            continue;
                        }

                        // users
                        if (!empty($abs['ab_users']) && !in_array($user->id, explode(',', $abs['ab_users']))) {
                            continue;
                        }
                        if (count($results)) continue;

                        $results = $this->getFetchAll('pbFile', [
                            'model_type' => $profile->_class,
                            'model_id' => $user->id,
                            'field_id' => 0,
                            'ef_field_id' => $field['id'],
                            'published' => 1
                        ]);
                    }

                    $values = [];
                    foreach ($results as $result) {
                        $values[] = [
                            'path' => $result['path'],
                            'filename' => $result['filename'],
                            'name' => $result['name'],
                            'title' => $result['title'],
                            'description' => $result['description'],
                            'url' => $result['url'],
                            'type' => $result['type'],
                        ];
                    }
                    $profile->set($field['field_name'], json_encode($values, JSON_UNESCAPED_UNICODE));
                    break;
            }
        }

    }
}