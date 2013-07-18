<?php

namespace Rocco\PhpSoapClient\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Rocco\PhpSoapClient\Command\Base\SoapCommand;


class GetWsdlCommand extends SoapCommand
{
  protected function configure()
  {
    parent::configure();

    $this->setName('wsdl');
    $this->setDescription('Get the WSDL of a soap service.');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $endpoint = $input->getOption('endpoint');

    if (false === isset($endpoint))
    {
      throw new \Exception('You must specify an endpoint');
    }

    $this->debug($output, sprintf('Exploring wsdl at %s', $endpoint));

    echo file_get_contents($endpoint) . PHP_EOL;

    $this->debug($output, 'Done');
  }
}
