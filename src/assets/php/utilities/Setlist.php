<?php namespace utilities\Setlist;

/**
 * Construct a list of part numbers from a file.
 */
class Setlist
{
    /**
     * Reads a file input in YAML format and output an array containing the part numbers.
     * @param string $path The path to the file.
     *
     * @return array
     */
    public function readFromFile(string $path): array
    {
        if (!file_exists($path)) {
            throw new \Exception("File '" . $path . "' does not seem to exist.", 1);
        }
        return yaml_parse_file($path);
    }
}
