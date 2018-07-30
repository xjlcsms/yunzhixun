(function(){
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