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
	$('.main-page-shop').click(function(){
        document.location = '/item/shop/' + $(this).data('id');
    });
	$('.popover-product-number').popover();
    $('input[type=text][name=mainSearchName]').typeahead({
        source: function (query, process) {
            return $.get('/ajax/find_products', {query: query},
                function (response) {
                    var data = [];
                    $.each(response, function(key, value)
                    {
                        data.push(value.target + '_' + value.id + '_' + value.name);
                    });

                    return process(data);
            }, 'json')
        }, highlighter: function(item) {
            var parts = item.split('_');
            return parts[2];
        }, updater: function(item) {
            var parts = item.split('_');
            var url = '';
            switch (parts[0]) {
                case 'group1':
                    url = '/catalog/?group_1=' + parts[1];
                    break;
                case 'group2':
                    url = '/catalog/?group_2=' + parts[1];
                    break;
                case 'group3':
                    url = '/catalog/?group_3=' + parts[1];
                    break;
                case 'name':
                    url = '/item/product/' + parts[1];
                    break;
            }

            if (url != '') {
                document.location = url;
            }
        }
    });
});