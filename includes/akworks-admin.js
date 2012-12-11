//scripts for akworks edit work links
jQuery(document).ready(function ($) {

	var textClass = "txt";
	var hrefClass = "href";
	var aRow = "<tr class='row'><td><input class='sel' type='checkbox'/></td><td class='"+textClass+"'>{/text/}</td><td class='"+hrefClass+"'>{/href/}</td></tr>";

	function addToLinksArea(linkText, linkHref){
		var arr = new Array();
		if($("#ak_works_links").val().trim().length > 0 && $("#ak_works_links").val() != undefined)
			arr = eval($("#ak_works_links").val());

		arr.push({'text':linkText, 'href':linkHref});
		$("#ak_works_links").html(JSON.stringify(arr));
	}
	function refreshLinksArea(){
		var arr = new Array();
		$("#ak_works_links").html('');
		$("#ak_works_links_table tr.row").each(function(){
			arr.push({'text':$(this).children('td.'+textClass).text(), 'href':$(this).children('td.'+hrefClass).text()});
		});
		$("#ak_works_links").html(JSON.stringify(arr));
	}

	//read textarea and display on table
	$("#ak_works_links_table").ready(function(){
		if($("#ak_works_links").val().length > 0){
			var arr = eval($("#ak_works_links").val()) || Array();
			for(var i=0; i<arr.length; i++){
				var href_str = aRow.replace('{/text/}', arr[i]['text']).replace('{/href/}', arr[i]['href']);
				$("#ak_works_links_table").append($(href_str));
			}
		}
	});

	//add a link
	$('#ak_works_links_add').click(function(){
		var linkText = $("#ak_works_links_text").val();
		var linkHref = $("#ak_works_links_href").val();
		var errCss = {'border':'1px solid red', 'background-color':'#fee'};
		//数据验证
		if(linkText == '' || linkText.length<=0 || linkText == null){
			$("#ak_works_links_text").css(errCss);
			return false;
		}
		if(!linkHref.match(new RegExp("[a-zA-Z0-9\\.-]+\\.([a-zA-Z]{2,4})(:\\d+)?(/[a-zA-Z0-9\\.\\-~!@#$%^&*+?:_/=<>]*)?"))){
			$("#ak_works_links_href").css(errCss);
			return false;
		}

		if(!linkHref.match(/^((http|ftp|https):\/\/){1}/))
			linkHref = "http://"  + linkHref;
		var href_str = aRow.replace('{/text/}', linkText).replace('{/href/}', linkHref);
		$("#ak_works_links_table").append($(href_str));
		addToLinksArea(linkText, linkHref);
		return false;
	});

	//delete selected
	$("#ak_works_links_del").click(function(){
		$("#ak_works_links_table tr").each(function(){
			if($(this).find(".sel").attr('checked')){
				$(this).find(".sel").attr('checked');
				$(this).remove();
			}
		});

		refreshLinksArea();
	});

	//select all / unselect all
	$("#ak_works_links_select_all").click(function(){
		var checked = $(this).attr('checked') == 'checked' ? 'checked' : false ;
		$("#ak_works_links_table .sel").each(function(){
			$(this).attr('checked', checked);
		});
	});
});

//datapicker
jQuery(document).ready(function ($){
	$("#ak_works_lastupdate").datepicker({
		dateFormat:"yy-mm-dd",  //设置日期格式
        changeMonth:true,   //是否提供月份选择
        changeYear:true,    //是否提供年份选择
        dayNamesMin: ['日','一','二','三','四','五','六'],  //日期简写名称
        monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']  //月份简写名称
	});
});