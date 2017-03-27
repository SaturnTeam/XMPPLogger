<?php
/**
 *
 * This file is licensed under the MIT License. See the LICENSE file.
 *
 * @author Dmitry Volynkin <thesaturn@thesaturn.me>
 */

namespace thesaturn\xmpplogger;

use Fabiang\Xmpp\Client;
use Fabiang\Xmpp\Protocol\Message;
use \Psr\Log\LogLevel;

class XMPPLogger implements \Psr\Log\LoggerInterface
{
    /**
     * XMPP client to send messages
     * @var Client
     */
    private $xmppClient;
    /**
     * @var callable
     * @see Echolog::defaultMessageFormatter()
     */
    private $messageFormatter;

    /**
     * @var string
     */
    private $level;

    /**
     * @var string
     */
    private $toJID;

    /**
     * @var int[]
     */
    protected static $rankings = array(
        LogLevel::DEBUG => 7,
        LogLevel::INFO => 6,
        LogLevel::NOTICE => 5,
        LogLevel::WARNING => 4,
        LogLevel::ERROR => 3,
        LogLevel::CRITICAL => 2,
        LogLevel::ALERT => 1,
        LogLevel::EMERGENCY => 0,
    );

    /**
     * @param Client $client
     * @param string $toJID
     * @param string $level
     */
    public function __construct(Client $client, $toJID, $level = LogLevel::DEBUG)
    {
        $this->xmppClient = $client;
        $this->toJID = $toJID;
        $this->level = $level;
        $this->messageFormatter = array($this, 'defaultMessageFormatter');
    }

    /**
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @param  string $level
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $hasLevel = isset(self::$rankings[$level]);

        if (!$hasLevel || ($hasLevel && self::$rankings[$level] <= self::$rankings[$this->level]))
        {
            $formatter = $this->messageFormatter;
            $message = new Message($formatter($level, $message, $context), $this->toJID);
            if (!$this->xmppClient->getConnection()->isConnected())
            {
                $this->xmppClient->connect();
            }
            $this->xmppClient->send($message);
        }
    }

    /**
     * @param  string $level
     * @param  string $message
     * @param  array $context
     * @return string
     */
    protected function defaultMessageFormatter($level, $message, array $context = array())
    {
        $message = sprintf('[%s] %s %s', date('Y-m-d H:i:s'), strtoupper($level), $message);
        if ($context)
        {
            $message .= ' ' . json_encode($context);
        }
        return $message;
    }

    /**
     * @param  callable $messageFormatter
     * @return void
     */
    public function setMessageFormatter(callable $messageFormatter)
    {
        $this->messageFormatter = $messageFormatter;
    }

    /**
     * @param  string $level
     * @return void
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }
}