<?php namespace utilities\Reader;

/**
 * Construct a list of part numbers from a file.
 */
class Reader
{
    /** Reads a file input in YAML format and output an array containing the part numbers.
     * @param string $path The path to the file.
     *
     * @return array
     */
    public function readCSVFile(string $path)
    {
        $content = array();

        if (!file_exists($path)) {
            throw new \Exception("File '" . $path . "' does not seem to exist.", 1);
        }

        $handle = fopen($path, 'r');
        while (($data = fgetcsv($handle, 1000, ';')) !== false) {
            if (isset($data[1])) {
                $content[$data[1]] = $data[0];
            } else {
                $content[$data[0]] = $data[0];
            }
        }

        return $content;
    }

    /** Reads a YAML string and return an array.
     * @param string $input The YAML string to parse.
     *
     * @return array
     */
    public function readFromString(string $input): array
    {
        return yaml_parse($input);
    }
}
