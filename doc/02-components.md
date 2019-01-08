## Collectors

Collectors gather information regarding a certain aspect of the application or environment.

- [PhpCollector](../src/Swatch/Collector/PhpCollector.php): Collects information regarding PHP.

## Formatters

Formatters convert the resulting information from collectors, in the appropriate format for the handler.

- [JsonFormatter](../src/Swatch/Formatter/JsonFormatter.php): Converts the resulting information in JSON format.

## Handlers

Handlers store the information for further processing or interpretation.

- [StreamHandler](../src/Swatch/Handler/StreamHandler.php): Writes resulting information into any PHP stream.