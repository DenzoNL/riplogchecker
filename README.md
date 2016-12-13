# Rip Log Checker [![Build Status](https://travis-ci.org/DenzoNL/riplogchecker.svg?branch=master)](https://travis-ci.org/DenzoNL/riplogchecker) [![StyleCI](https://styleci.io/repos/75951674/shield?branch=master)](https://styleci.io/repos/75951674)
A CD rip log checker package. Currently only supports EAC rip logs.

## Notice - Alpha software
This software is still very much in the alpha stage. It works as a proof
of concept, but the checks that have been implemented, have been imported
poorly. When you find an issue, please report it here on GitHub so it can be fixed.

## TODO
* Implement scoring based on EAC-logs parsed into Json format

* Test Json Parser thoroughly and implement exceptions / checks for older logs.

* Colored output in both CLI and HTML.

## Installation

### With Composer
```
$ composer require denzo/riplogchecker
```
## Usage

## Command line interface

Basic usage

``` php
$ php riplogchecker path/to/log/file.log
```

Output the score AND the resulting Json
``` php
$ php riplogchecker path/to/log/file.log --json
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

/* Retrieve the Json */
$logChecker->getScorer->getParser()->getJson();
```
