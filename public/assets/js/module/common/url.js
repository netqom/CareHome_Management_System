/******************************
 Common function Section
 *******************************/

/*
*	Update Url Params
*
*/

const updateURL = (key,val) => {
    var url = window.location.href;
    var reExp = new RegExp("[\?|\&]"+key + "=[0-9a-zA-Z\_\+\-\|\.\,\;]*");
    if(reExp.test(url)) {
        // update
        var reExp = new RegExp("[\?&]" + key + "=([^&#]*)");
        var delimiter = reExp.exec(url)[0].charAt(0);
        url = url.replace(reExp, delimiter + key + "=" + val);
    } else {
        // add
        var newParam = key + "=" + val;
        //if(url.indexOf('?')===-1){url += '?';}
		var separator = (window.location.href.indexOf("?")===-1)?"?":"&";
        if(url.indexOf('#') > -1){
            var urlparts = url.split('#');
            url = urlparts[0] +  separator + newParam +  (urlparts[1] ?  "#" +urlparts[1] : '');
        } else {
            url += separator + newParam;
        }
    }
    window.history.pushState(null, document.title, url);
}

/*
*	Remove Url Params
*
*/

const removeParamFromUrl = (key) => {
	var url = window.location.href;
    var reExp = new RegExp("[\?|\&]"+key + "=[0-9a-zA-Z\_\+\-\|\.\,\;]*");
    if(reExp.test(url)) {
        // update
        var reExp = new RegExp("[\?&]" + key + "=([^&#]*)");
        var delimiter = reExp.exec(url)[0].charAt(0);
        url = url.replace(reExp, '');
    }
	window.history.pushState(null, document.title, url);
}

/*
*	Remove All Url Params
*
*/

const removeAllParamFromUrl = () => {
	var uri = window.location.toString();
	if (uri.indexOf("?") > 0) {
	    var clean_uri = uri.substring(0, uri.indexOf("?"));
	    window.history.replaceState({}, document.title, clean_uri);
	}
}

/*
*	Get Url Params By Name
*
*/

const getParameterByName = (name, url) => {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}

/*
*	Check Valid Url
*
*/

const validURL = (str) => {
	var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
	'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
	'((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
	'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
	'(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
	'(\\#[-a-z\\d_]*)?$','i'); // fragment locator
	return !!pattern.test(str);
}

/*
*	Parse Query String
*	#(Return Query params as array)
*
*/

const parseQueryString = () => {
	var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		vars[key] = value;
    });
	return vars;
}

/*
*	Get Current Page
*	#(Return Query params as array)
*
*/

const getCurrentPage = (segment=null) => {
	var path_name = window.location.pathname;
	if(segment == null){
		return path_name.split("/").pop();
	}else{
		return path_name.split("/")[segment];
	}
}		

/*
*	Uppercase first Word String
*	#(Return first word uppercase)
*
*/

const ucfirst = (str,force) => {
	str=force ? str.toLowerCase() : str;
	return str.replace(/(\b)([a-zA-Z])/,
    function(firstLetter){
		return   firstLetter.toUpperCase();
    });
 }

/*
*	Uppercase all Word String
*	#(Return all word uppercase)
*
*/

const ucwords = (str,force) => {
	str=force ? str.toLowerCase() : str;  
	return str.replace(/(\b)([a-zA-Z])/g,
            function(firstLetter){
              return   firstLetter.toUpperCase();
            });
}

