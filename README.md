# Rip Log Checker
A CD rip log checker package. Currently only supports EAC rip logs.

## Installation

### With Composer
```
$ composer require denzo/riplogchecker
```
## Usage

## Command line interface

```
$ php riplogchecker path/to/log/file.log
```

### In code
```php
/* Make sure we use the RipLogChecker class*/
use RipLogChecker\RipLogChecker;

/* Load a log file */
$logFile = file_get_contents('eac_rip.log');

/* Create new logchecker object */
$logChecker = new RipLogChecker($logFile);

/* Retrieve the log score */
$score = $logChecker->getScore();
```