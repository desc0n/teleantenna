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
	$('.popover-product-number').popover();
    $('#mainSearchName').typeahead({
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
                case 'category':
                    url = '/catalog/?categoryId=' + parts[1];
                    break;
                case 'product':
                    url = '/item/product/' + parts[1];
                    break;
            }

            if (url !== '') {
                document.location = url;
            }
        }
    });
});