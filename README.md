# PhpUnit resource helper

Fixtures are for databases, resources for the rest.

Help your phpunit tests to load resources from files, like, json content, raw e-mails, logs file, etc.

## Usage

```shell
composer require --dev erwane/phpunit-resource-helper
```

Create a `resources` directory in your `tests` dir and put your files in. You can add subdirectories.

```php
use ResourceHelper\File;

// Get <project_dir>/tests/resources/webhooks/mailgun.json content
$content = File::instance()->getContent('webhooks/mailgun.json');

// Create a copy you can manipulate without destroy your resource.
$copy = File::instance()->getCopy('emails/change-my-headers.eml');
```

## Methods
