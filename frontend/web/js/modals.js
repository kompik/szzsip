$('#add-attendant').click(function(e){
   e.preventDefault();
   var $modal = $('#modal');
   var $url = $(this).attr('href');
   $modal.find('.modal-content').load($url);
   $modal.modal('show');
});


