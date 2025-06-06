<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
	
	/**
	* Prepare paginationfor the data
	*
	* @return View
	*/
	public function preparePaginationData($request, $current_page_count, $total_count)
	{
		$meta = [];
		$meta['total']   = $total_count;
		$meta['perpage'] = isset($request->perpage) ? $request->perpage : 10;
		$meta['pages']   = ceil($total_count/$meta['perpage']);
		$meta['field']   = isset($request->sort_field) ? $request->sort_field : 'id';
		$meta['sort']    = isset($request->sort_order) ? $request->sort_order : 'desc';
		$meta['page']    = isset($request->page) ? $request->page : 1;
		$meta['current_page_count']    = $current_page_count->count();
		$meta['request']	= json_encode($request->all());
		
		return view("common-partials.pagination.pagination", compact('meta'))->render();
	}
	
	public function prepareApiPaginationData($request, $current_page_count, $total_count)
	{
		$meta = [];
		$meta['total']   = $total_count;
		$meta['perpage'] = isset($request->perpage) ? $request->perpage : 10;
		$meta['pages']   = ceil($total_count/$meta['perpage']);
		$meta['page']    = isset($request->page) ? $request->page : 1;
		$meta['current_page_record_count'] = $current_page_count->count();
		return $meta;
	}
	
	/**
	* Get Offset and limit
	*
	* @return array()
	*/
	public function dataOffsetLimit($request)
	{
		$perpage     = isset($request->perpage) ? $request->perpage : 10;
		$page        = isset($request->page) ? $request->page : 1;
        $offset      = ($page -1)*$perpage;
		return [$offset, $perpage];
	}
}
