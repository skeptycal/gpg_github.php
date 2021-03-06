<?php
/**
* Get options from the command line or web request.
*
* Reference: https://php.net/manual/en/function.getopt.php
*
* @param string $options
* @param array $longopts
* @return array
*/
function get_opt_req($options, $longopts)
{
    if (PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR'])) {  // command line
        return getopt($options, $longopts);
    } elseif (isset($_REQUEST)) {  // web script
        $found = [];

        $shortopts = preg_split('@([a-z0-9][:]{0,2})@i', $options, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $opts = array_merge($shortopts, $longopts);

        foreach ($opts as $opt) {
            if (substr($opt, -2) === '::') {  // optional
                $key = substr($opt, 0, -2);

                if (isset($_REQUEST[$key]) && !empty($_REQUEST[$key])) {
                    $found[$key] = $_REQUEST[$key];
                } elseif (isset($_REQUEST[$key])) {
                    $found[$key] = false;
                }
            } elseif (substr($opt, -1) === ':') {  // required value
                $key = substr($opt, 0, -1);

                if (isset($_REQUEST[$key]) && !empty($_REQUEST[$key])) {
                    $found[$key] = $_REQUEST[$key];
                }
            } elseif (ctype_alnum($opt)) {  // no value
                if (isset($_REQUEST[$opt])) {
                    $found[$opt] = false;
                }
            }
        }

        return $found;
    }

    return false;
}
