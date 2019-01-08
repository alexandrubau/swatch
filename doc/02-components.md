## Collectors

Collectors gather information regarding a certain aspect of the application or environment and write them in the report.

- [PhpCollector](src/Swatch/Collector/PhpCollector.php): Collects information regarding PHP.

## Formatters

Formatters convert the report in the appropriate format for the handler.

- [JsonFormatter](src/Swatch/Formatter/JsonFormatter.php): Outputs the report in JSON format.

## Handlers

Handlers store the report for further processing or interpretation.

- [StreamHandler](src/Swatch/Handler/StreamHandler.php): Writes reports into any PHP stream.