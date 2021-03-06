jQuery(document).on('submit','#iniciosesion',function(event){
    event.preventDefault();

    jQuery.ajax({
        url: 'login/gestionlogin.php',
        type: 'POST',
        dataType: 'json',
        data: $(this).serialize(),
        beforeSend: function(){
        }
    })
    .done(function(respuesta){
        console.log(respuesta);
        if(!respuesta.error){
            if(respuesta.tipo == 1){
                location = "admindash.php";
            }else if(respuesta.tipo == 0){
                location = "userdash.php";
            }else{
                location = "admindash.php";
            }
        }else{
            $('.inicio').slideDown('slow');
            setTimeout(function(){
                $('.inicio').slideUp('slow');
            }, 3000);
        }
    })
    .fail(function(resp){
        console.log(resp.responseText);
        $('.error').slideDown('slow');
        setTimeout(function(){
            $('.error').slideUp('slow');
        }, 3000);
    })
    .always(function(){
        console.log("Complete");
    });
});