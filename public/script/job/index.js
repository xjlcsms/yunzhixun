(function(){
	$('#open').click(function() {
		var params = {
			username: 'text01',
			password : 'text011',
			companyName : 'text01',
			type: '1',
			account: '123456',
			rawPassword  : '123456'
		}
		$.post('/user/insert', params, function(res) {
			console.log(res);
		})
	})
	$('.recharge').click(function() {
		var params = {
			type: 3,
      smstype: 0,
      mobiles: '18030016446',
      taskid: 1,
      content: '1111'
		}
		$.post('/job/sms', params, function(res) {
			console.log(res)
		})
	})
})()