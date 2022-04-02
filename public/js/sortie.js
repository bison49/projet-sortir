$(document).ready(
    $('.liste').click(function () {
        let sortie_id = $(this).attr("data-sortie");
        let orga_id = $(this).attr("data-orga")
        let user_id = $(this).attr("data-user")
        $.ajax({
            method: "POST",
            url: '/sortie/afficherPart',
            data: {
                'sortie_id': sortie_id,
            }
        }).done(function (response) {

            $('.listing').append($('<table></table>').addClass("table table-dark table-hover table-bordered")
            );
            let thead = $('<thead></thead>');
            let tr = $('<tr></tr>');
            tr.append($('<th scope="col">#</th>'))
            tr.append($('<th scope="col">' + "Pseudo" + '</th>'));
            tr.append($('<th scope="col">' + "Nom et prénom" + '</th>'));
            tr.append($('<th scope="col"></th>'));
            thead.append(tr);
            $('.listing .table').append(thead);
            let tbody = $('<tbody></tbody>');
            $('.listing .table').append(tbody);
            response.forEach(element => ajouterDom(element, orga_id, user_id));
        })

    }))
let cpt = 1


function ajouterDom(data, orga_id, user_id) {
    console.log(user_id)
    let tr = $('<tr></tr>')
    tr.append($('<td>' + cpt + '</td>'))
    tr.append($('<td>' + data.pseudo + '</td>'))
    tr.append($('<td>' + data.nom + ' ' + data.prenom + '</td>'))
    let td;
    let bouton;
    if (orga_id == user_id && data.id != null) {
        td = ($('<td></td>')).addClass('text-center')
        bouton = $('<button>Afficher profil</button>').addClass('btn btn-warning');
        bouton.on('click', data.id, afficher)
    } else {
        td = ($('<td></td>'))
    }
    td.append(bouton)
    tr.append(td)
    $('.listing .table').append(tr);
    cpt++;
}

function afficher(data) {
    window.location.href = '/profilParticipant/' + data.data;
}