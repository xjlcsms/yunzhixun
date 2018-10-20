var unum = 0;
var inputLock = false;
(function(){
  document.querySelector('#sign').addEventListener('compositionstart', function(){
    inputLock = true;
  });
  document.querySelector('#sign').addEventListener('compositionend', function(){
    var content = $('#content').val();
    var end = 0;
    var title = '';
    if (this.value.length > 8) {
      this.value = this.value.substring(0, 8)
      $('#sign').val(this.value);
    }

    end = content.indexOf('】');
    if (end > -1) {
      content = content.substring(end + 1);
    }
    title = '【' + this.value + '】';
    content = title + '' + content;
    showLen(content);
    inputLock = false;
  });
  $('input[name="sign"]').bind('input propertychange', function() { 
    var val = $(this).val();
    var content = $('#content').val();
    var end = 0;
    var title = '';

    if (val.length > 8) {
      val = val.substring(0, 8)
      $(this).val(val);
    }

    end = content.indexOf('】');
    if (end > -1) {
      content = content.substring(end + 1);
    }
    title = '【' + val + '】';
    content = title + '' + content;
    showLen(content);
  });
  // 号码池改变事件
  $('input[name="smstype"]').change(function() {
    if (input[name="smstype"] == 1) {
      $('#phoneText').val('');
    }
  })
  // 字数统计
  $('textarea[name="content"]').bind('input propertychange', function() { 
    var content = $(this).val()
    showLen(content);
  });

  // 导入手机方式选择
  $('input[type="radio"]').click(function(){
    if ($(this).val() == 1) {
      $('#auto').removeClass('none');
      $('#manual').addClass('none');
    } else {
      $('#auto').addClass('none');
      $('#manual').removeClass('none'); 
      $('#smsFile').val('');
      $('#result').addClass('none');
    }
  });

  // 文件上传
  $('#smsFile').change(function() {
    var formdata = new FormData();
    var smsfile = $("#smsFile")[0].files[0];
    var idx = window.location.href.indexOf('?id=')
    var taskid =  window.location.href.substring(idx+4);

    formdata.append("smsfile",smsfile);
    $.ajax({
      url: '/index/job/smsfile?taskid=' + taskid,
      type: "post",
      data: formdata,
      async: true,
      cache: false,
      contentType: false,
      processData: false,
      success: function(res){
        if (res.status === true) {
          $('#fileName').text(res.data.filename);
          $('#totalNum').text(res.data.total);
          $('#useNum').text(res.data.true);
          showLen($('#content').val());
          $('#errNum').text(res.data.repeat);
          $('#reNum').text(res.data.repeat);
          $('#auto').addClass('none');
          $('#result').removeClass('none');
        } else {
          alert(res.msg)
        }
      },
      error: function(err){
        console.log(err);
      }
    });
  });

  // 删除文件
  $('#delSmsFile').click(function() {
    var params = {
      fileName: $('#fileName').text()
    }
    $.post('/index/job/delsmsf', params, function(res) {
      if (res.status === true) {
        $('#smsFile').val('');
        $('#result').addClass('none');
        $('#auto').removeClass('none');
        $('#useNum').text('');
        showLen($('#content').val());
      } else {
        alert(res.msg);
      }
    })
  });

  // 短信发送
  $('#sendBtn').click(function() {
    var idx = window.location.href.indexOf('?id=')
    var taskid =  window.location.href.substring(idx+4);
    var params = {
      type: $('#type').val(),
      smstype: $('input[name=smstype]:checked').val(),
      taskid: taskid,
      content: $('#content').val(),
      smsfile: $('#fileName').text(),
      mobiles: $('#phoneText').val()
    }
    $.post('/index/job/sms', params, function(res) {
      if (res.status === true) {
        alert(res.msg)
        window.location.href = '/index/job/deal?taskid=' + taskid
      } else {
        alert(res.msg)
      }
    })
  });
  // 监听短信发送类型
  $('input[name=smstype]').change(function() {
    showLen($('#content').val());
  })
  // 监听手机输入
  $('textarea[name="phoneText"]').bind('input propertychange', function() { 
    showLen($('#content').val());
  });
})()

function showLen(content) {
  var len = 0;
  var branch = 0;
  var mobileArr = [];
  len = content.length;
  if (len >= 498 ) {
    content = content.substring(0,500);
  }
  len = content.length;
  if (len <= 70 && len != 0) {
    branch = 1;
  } else {
    branch = 1;
    if ((len - 70) % 68 == 0) {
      branch += (len - 70)/68;
    } else {
      branch += (len - 70)/68 + 1
    }
  }
  $('#content').val(content);
  $('#num').text(len);
  $('#branch').text(parseInt(branch));
  if ($('input[name="smstype"]:checked').val() == 2) {
    unum = 0;
    if ($('#phoneText').val() != '') {
      if ($('#phoneText').val().indexOf('，') > -1) {
        mobileArr = $('#phoneText').val().split('，');
      } else {
        mobileArr = $('#phoneText').val().split(',');
      }
      for (var i in mobileArr) {
        if (mobileArr[i].length == 11) {
          unum++
        }
      }
    }
  } else {
    unum = $('#useNum').text() != '' ? parseInt($('#useNum').text()) : 0;
  }
  $('#use').text(parseInt(branch) * unum);
}