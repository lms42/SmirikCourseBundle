$(document).on('ready', function(e){
    $(document).on("click", ".user-task-review-action-type", function (e) {
        e.preventDefault();
        
        var button = $(e.target);
        var form = $('#user-task-review-form');
        
        url_data = form.serialize() + '&action=' + button.attr('name');
        
        $.post(form.attr('action'), url_data, function (data) {
            if (data.result && $('#object' + data.result).length > 0) {
                $('#object' + data.result).hide();
            }
            form.find('.btn-close').trigger('click');
        }, 'json');
    }); 
    $('#accordion_affix').collapse({
      toggle: false
    });
});

function assign_quiz_to_lesson(quiz_id, route, success_text)
{
	checked = [];
	$.each($('.admin_item_checkbox'), function(index, value){
		if (value.checked)
		{
			checked.push(value.value);
		}
	});
	h = {};
	h['ids'] = checked;
  $.post(route, $.param(h), function(data) {
		$.each($('.admin_item_checkbox'), function(index, value){
			value.checked = false;
		});
		$('#alerts').append($('#alert_template').clone().show());
  });
}

