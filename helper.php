<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 */

defined('_JEXEC') or die;

use Joomla\String\StringHelper;

/**
 * Helper for mod_brochure
 */
class ModBrochureHelper
{
    /**
     * Converts CSV to an array
     *
     * @param  string             $csv          CSV to convert
     * @param  bool               $header_keys  Does the CSV contain a header row?
     * @param  mixed (int|false)  $cols         The number of columns each row is expected to have
     *
     * @return  mixed
     */
    public static function csvArray($csv, $header_keys = false, $cols = false)
    {
        if (!is_string($csv)) {
            trigger_error('Function \'csvarray\' expects argument 1 to be an string', E_USER_ERROR);
            return false;
        }
        
        // Normalise new lines:
        trim(preg_replace('~\r\n?~', "\n", $csv));
        
        // Remove breaks from within quotes:
        if (preg_match_all('/"[^"]+"/', $csv, $matches)) {
            foreach($matches[0] as $match) {
                $new = preg_replace('/(\n|\r)/', '{_NEWLINE_}', $match);
                $csv = preg_replace('/' . preg_quote($match, '/') . '/', $new, $csv);
            }
        }
        $csv = utf8_encode($csv);
        $csv_array = explode(PHP_EOL, $csv);
        $data = array();
        $cell_total = 0;
        $row_count = 0;
        $headers = array();
        // Process each line:
        foreach($csv_array as $line) {
            $cell_count = 0;
            $row_count++;
            if (preg_match_all('/"[^"]+"/', $line, $matches)) {
                foreach($matches[0] as $match) {
                    $new = preg_replace('/,/', '{_COMMA_}', $match);
                    $line = preg_replace('/' . preg_quote($match, '/') . '/', $new, $line);
                }
            }
            $line = preg_replace('/""/', '{_QUOTE_}', $line);
            $line = preg_replace('/"/', '', $line);
            $line = preg_replace('/{_QUOTE_}/', '"', $line);
            $line = preg_replace('/,/', '\n', $line);
            $line = preg_replace('/{_COMMA_}/', ',', $line);
            $line = preg_replace('/\s{2,}/', ' ', $line);
            $line = preg_replace('/{_NEWLINE_}/', "\n", $line);
            $cells = explode('\n', $line);
            if (is_int($cols) && $cols > 1) {
                $cells = array_pad($cells, $cols, '');
            }
            $row = array();

            $i = 0;
            foreach($cells as $cell) {
                $cell_count++;
                $cell = trim(htmlentities($cell, ENT_QUOTES));
                if ($row_count == 1) {
                    if (mb_strlen($cell) > 0) {
                        $cell_total = $cell_count;
                    }
                }
                if ($cell_count <= $cell_total) {
                    if ($header_keys && $row_count == 1) {
                        $headers[] = $cell;
                    }
                    if (!$header_keys || $row_count == 1) {
                        $row[] = $cell;
                    } else {
                        $row[$headers[$i]] = $cell;
                    }
                }
                $i++;
            }
            if (!$header_keys || $row_count == 1) {
                $first = 0;
            } else {
                $first = $headers[0];
            }
            if (mb_strlen($row[$first]) > 0) {
                $data[] = $row;
            }
        }
        return $data;
    }
}
