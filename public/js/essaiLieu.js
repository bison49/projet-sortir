$(document).ready(
    $('.testmomo').click(function (event) {
        event.preventDefault();
    }))
    $('#btnAjouter').click(function (event) {

        event.preventDefault();
        let ville = $('#lieux_ville').val();
        let lieux_nom = $('#lieux_nom').val();
        let lieux_rue = $('#lieux_rue').val();
        let lieux_longitude = $('#lieux_longitude').val();
        let lieux_latitude = $('#lieux_latitude').val();

        $.ajax({
            method: "POST",
            url: '/lieux/ajout',
            data: {
                'nom': lieux_nom,
                'rue': lieux_rue,
                'longitude': lieux_longitude,
                'latitude': lieux_latitude,
                'ville': ville,
            }
        }).done(function (response) {
            $('#sortie_noLieu').html('');
            for (var i = 0; i < response.length; i++) {
                var lieu = response[i];
                let option = $('<option value="' + lieu["id"] + '">' + lieu["nom"] + '</option>');

                $('#sortie_noLieu').append(option);
            }
        })
        $('#sortie_ville').val(ville);
        reset();
    })

function reset() {
    $('#lieux_nom').val('');
    $('#lieux_rue').val('');
    $('#lieux_longitude').val('');
    $('#lieux_latitude').val('');
}

$('.btn-secondary').click(function (event) {
    event.preventDefault();
    reset();
})
