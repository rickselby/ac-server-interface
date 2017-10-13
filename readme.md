# Assetto Corsa Server Client

![PHP 7.0+](https://img.shields.io/badge/php-7.0%2B-blue.svg)
[![Build Status](https://travis-ci.org/rickselby/ac-server-interface.svg?branch=travis)](https://travis-ci.org/rickselby/ac-server-interface)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rickselby/ac-server-interface/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rickselby/ac-server-interface/?branch=master)
[![Style CI](https://styleci.io/repos/75005750/shield?style=plastic)](https://styleci.io/repos/75005750/)

This is the client app that goes with... a master app to control a number of clients. To be created.

It's a web interface to control an assetto corsa server.

## Requirements

* PHP, apache/nginx/whatever takes your fancy
* A cron job set up
    * `* * * * * /path/to/artisan schedule:run >> /dev/null 2>&1`
## .env

* `AC_SERVER_SCRIPT=` Path to a copy of [this script](https://github.com/rickselby/AssettoCorsaLinuxScripts)
    * The web user must be able to execute this script
        * Personally I set up a line in sudoers:
        * `www-data ALL=(steam) NOPASSWD: /path/to/assetto-server.sh`
* `AC_SERVER_ROOT=` Path to AC server root
    * The web user must be able to write to the config files
* `MASTER_SERVER_URL=` URL to an API willing to accept `POST`ed results
* `MASTER_IP=` IP of the machine that is allowed to make requests to this API

## API

All end points expect JSON and return JSON

* `GET ping`
    * returns `['success' => true]`
* `PUT config/server`
    * expects `['content' => '']`
    * returns `['updated' => bool]`
* `PUT config/entry-list`
    * expects `['content' => '']`
    * returns `['updated' => bool]`
* `PUT start`
    * returns `['success' => bool]`
* `PUT stop`
    * returns `['success' => bool]`
* `GET running`
    * returns `['running' => bool]`
* `GET results/latest`
    * returns `['results' => string]`
* `GET results/all`
    * returns `['filename' => string, 'filename' => string...]`
* `GET log/server`
    * returns `['log' => string]`
* `GET log/system`
    * returns `['filename' => string, 'filename' => string...]`
