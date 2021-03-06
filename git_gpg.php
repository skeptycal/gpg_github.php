#!/usr/bin/env php
<?php
/**
 * Returns the gpg secret key ready for commit signing with Git.
 *
 * This contains the gpg secret key ready for commit signing with Git. Uses a
 * regex based function to extract the gpg secret key. This will select only
 * the first matching key.
 *
 * Tested with:
 *      - PHP version 7.3.5 (cli)
 *      - gpg (GnuPG) 2.2.15
 *      - libgcrypt 1.8.4
 *      - macOS 10.14.5
 *
 * GPG is licensed under GPLv3+
 * License GPLv3+: GNU GPL version 3 or later
 * https://www.gnu.org/licenses/gpl-3.0.html
 * This is free software: you are free to change and redistribute it.
 * There is NO WARRANTY, to the extent permitted by law.
 *
 * @package   CLI_Utilities
 * @author    Michael Treanor  <skeptycal@gmail.com>
 * @copyright 2018 (C) Michael Treanor
 * @license   GPLv3+: GNU GPL version 3 or later <https://gnu.org/licenses/gpl.html>
 * @version   GIT: https://www.github.com/skeptycal/git_gpg
 * @link      https://github.com/skeptycal
 * @return    $gpg_out[1] - string
 */

/**
 * Returns the gpg secret key for the current user.
 *
 * @return string
 */
function funcGetGpg()
{
    preg_match_all(
        '/^.*sec.{3}rsa4096\/(\w{16}+)/im',
        `gpg --list-secret-keys --keyid-format LONG`,
        $gpg_out
    );
    return implode('', $gpg_out[1]);
}

if (strpos(php_sapi_name(), 'cli') !== false) {
    // loop through each element in the $argv array
    // dummy code ... really
    foreach ($argv as $value) {
        echo "$value\n";
    }

    // CLI output with warning
    echo "\n\e[0;31m > GPG Secret (Do Not Share This!!): \e[0m";
    echo funcGetGpg(), "\n\n";
    return true; // exit with resource unchanged.
}

/*
 * References:
 * ***************************************************************************
 * Based on GitHub instructions "Telling Git about your signing key"
 * https://help.github.com/articles/telling-git-about-your-signing-key/
 *
 * And: "Generating a new GPG key"
 * https://help.github.com/articles/generating-a-new-gpg-key/
 *
 * This works with 4096 keys:
 *   if you wish to use it with other keys,
 *   replace 'rsa4096' in $pattern with the appropriate number
 */

/*
 * Changelog:
 * ***************************************************************************
 * 1.3 Formatting (pass phpcs and phpcbf with php-cs-fixer)
 *
 * 1.2 Add color CLI message with warning
 *
 * 1.1 Refactored and created a function that returns the value
 * instead of a local string. Added CLI codeblock to echo value
 * if run from command line
 *
 * 1.0 Creates a variable that contains the extracted gpg private
 * code from the local key.
 */
