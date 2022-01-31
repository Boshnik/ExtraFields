<?php
/** @var MODX\Revolution\modX $modx */

require_once MODX_CORE_PATH . 'components/extrafields/vendor/autoload.php';

$modx->services['extrafields'] = $modx->services->factory(function($c) use ($modx) {
    return new Boshnik\ExtraFields\ExtraFields($modx);
});