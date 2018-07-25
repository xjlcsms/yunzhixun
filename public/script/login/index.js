$(function(){

  $('#login').click(function() {
    var params = {
      username: $('#username').val(),
      password: $('#password').val(),
      isremember: $('#isremember').prop("checked") ? 1 : 0
    }
    //登陆
    $.get('/login/i', params, function(data){
      if (data.status === true) {
        window.location.href = '/'
      }
    })
  })
 
})