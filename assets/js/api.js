$(function() {
    $(".deleteBtn").on('click', function()  {
        let id = $(this).data('entity-id');
        $.ajax({
            type: 'POST',
            url: '/cp/sdf/Application/del',
            data: {
                idF: id,
            },
            dataType: 'json',
            success: function (data) {
                console.log('OK');
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    });
});
