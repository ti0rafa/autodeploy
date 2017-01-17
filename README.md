# Autodeploy

Process Bitbucket webhook 2.0, please be advise that this is a work in progress.

### Installation

Using composer

```bash
$ composer require tiorafa/autodeploy
```

### Usage

Create a PHP similar to this:

```php
<?php

require_once 'composer/autoload.php'; // autoloader

use AutoDeploy\Handler;

$Handler = new Handler();
$Handler->add('team/repo_name', [
    'branch' => 'master',
    'folder' => 'folder_name',
    'destination' => '/var/www/test/repos'
]);

$Handler->register();

```

Point Bitbucket's webhook to that file.

### Configure user running php

The user running php must be configured correctly. Please make sure to check any of the following steps.

##### Figure who is running php

To figure out who is running run `whoami` in a php script, create a file with the following code:

```
<?php

exec('whoami');
```

Common values are:

- www-data
- apache
- etc.

The following steps will use `www-data` but please change it to what ever user your php is running from.


##### Paths must be writable

Make your sure `destination` is writable for `www-data`

```
$ sudo chown www-data {destination_path}
```

##### Configure ssh

```
$ sudo -u www-data ssh-keygen -t rsa
```

You may need to create a .ssh directory somewhere in the file system with access for `www-data`.
