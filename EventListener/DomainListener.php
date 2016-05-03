<?php

namespace GeekHub\DomainRoutingBundle\EventListener;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputOption;

class DomainListener
{
    /**
     * @param ConsoleCommandEvent $event
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $definition = $event->getCommand()->getDefinition();
        $input = $event->getInput();

        // add the option to the application's input definition
        $definition->addOption(
            new InputOption('hostname', null, InputOption::VALUE_REQUIRED, 'Domain name for include routing', null)
        );
        $input->bind($definition);
    }
}
