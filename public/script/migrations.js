let url = window.location.protocol + '//' + window.location.hostname

$.post('/get-migrations')
    .done((res) => {
        try {
            res = JSON.parse(res);
            if (res.status === 'success') {
                $('[data-action=migration-container]').each((parentIndex, parent) => {
                    
                });

                $(res.data).each((descriptionIndex, description) => {
                    const parent = $('[data-action=migration-container][data-id=' + description.description + ']');
                    $(parent).find('[data-action=execute-migration][]').addClass('disabled');
                });
            }
        } catch ($e) {

        }
    });

$('[data-action=execute-migration]').each((indexButton, button) => {
    if ($(button).data('id') === 0 && $(button).data('type') === 'down') {
        $(button).addClass('disabled');
    }

    $(button).on('click', () => {
        $.post(url + '/migration', {
            up: $(button).data('type'),
            migrationID: $(button).data('id')
        }).done((res) => {
            try {
                res = JSON.parse(res);
                if (res.status === 'success') {
                    $(button).closest('tr')
                        .find($('[data-action=migration-checkbox]'))
                        .prop('checked', $(button).data('type') === 'up');
                    $(button).prop('disabled', true);
                    $(button).closest($('tr')).find($('[data-action=execute-migration]')).each((indexButton2, button2) => {
                        if (!$(button).data('id') === 0 || $(button).data('type') !== 'down') {
                            $(button2).toggleClass('disabled');
                        }
                    })
                }
            } catch (e) {
            }
        });
    });
});