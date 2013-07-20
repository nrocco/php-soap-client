<?php

namespace PhpSoapClient\Command\Base;

use PhpSoapClient\Client\SoapClient;
use PhpSoapClient\Helper\LoggerHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class SoapCommand extends Command
{
  protected $logger;

  protected function configure()
  {
    parent::configure();

    $this->addOption(
      'endpoint',
      null,
      InputOption::VALUE_REQUIRED,
      'Specify the url to the wsdl of the SOAP webservice to inspect.'
    );

    $this->addOption(
      'cache',
      null,
      InputOption::VALUE_NONE,
      'Flag to enable caching of the wsdl. By default this is disabled.'
    );
  }

  protected function initialize(InputInterface $input, OutputInterface $output)
  {
    $this->logger = new LoggerHelper($output);
  }

  protected function getSoapClient($endpoint, $cache=false, $timeout=120)
  {
    if (empty($endpoint))
    {
      throw new \Exception('You must specify an endpoint.');
    }

    if (true === $cache)
    {
      $this->logger->debug('Enabling caching of wsdl');
      $cache = WSDL_CACHE_MEMORY;
    }
    else
    {
      $this->logger->debug('Wsdls are not being cached.');
      $cache = WSDL_CACHE_NONE;
    }

    ini_set('default_socket_timeout', $timeout);
    $this->logger->debug('Set socket timeout to %s seconds.', $timeout);

    return new SoapClient($endpoint, array(
      'trace' => 1,
      'exceptions' => true,
      'connection_timeout' => $timeout,
      'cache_wsdl' => $cache,
    ));
  }
}
