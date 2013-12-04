jQuery(document).on("click", "a.ajax-ready", function(event){
    event.preventDefault();
    var url = $(this).attr('href');
    jQuery.ajax({
        url : url
    })
    .success(function(data){
        jQuery('body').append('\
        <div class="modal fade" id="success-modal" role="dialog" aria-labelledby="success" aria-hidden="true"\\n\
        <div class="modal-dialog">\
            <div class="modal-content">\
              <div class="modal-header">\
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
                <h4 class="modal-title" id="success">Récupérer la liste de vos pads</h4>\
              </div>\
              <div class="modal-body">\
                '+data+'\
              </div>\
              <div class="modal-footer">\
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
              </div>\
            </div>\
          </div>\
        </div>\
        ');
        $('#success-modal').modal();
    })
    .error(function(jqXHR, desc, errorThrown){
        var errorHTML = jQuery(jqXHR.responseText);
        console.log(jqXHR.responseText);
        jQuery('body').append('\
        <div class="modal fade" id="error-modal" role="dialog" aria-labelledby="error" aria-hidden="true"\\n\
        <div class="modal-dialog">\
            <div class="modal-content">\
              <div class="modal-header">\
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
                <h4 class="modal-title" id="error">Une erreur est survenue</h4>\
              </div>\
              <div class="modal-body">\
                Oups! Il semble que quelque chose ne fonctionne pas comme prévu. Tentez de recommencer plus tard.\
              </div>\
              <div class="modal-footer">\
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
              </div>\
            </div>\
          </div>\
        </div>\
        ');
        $('#error-modal').modal();
    });
});