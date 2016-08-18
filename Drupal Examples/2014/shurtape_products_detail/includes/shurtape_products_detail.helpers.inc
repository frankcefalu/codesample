<?php
/**
 * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName, or first name -&gt; firstName)
 * @param    string   $str                     String in underscore format
 * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
 * @return   string                              $str translated into camel caps
 */
function _u2cc($str) {
	return preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '_', strtolower($str)));
}
?>