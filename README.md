# mezzio-httphandlerrunner-roadrunner

[![Build Status](https://github.com/boesing/mezzio-httphandlerrunner-roadrunner/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/boesing/mezzio-httphandlerrunner-roadrunner/actions/workflows/continuous-integration.yml)

This library provides an implementation to run mezzio via `spiral/roadrunner`.

## Installation

Run the following to install this library:

```bash
$ composer require boesing/mezzio-httphandlerrunner-roadrunner
```

### Creating roadrunner configuration

```yaml
server:
  command: "php -dopcache.enable_cli=1 -dopcache.validate_timestamps=0 vendor/bin/laminas roadrunner:run"

http:
  address: "localhost:8080"
  pool:
    num_workers: 8

logs:
  mode: production
  channels:
    http:
      level: info # Log all http requests, set to info to disable
    server:
      level: debug # Everything written to worker stderr is logged
```

### Retrieve roadrunner binary

To be able to run roadrunner, you need to fetch the roadrunner binary. Use the following command to do so for your environment:
`vendor/bin/rr get-binary`

**NOTE: By doing so, a `rr` binary will be fetched to your current working directory. That binary only works for your current environment. So when you execute that command on MacOS, you will receive a binary prepared to run on MacOS and which is not compatible with Ubuntu for example.**


## Run

To run your project, simply execute the binary fetched in the [Retrieve roadrunner binary installation step](#retrieve-roadrunner-binary):

`./rr serve`

This should ramp up the amount of workers configured in the [Creating roadrunner configuration installation step](#creating-roadrunner-configuration) and listens to the configured port.

## Support

* [Issues](https://github.com/boesing/mezzio-httphandlerrunner-roadrunner/issues/)
