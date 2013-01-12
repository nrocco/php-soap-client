<?php

namespace Rocco\Console;


class Logger
{
  const DEBUG = 0;
  const INFO = 1;
  const WARN = 2;
  const ERROR = 3;

  protected $level;
  protected $labels = array(
    0 => 'DEBUG',
    1 => 'INFO',
    2 => 'WARN',
    3 => 'ERROR',
  );

  protected $_left_padding = 8;

  public function __construct($level = 1)
  {
    $this->set_level($level);
  }

  public function set_level($level)
  {
    $this->level = $level;
  }

  public function debug()
  {
    $this->_log_stdout(self::DEBUG, func_get_args());
  }

  public function info()
  {
    $this->_log_stdout(self::INFO, func_get_args());
  }

  public function warn()
  {
    $this->_log_stderr(self::WARN, func_get_args());
  }

  public function error()
  {
    $this->_log_stderr(self::ERROR, func_get_args());
  }

  protected function _log_stdout($level, $args)
  {
    return $this->_log('php://stdout', $level, $args);
  }

  protected function _log_stderr($level, $args)
  {
    return $this->_log('php://stderr', $level, $args);
  }

  protected function _log($stream, $level, $args)
  {
    if ($level >= $this->level)
    {
      file_put_contents($stream, 
                        $this->_get_label_for($level) . call_user_func_array('sprintf', $args) . PHP_EOL);
    }
  }

  protected function _get_label_for($level)
  {
    if (array_key_exists($level, $this->labels))
    {
      return str_pad('[' . $this->labels[$level] . '] ', $this->_left_padding);
    }
    else
    {
      return '';
    }
  }

  protected function _get_curr_time()
  {
    list($microSec, $timeStamp) = explode(" ", microtime());
    return sprintf('[%s:%s]', date('Y-m-d H:i', $timeStamp), date('s', $timeStamp) + $microSec);
  }
}
