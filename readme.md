# Swatch

Helps you collect various information regarding your project or environment and sends it to different handlers.

## Installation

Install using `composer require alexandrubau/swatch`.

## Basic usage

Create a file called `swatch.json` with content:
 
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

Soon to come.

### Authors

- Alexandru Bau [[Github](https://github.com/alexandrubau)]

### License

Swatch is licensed under the MIT License - see the LICENSE file for details.

### Acknowledgements

This library is heavily inspired by [Composer](https://github.com/composer/composer) and [Monolog](https://github.com/Seldaek/monolog) PHP libraries.