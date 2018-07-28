(function() {
	$('.recharge').click(function() {
		$('#auditModal').modal('show');
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
  	var params = {
  		id: 1,
  		audit: 'adopted',
  		reason: ''
  	}
  	$.post('/template/aduit', params, function(res){
  		console.log(res);
  	})
  })
})()