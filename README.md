# letto
letto: Lancers Engineers Motto

It is a php tool group to maximize development resources.

## Installation

```bash
$ php composer.phar require lancers/letto:^1.0
```

## Usage
Using the loader you can use one of the provided tools.

```php
use \Letto\Loader as LettoLoader;
$letto = new LettoLoader((bool){isDevelopment});
```

The following can be used with lazy loading.

### ChatWork Notification
e.g.)

```php
$letto->chatwork->setToken({api token});
$letto->chatwork->room({roomId})->info({title}, {message});
```

### Config Loader
e.g.)

```php
// setup load path
$letto->config->addPath('/path/to');
// or
$letto->config->addpath([
    '/path/to/dir',
    '/foo/bar/path/to/dir'
]);

// load (ext: .php .yml .ini)
// default is php
$letto->config->load('letto', 'php');
$config = $letto->config->load('hoge', 'yml');

// can get array data with dot separator.
$className = $letto->config->get('letto.class_load.0', {default params});
```

### Debug Logger
e.g.)

```php
// default dump path: /tmp/letto_debug.log
$letto->debug->log({mixed});

// setup dump file path
$letto->debug->setLogPath('/path/to/file.log');
$letto->debug->log({mixed});
```

The following can be used Chrome Logger.

see: https://craig.is/writing/chrome-logger
