var mainAssort = [];
function setMainAssort() {
	var searchText = $('#mainSearchName').val();
	$.ajax({type: 'POST', url: '/ajax/get_main_assort', async: true, data:{searchText: searchText},
		success: function(data){//console.log(data);
			setVariables(data);
			getTypeahead(getMainGoodsNameArr(JSON.parse(data)));
		}
	});
}
function getTypeahead(arr){
	$.ajax({type: 'POST', url: '/ajax/get_typeahead', async: true, data:{arr: arr},
		success: function(data){//console.log(data);
			$('#typeahead').html(data);
		}
	});
}
function setVariables(data){
	mainAssort = JSON.parse(data);
}
function getMainAssort(){
	return mainAssort;
}
function getMainGoodsNameArr(arr){
	var goodsArr = [];
	$.each( arr, function( key, value ) {
		if(value['product_name'] != undefined)
	  		goodsArr.push(value['product_name']);
		if(value['name'] != undefined)
	  		goodsArr.push(value['name']);
		if(value['group_1_name'] != undefined)
	  		goodsArr.push(value['group_1_name']);
		if(value['group_2_name'] != undefined)
	  		goodsArr.push(value['group_2_name']);
		if(value['group_3_name'] != undefined)
	  		goodsArr.push(value['group_3_name']);
	});
	return goodsArr;
}
function getGroupArr(arr, group_id){
	var goodsArr = [];
	$.each( arr, function( key, value ) {
	  goodsArr.push(value['group_' + group_id +'_name']);
	});
	return goodsArr;
}
function getMainNameArrKey(arr, val){
	var arrKey = -1;
	$.each( arr, function( key, value ) {
		if(value['product_name'] == val)
			arrKey = key;
		if(value['name'] == val)
			arrKey = key;
	});
	return arrKey;
}
function groupArrKey(arr, val, group_id){
	var arrKey = -1;
	$.each( arr, function( key, value ) {
		if(value['group_' + group_id + '_name'] == val)
			arrKey = key;
	});
	return arrKey;
}
function searchAction(val){
	$.ajax({type: 'POST', url: '/ajax/get_main_assort', async: true, data:{searchText: val},
		success: function(data) {
			var assort = JSON.parse(data);
			var nameArrKey = -1;
			var group1ArrKey = -1;
			var group2ArrKey = -1;
			var group3ArrKey = -1;
			var searchVal = new RegExp(val, 'ig');console.log(assort);
			$.each(assort, function (key, value) {
				if (value['product_name'] != undefined)
					nameArrKey = value['id'];
				if (value['group_1_name'] != undefined)
					group1ArrKey = value['group_1'];
				if (value['group_2_name'] != undefined)
					group2ArrKey = value['group_2'];
				if (value['group_3_name'] != undefined)
					group3ArrKey = value['group_3'];
			});
			var keysData = {
				nameArrKey: nameArrKey,
				group1ArrKey: group1ArrKey,
				group2ArrKey: group2ArrKey,
				group3ArrKey: group3ArrKey
			};
			changeLocation(keysData);
		}
	});
}
function changeLocation(keysData){
	var nameArrKey = keysData['nameArrKey'];
	var group1ArrKey = keysData['group1ArrKey'];
	var group2ArrKey = keysData['group2ArrKey'];
	var group3ArrKey = keysData['group3ArrKey'];
	var assort = getMainAssort();
	if(group1ArrKey != -1){
		document.location = '/catalog/?group_1='+group1ArrKey;
	} else if(group2ArrKey != -1){
		document.location = '/catalog/?group_2='+group2ArrKey;
	} else if(group3ArrKey != -1){
		document.location = '/catalog/?group_3='+group3ArrKey;
	}else if(nameArrKey != -1){
		document.location = '/item/product/'+nameArrKey;
	}
}
function changGroupVisibility(groupId) {
	var $rows = $('.group-row[data-group=' + groupId +']');
	if ($rows.css('display') == 'none') {
		$rows.show('slow');
	} else {
        $rows.hide('slow');
	}
}
function changEmptyGroupVisibility(groupId) {
	var $rows = $('.empty-group-row[data-group=' + groupId +']');
	if ($rows.css('display') == 'none') {
		$rows.show('slow');
	} else {
        $rows.hide('slow');
	}
}
$(document).ready(function(){
	$('#mainSearchName').keyup(function(){
		setMainAssort();
	});
	$('#mainSearchBtn').click(function(){
		var val = $('#mainSearchName').val();
		searchAction(val);
	});
	$('body').click(function(){
        $('#typeahead .typeahead').css('display', 'none');
    });
	$('.main-page-shop').click(function(){
        document.location = '/item/shop/' + $(this).data('id');
    });
});