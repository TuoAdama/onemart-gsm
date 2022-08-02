$(function(){

    var alert = $('#restart-alert');

    setInterval(function(){
        notify();
    }, 5 * 60 * 1000);

    function notify(){
        fetch('/notifcation')
        .then(response => response.json())
        .then(response => {
            response.notify ? alert.removeClass('d-none') : alert.addClass('d-none');
        })
    } 
});