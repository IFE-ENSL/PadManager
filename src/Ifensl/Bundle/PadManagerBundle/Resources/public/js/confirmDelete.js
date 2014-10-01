$('button.confirm-delete').on('click', function() {
    if(confirm("Voulez-vous vraiment supprimer ce pad?")) {
        return true;
    }
    else {
        return false;
    }
});