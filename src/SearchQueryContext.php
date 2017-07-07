<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 2014/12/26
 * Time: 10:45
 */
namespace EsSolrQuery;

interface Query_SearchQueryContext {
	const OP_AND   = 'AND';
	const OP_OR    = 'OR';
	const OP_GT    = 'GT';
	const OP_LT    = 'LT';
	const OP_GTEQ  = 'GTEQ';
	const OP_LTEQ  = 'LTEQ';
	const OP_NOT   = 'NOT';
	const OP_IN    = 'IN';
	const OP_MATCH = 'MATCH';
	const OP_EQ    = 'EQ';
	public function field($v, $op);
	public function value($v, $op);
}
