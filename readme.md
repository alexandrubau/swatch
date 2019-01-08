# Swatch

Collects various information regarding your project or environment and sends it to different handlers.

## Installation

Install using `composer require alexandrubau/swatch`.

## Basic usage

Create a file called `swatch.json`:
 
```
{
    "name": "MyApp",
    "collectors": {
        "Foo": {
            "class": "Swatch\\Collector\\PhpCollector"
        }
    },
    "formatters": {
        "Bar": {
            "class": "Swatch\\Formatter\\JsonFormatter",
            "pretty": true
        }
    },
    "handlers": {
        "Baz": {
            "class": "Swatch\\Handler\\StreamHandler",
            "formatter": "Bar",
            "stream": "/tmp/test.log"
        }
    }
}
```

Run `./vendor/bin/swatch report` command.


## Documentation

- [Command line config file](doc/01-config.md)
- [Collectors, formatters and handlers](doc/02-components.md)
- [Extending components](doc/03-extend.md)

### Authors

- Alexandru Bau [[Github](https://github.com/alexandrubau)]

### Contributing

Any pull requests are welcome. A full development environment built using Vagrant and Ansible is available [here](https://github.com/alexandrubau/swatch-dev).

### License

Swatch is licensed under the MIT License - see the LICENSE file for details.

### Acknowledgements

This library is heavily inspired by [Composer](https://github.com/composer/composer) and [Monolog](https://github.com/Seldaek/monolog) PHP libraries.