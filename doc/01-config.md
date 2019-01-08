## Config

Swatch can run from command line using a config file in JSON format. Create a file called `swatch.json` and place it in the root directory of your project.

You can then run `./vendor/bin/swatch report <config>`. By default it will look for the `swatch.json` config file in the current working directory.

### Name

You must specify a name for your report. This is useful if you plan using Swatch on several applications or environments.

```
{
    "name": "MyApp.dev"
}
```

### Collectors, formatters and handlers

All components can be defined by using either a string or an object.

```
{
    "collectors": {
        "Foo": "Swatch\\Collector\\PhpCollector"
    }
}
```

Or, if the component has options available:

```
{
    "handlers": {
        "Baz": {
            "class": "My\\Custom\\Handler",
            "option1": true,
            "option2": false
        }
    }
}
```