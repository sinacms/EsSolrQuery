<?php
/**
 * Created by PhpStorm.
 * User: zhongyuan5 zhaoqing2
 * Date: 2014/12/26
 * Time: 10:28
 */
namespace EsSolrQuery;

require_once 'Query/SearchQueryContext.php';

abstract class Query_LogicalExpression {
	/**
	 * @param Query_LogicalExpression[]
	 * @return Query_AndExpression
	 */
	public static function createAnd($exps) {
		return new Query_AndExpression($exps);
	}

	public static function createEq($field, $value) {
		return new Query_EqExpression($field, $value);
	}

	/**
	 * @param Query_LogicalExpression[] $exps
	 * @return Query_OrExpression
	 */
	public static function createOr($exps) {
		return new Query_OrExpression($exps);
	}

	public static function createNot($v) {
		return new Query_NotExpression($v);
	}

	public static function createGt($field, $value) {
		return new Query_GtExpression($field, $value);
	}

	public static function createLt($field, $value) {
		return new Query_LtExpression($field, $value);
	}

	public static function createGteq($field, $value) {
		return new Query_GteqExpression($field, $value);
	}

	public static function createLteq($field, $value) {
		return new Query_LteqExpression($field, $value);
	}

	public static function createMatch($field, $value) {
		return new Query_MatchExpression($field, $value);
	}

	public static function createIn($field, $values) {
		return new Query_InExpression($field, $values);
	}

	abstract public function getQueryString(Query_SearchQueryContext $context);
}

class Query_AndExpression extends Query_LogicalExpression {
	/**
	 * @var Query_LogicalExpression[]
	 */
	private $_subs = [];
	public function __construct($subExpressions) {
		$this->_subs = $subExpressions;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		$expStrings = [];
		foreach ($this->_subs as $sub) {
			$expStrings[] = $sub->getQueryString($context);
		}
		return '('.join(' AND ', $expStrings).')';
	}
}

class Query_EqExpression extends Query_LogicalExpression {
	private $_field;
	private $_value;
	public function __construct($field, $value) {
		$this->_field = $field;
		$this->_value = $value;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		return $context->field($this->_field, Query_SearchQueryContext::OP_EQ).":".$context->value($this->_value, Query_SearchQueryContext::OP_EQ);
	}
}

class Query_OrExpression extends Query_LogicalExpression {
	private $_subs = [];
	public function __construct($subExpressions) {
		$this->_subs = $subExpressions;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		$expStrings = [];
		foreach ($this->_subs as $sub) {
			$expStrings[] = $sub->getQueryString($context);
		}
		return '('.join(' OR ', $expStrings).')';
	}
}

class Query_NotExpression extends Query_LogicalExpression {

	private $_value;
	public function __construct($value) {
		$this->_value = $value;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		return 'NOT '.$context->value($this->_value, Query_SearchQueryContext::OP_NOT);
	}
}

class Query_GtExpression extends Query_LogicalExpression {

	private $_field;
	private $_value;
	public function __construct($field, $value) {
		$this->_field = $field;
		$this->_value = $value;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		return $context->field($this->_field, Query_SearchQueryContext::OP_GT)
		.':{'.$context->value($this->_value, Query_SearchQueryContext::OP_EQ).' TO *}';
	}
}

class Query_LtExpression extends Query_LogicalExpression {
	private $_field;
	private $_value;
	public function __construct($field, $value) {
		$this->_field = $field;
		$this->_value = $value;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		return $context->field($this->_field, Query_SearchQueryContext::OP_LT).':{* TO '.$context->value($this->_value, Query_SearchQueryContext::OP_LT).'}';
	}
}

class Query_GteqExpression extends Query_LogicalExpression {

	private $_field;
	private $_value;
	public function __construct($field, $value) {
		$this->_field = $field;
		$this->_value = $value;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		return $context->field($this->_field, Query_SearchQueryContext::OP_GTEQ).':['.$context->value($this->_value, Query_SearchQueryContext::OP_GTEQ).' TO *]';
	}
}

class Query_LteqExpression extends Query_LogicalExpression {

	private $_field;
	private $_value;
	public function __construct($field, $value) {
		$this->_field = $field;
		$this->_value = $value;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		return $context->field($this->_field, Query_SearchQueryContext::OP_LTEQ).':[* TO '.$context->value($this->_value, Query_SearchQueryContext::OP_LTEQ).']';
	}
}

class Query_MatchExpression extends Query_LogicalExpression {

	private $_field;
	private $_value;
	public function __construct($field, $value) {
		$this->_field = $field;
		$this->_value = $value;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		return $context->field($this->_field, Query_SearchQueryContext::OP_MATCH).':'.$context->value($this->_value, Query_SearchQueryContext::OP_MATCH);
	}
}

class Query_InExpression extends Query_LogicalExpression {
	private $_field;
	private $_values;

	/**
	 * @param string $field
	 * @param  array $values
	 */
	public function __construct($field, $values) {
		$this->_field  = $field;
		$this->_values = $values;
	}

	public function getQueryString(Query_SearchQueryContext $context) {
		$exprs = [];
		foreach ($this->_values as $value) {
			$exprs[] = $context->field($this->_field, Query_SearchQueryContext::OP_IN).':'.$context->value($value, Query_SearchQueryContext::OP_IN);
		}
		$expr = implode(' OR ', $exprs);
		return '('.$expr.')';
	}
}
