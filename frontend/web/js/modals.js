$('#add-attendant').click(function(e){
    e.preventDefault();
    ShowModal($(this));
});

$('#add-email').click(function(e){
    e.preventDefault();
    ShowModal($(this));
});

$('#change-status').click(function(e){
    e.preventDefault();
    ShowModal($(this));
});

$('#modal').on('hidden.bs.modal', function(){
    $(this).find('.modal-content').html('')
});

function ShowModal($o){
    var $modal = $('#modal');
    var $url = $o.attr('href');
    $modal.find('.modal-content').load($url);
    $modal.modal('show');
}
