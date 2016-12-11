# Rip Log Checker [![Build Status](https://travis-ci.org/DenzoNL/riplogchecker.svg?branch=master)](https://travis-ci.org/DenzoNL/riplogchecker)
A CD rip log checker package. Currently only supports EAC rip logs.

## Notice - Alpha software
This software is still very much in the alpha stage. It works as a proof
of concept, but the checks that have been implemented, have been imported
poorly. When you find an issue, please report it here on GitHub so it can be fixed.

## TODO
* Implement proper, reliable regexes. The current regexes simply do a hard
 check for a certain key value pair, they do not parse the actual values for a given key.
 
* As a result of the previously mentioned TODO, parse EAC logs into a JSON object
 so other software can easily parse it.

* Per-track CRC mismatch detection. It currently just checks if there is any. It
  doesn't list it per track.
  
* Implementation of additional checks such as test-and-copy, accurate rip scores.

* Colored output in both CLI and HTML.

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
