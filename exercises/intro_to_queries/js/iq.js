$( document ).ready(function() {
    $('#users').select2({
        width: '25%',
        data: introQueries.users
    });

    $('#submit').click(function() {
        let formData = new FormData($('form')[0]);
        $.ajax(
            {
                url: introQueries.ajaxpage,
                type: 'POST',
                processData: false,
                contentType: false,
                something: 'true',
                data: formData,
                success: function(pageData, textStatus, jqXHR) {
                    alert(pageData);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('an error occured');
                    console.log(errorThrown);
                }
            });
    });
});
