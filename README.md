# XMPPLogger
This is simple PSR3 compliant XMPPLogger for PHP. Uses [Fabiang XMPP client](https://github.com/fabiang/xmpp) for sending messages.
## System requirements
PHP >= 5.3

## Usage:
At first you need to set up xmpp connection:
```
use Fabiang\Xmpp\Options;
use Fabiang\Xmpp\Client;
$options = new Options('tcp://example.com:5222');
$options->setUsername('twilight')->setPassword('sparkle');
$client = new Client($options);
```
Now you can create logger
```
use \thesaturn\xmpplogger\XMPPLogger;
$log = new XMPPLogger($client, $config['xmpp']['to'], 'debug');
$log->info("Hello log");
```
## Installation
Add following lines to the composer:
```
"require": {
    "thesaturn/xmpplogger": "1.0"
}
```
