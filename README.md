# XMPPLogger
This is simple PSR3 compliant XMPPLogger for PHP. Uses [Fabiang XMPP client](https://github.com/fabiang/xmpp) for sending messages.
## Usage:
At first you need to set up xmpp connection:
```
use Fabiang\Xmpp\Options;
use Fabiang\Xmpp\Client;
$options = new Options('tcp://example.com:5222');
$options->setUsername('saturn')->setPassword('saturn');
$client = new Client($options);
```
Now you can create logger
```
use \thesaturn\xmpplogger\XMPPLogger;
$log = new XMPPLogger($client, $config['xmpp']['to']);
$log->info("hello log");
```
## Instalation
Add a string to the composer:
```
"require": {
    "thesaturn/xmpplogger": "1.0"
}
```
