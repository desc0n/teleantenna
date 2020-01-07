var mainAssort = [];
function setMainAssort(row, searchText, type) {
	$.ajax({type: 'POST', url: '/ajax/get_main_assort', async: true, data:{searchText: searchText, type:type},
		success: function(data){
			setVariables(data);
			if (type == 'name')
				getTypeaheadName(row, getGoodsNameArr(JSON.parse(data)));
			else if (type == 'code')
				getTypeaheadCode(row, getGoodsCodeArr(JSON.parse(data)));
		}
	});
}
function getTypeaheadName(row, arr){
	var viewType = $('#incomeId').length || $('#writeoffId').length ? 'post' : null;
	$.ajax({type: 'POST', url: '/ajax/get_admin_typeahead', async: true, data:{arr: arr, row: row, type: 'name', viewType: viewType},
		success: function(data){
			$('#typeaheadName'+row).html(data);
		}
	});
}
function getTypeaheadCode(row, arr){
	var viewType = $('#incomeId').length || $('#writeoffId').length ? 'post' : null;
	$.ajax({type: 'POST', url: '/ajax/get_admin_typeahead', async: true, data:{arr: arr, row: row, type: 'code', viewType: viewType},
		success: function(data){console.log(data);
			$('#typeaheadCode'+row).html(data);
		}
	});
}
function setVariables(data){
	mainAssort = JSON.parse(data);
}
function getAssort(searchText){
	return JSON.parse($.ajax({type: 'POST', url: '/ajax/get_assort', async: false, data:{searchText:searchText}})
		.responseText);
}
function getUsers(){
	return JSON.parse($.ajax({type: 'POST', url: '/ajax/get_users', async: false, data:{}})
		.responseText);
}
function getGoodsNameArr(arr){
	var goodsArr = [];
	$.each( arr, function( key, value ) {
		if(value['product_name'] != undefined) {
			var pair = new Array(value['id'], value['product_name']);
			goodsArr.push(pair);
		}
	});
	return goodsArr;
}
function getUserNamesArr(arr){
	var usernameArr = [];
	$.each( arr, function( key, value ) {
		usernameArr.push(value['username']);
	});
	return usernameArr;
}
function getGoodsCodeArr(arr){
	var goodsArr = [];
	$.each( arr, function( key, value ) {
		if(value['code'] != undefined) {
			var pair = new Array(value['id'], value['code'], value['product_name']);
			goodsArr.push(pair);
		}
	});
	return goodsArr;
}
function getNameArrKey(arr, val){
	var arrKey = -1;
	$.each( arr, function( key, value ) {
		if(value['name'] == val)
			arrKey = key;
	});
	return arrKey;
}
function getCodeArrKey(arr, val){
	var arrKey = -1;
	$.each( arr, function( key, value ) {
		if(value['code'] == val)
			arrKey = key;
	});
	return arrKey;
}
function typeAheadName(row, searchText){
	$('.goodsName[row='+row+']').typeahead({ 
		source: getGoodsNameArr(getAssort(searchText)),
		items: 5,
		updater: function(item) {
			var arrKey = getNameArrKey(getAssort(searchText), item);
			if(arrKey != -1){//console.log(assort[arrKey]);
				var assort = getAssort(searchText);
				$('#goodsRootNumHtml_'+row).html(assort[arrKey]['root_num']);
				$('#goodsRootNum_'+row).val(assort[arrKey]['root_num']);
				$('#goodsId_'+row).val(assort[arrKey]['id']);
				$('#goodsPrice_'+row).val(assort[arrKey]['price']);
				$('#goodsCode_'+row).val(assort[arrKey]['code']);
				$('#goodsName_'+row).blur();
			}
			return item;
		}
	});
}
function typeAheadCode(row, searchText){
	$('.goodsCode[row='+row+']').typeahead({ 
		source: getGoodsCodeArr(getAssort(searchText)),
		items: 5,
		updater: function(item) {
			var arrKey = getCodeArrKey(getAssort(searchText), item);
			if(arrKey != -1){//console.log(assort[arrKey]);
				var assort = getAssort(searchText);
				$('#goodsRootNumHtml_'+row).html(assort[arrKey]['root_num']);
				$('#goodsRootNum_'+row).val(assort[arrKey]['root_num']);
				$('#goodsId_'+row).val(assort[arrKey]['id']);
				$('#goodsPrice_'+row).val(assort[arrKey]['price']);
				$('#goodsName_'+row).val(assort[arrKey]['name']);
				$('#goodsCode_'+row).blur();
			}
			return item;
		}
	});
}
function getPostData(row){
    var postData = {
        row : row,
        realizationId : $('#realizationId').length ? $('#realizationId').val() : 0,
        incomeId : $('#incomeId').length ? $('#incomeId').val() : 0,
        cashincomeId : $('#cashincomeId').length ? $('#cashincomeId').val() : 0,
        returnId : $('#returnId').length ? $('#returnId').val() : 0,
		writeoffId : $('#writeoffId').length ? $('#writeoffId').val() : 0,
		cashwriteoffId : $('#cashwriteoffId').length ? $('#cashwriteoffId').val() : 0,
		cashreturnId : $('#cashreturnId').length ? $('#cashreturnId').val() : 0,
        productId : $('#goodsId_'+row).length ? $('#goodsId_'+row).val() : 0,
        comment : $('#goodsComment_'+row).length ? $('#goodsComment_'+row).val() : '',
        code : $('#goodsCode_'+row).length ? $('#goodsCode_'+row).val() : 0,
        name : $('#goodsName_'+row).length ? $('#goodsName_'+row).val() : '',
        num : $('#goodsNum_'+row).length ? $('#goodsNum_'+row).val() : 0,
        price : $('#goodsPrice_'+row).length ? $('#goodsPrice_'+row).val() : 0
    }
    return postData;
}
function addRealisationPosition(row){
	var postData = getPostData(row);
	$.ajax({type: 'POST', url: '/ajax/add_realisation_position', async: true, data:{postData:postData}})
	.done(function(data){/*console.log(data);*/if(data == true) location.reload();});
}
function addIncomePosition(row){
	var postData = getPostData(row);
	$.ajax({type: 'POST', url: '/ajax/add_income_position', async: true, data:{postData:postData}})
	.done(function(data){if(data == true) location.reload();});
}
function addReturnPosition(row){
	var postData = getPostData(row);
	$.ajax({type: 'POST', url: '/ajax/add_return_position', async: true, data:{postData:postData}})
	.done(function(data){if(data == true) location.reload();});
}
function addWriteoffPosition(row){
	var postData = getPostData(row);
	$.ajax({type: 'POST', url: '/ajax/add_writeoff_position', async: true, data:{postData:postData}})
	.done(function(data){if(data == true) location.reload();});
}
function addCashincomePosition(row){
	var postData = getPostData(row);
	$.ajax({type: 'POST', url: '/ajax/add_cashincome_position', async: true, data:{postData:postData}})
		.done(function(data){if(data == true) location.reload();});
}
function addCashwriteoffPosition(row){
	var postData = getPostData(row);
	$.ajax({type: 'POST', url: '/ajax/add_cashwriteoff_position', async: true, data:{postData:postData}})
		.done(function(data){if(data == true) location.reload();});
}
function addCashreturnPosition(row){
	var postData = getPostData(row);
	$.ajax({type: 'POST', url: '/ajax/add_cashreturn_position', async: true, data:{postData:postData}})
		.done(function(data){if(data == true) location.reload();});
}
function getUserRoles(username){
	$.ajax({type: 'POST', url: '/ajax/get_user_roles', async: true, data:{username:username}})
		.done(function(data){
			var decodeData = JSON.parse(data);
			if(decodeData.length > 0) {
				$('#tdChangeUserRoles').html('');
				for (i = 0; i < decodeData.length; i++) {
					$('#checkUserRoles_' + decodeData[i]['role_id']).attr('checked', '');
					$('#tdChangeUserRoles').html(decodeData[i]['username']);
					$('#changeUserRolesId').val(decodeData[i]['user_id']);
					$('#checkUserRoles_' + decodeData[i]['role_id']).attr('checked', 'checked');
				}
			}
		});
}
function changeUserRole(id){
	$.ajax({type: 'POST', url: '/ajax/change_user_role', async: true, data:{username:$('#tdChangeUserRoles').text(),user_id:$('#changeUserRolesId').val(),role_id:id}})
		.done(function(data){});
}
function changeUserShop(user_id, shop_id){
	$.ajax({type: 'POST', url: '/ajax/change_user_shop', async: true, data:{user_id:user_id,shop_id:shop_id}})
		.done(function(data){
			//console.log(data);
		});
}
function addPositionFromCode(value, row){
	var arrKey = getCodeArrKey(getAssort(value), value);
		if(arrKey != -1){
			var assort = getAssort(value);
			$('#goodsId_'+row).val(assort[arrKey]['id']);
			addRealisationPosition(row);
		}
}
function openSearchModal(row){
	$('#searchModalRow').val(row);
	$('#searchModal').modal();
}
function setSearchModalItem(id){
	var row = $('#searchModalRow').val();

	$.ajax({type: 'POST', url: '/ajax/get_product', async: true, data:{id:id, type: 'manager'}})
		.done(function(data){
			var productData = JSON.parse(data);
			if ($('#goodsId_' + row).length) {
				$('#goodsId_' + row).val(productData.id);
			}

			if ($('#goodsCode_' + row).length) {
				$('#goodsCode_' + row).val(productData.code);
			}

			if ($('#goodsName_' + row).length) {
				$('#goodsName_' + row).val(productData.name);
			}

			if ($('#goodsPrice_' + row).length) {
				var discount = $('#discount').val();
				var rootPrice = productData.price * 1;
				var newPrice = Math.ceil(((100 - discount) / 100) * rootPrice);
				$('#goodsPrice_' + row).val(newPrice);
			}

			if ($('#goodsRootNum_' + row).length) {
				$('#goodsRootNum_' + row).val(productData.num.num);
			}

			if($('#rootNum_' + row).length) {
				$('#rootNum_' + row).html(productData.num.num);
			}

			if ($('#searchModal').css('display') == 'block') {
				$('#searchModal').modal('toggle');
			}
		});
}
function setRealizationContractor(){
	if($('#realizationId').length) {
		var realizationId = $('#realizationId').val();
        var discount = $('#discount').val();

	/*$.ajax({type: 'POST', url: '/ajax/set_realization_contractor', async: true, data:{user_id:user_id, realizationId:realizationId}})
		.done(function(data){
			location.reload();
		});
	*/
        var rowNum = $('#rowNum').val() * 1;

        for (i = 1; i <= rowNum; i++) {
            if ($('#goodsId_' + i).length) {
                var data = $.ajax({type: 'POST', url: '/ajax/get_product', async: false, data:{id:$('#goodsId_' + i).val(), type: 'manager'}})
                    .done(function(data) {
                }).responseText;
                var productData = JSON.parse(data);
                if ($('#goodsPrice_' + i).length && productData.price != undefined) {
                    var rootPrice = productData.price * 1;
                    var newPrice = Math.ceil(((100 - discount) / 100) * rootPrice);
                    $('#goodsPrice_' + i).val(newPrice);
                }
            }
        }
    }
}

function addRow() {
	var rowNum = $('#rowNum').val() * 1;
	var newRowNum = rowNum + 1;
	var rowText = "" +
        "<tr id='row" + newRowNum + "'>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsCode_" + newRowNum + "' row='" + newRowNum + "' value='' autocomplete='OFF' data-items='7' onkeyup=\"javascript: initTypeahead($(this).attr('row'), $(this).val(), 'code');\">" +
		"<input type='hidden' id='goodsId_" + newRowNum + "' row='" + newRowNum + "' name='id[]' value=''>" +
		"<div class='col-xs-12 admin-typeahead' id='typeaheadCode" + newRowNum + "'></div>" +
		"</td>" +
		"<td>" +
		"<div class='input-group'>" +
		"<input type=text class='form-control' id='goodsName_" + newRowNum + "' row='" + newRowNum + "' value='' autocomplete='OFF' data-items='7' onkeyup=\"javascript: initTypeahead($(this).attr('row'), $(this).val(), 'name');\">" +
		"<div class='col-xs-12 admin-typeahead' id='typeaheadName" + newRowNum + "'></div>" +
		"<div class='input-group-btn'>" +
		"<button type='button' class='btn btn-default' onclick='javascript: openSearchModal(" + newRowNum + ");'><span class='glyphicon glyphicon-search'></span></button>" +
		"</div>" +
		"</div>" +
		"</td>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsComment_" + newRowNum + "' row='" + newRowNum + "' name='comment[]' value=''>" +
		"</td>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsNum_" + newRowNum + "' row='" + newRowNum + "' name='num[]' value='1'>" +
		"<input type='hidden' id='goodsRootNum_" + newRowNum + "' row='" + newRowNum + "' value='1'>" +
		"</td>" +
        "<td class='text-center'>" +
        "<button type='button' class='btn btn-default' onclick='deleteRow(" + newRowNum + ");'>" +
        "<span class='glyphicon glyphicon-trash'></span>" +
        "</button>" +
        "</td>" +
		"</tr>";
	$('#tableBody').append(rowText);
	$('#rowNum').val(newRowNum);
}

function addCashRow() {
	var rowNum = $('#rowNum').val() * 1;
	var newRowNum = rowNum + 1;
	var rowText = "" +
		"<tr id='row" + newRowNum + "'>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsComment_" + newRowNum + "' row='" + newRowNum + "' name='comment[]' value=''>" +
		"</td>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsPrice_" + newRowNum + "' row='" + newRowNum + "' name='price[]' value=''>" +
		"</td>" +
		"<td class='text-center'>" +
		"<button type='button' class='btn btn-default' onclick='deleteRow(" + newRowNum + ");'>" +
		"<span class='glyphicon glyphicon-trash'></span>" +
		"</button>" +
		"</td>" +
		"</tr>";
	$('#tableBody').append(rowText);
	$('#rowNum').val(newRowNum);
}

function addRealizationRow() {
	var rowNum = $('#rowNum').val() * 1;
	var newRowNum = rowNum + 1;
	var rowText = "" +
        "<tr id='row" + newRowNum + "'>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsCode_" + newRowNum + "' row='" + newRowNum + "' value='' autocomplete='OFF' data-items='7' onkeyup=\"javascript: initTypeahead($(this).attr('row'), $(this).val(), 'code');\">" +
		"<input type='hidden' id='goodsId_" + newRowNum + "' row='" + newRowNum + "' name='id[]' value=''>" +
		"<div class='col-xs-12 admin-typeahead' id='typeaheadCode" + newRowNum + "'></div>" +
		"</td>" +
		"<td>" +
		"<div class='input-group'>" +
		"<input type=text class='form-control' id='goodsName_" + newRowNum + "' row='" + newRowNum + "' value='' autocomplete='OFF' data-items='7' onkeyup=\"javascript: initTypeahead($(this).attr('row'), $(this).val(), 'name');\">" +
		"<div class='col-xs-12 admin-typeahead' id='typeaheadName" + newRowNum + "'></div>" +
		"<div class='input-group-btn'>" +
		"<button type='button' class='btn btn-default' onclick='javascript: openSearchModal(" + newRowNum + ");'><span class='glyphicon glyphicon-search'></span></button>" +
		"</div>" +
		"</div>" +
		"</td>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsPrice_" + newRowNum + "' row='" + newRowNum + "' name='price[]' value=''>" +
		"</td>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsNum_" + newRowNum + "' row='" + newRowNum + "' name='num[]' value='1'>" +
		"</td>" +
		"<td>" +
		"<span id='rootNum_" + newRowNum + "'></span>" +
		"<input type='hidden' id='goodsRootNum_" + newRowNum + "' row='" + newRowNum + "' value='1'>" +
		"</td>"+
		"<td class='text-center'>" +
		"<button type='button' class='btn btn-default' onclick='deleteRow(" + newRowNum + ");'>" +
		"<span class='glyphicon glyphicon-trash'></span>" +
		"</button>" +
		"</td>" +
		"</tr>";
	$('#tableBody').append(rowText);
	$('#rowNum').val(newRowNum);
}

function addCommentRow() {
	var rowNum = $('#rowNum').val() * 1;
	var newRowNum = rowNum + 1;
	var rowText = "" +
        "<tr id='row" + newRowNum + "'>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsCode_" + newRowNum + "' row='" + newRowNum + "' value='' autocomplete='OFF' data-items='7' onkeyup=\"javascript: initTypeahead($(this).attr('row'), $(this).val(), 'code');\">" +
		"<input type='hidden' id='goodsId_" + newRowNum + "' row='" + newRowNum + "' name='id[]' value=''>" +
		"<div class='col-xs-12 admin-typeahead' id='typeaheadCode" + newRowNum + "'></div>" +
		"</td>" +
		"<td>" +
		"<div class='input-group'>" +
		"<input type=text class='form-control' id='goodsName_" + newRowNum + "' row='" + newRowNum + "' value='' autocomplete='OFF' data-items='7' onkeyup=\"javascript: initTypeahead($(this).attr('row'), $(this).val(), 'name');\">" +
		"<div class='col-xs-12 admin-typeahead' id='typeaheadName" + newRowNum + "'></div>" +
		"<div class='input-group-btn'>" +
		"<button type='button' class='btn btn-default' onclick='javascript: openSearchModal(" + newRowNum + ");'><span class='glyphicon glyphicon-search'></span></button>" +
		"</div>" +
		"</div>" +
		"</td>" +
        "<td>" +
        "<input type=text class='form-control' id='goodsComment_" + newRowNum + "' row='" + newRowNum + "' name='comment[]' value=''>" +
        "</td>" +
		"<td>" +
		"<input type=text class='form-control' id='goodsNum_" + newRowNum + "' row='" + newRowNum + "' name='num[]' value='1'>" +
		"</td>" +
		"<td>" +
		"<span id='rootNum_" + newRowNum + "'></span>" +
		"<input type='hidden' id='goodsRootNum_" + newRowNum + "' row='" + newRowNum + "' value='1'>" +
		"</td>"+
		"<td class='text-center'>" +
		"<button type='button' class='btn btn-default' onclick='deleteRow(" + newRowNum + ");'>" +
		"<span class='glyphicon glyphicon-trash'></span>" +
		"</button>" +
		"</td>" +
		"</tr>";
	$('#tableBody').append(rowText);
	$('#rowNum').val(newRowNum);
}

function initTypeahead(row, val, type){
	$('#searchModalRow').val(row);
	setMainAssort(row, val, type);
}

function deleteRow(id) {
	$('#row' + id).remove();
}

function reloadRealization() {
    document.location = '/admin/realization/?realization=' + $('#realizationId').val();
}

function checkOrders() {
	$.ajax({type: 'POST', url: '/ajax/check_orders', async: true})
	.done(function(data) {
		if (data > 0) {
			$('#orderCheck').css('display', 'block');

			return true;
		}

		$('#orderCheck').css('display', 'none');
	});
}

checkOrders();
setInterval('checkOrders()', 30000);

function checkNumSubmit(interface) {
    var rowNum = $('#rowNum').val() * 1;
    var checkZero = 0;
    var checkRoot = 0;
    var checkId = false;
	var ids = $('.ids');

    for (i=1; i <= rowNum; i++) {
        if ($('#goodsNum_' + i).length) {
            if ($('#goodsNum_' + i).val() == 0) {
                checkZero++;
            }

            if ($('#goodsNum_' + i).val() * 1 > $('#goodsRootNum_' + i).val() * 1) {
                checkRoot++;
            }
        } else {
			checkZero++;
		}
    }

	for (i = 0; i < ids.length; i++) {
		if (ids[i].value != '') {
			checkId = true;
		}
	}

	if (checkZero != 0) {
		alert('Не указано количество!');

		if (interface == 'realization') {
			$('#carryOutRealizationPost').removeAttr('disabled');
		} else if (interface == 'writeoff') {
			$('#carryOutWriteoffPost').removeAttr('disabled');
		}

		return false
	}

	if (checkRoot != 0) {
		alert('Указанное количество больше наличия!');

		if (interface == 'realization') {
			$('#carryOutRealizationPost').removeAttr('disabled');
		} else if (interface == 'writeoff') {
			$('#carryOutWriteoffPost').removeAttr('disabled');
		}

		return false;
	}

	if (!checkId && interface == 'realization') {
		alert('Не обнаружен код товара! Переподберите товар заново.');

		$('#carryOutRealizationPost').removeAttr('disabled');

		return false;
	}

	if (interface == 'realization') {
		$('#carryOutRealizationForm').submit();
		setTimeout("reloadRealization()", 1000);
	} else if (interface == 'writeoff') {
		$('#carryOutWriteoffForm').submit();
	}
}
function showRedactCategoryForm(categoryId) {
    $('#redactProductCategory' + categoryId + ' .redact-category-form').css('display', 'inline-block');
    $('#redactProductCategory' + categoryId + ' .redact-category-name').css('display', 'none');
}
function hideRedactCategoryForm(categoryId) {
    $('#redactProductCategory' + categoryId + ' .redact-category-form').css('display', 'none');
    $('#redactProductCategory' + categoryId + ' .redact-category-name').css('display', 'inline-block');
    $('#redactProductCategory' + categoryId + ' .redact-category-form button').removeAttr('disabled');
}
function removeFromPopularCategories(categoryId) {
    patchCategory(categoryId, {is_popular: 0});
    $('#redactProductCategory' + categoryId + ' .change-popular-category').removeClass('glyphicon-star').addClass('glyphicon-star-empty').css('color', '#000');
}
function addToPopularCategories(categoryId) {
    patchCategory(categoryId, {is_popular: 1});
    $('#redactProductCategory' + categoryId + ' .change-popular-category').removeClass('glyphicon-star-empty').addClass('glyphicon-star').css('color', '#E25734');
}
function patchCategory(categoryId, data) {
    data.action = 'patchProductCategory';
    data.productCategoryId = categoryId;
    $.ajax({
        method: 'POST',
        async: true,
        url: '/admin/product',
        data: data
    }).done(function () {
		if(data.productCategoryName !== undefined) {
            $('#redactProductCategory' + categoryId + ' .redact-category-name .redact-category-name-value').html(data.productCategoryName);
            hideRedactCategoryForm(categoryId);
		}
    });
}
function showLoadCategoryImgForm(categoryId) {
	$('#loadCategoryImgModal .modal-content').html('');
    $.ajax({
        method: 'GET',
        async: true,
        url: '/admin/show_load_category_img_form?categoryId=' + categoryId
    }).done(function (html) {
        $('#loadCategoryImgModal .modal-content').html(html);
        $('#loadCategoryImgModal').modal('toggle');
    });
}
function loadCategoryImg(categoryId) {
	var data = new FormData();
	data.append('categoryId', categoryId);
    data.append('category_img_name', $('#loadCategoryImgModal input[name=category_img_name]')[0].files[0]);

    $.ajax({
        method: 'POST',
        async: true,
        url: '/admin/load_category_img',
        data: data,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.result === 'success') {
                $('.redact-category-img-' + categoryId + ' > img').attr('src', '/public/i/categories/thumb/' +  categoryId + '_' + response.imgName);
            } else {
                alert('Ошибка загрузки!');
            }
            $('#loadCategoryImgModal').modal('toggle');
        }
    });
}

function removeCategory(categoryId){
    if(confirm('Подтвердить удаление группы?')) {
        $.ajax({
            type: 'POST',
            url: '/admin/product',
            async: true,
            data: {productCategoryId: categoryId, action: 'removeProductCategory'},
            success: function () {
                $('#redactProductCategory' + categoryId).remove();
            }
        });
    }
}
function writeProducts(categoryId){
    $.ajax({type: 'GET', url: '/ajax/category_products?categoryId=' + categoryId, async: true,
        success: function(data){
            $('#categoryProducts' + categoryId).html(data);
        }
    });
}
function addProduct(categoryId){
    const name = $('#newProduct' + categoryId).val();
    if(!name) {
        alert('Введите название товара!');
        return;
    }
    $.ajax({type: 'POST', url: '/admin/product', async: true, data: {name: name, categoryId: categoryId, action: 'addProduct'},
        success: function(){
            $('#redactProductCategory' + categoryId + ' .show-category-products-btn-' + categoryId).data('hidden', '1');
            showProductsList(categoryId);
        }
    });
}
function removeProduct(productId){
    $.ajax({type: 'POST', url: '/admin/product', async: true, data: {productId: productId, action: 'removeProduct'},
        success: function(){
            $('#productInfo' + productId).remove();
        }
    });
}
function showSubCategories(categoryId) {
	const hidden = $('#redactProductCategory' + categoryId).data('hidden');
	if(hidden * 1 === 1) {
        $('#redactProductCategory' + categoryId + ' .redact-category-sub-row-' + categoryId).show();
        $('#redactProductCategory' + categoryId + ' .show-sub-category-btn-' + categoryId).removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        $('#redactProductCategory' + categoryId).data('hidden', '0');
    } else {
        $('#redactProductCategory' + categoryId + ' .redact-category-sub-row-' + categoryId).hide();
        $('#redactProductCategory' + categoryId + ' .show-sub-category-btn-' + categoryId).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        $('#redactProductCategory' + categoryId).data('hidden', '1');
	}
}
function showProductsList(categoryId) {
	const hidden = $('#redactProductCategory' + categoryId + ' .show-category-products-btn-' + categoryId).data('hidden');
	if(hidden * 1 === 1) {
        $.ajax({type: 'GET', url: '/ajax/admin_category_products_list?categoryId=' + categoryId, async: true,
            success: function(data){
                $('#redactProductCategory' + categoryId + ' .category-products-list-' + categoryId).html(data);
                $('#redactProductCategory' + categoryId + ' .show-category-products-btn-' + categoryId).html('Скрыть список товаров').data('hidden', '0');
            }
        });
    } else {
        $('#redactProductCategory' + categoryId + ' .category-products-list-' + categoryId).html('');
        $('#redactProductCategory' + categoryId + ' .show-category-products-btn-' + categoryId).html('Показать список товаров').data('hidden', '1');
	}
}

$(document).ready(function() {
    $('.goodsName').keyup(function(){initTypeahead($(this).attr('row'), $(this).val(), 'name');});
    $('.goodsCode').keyup(function(){initTypeahead($(this).attr('row'), $(this).val(), 'code');});
    $('.goodsNum').blur(function(){var checkError = 0;if($('#goodsRootNum_'+$(this).attr('row')).val()*1 < $(this).val()*1){checkError = 1;}if(checkError == 0){addRealisationPosition($(this).attr('row'));}else if(checkError == 1) {alert('Указанное количество больше наличия на складе!');location.reload();}});
    $('.goods-field-realization').blur(function(){
        var checkError = 0;
        if($(this).attr('class').indexOf('goodsNum') != -1)
            if($('#goodsRootNum_'+$(this).attr('row')).val()*1 < $(this).val()*1)
                checkError = 1;
        if(checkError == 0)
            addRealisationPosition($(this).attr('row'));
        else if(checkError == 1)
            alert('Указанное количество больше наличия на складе!');
    });
    $(".goodsCode").keypress(function(e){if(e.keyCode==13){setMainAssort($(this).attr('row'), $(this).val(), 'code');}});
    $('.goods-field-income').blur(function(){addIncomePosition($(this).attr('row'));});
    $('.goods-field-cashincome').blur(function(){addCashincomePosition($(this).attr('row'));});
    $('.goods-field-return').blur(function(){addReturnPosition($(this).attr('row'));});
    $('.goods-field-cashreturn').blur(function(){addCashreturnPosition($(this).attr('row'));});
    $('.goods-field-writeoff').blur(function(){addWriteoffPosition($(this).attr('row'));});
    $('.goods-field-cashwriteoff').blur(function(){addCashwriteoffPosition($(this).attr('row'));});
    $('#searchUserName').typeahead({
        source: getUserNamesArr(getUsers()),
        items: 5,
        updater: function(item) {
            getUserRoles(item);
            return item;
        }
    });
    $('#searchUserName').blur(function(){getUserRoles($(this).val());});
    $('.checkUserRoles').change(function(){changeUserRole($(this).val());});
    $('.changeUserShop').change(function(){changeUserShop($(this).attr('user-id'), $(this).val());});
    $('#realizationContractor').change(function(){$('#discount').val($("#realizationContractor option:selected" ).data('discount'));setRealizationContractor();});
    $('body').click(function(){$('.admin-typeahead .typeahead').css('display', 'none');});
    $('#carryOutRealizationPost').click(function(){$(this).attr('disabled', 'disabled');checkNumSubmit('realization');});
    $('#carryOutWriteoffPost').click(function(){$(this).attr('disabled', 'disabled');checkNumSubmit('writeoff');});
});