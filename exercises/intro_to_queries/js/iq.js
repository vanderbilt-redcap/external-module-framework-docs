$( document ).ready(function() {
    $('#users').select2({
        width: '25%',
        data: introQueries.users
    });

    if(introQueries.message){
        alert(introQueries.message)
    }
});
