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
      console.log(res)
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
    console.log(params)
    $.post('/user/reback', params, function(res) {
      console.log(res)
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
    console.log(params);
    $.post('/user/resetpwd', params, function(res) {
      console.log(res)
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
      resetpwd: $('input[name=surePwd]').val()
    }
    $.post('/user/del', params, function(res) {
      console.log(res)
      delModalFina();
    })
  })
  $('#deleteSure').click(function() {
    $('#deleteModal').modal('hide');
    delModalInit();
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