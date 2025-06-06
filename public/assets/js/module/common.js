$(document).ready(function() {
	/*ajax add csrf token*/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //Tooltip on buttons
	$("body").tooltip({ selector: '[data-toggle=tooltip]' });
	
    $('#small_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
    $('#medium_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });             
    $('#large_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
    $('#full_screen_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

	/****Modal Animation Init on show**/
	$(".modal").on('show.bs.modal', function (e) {
		//var x = $(this).attr("data-easein");
		var x = 'swoopIn';
		//$(".modal-dialog").velocity("transition."+x);
	})
	/****Modal Animation Init on hide**/
	$(".modal").on('click', '.close-modal', function(){
		//var x = $(document).find(".modal").attr("data-easeout");
		var x = 'swoopOut';
		//$(".modal-dialog").velocity("transition."+x, function(){
			$(".modal").modal('hide');
		//});
	})
	
	/****Control list search filter**/
	$(document).on('click','#list_search_filter',function(){
      removeParamFromUrl('page');
		var div = document.getElementById('search_from_fields');
		$(div).find('input, select, textarea')
        .each(function() {
			//console.log($(this).attr("name"), $(this).val());
			updateURL($(this).attr("name"), $(this).val())
        });
		/*****After update query params call get data function**********/
		  var check_validation = 'true';
                     
          if( $("#start_date").val()!='' || $("#end_date").val()!='' ){
            
            if( $("#start_date").val()=='' ){  
            
              check_validation = 'false';
              $("#start_date").css('border-color','red');
            
            }else{
                  $("#start_date").css('border-color','');    
            } 

            if( $("#end_date").val()=='' ){  
              
              check_validation = 'false';
              $("#end_date").css('border-color','red');
            
            }else{
                  $("#end_date").css('border-color','');     
            } 
          }
          
          if(check_validation=='true'){ 
             getData();
          } 
	});
	
	/****Control Sort Order********/
	$(document).on('click', '.sort-table-data', function(){
		var anchor_obj    = $(this).find('a:first');
		var current_order = anchor_obj.data('sort-order');
		var new_order     = current_order == '' ? 'asc' : (current_order == 'asc' ? 'desc' : 'asc');
		var new_icon      = new_order == 'asc' ? '<i class="fa fa-sort-up active-sort"></i><i class="fa fa-sort-down inactive-sort"></i>' : '<i class="fa fa-sort-up inactive-sort"></i><i class="fa fa-sort-down active-sort"></i>';
		/*****Check and Remove other field Sorting if current field and last field not same**********/
		checkSort($(anchor_obj).data('sort-by'));
		/*****Update New Sort Order**********/
		anchor_obj.html(new_icon);
		anchor_obj.data("sort-order", new_order);
		/*****Update Query Params**********/
		updateURL('sort_order', new_order);
		updateURL('sort_field', $(anchor_obj).data('sort-by'));
		/*****After update query params call get data function**********/
		getData();
	})

    $("body").on("click", '.confirm_delete', function(event) {
		var item_id = $(this).data('item-id');
		var item_type = $(this).data('item-type');
		var delete_request = $(this).data('delete-request');
		if(typeof item_type === "undefined"){
			item_type = 'data';
		}
		
		if(item_type != 'user' ){
			Swal.fire({
				text: "Are you sure you want to delete this "+ item_type +"?",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, delete it!",
			}).then((result) => {
				if (result.value) {
					ShowFormLoading();
					$('#delete_form_'+item_id).submit();
				}
			});
		}else{
			var showDeny = false; // Set the default value
			// Add your condition here to determine whether to show the deny button
			if (delete_request == 1) {
				showDeny = true;
			}
			Swal.fire({
				text: "Are you sure you want to delete this " + item_type + "?",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes, delete it!",
				showDenyButton: showDeny, // Show the additional button
				denyButtonColor: "#FF0000", // Color for the additional button
				denyButtonText: "Cancel request!", // Text for the additional button
			}).then((result) => {
				if (result.isConfirmed) {
					// If "Yes, delete it!" button is clicked
					ShowFormLoading();
					$('#delete_form_' + item_id).submit();
				} else if (result.isDenied) {
					ShowFormLoading();
					$('#delete_request_form_' + item_id).submit();
				}
			});
		}
		
	});
	
	//check box initalise
	$('.i-checks').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
	});
	
	//add date range picker
	/* $('#data_5 .input-daterange').datepicker({
		keyboardNavigation: false,
		forceParse: false,
		format: 'yyyy-mm-dd',
		autoclose: true, 
		todayHighlight: true,
	}); */
	//set date range picker
	$('#start_date').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function(e) {
        // Update the end date picker's start date based on the selected start date
        var startDate = e.date;
        $('#end_date').datepicker('setStartDate', startDate);
        // Clear end date if it is before the new start date
        var endDate = $('#end_date').datepicker('getDate');
        if (endDate && endDate < startDate) {
            $('#end_date').datepicker('setDate', null);
        }
    });

    // Initialize the end date picker
    $('#end_date').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        todayHighlight: true,
		startDate: '-infinity', // Allow selecting any past date
        endDate: new Date() // Prevent selecting future dates
    });
	$('#set_date_range').click(function(){
		updateURL('start_date', $('#start_date').val());
		updateURL('end_date', $('#end_date').val());
		getData();
	})
	//clear date range picker
	$('#clear_date_range').click(function(){
		$('#start_date').val('').datepicker('clearDates');
		$('#end_date').val('').datepicker('clearDates');
		$('#start_date').val('');
		$('#end_date').val('');
		removeParamFromUrl('start_date')
		removeParamFromUrl('end_date')
		getData();
	})

});

/*
 *
 *	create URL
 *
 *
 */

const createURL = (string) => {
    
    return BASE_URL + '/' + string;
}

const createCommonURL = (string) => {
   
    return BASE_URL + '/' + string;
}

/*
 *
 *	get Create order category id from session
 *
 *
 */
 const catgorySessionId = () => {
    return CREATE_ORDER_CATEGORY;
}

/*
 *
 *	Add Button Loader
 *
 *
 */
const addButtonLoader = (obj) => {
    var spinner_html = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Wait...';
    $(obj).html(spinner_html)
    $(obj).attr("disabled", true);
}

/*
 *
 *	Remove Button Loader
 *
 *
 */
const removeButtonLoader = (obj) => {
    $(obj).html('')
    $(obj).html($(obj).data('button-text'));
    $(obj).attr("disabled", false);
}

/*
 *
 *	Add Anchor Loader Icon
 *
 *
 */
const addAnchorLoader = (obj) => {
    var spinner_html = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Wait...';
    $(obj).html(spinner_html);
	$(obj).css('pointer-events', 'none').css('cursor', 'default');
}

/*
 *
 *	Remove Anchor Loader Icon
 *
 *
 */
const removeAnchorLoader = (obj) => {
    $(obj).html('')
    $(obj).html($(obj).data('button-icon'));
    $(obj).css('pointer-events', 'auto').css('cursor', 'pointer');
}

const addCardLoader = (obj) => { 
   $(obj).append('<div class="global-loader border"><div class="align-items-center d-flex flex-column h-100 justify-content-center loader-div text-center"><img src="'+ CONTENT_LOADING +'" alt="loading-content"><span class="ml-2 mr-3">  Loading...</span></div></div>');
}
const removeCardLoader = (obj) => { 
   $(document).find('.global-loader').remove();
}


//Common alert message
const bootstrapAlert = (message_object, type = 'json') => {
    var message = type == 'json' ? createJsonMesageHTML(message_object) : '';
    $('#validation_msg_div').html('');
    $('#validation_msg_div').html(message);
    $('#validation_msg_div').removeClass('d-none').addClass('d-block');
}

const createJsonMesageHTML = (message_object) => {
    return '<div class="alert alert-danger alert-dismissible">\
				<ul>' + createMessageListFromJson(message_object) + '</ul>\
		   </div>';
}

const createMessageListFromJson = (json_message) => {
    var msg_list = '';
    $.each(json_message, (i, v) => {
        msg_list += '<li>' + v + '</li>';
    });
    return msg_list;
}

const toastAlert = (msg_type, message, msg_position = 'top-right', close_time = '2000', auto_close = true) => {
	Swal.fire({
		toast: true,
		icon: msg_type,
		title: message,
		position: msg_position,
		showConfirmButton: false,
		timer: close_time,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})
}

/*
*
*	Pagination update Page no and call get data
*	params (page no)
*/

const updatePageNo = (page_no, request = null) => {

    if (request !=null && request.nested) {
        getSupplierRestaurantOrder(request.restaurant_id,request.supplier_id , page_no, request.search);
    } else if (request !=null && request.category_id) {
        getSupplierData(request.category_id, page_no);
    }
    else {
        updateURL('page', page_no);
        /*****After update query params call get data function**********/
        getData();
    }
}

/*
*
*	Check The Sorting Field
*
*/

const checkSort = (selected_name) => {
	var last_name = getParameterByName('sort_field');
	if (last_name !== null){
		if(selected_name != last_name){
			resetSort();
		}
	}
}

/*
*
*	Remove all Sort Icon and Sort order From All Fileds
*
*/

const resetSort = () => {
	let checkedData = $('.sort-table-data').map(function(){
		var anchor_obj = $(this).find('a:first');
		anchor_obj.data('sort-order', '');
		anchor_obj.html('<i class="fa fa-sort-up inactive-sort"></i><i class="fa fa-sort-down inactive-sort"></i>');
	});
}
/*
*
*	Reset all filters
*
*/
const resetFilter = () => {
    removeAllParamFromUrl();
	$('#search_from_fields')[0].reset();
    $('#search').val('');
	resetSort();
	getData();
}
/*
*
*	Show loader for content section
*
*
*/

const showProcessingElement = (obj) => {
	//var container_div = $(document).find('.global-content-container');
		//container_div.addClass('global-loader-position');
	var loader_html = '<div class="global-loader" style="" id="loader-icon-div">\
						<div class="text-center">\
							<img src="'+ CONTENT_LOADING +'" alt="loading-content">\
							<span class="ml-2 mr-3">  Loading...</span>\
						</div>\
					</div>';

	$(obj).prepend(loader_html);
}

/*
*
*	Show loader for content section
*
*
*/

const hideProcessingElement = (obj) => {
	//var container_div = $(document).find('.global-content-container');
	//container_div.removeClass('global-loader-position');
	$(document).find('.global-loader').remove();
}

/*
*
*	Show loader for content section
*
*
*/

const showProcessing = () => {
	var container_div = $(document).find('.global-content-container');
		container_div.addClass('global-loader-position');
	var loader_html = '<div class="global-loader" style="" id="loader-icon-div">\
						<div class="text-center">\
							<img src="'+ CONTENT_LOADING +'" alt="loading-content">\
							<span class="ml-2 mr-3">  Loading...</span>\
						</div>\
					</div>';

	container_div.prepend(loader_html);
}

function ShowFormLoading() {
	console.log('form loader show');
	$('.form-loader').show();
	return true;
}

/*
*
*	Show loader for content section
*
*
*/

const hideProcessing = () => {
	var container_div = $(document).find('.global-content-container');
	container_div.removeClass('global-loader-position');
	$(document).find('.global-loader').remove();
}

/*
 *
 *	Get Page Data
 *
 *
 */
const getData = () => {
	//first remove table body content
	$('table tbody tr:not(:first)').remove();
	addCardLoader('.ibox-content')
	var current_page = getCurrentPage();
	var params       = parseQueryString();
	params['current_page'] = current_page;
	params['current_url']=window.location.href,  // Full URL
        params['current_path']= window.location.pathname // Path only
	$.ajax({
		url: data_url,
		type: "GET",
		data: params,
	}).done(function(response) {
		if (response.type == 'error') {
			console.log('responsejhkhkh',response)
			
			toastAlert(response.type, response.msg);
		} else {
			removeCardLoader();
			if ($('table#data_list').length) {
				//$('table tbody tr:not(:first)').remove();
				$(response.html).insertAfter("table tbody tr:first");
				$('#pagination-section').html(response.pagination);
				if(response.total_revenue){
					$('#total_revenue').text(response.total_revenue)
				}
			} else {
				window.location.reload();
			}
		}
	});
}

const confirmDischarged = (type) => {
	console.log('yes submit dischrge form', type);
	Swal.fire({
		text: "Are you sure you want to "+type+" this patient?",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Yes,  proccedd!",
	}).then((result) => {
		if (result.value) {
			console.log('yes submit dischrge form');
		}
	});
};


const redirectPage = (page) => { 
  window.location.href = createURL(page)
}

function redirectDetailPage(page, id=null)  {
    //window.location.href = createURL(page)
    window.open(page, '_blank');
}

const removeElement = (obj) => {
  obj.remove()
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}


