<?php

namespace Swatch;

use JsonSchema\Validator;

/**
 * Class Config
 *
 * @author Alexandru Bau <alexandru.bau@gmail.com>
 */
class Config
{
    const SCHEMA_PATH = 'res/swatch-schema.json';

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    private $data;

    /**
     * Config constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Reads the configuration.
     *
     * @return array
     */
    public function read(): array
    {
        if ($this->data) {

            return $this->data;
        }

        $content = $this->getContent();

        $json = $this->parse($content);

        $this->validate($content);

        return $this->data = $json;
    }

    /**
     * Transforms the configuration.
     *
     * @param string $data
     * @return array
     */
    protected function parse(string $data): array
    {
        $json = json_decode($data, true);

        if (is_null($json) && json_last_error() !== JSON_ERROR_NONE) {

            $error = json_last_error_msg();

            $msg = sprintf('The file "%s" does not contain valid JSON: ' . $error, $this->path);

            throw new \InvalidArgumentException($msg);
        }

        return $json;
    }

    /**
     * Validates the configuration.
     *
     * @param string $content
     */
    protected function validate(string $content): void
    {
        $json = json_decode($content);

        $schema = $this->getSchema();

        $validator = new Validator();

        $validator->validate($json, $schema);

        if ($validator->isValid()) {

            return;
        }

        $errors = [];

        foreach ($validator->getErrors() as $error) {

            $errors[] = sprintf("[%s] %s", $error['property'], $error['message']);
        }

        $list = implode(PHP_EOL, $errors);

        $msg = sprintf('"%s" does not match the expected JSON schema:' . PHP_EOL . $list, $this->path);

        throw new \InvalidArgumentException($msg);
    }

    /**
     * Retrieves content from the config file.
     *
     * @return string
     */
    private function getContent(): string
    {
        if (!file_exists($this->path)) {

            $error = sprintf('There is no config file at "%s"', $this->path);

            throw new \RuntimeException($error);
        }

        $content = file_get_contents($this->path);

        if (!$content) {

            $error = sprintf('Could not read config from "%s"', $this->path);

            throw new \RuntimeException($error);
        }

        return $content;
    }

    /**
     * Retrieves the schema used for validation.
     *
     * @return object
     */
    private function getSchema(): object
    {
        $path = 'file://' . __DIR__ . '/../../' . static::SCHEMA_PATH;

        $schema = (object)['$ref' => $path];

        return $schema;
    }
}