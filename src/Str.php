<?php namespace Origami\Support;

class Str {

    public static function splitName($name) {
        $firstname = $name = trim($name);

        if ( strpos($name, ' ') !== false ) {
            list($firstname, $lastname) =  explode(' ', $name, 2);
        }

        return array($firstname, ( isset($lastname) ? $lastname : NULL ));
    }

    public static function possessive($str) {
        if ( ! $str ) {
            return '';
        }

        if ( substr($str, -1) == 's' ) {
            return $str.'’';
        }

        return $str.'’s';
    }

}