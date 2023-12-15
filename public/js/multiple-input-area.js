$(document).on('click', '.btn-add', function(e){
        e.preventDefault();

        var controlForm = $(this).parents().parents('.block-copy'),
            currentEntry = $(this).parents('.entry:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('textarea:first').val('');
        controlForm.find('.entry:not(:last) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-info').addClass('btn-default')
            .html('<span class="glyphicon glyphicon-minus"></span>');
    }).on('click', '.btn-remove', function(e){
		$(this).parents('.entry:first').remove();
		e.preventDefault();
		return false;
});