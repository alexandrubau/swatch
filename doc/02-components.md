## Collectors

A collector gathers information regarding a certain aspect of the application or environment and sends it down the pipeline in order to be handled.

- [PhpCollector](src/Swatch/Collector/PhpCollector.php): Collects information regarding PHP.

## Formatters

Converts the report in the appropriate format for the handler.

- [JsonFormatter](src/Swatch/Formatter/JsonFormatter.php): Outputs the report in JSON format.

## Handlers

A handler stores the report for further processing or interpretation.

- [StreamHandler](src/Swatch/Handler/StreamHandler.php): Writes reports into any PHP stream.