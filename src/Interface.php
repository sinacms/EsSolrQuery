<?php
/**
 * 声明查询类接口
 */
namespace EsSolrQuery;

interface Query {

	public static function _and($exprs);

	public static function _or($exprs);

	public static function _not($expr);

	public static function _eq($fieldname, $value);

	public static function _gt($fieldname, $value);

	public static function _lt($fieldname, $value);

	public static function _gteq($fieldname, $value);

	public static function _lteq($fieldname, $value);

	public static function _match($fieldname, $value);

	public static function _in($fieldname, $values);

}