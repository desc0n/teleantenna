function getCart(){
	if($('.catalog-table').length || $('.item-cart-add').length){
		$.ajax({type: 'POST', url: '/ajax/get_cart', async: true, data:{}})
				.done(function(data){
					var cartData = JSON.parse(data);
					for(i=0;i<cartData.length;i++){
						hideCartAddButton(cartData[i]['product_id']);
						showCartInButton(cartData[i]['product_id']);
					}
				});
	}
}
function getCartNum(){
	$.ajax({type: 'POST', url: '/ajax/get_cart_num', async: true, data:{}})
			.done(function(data){$('.cart-num').html(data);});
}
getCart();
getCartNum();
function setInCart(id){
	$.ajax({type: 'POST', url: '/ajax/set_in_cart', async: true, data:{product_id: id}})
			.done(function(data){$('.cart-num').html(data);hideCartAddButton(id);showCartInButton(id);});
}
function setCartPositionNum(action, id){
	var checkNum = 0;
	var rootNum = $('#positionNum_'+id).val();
	if(action == 'minus' && rootNum == 1)
		checkNum = 1;
	if(checkNum == 0) {
		var newNum = action == 'minus' ? (rootNum * 1 - 1) : (rootNum * 1 + 1);
		$.ajax({type: 'POST', url: '/ajax/set_cart_position_num', async: true, data: {num: newNum, id: id}})
				.done(function (data) {
					$('#positionNum_' + id).val(newNum);
					$('#positionSum_' + id).html(newNum * $('#positionPrice_' + id).val());
					rewriteAllPrice();
					getCartNum();
				});
	}
}
function rewriteAllPrice(){
	var positionsPrices = $('.position-price');
	var positionsNum = $('.position-num');
	var allPrice = 0;
	if(positionsPrices.length > 0) {
		for (i=0;i<positionsPrices.length;i++){
			allPrice += positionsPrices[i].value * positionsNum[i].value;
		}
	}
	$('#allPrice').html(allPrice);
}
function removeCartPosition(id){
	$.ajax({type: 'POST', url: '/ajax/remove_from_cart', async: true, data:{id: id}})
			.done(function(data){$('#tableRow_'+id).remove();rewriteAllPrice();getCartNum();});
}
function removeAllCartPositions(){
	$.ajax({type: 'POST', url: '/ajax/remove_all_cart', async: true, data:{}})
			.done(function(data){$('.cart-table tbody').html('');rewriteAllPrice();getCartNum();});
}
function hideCartAddButton(id){
	$('#addCartButton_'+id).css('display', 'none');
}
function showCartInButton(id){
	$('#addInCartButton_'+id).css('display', 'block');
}
function createOrder(){
	var checkPhone = $('#customerPhone').val();
	var replacePhone = checkPhone.replace(/[^\d,]/g, '');
	var $selectedShop = $('.cart-shop:checked');

	if(replacePhone.length != 10){
		$('#error-message').html('Некорректно указан номер телефона!');
		$('#errorModal').modal();

		return false;
	}

	if ($('#allPrice').text() == 0) {
		$('#error-message').html('В корзине отсутствуют товары!');
		$('#errorModal').modal();

		return false;
	}

	if (!$selectedShop.length) {
		$('#error-message').html('Не выбран магазин!');
		$('#errorModal').modal();

		return false;
	}

	$.ajax({
		type: 'POST',
		url: '/ajax/check_availability',
		async: true,
		data:{
			selectedShop: $selectedShop.val()
		}
	})
		.done(function(data){
			var result = JSON.parse(data);

			if (result.length > 0) {
				var text = '<strong>В выбранном магазине отсутствует товар</strong><br /><br />';

				i = 1;

				for (var key in result) {
					text += '<div>' + i + '. ' + result[key] + '</div>';

					i++;
				}

				text += '<br /><div><strong>Время сборки заказа увеличится. </strong></div><br />'
				text += '<div><button class="btn btn-success" onclick=newOrderForm.submit();"">Продолжить?</button></div>'

				$('#error-message').html(text);
				$('#errorModal').modal();

				return false;
			}

			newOrderForm.submit();
		})
	;

}
function getCustomerCartPost(){
	var postData = {
		customerName : $('#customerName').length ? $('#customerName').val() : '',
		customerPhone : $('#customerPhone').length ? $('#customerPhone').val() : '',
		customerMail : $('#customerMail').length ? $('#customerMail').val() : '',
		customerStreet : $('#customerStreet').length ? $('#customerStreet').val() : '',
		customerHouse : $('#customerHouse').length ? $('#customerHouse').val() : '',
		customerFlat : $('#customerFlat').length ? $('#customerFlat').val() : '',
		customerComment : $('#customerComment').length ? $('#customerComment').val() : '',
		customerDeliveryType : $('.delivery-type').length ? $('input[class=delivery-type]:radio:checked').val() : ''
	}
	return postData;
}
function setInCartCustomer(){
	$.ajax({type: 'POST', url: '/ajax/set_cart_customer', async: true, data:{postData: getCustomerCartPost()}})
			.done(function(data){});
}
function setCartShop(id){
	$.ajax({type: 'POST', url: '/ajax/set_cart_shop', async: true, data:{shop_id: id}})
			.done(function(data){});
}
function setDeliveryType(id){
	$.ajax({type: 'POST', url: '/ajax/set_delivery_type', async: true, data:{type_id: id}})
			.done(function(data){});
}
$(document).ready(function() {
	$('.cart-add').click(function(){
		setInCart($(this).val());
	});
	$('.position-num-plus').click(function(){
		setCartPositionNum('plus', $(this).val());
	});
	$('.position-num-minus').click(function(){
		setCartPositionNum('minus', $(this).val());
	});
	$('.removePosition').click(function(){
		removeCartPosition($(this).val());
	});
	$('.removeAllPositions').click(function(){
		removeAllCartPositions();
	});
	$('.delivery-type').click(function(){
		if($(this).val() == 0) {
			$('#deliveryTypeForm1').show();
			$('#deliveryTypeForm2').hide();
		}

		if($(this).val() == 1) {
			$('#deliveryTypeForm1').hide();
			$('#deliveryTypeForm2').show();
		}

		setInCartCustomer();
	});
	$('#shortCustomerPhone').keyup(function(){
		$('#customerPhone').val($(this).val());
	});
	$('#customerPhone').keyup(function(){
		$('#shortCustomerPhone').val($(this).val());
	});
	$('#shortCustomerMail').keyup(function(){
		$('#customerMail').val($(this).val());
	});
	$('#customerMail').keyup(function(){
		$('#shortCustomerMail').val($(this).val());
	});
	$('.cart-customer-field').keyup(function(){
		setInCartCustomer();
	});
	$('.cart-shop').click(function(){
		setCartShop($(this).val());
	});
});