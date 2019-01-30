<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_brochure
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

use Joomla\String\StringHelper;

require_once __DIR__ . '/vendor/autoload.php';


//require_once(JPATH_ROOT . '/administrator/components/com_extras/libraries/Markdown/Markdown.inc.php');
use \Michelf\Markdown;

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

    /**
     * Creates an HTML-friendly string for use in id's
     *
     * @param string $text
     * @return string
     * @access public
     */
    public static function htmlID($text)
    {
        if (!is_string($text)) {
            trigger_error('Function \'html_id\' expects argument 1 to be an string', E_USER_ERROR);
            return false;
        }
        $return = strtolower(trim(preg_replace('/\s+/', '-', self::stripPunctuation($text))));
        return $return;
    }


    /**
     * Strips punctuation from a string
     *
     * @param string $text
     * @return string
     * @access public
     */
    public static function stripPunctuation($text)
    {
        if (!is_string($text)) {
            trigger_error('Function \'strip_punctuation\' expects argument 1 to be an string', E_USER_ERROR);
            return false;
        }
        $text = html_entity_decode($text, ENT_QUOTES);

        $urlbrackets = '\[\]\(\)';
        $urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
        $urlspaceafter = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
        $urlall = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;

        $specialquotes = '\'"\*<>';

        $fullstop = '\x{002E}\x{FE52}\x{FF0E}';
        $comma = '\x{002C}\x{FE50}\x{FF0C}';
        $arabsep = '\x{066B}\x{066C}';
        $numseparators = $fullstop . $comma . $arabsep;

        $numbersign = '\x{0023}\x{FE5F}\x{FF03}';
        $percent = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
        $prime = '\x{2032}\x{2033}\x{2034}\x{2057}';
        $nummodifiers = $numbersign . $percent . $prime;
        $return = preg_replace(
        array(
            // Remove separator, control, formatting, surrogate,
            // open/close quotes.
            '/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
            // Remove other punctuation except special cases
            '/\p{Po}(?<![' . $specialquotes .
            $numseparators . $urlall . $nummodifiers . '])/u',
            // Remove non-URL open/close brackets, except URL brackets.
            '/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
            // Remove special quotes, dashes, connectors, number
            // separators, and URL characters followed by a space
            '/[' . $specialquotes . $numseparators . $urlspaceafter .
            '\p{Pd}\p{Pc}]+((?= )|$)/u',
            // Remove special quotes, connectors, and URL characters
            // preceded by a space
            '/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
            // Remove dashes preceded by a space, but not followed by a number
            '/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
            // Remove consecutive spaces
            '/ +/',
            ), ' ', $text);
        $return = str_replace('/', '_', $return);
        return str_replace("'", '', $return);
    }

    /**
     * Gets a twig instance - useful as we don't have to re-declare customisations each time.
     *
     * @param  array    $tpls   Array of strings bound to template names
     * @return object
     */
    public static function getTwig($tpls)
    {
        $loader = new Twig_Loader_Array($tpls);
        $twig   = new Twig_Environment($loader);

        // Add markdown filter:
        $md_filter = new Twig_SimpleFilter('md', function ($string) {
            $new_string = '';
            // Parse md here
            $new_string = Markdown::defaultTransform($string);
            return $new_string;
        });

        $twig->addFilter($md_filter);
        // Use like {{ var|md|raw }}

        // Add pad filter:
        $pad_filter = new Twig_SimpleFilter('pad', function ($string, $length, $pad = ' ', $type = 'right') {
            $new_string = '';
            switch ($type) {
                case 'right':
                    $type = STR_PAD_RIGHT;
                    break;
                case 'left':
                    $type = STR_PAD_LEFT;
                    break;
                case 'both':
                    break;
                    $type = STR_PAD_BOTH;
            }
            $length = (int) $length;
            $pad    = (string) $pad;
            $new_string = str_pad($string, $length, $pad, $type);

            return $new_string;
        });
        $twig->addFilter($pad_filter);

        // Add regex_replace filter:
        $regex_replace_filter = new Twig_SimpleFilter('regex_replace', function ($string, $search = '', $replace = '') {
            $new_string = '';

            $new_string = preg_replace($search, $replace, $string);

            return $new_string;
        });
        $twig->addFilter($regex_replace_filter);

        // Add html_id filter:
        $html_id_filter = new Twig_SimpleFilter('html_id', function ($string) {
            $new_string = '';

            $new_string = self::htmlID($string);

            return $new_string;
        });
        $twig->addFilter($html_id_filter);


        return $twig;
    }
}
