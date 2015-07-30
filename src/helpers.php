<?php

use Origami\Support\Str;

if ( ! function_exists('str_split_name') ) {
    function str_split_name($name) {
        return Str::splitName($name);
    }
}

if ( ! function_exists('str_possessive') ) {
    function str_possessive($str) {
        return Str::possessive($str);
    }
}

if ( ! function_exists('select_placeholder') ) {
    function select_placeholder(array $options, $placeholder = '-- Select an option --') {
        return [ '' => $placeholder ] + $options;
    }
}

if ( ! function_exists('link_sort') ) {
	function link_sort($key, $label, $default = null) {
		return app('Origami\Support\Filter')->sortByLink($key, $label, $default);	
	}
}