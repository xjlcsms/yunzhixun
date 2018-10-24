$(function() {
  var taskid;
  $('.rollback').click(function() {
    taskid = $(this).attr('data-id')
    $('#rejectModal').modal('show');
  })
  $('#sure').click(function() {
    var params = {
      taskid: taskid
    }
    console.log(params)
    $.post('/index/job/reject', params, function(res) {
      $('#rejectModal').modal('hide');
      if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
    })
  })
})