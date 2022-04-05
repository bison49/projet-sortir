$(document).ready(


    $('#btnAjouter').click(function (event) {

            event.preventDefault();
        let lieux_nom = $(this).attr();
        let lieux_rue = $(this).attr("lieux[rue]");
        let lieux_longitude = $(this).attr("lieux[longitude]");
        let lieux_latitude = $(this).attr("lieux[latitude]");
        console.log(lieux_nom);
   /*     $.ajax({
            method: "POST",
            url: '/sortie/afficherPart',
            data: {
                'sortie_id': sortie_id,
            }
        }).done(function (response) {

            $('.listing').append($('<table></table>').addClass("table table-dark table-hover table-bordered")
            );
            let thead = $('<thead></thead>');
            let tr = $('<tr></tr>').addClass('text-center');
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
*/
    }))