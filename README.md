# PhpUnit resource helper

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![codecov](https://codecov.io/gh/Erwane/phpunit-resource-helper/branch/1.x/graph/badge.svg?token=hF5HhETnkg)](https://codecov.io/gh/Erwane/phpunit-resource-helper)
[![Build Status](https://github.com/Erwane/phpunit-resource-helper/actions/workflows/ci.yml/badge.svg?branch=1.x)](https://github.com/Erwane/phpunit-resource-helper/actions)
[![Packagist Downloads](https://img.shields.io/packagist/dt/Erwane/phpunit-resource-helper)](https://packagist.org/packages/Erwane/phpunit-resource-helper)
[![Packagist Version](https://img.shields.io/packagist/v/Erwane/phpunit-resource-helper)](https://packagist.org/packages/Erwane/phpunit-resource-helper)

Fixtures are for databases, resources for the rest.

Help your phpunit tests to load resources from files, like, json content, raw e-mails, logs file, etc.

## Version map

| branch | This package version | PHP min |       PHPUnit        |
|:------:|----------------------|:-------:|:--------------------:|
|  1.x   | ^1.0                 | PHP 7.2 | ^7.1 \| ^8.0 \| ^9.0 |
|  2.0   | ^2.0                 | PHP 8.0 |     ^8.5 \| ^9.3     | 

## Usage

```shell
composer require --dev erwane/phpunit-resource-helper
```

Create a `resources` directory in your `tests` dir and put your files in. You can add subdirectories.

You can also configure your base directory and tmp directory in your `tests/bootstrap.php` file:

```php
use ResourceHelper\ResourceHelper;

ResourceHelper::setBaseDir('/project/tests_resources/');
ResourceHelper::setTmpDir('/project/tmp/');
```

In your test, you can get your resources path, content or copy with `File` methods:

```php
use ResourceHelper\File;

// Get <project_dir>/tests/resources/webhooks/mailgun.json content
$content = File::getContent('webhooks/mailgun.json');

// Create a copy you can manipulate without destroy your resource.
$copy = File::getCopy('accounting/invoices.csv');
```

You can clean your tmp directory with PHPUnit extension.  
Only successful tests are cleaned, this allows you to check your resources copy files when test failed.  
Set up ResourceHelper extension in your `phpunit.dist.xml` configuration file:

```xml
<!-- phpunit.dist.xml -->
<extensions>
    <extension class="ResourceHelper\PHPUnitExtension"></extension>
</extensions>
```

## `File` Methods

### getPath(string \$path): string

Get resource absolute path from relative `$path`.

```php
$path = File::getPath('file.csv');
// $path = '<project>/tests/resources/my-file.csv'
```

### getInfo(string \$path): array

Get resource information from relative `$path`.

```php
$info = File::getInfo('file.csv');
```

`$info` will contain:

```php
[
    'path' => '/path/file.csv', // Absolute resource path
    'filename' => 'file.csv', // Filename
    'hash' => 'abcdef0123456789', // File hash
]
```

### getCopy(string \$path, TestCase \$test): array

Copy the resource to a temporary directory relative to current test.  
Return the information of this copy.

```php
$info = File::getCopy('file.csv');
```

`$info` will contain:

```php
[
    'path' => '/tmp/project/Test_Method/file.ext', // Absolute resource copy path
    'filename' => 'file.ext', // Filename
    'hash' => 'abcdef0123456789', // File hash
]
```
