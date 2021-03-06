<?php

namespace App\Core\Utils;

class Str
{
    static $plural = [
        '/(quiz)$/i'               => "$1zes",
        '/^(ox)$/i'                => "$1en",
        '/([m|l])ouse$/i'          => "$1ice",
        '/(matr|vert|ind)ix|ex$/i' => "$1ices",
        '/(x|ch|ss|sh)$/i'         => "$1es",
        '/([^aeiouy]|qu)y$/i'      => "$1ies",
        '/(hive)$/i'               => "$1s",
        '/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
        '/(shea|lea|loa|thie)f$/i' => "$1ves",
        '/sis$/i'                  => "ses",
        '/([ti])um$/i'             => "$1a",
        '/(tomat|potat|ech|her|vet)o$/i'=> "$1oes",
        '/(bu)s$/i'                => "$1ses",
        '/(alias)$/i'              => "$1es",
        '/(octop)us$/i'            => "$1i",
        '/(ax|test)is$/i'          => "$1es",
        '/(us)$/i'                 => "$1es",
        '/s$/i'                    => "s",
        '/$/'                      => "s"
    ];
    
    static $singular = [
        '/(quiz)zes$/i'             => "$1",
        '/(matr)ices$/i'            => "$1ix",
        '/(vert|ind)ices$/i'        => "$1ex",
        '/^(ox)en$/i'               => "$1",
        '/(alias)es$/i'             => "$1",
        '/(octop|vir)i$/i'          => "$1us",
        '/(cris|ax|test)es$/i'      => "$1is",
        '/(shoe)s$/i'               => "$1",
        '/(o)es$/i'                 => "$1",
        '/(bus)es$/i'               => "$1",
        '/([m|l])ice$/i'            => "$1ouse",
        '/(x|ch|ss|sh)es$/i'        => "$1",
        '/(m)ovies$/i'              => "$1ovie",
        '/(s)eries$/i'              => "$1eries",
        '/([^aeiouy]|qu)ies$/i'     => "$1y",
        '/([lr])ves$/i'             => "$1f",
        '/(tive)s$/i'               => "$1",
        '/(hive)s$/i'               => "$1",
        '/(li|wi|kni)ves$/i'        => "$1fe",
        '/(shea|loa|lea|thie)ves$/i'=> "$1f",
        '/(^analy)ses$/i'           => "$1sis",
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'  => "$1$2sis",        
        '/([ti])a$/i'               => "$1um",
        '/(n)ews$/i'                => "$1ews",
        '/(h|bl)ouses$/i'           => "$1ouse",
        '/(corpse)s$/i'             => "$1",
        '/(us)es$/i'                => "$1",
        '/s$/i'                     => ""
    ];
    
    static $irregular = [
        "move"   => "moves",
        "foot"   => "feet",
        "goose"  => "geese",
        "sex"    => "sexes",
        "child"  => "children",
        "man"    => "men",
        "tooth"  => "teeth",
        "person" => "people",
        "valve"  => "valves"
    ];
    
    static $uncountable = [ 
        "sheep", 
        "fish",
        "deer",
        "series",
        "species",
        "money",
        "rice",
        "information",
        "equipment"
    ];
    
    public static function pluralize(string $string) 
    {
        if (in_array(strtolower($string), self::$uncountable))
            return $string;

        foreach (self::$irregular as $pattern => $result){
            $pattern = "/" . $pattern . "$/i";
            
            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }
        
        foreach (self::$plural as $pattern => $result){
            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }
        
        return $string;
    }
    
    public static function singularize(string $string )
    {
        if (in_array(strtolower($string), self::$uncountable))
            return $string;

        foreach (self::$irregular as $result => $pattern){
            $pattern = "/" . $pattern . "$/i";
            
            if (preg_match($pattern, $string))
                return preg_replace( $pattern, $result, $string);
        }
        
        foreach (self::$singular as $pattern => $result){
            if (preg_match( $pattern, $string))
                return preg_replace($pattern, $result, $string);
        }
        
        return $string;
    }
    
    public static function pluralize_if($count, $string)
    {
        if ($count == 1)
            return "1 $string";
        else
            return $count . " " . self::pluralize($string);
    }

    public static function startsWith($string, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== "" && substr($string, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }

    public static function endsWith($string, $needles)
    {
        foreach ((array) $needles as $needle) {
            if (substr($string, -strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }

    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    public static function snake($value, $delimiter = '_')
    {
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));
            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));
        }

        return $value;
    }

    public static function replaceArray($search, array $replace, $subject)
    {
        foreach ($replace as $value) {
            $subject = static::replaceFirst($search, $value, $subject);
        }

        return $subject;
    }

     public static function replaceFirst($search, $replace, $subject)
    {
        if ($search == "")
            return $subject;

        $position = strpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }
}