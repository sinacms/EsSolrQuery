<?php
namespace EsSolrQuery;

class Query_ES implements Query_Interface {

	public static function specialCharsFilter($str) {
		$chars = ['+', '-', '&', '|', '!', '(', ')',
			'{', '}', '[', ']', '^', '"', '~',
			'*', '?', ':', '\\',
		];
		foreach ($chars as $val) {
			$str = str_replace('\\'.$val, $val, $str);
		}

		return addcslashes($str, '+-&|!(){}[]^"~*?:\\');
	}

	public static function keyCharsFilter($key) {
		return preg_replace('/[^0-9a-zA-Z_]*/', '', $key);
	}

	public static function _and($exprs) {
		$expr = implode(' AND ', $exprs);
		return '('.$expr.')';
	}

	public static function _or($exprs) {
		$expr = implode(' OR ', $exprs);
		return '('.$expr.')';
	}

	public static function _not($expr) {
		return ' NOT '.self::specialCharsFilter($expr);
	}

	//精准匹配
	public static function _eq($fieldname, $value) {
		return '_NA_'.self::keyCharsFilter($fieldname).':'.self::specialCharsFilter($value);
	}

	public static function _gt($fieldname, $value) {
		return self::keyCharsFilter($fieldname).':{'.self::specialCharsFilter($value).' TO *}';
	}

	public static function _lt($fieldname, $value) {
		return self::keyCharsFilter($fieldname).':{* TO '.self::specialCharsFilter($value).'}';
	}

	public static function _gteq($fieldname, $value) {
		return self::keyCharsFilter($fieldname).':['.self::specialCharsFilter($value).' TO *]';
	}

	public static function _lteq($fieldname, $value) {
		return self::keyCharsFilter($fieldname).':[* TO '.self::specialCharsFilter($value).']';
	}

	public static function _match($fieldname, $value) {
		return self::keyCharsFilter($fieldname).':'.self::specialCharsFilter($value);
	}

	public static function _in($fieldname, $values) {
		$fieldname = self::keyCharsFilter($fieldname);
		$exprs     = array();
		foreach ($values as $value) {
			$exprs[] = $fieldname.':'.self::specialCharsFilter($value);
		}
		$expr = implode(' OR ', $exprs);
		return '('.$expr.')';
	}

}