(function() {
  $('.showcontent').click(function() {
    $('#content').val('');
    $('#content').val($(this).text())
    $('#detailModal').modal('show');
  })
  $('#close').click(function() {
    $('#detailModal').modal('hide');
  })
})()