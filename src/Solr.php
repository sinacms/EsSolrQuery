<?php
namespace EsSolrQuery;

class Solr implements Query {
	public static function _and($exprs) {
		$expr = implode(' AND ', $exprs);
		return '('.$expr.')';
	}

	public static function _or($exprs) {
		$expr = implode(' OR ', $exprs);
		return '('.$expr.')';
	}

	public static function _not($expr) {
		return ' NOT '.$expr;
	}

	public static function _eq($fieldname, $value) {
		return $fieldname.':'.$value;
	}

	public static function _gt($fieldname, $value) {
		return $fieldname.':{'.$value.' TO *}';
	}

	public static function _lt($fieldname, $value) {
		return $fieldname.':{* TO '.$value.'}';
	}

	public static function _gteq($fieldname, $value) {
		return $fieldname.':['.$value.' TO *]';
	}

	public static function _lteq($fieldname, $value) {
		return $fieldname.':[* TO '.$value.']';
	}

	public static function _match($fieldname, $value) {
		return $fieldname.':'.$value;
	}

	public static function _in($fieldname, $values) {
		$exprs = array();
		foreach ($values as $value) {
			$exprs[] = $fieldname.':'.$value;
		}
		$expr = implode(' OR ', $exprs);
		return '('.$expr.')';
	}

}