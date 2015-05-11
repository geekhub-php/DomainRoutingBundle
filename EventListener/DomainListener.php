<?php

namespace GeekHub\DomainRoutingBundle\EventListener;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputOption;

class DomainListener
{
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $inputDefinition = $event->getCommand()->getApplication()->getDefinition();

        // add the option to the application's input definition
        $inputDefinition->addOption(
            new InputOption('hostname', null, InputOption::VALUE_REQUIRED, 'Domain name for include routing', null)
        );
    }
}
