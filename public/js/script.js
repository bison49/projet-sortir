function fonctionRechercheTextFiltre()
{
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("form_rechercher");
            filter = input.value.toUpperCase();
            table = document.getElementById("Table");
            tr = table.getElementsByTagName("tr");
                for (i = 0; i < tr.length; i++)
                {
                    td = tr[i].getElementsByTagName("td")[0];
                    if (td)
                    {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1)
                        {
                            tr[i].style.display = "";
                        }
                        else
                        {
                            tr[i].style.display = "none";
                        }
                    }
                }
}




    function rechercherAvancer4(){(function rechercher() {
    "use strict";

    var TableFilter = (function () {
        var search;

        function dquery(selector) {
            // Renvoie un tableau des éléments correspondant au sélecteur
            return Array.prototype.slice.call(document.querySelectorAll(selector));
        }

        function onInputEvent(e) {
            // Récupère le texte à rechercher
            var input =  e.target;
            search = input.value.toLocaleLowerCase();
            // Retrouve les lignes où effectuer la recherche
            // (l'attribut data-table de l'input sert à identifier la table à filtrer)
            var selector = input.getAttribute("data-table4") + " tbody tr";
            var rows = dquery(selector);
            // Recherche le texte demandé sur les lignes du tableau
            [].forEach.call(rows, filter);

        }

        function filter(row) {
            // Mise en cache de la ligne en minuscule
            if (row.lowerTextContent === undefined)
                row.lowerTextContent = row.textContent.toLocaleLowerCase();
            // Masque la ligne si elle ne contient pas le texte recherché
            row.style.display = row.lowerTextContent.indexOf(search) === -1 ? "none" : "table-row";
        }

        return {
            init: function () {
                // Liste des champs de saisie avec un attribut data-table
                var inputs = dquery("input[data-table4]");
                [].forEach.call(inputs, function (input) {
                    // Déclenche la recherche dès qu'on saisi un filtre de recherche
                    input.oninput = onInputEvent;
                    // Si on a déjà une valeur (suite à navigation arrière), on relance la recherche
                    if (input.value !== "") input.oninput({ target: input });
                });

            }
        };

    })();

    TableFilter.init();
})();

}





    function rechercherAvancer3(){(function rechercher() {
    "use strict";

    var TableFilter = (function () {
        var search;

        function dquery(selector) {
            // Renvoie un tableau des éléments correspondant au sélecteur
            return Array.prototype.slice.call(document.querySelectorAll(selector));
        }

        function onInputEvent(e) {
            // Récupère le texte à rechercher
            var input =  e.target;
            search = input.value.toLocaleLowerCase();
            // Retrouve les lignes où effectuer la recherche
            // (l'attribut data-table de l'input sert à identifier la table à filtrer)
            var selector = input.getAttribute("data-table3") + " tbody tr";
            var rows = dquery(selector);
            // Recherche le texte demandé sur les lignes du tableau
            [].forEach.call(rows, filter);

        }

        function filter(row) {
            // Mise en cache de la ligne en minuscule
            if (row.lowerTextContent === undefined)
                row.lowerTextContent = row.textContent.toLocaleLowerCase();
            // Masque la ligne si elle ne contient pas le texte recherché
            row.style.display = row.lowerTextContent.indexOf(search) === -1 ? "none" : "table-row";
        }

        return {
            init: function () {
                // Liste des champs de saisie avec un attribut data-table
                var inputs = dquery("input[data-table3]");
                [].forEach.call(inputs, function (input) {
                    // Déclenche la recherche dès qu'on saisi un filtre de recherche
                    input.oninput = onInputEvent;
                    // Si on a déjà une valeur (suite à navigation arrière), on relance la recherche
                    if (input.value !== "") input.oninput({ target: input });
                });

            }
        };

    })();

    TableFilter.init();
})();

}



    function rechercherAvancer2(){(function rechercher() {
    "use strict";

    var TableFilter = (function () {
        var search;

        function dquery(selector) {
            // Renvoie un tableau des éléments correspondant au sélecteur
            return Array.prototype.slice.call(document.querySelectorAll(selector));
        }

        function onInputEvent(e) {
            // Récupère le texte à rechercher
            var input =  e.target;
            search = input.value.toLocaleLowerCase();
            // Retrouve les lignes où effectuer la recherche
            // (l'attribut data-table de l'input sert à identifier la table à filtrer)
            var selector = input.getAttribute("data-table2") + " tbody tr";
            var rows = dquery(selector);
            // Recherche le texte demandé sur les lignes du tableau
            [].forEach.call(rows, filter);

        }

        function filter(row) {
            // Mise en cache de la ligne en minuscule
            if (row.lowerTextContent === undefined)
                row.lowerTextContent = row.textContent.toLocaleLowerCase();
            // Masque la ligne si elle ne contient pas le texte recherché
            row.style.display = row.lowerTextContent.indexOf(search) === -1 ? "none" : "table-row";
        }

        return {
            init: function () {
                // Liste des champs de saisie avec un attribut data-table
                var inputs = dquery("input[data-table2]");
                [].forEach.call(inputs, function (input) {
                    // Déclenche la recherche dès qu'on saisi un filtre de recherche
                    input.oninput = onInputEvent;
                    // Si on a déjà une valeur (suite à navigation arrière), on relance la recherche
                    if (input.value !== "") input.oninput({ target: input });
                });

            }
        };

    })();

    TableFilter.init();
})();

}
    function rechercherAvancer() {
    (function rechercher() {
        "use strict";

        var TableFilter = (function () {
            var search;

            function dquery(selector) {
                // Renvoie un tableau des éléments correspondant au sélecteur
                return Array.prototype.slice.call(document.querySelectorAll(selector));
            }

            function onInputEvent(e) {
                // Récupère le texte à rechercher
                var input = e.target;
                search = input.value.toLocaleLowerCase();
                // Retrouve les lignes où effectuer la recherche
                // (l'attribut data-table de l'input sert à identifier la table à filtrer)
                var selector = input.getAttribute("data-table") + " tbody tr";
                var rows = dquery(selector);
                // Recherche le texte demandé sur les lignes du tableau
                [].forEach.call(rows, filter);
                // Mise à jour du compteur de ligne (s'il y en a un de défini)
                // (l'attribut data-count de l'input sert à identifier l'élément où afficher le compteur)
                var writer = input.getAttribute("data-count");
                if (writer) {
                    // S'il existe un attribut data-count, on compte les lignes visibles
                    var count = rows.reduce(function (t, x) {
                        return t + (x.style.display === "none" ? 0 : 1);
                    }, 0);
                    // Puis on affiche le compteur
                    dquery(writer)[0].textContent = count;
                }
            }

            function filter(row) {
                // Mise en cache de la ligne en minuscule
                if (row.lowerTextContent === undefined)
                    row.lowerTextContent = row.textContent.toLocaleLowerCase();
                // Masque la ligne si elle ne contient pas le texte recherché
                row.style.display = row.lowerTextContent.indexOf(search) === -1 ? "none" : "table-row";
            }

            return {
                init: function () {
                    // Liste des champs de saisie avec un attribut data-table
                    var inputs = dquery("input[data-table]");
                    [].forEach.call(inputs, function (input) {
                        // Déclenche la recherche dès qu'on saisi un filtre de recherche
                        input.oninput = onInputEvent;
                        // Si on a déjà une valeur (suite à navigation arrière), on relance la recherche
                        if (input.value !== "") input.oninput({target: input});
                    });
                }
            };

        })();

        TableFilter.init();
    })();


}



