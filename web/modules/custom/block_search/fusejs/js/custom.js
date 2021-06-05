var rest_search = new XMLHttpRequest();
var searchElement = document.getElementById('input_search');
var results = document.getElementById('results');
rest_search.open("GET", "/rest/search", true);
rest_search.setRequestHeader("Content-Type", "application/json");
rest_search.send();

rest_search.onreadystatechange = function () {
    if (rest_search.readyState == 4) {
        var list = JSON.parse(rest_search.responseText);

        var options = {
            keys: [{
                name: 'title',
                weight: 0.9
            }, {
                name: 'field_chapo',
                weight: 0.7
            }, {
                name: 'field_sous_titre',
                weight: 0.8
            }]
        };
        var fuse = new Fuse(list, options);


        searchElement.addEventListener('keyup', function (e) {
            var response = fuse.search(searchElement.value);
            var content_type = {
              'Agenda':'AGENDA',
              'Page de base':'INFOS GÉNÉRALES',
              'Actualité':'ACTUALITÉ',
              'Enseignants':'ENSEIGNANT',
              'Pratiquer':'PRATIQUER',
              'Se former':'SE FORMER',
              'Atelier éducatifs':'ATELIER ÉDUCATIF'};
            results.innerHTML = '';

            for (var i = 0, div; i < 5; i++) {
                if(response.length) {
                    div = results.appendChild(document.createElement('div'));
                    div.innerHTML =
                        '<div class="row p-1 m-1">' +
                        '<div class="col-8">' + response[i].title + '</div>' +
                        '<div class="col-4 text-align-right">' + content_type[response[i].type] + '</div>' +
                        '</div>'
                }
            }
        });
    }
};
