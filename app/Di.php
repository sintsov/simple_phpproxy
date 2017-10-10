<?php
/**
 * Class description
 *
 * @author Sintsov Roman <romiras_spb@mail.ru>
 * @copyright Copyright (c) 2017, simple_phpproxy
 */

namespace SimplePHPProxy;

use DI\ContainerBuilder;
use DI\Container;

class Di
{
    /** @var Container */
    protected $container;

    public function __construct()
    {
        $builder = new ContainerBuilder;
        $builder->addDefinitions(__DIR__.'/config/etc.php');
        $this->container = $builder->build();
    }

    public function get()
    {
        return $this->container;
    }
}