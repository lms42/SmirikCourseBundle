$(document).on('ready', function(e){
    $(document).on("submit", "form.user-task-review", function (e) {
        e.preventDefault();
        var form = $(e.target);
        $.post(form.attr('action'), form.serialize(), function (data) {
            if (data.result && $('#object' + data.result).length > 0) {
                $('#object' + data.result).hide();
            }
            form.find('.btn-close').trigger('click');
        }, 'json');
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

