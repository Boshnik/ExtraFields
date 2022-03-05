<?php

namespace Boshnik\ExtraFields\Events;

abstract class Event
{

    /** @var modX $modx */
    protected $modx;

    /** @var ExtraFields $extrafields */
    protected $extrafields;

    /** @var array $scriptProperties */
    protected $scriptProperties;

    public $modxversion;

    public function __construct($modx, &$scriptProperties)
    {
        $this->modx = $modx;
        $this->scriptProperties =& $scriptProperties;

        if ($this->modx->services instanceof Psr\Http\Client\ClientInterface) {
            $this->extrafields = $this->modx->services->get('extrafields');
        } else {
            $this->extrafields = $this->modx->getService('extrafields', 'ExtraFields', MODX_CORE_PATH . 'components/extrafields/model/');
        }
        $this->modxversion = $this->extrafields->config['modxversion'];
    }

    abstract public function run();
}