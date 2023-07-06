$( document ).ready(function() {

        // enable the submission button once a field_name is selected
        $( '#field' ).change(function() {
            $( '#submission' ).removeAttr('disabled');
        });
});
