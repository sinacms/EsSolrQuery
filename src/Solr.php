<?php
namespace EsSolrQuery;

class Solr implements Query {
    public static function value($value){
        return (is_string($value) || $value < 0 ) ? '"'.$value.'"' : $value;
    }
	public static function _not_exists($fieldname) {
		return '-('.$fieldname.':*)';
	}
	public static function _exists($fieldname) {
		return '('.$fieldname.':*)';
	}

    /**
     * @param $fieldname
     * @return string
     */
	public static function _not_empty($fieldname) {
		return $fieldname.':["" TO *]';
	}
    /**
     * @param $fieldname
     * @return string
     */
	public static function _empty($fieldname) {
		return '-'.self::_not_empty($fieldname);
	}
	public static function _and(array $exprs) {
	    if(sizeof($exprs) == 0) return '';
		$expr = implode(' AND ', $exprs);
		return '('.$expr.')';
	}

	public static function _or(array $exprs) {
        if(sizeof($exprs) == 0) return '';
        $expr = implode(' OR ', $exprs);
		return '('.$expr.')';
	}

	public static function _not($expr) {
		return ' NOT '.$expr;
	}

	public static function _eq($fieldname, $value) {
		return $fieldname.':'.self::value($value);
	}

	public static function _gt($fieldname, $value) {
		return $fieldname.':{'.self::value($value).' TO *}';
	}

	public static function _lt($fieldname, $value) {
		return $fieldname.':{* TO '.self::value($value).'}';
	}

	public static function _gteq($fieldname, $value) {
		return $fieldname.':['.self::value($value).' TO *]';
	}

	public static function _lteq($fieldname, $value) {
		return $fieldname.':[* TO '.self::value($value).']';
	}

	public static function _match($fieldname, $value) {
		return $fieldname.':'.self::value($value);
	}

	public static function _in($fieldname, array $values) {
        $exprs = array();
		foreach ($values as $value) {
			$exprs[] = $fieldname.':'.self::value($value);
		}
		$expr = implode(' OR ', $exprs);
		return '('.$expr.')';
	}

}