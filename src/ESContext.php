<?php
namespace EsSolrQuery;

require_once 'Query/SearchQueryContext.php';
/**
 * Created by PhpStorm.
 * User: zhongyuan5 zhaoqing2
 * Date: 2014/12/26
 * Time: 11:04
 */

class Query_ESContext implements Query_SearchQueryContext {
	public function field($v, $op) {
		switch ($op) {
			case Query_SearchQueryContext::OP_EQ:
				return '_NA_'.$v;
		}
		return $v;
	}

	public function value($v, $op) {
		return addcslashes($v, '+-&|!(){}[]^"~*?:\\');
	}
}