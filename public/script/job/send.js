(function(){
  $('input[name="title"]').bind('input propertychange', function() { 
    var val = $(this).val();
    var content = $('#content').val();
    var end = 0;
    var title = '';

    if (val.length > 8) {
      val = val.substring(0, 8)
      $(this).val(val);
    }

    end = content.indexOf(']');
    if (end > -1) {
      content = content.substring(end + 1);
    }
    title = '[' + val + ']';
    content = title + '' + content;
    showLen(content);
  });

  $('textarea[name="content"]').bind('input propertychange', function() { 
    var content = $(this).val()
    showLen(content);
  });

  $('input[type="radio"]').click(function(){
    if ($(this).val() == 1) {
      $('#auto').removeClass('none');
      $('#manual').addClass('none');
    } else {
      $('#auto').addClass('none');
      $('#manual').removeClass('none');
    }
  });
})()

function showLen(content) {
  var len = 0;
  var branch = 1;

  len = content.length;
  if (len >= 498 ) {
    content = content.substring(0,500);
  }
  len = content.length;
  if (len <= 70) {
  } else {
    if ((len - 70) % 68 == 0) {
      branch += (len - 70)/68;
    } else {
      branch += (len - 70)/68 + 1
    }
  }
  $('#content').val(content);
  $('#num').text(len);
  $('#branch').text(parseInt(branch));
}