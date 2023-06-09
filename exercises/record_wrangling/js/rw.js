$( document ).ready(function() {

        // enable the submission button once a field_name is selected
        $( '#field' ).change(function() {
            $( '#submission' ).removeAttr('disabled');
        });


        // send an AJAX request when the submission button is clicked
        $('#submission').click(function() {
            console.log(recordWrangling.ajaxpage);
            let field_name = $( '#field option:selected' ).val();
            let new_value = $( '#newValue' ).val();
            $.post(recordWrangling.ajaxpage, 
                {
                    field_name: field_name,
                    new_value: new_value
                }
            )
            .done(function(data) {
                // Do something with a response
                alert(`Inserted ${new_value} into ${field_name} for all records`);
            })
            .fail(function(err) {
                // Do something with an error
                //alert('fail');
                console.log(err);
            });
        });
});
