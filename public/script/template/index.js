(function() {
	$('.recharge').click(function() {
		$('#auditModal').modal('show');
    $('#id').val($(this).attr('data-id'));
	})
  $('.showcontent').click(function() {
    $('#content').val('');
    $('#content').val($(this).text())
    $('#detailModal').modal('show');
  })
  $('#close').click(function() {
    $('#detailModal').modal('hide');
  })
  $('#audit').click(function() {
    var params = $('#auditForm').serializeArray();
  	$.post('/template/aduit', params, function(res){
  		if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
  	})
  })
})()