var userContainer = $('#ifensl_padmanagerbundle_pad_padUsers');
var containerLength = userContainer.find(':input').length;
var $addUserLink = $('<a href="#" class="add_user_link">Ajouter un utilisateur (courriel)</a>');
var $newLink = $('<div></div>').append($addUserLink);

jQuery(document).ready(function() {

    userContainer.find('div').each(function() {
        addUserFormDeleteLink($(this));
    });

    // ajoute l'ancre « ajouter un utilisateur (courriel)»
    userContainer.append($newLink);

    $addUserLink.on('click', function(e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();

        // ajoute un nouveau formulaire user (voir le prochain bloc de code)
        addUserForm(userContainer, $newLink);
    });

    // On ajoute un premier formulaire au départ
    if (containerLength === 0) {
        addUserForm(userContainer, $newLink);
    } else {
    // Pour les autres utilisateurs, on ajoute un lien de suppression
        userContainer.children('div').each(function() {
            addUserFormDeleteLink($userFormLi);
        });
    }
});

function addUserForm(userContainer, $newLink) {
    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = userContainer.attr('data-prototype');
    var newForm = prototype.replace(/__name__label__/g, '');

    // Affiche le formulaire dans la page dans un div, avant le lien "ajouter un utilisateur"
    var $newFormLi = $('<div></div>').append(newForm);
    $newLink.before($newFormLi);

    // Ne pas ajouter de bouton suppression sur le premier formulaire
    if (containerLength !== 0) {
        addUserFormDeleteLink($newFormLi);
    }

    containerLength++;
}

function addUserFormDeleteLink($userFormLi) {
    var $removeFormA = $('<a href="#">Supprimer cet utilisateur</a>');
    $userFormLi.append($removeFormA);
    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $userFormLi.remove();
    });
}