<?php
/*
*
*	This file contains the functions related to data table listing
*	Note : This file needs to be included in composer.json (autoload->files)
*		   
*
*/

if (! function_exists('checkSortOrder')) {
	function checkSortOrder($col_name, $default_active='', $default_sort='')
	{	
		if(Request::has('sort_order')){
			if(Request::get('sort_field') == $col_name){
				return defaultSortOrder(Request::get('sort_field'), Request::get('sort_field'), Request::get('sort_order'));
				/*if(Request::get('sort_order') == 'asc'){
					return '<a class="d-inline-flex flex-column" href="javascript:;" data-sort-order="'. Request::get('sort_order') .'" data-sort-by="'. $col_name .'"><i class="fa fa-sort-up active-sort"></i><i class="fa fa-sort-down inactive-sort"></i></a>';
				}else{
					return '<a class="d-inline-flex flex-column" href="javascript:;" data-sort-order="'. Request::get('sort_order') .'" data-sort-by="'. $col_name .'"><i class="fa fa-sort-up inactive-sort"></i><i class="fa fa-sort-down active-sort"></i></a>';
				}*/
			}else{
				return defaultSortOrder($col_name, $default_active='', $default_sort='');
			}
		}else{
			return defaultSortOrder($col_name, $default_active='', $default_sort='');
		}
	}
}

if (! function_exists('defaultSortOrder')) {
	function defaultSortOrder($col_name, $default_active='', $default_sort='')
	{	
		$desc_current_class = 'inactive-sort';
		$asc_current_class  = 'inactive-sort';
		if($default_active != '' ){
			if($default_active == $col_name){
				if($default_sort != '' && $default_sort == 'asc'){
					$asc_current_class  = 'active-sort';
				}else{
					$desc_current_class = 'active-sort';
				}
			}
		}
		//die($asc_current_class);
		return '<a class="d-inline-flex flex-column" href="javascript:;" data-sort-order="'.$default_sort.'" data-sort-by="'. $col_name .'"><i class="fa fa-sort-up '.$asc_current_class.'"></i><i class="fa fa-sort-down '.$desc_current_class.'"></i></a>';
	}
}



if (! function_exists('filterColomnExist')) {
	function filterColomnExist($filter, $colomn, $type=null)
	{	
		if(is_null($filter)){
			return '';
		}
		$filter_array = explode(',', $filter);
		if(!is_null($type) && $type == 'checkbox'){
			//return $colomn;
			return count($filter_array) == 0 ? '' : (in_array($colomn, $filter_array) ? 'checked' : '');
		}else{
			return count($filter_array) == 0 ? '' : (!in_array($colomn, $filter_array) ? 'd-none' : '');
		}
		
	}
}
