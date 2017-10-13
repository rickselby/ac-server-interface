# Assetto Corsa Sprint Racing Client

This is the client app that goes with the [Assetto Corsa Sprint Racing Server](https://github.com/rickselby/acsr-server).
It handles running an Assetto Corsa server for an event.

## Requirements

* PHP (duh)
    * Curl
* A cron job set up
    * `* * * * * /path/to/artisan schedule:run >> /dev/null 2>&1`
## .env

* `AC_SERVER_SCRIPT=` Path to a copy of [this script](https://github.com/rickselby/AssettoCorsaLinuxScripts)
    * The web user must be able to execute this script
        * Personally I set up a line in sudoers:
        * `www-data ALL=(steam) NOPASSWD: /path/to/assetto-server.sh`
* `AC_SERVER_CONFIG_PATH=` Path to AC server config file directory
    * The web user must be able to write these files
* `AC_SERVER_LOG_PATH=` Path to AC server log file directory
* `AC_SERVER_RESULTS_PATH=` Path to AC server results file directory
* `ACSR_SERVER_URL=` URL to an API willing to accept `POST`ed results
* `ACSR_IP=` IP of the machine that is allowed to make requests to this API

## API

All end points expect JSON and return JSON

* `GET ping`
    * returns `['success' => true]`
* `PUT config`
    * expects `['content' => '']`
    * returns `['updated' => bool]`
* `PUT entrylist`
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
