$(document).on('change', '#sortie_ville', function () {
    chargerListeLieux();
})

function chargerListeLieux(){

        $.ajax({ method: "POST",
        url: $('#path_ajax').data("href"),
        data: {
        'ville_id' : $('#sortie_ville').val(),
    }
    }).done(function (response) {
        $('#sortie_noLieu').html('');
        for(var i = 0 ; i < response.length ; i++) {
            var lieu = response[i];
            let option = $('<option value="'+lieu["id"]+'">'+lieu["nom"]+'</option>');

    $('#sortie_noLieu').append(option); } }) }