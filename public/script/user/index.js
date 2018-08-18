(function() {
  var userid = ''
  // 充值
  $('.recharge').click(function() {
    userid = $(this).attr('data-id');
    $('#rechargeModal').modal('show');
  })
  $('#recharge').click(function() {
    var params = {
      userid: userid,
      recharge: $('input[name=recharge]').val()
    }
    $.post('/user/recharge', params, function(res) {
      if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
    })
  })

  // 回退
  $('.rollback').click(function() {
    userid = $(this).attr('data-id');
    $('#rollbackModal').modal('show');
  })
  $('#rollback').click(function() {
    var params = {
      userid: userid,
      reback: $('input[name=rollback]').val()
    }
    $.post('/user/reback', params, function(res) {
      if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
    })
  })

  // 重置密码
  $('.reset').click(function() {
    userid = $(this).attr('data-id');
    $('#resetModal').modal('show');
  })
  $('#reset').click(function() {
    var params = {
      userid: userid,
      resetPwd: $('input[name=reset]').val()
    }
    $.post('/user/resetpwd', params, function(res) {
      if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
    })
  })

  // 帐号删除
  $('.delete').click(function() {
    userid = $(this).attr('data-id');
    $('#deleteModal').modal('show');
  })
  $('#deletePrev').click(function() {
    var params = {
      userid: userid,
      surePwd: $('input[name=surePwd]').val()
    }
    $.post('/user/del', params, function(res) {
      if (res.status === true) {
        delModalFina();
      } else {
        alert(res.msg)
      }
    })
  })
  $('#deleteSure').click(function() {
    delModalInit();
    $('#deleteModal').modal('hide');
  })

  // 开户
  $('#open').click(function() {
    $('#openModal').modal('show');
  })
  $('#openBtn').click(function() {
    var params = $('#userForm').serializeArray();
    $.post('/user/insert', params, function(res) {
      if (res.status === true) {
        location.reload();
      } else {
        alert(res.msg);
      }
    })
  })
})()

function delModalInit() {
  $('input[name=surePwd]').show();
  $('#deletePrev').show();
  $('#delTitle').text('身份确认')
  $('#rate').hide();
  $('#deleteSure').hide();
}
 function delModalFina() {
  $('input[name=surePwd]').hide();
  $('#deletePrev').hide();
  $('#delTitle').text('帐号删除')
  $('#rate').show();
  $('#deleteSure').show();
}