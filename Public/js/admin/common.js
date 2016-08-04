/* add button */

$('#button-add').click(function(){
	var url = SCOPE.add_url;
	window.location.href = url;

});

/*submit form*/

$('#singcms-button-submit').click(function(){
	var data = $('#singcms-form').serializeArray();
	postData = {};
	$(data).each(function(i){
		postData[this.name] = this.value;
	});
	
	var url = SCOPE.save_url;
	var jump_url = SCOPE.jump_url;
	$.post(url,postData,function(result){
		if(result.status == 1){
			return dialog.success(result.message,jump_url);
		}else if(result.status ==0){
			return dialog.error(result.message);
		}
	},'JSON');
});


/*edit*/

$('.singcms-table #singcms-edit').on('click', function(){
	var id = $(this).attr('attr-id');
	var url = SCOPE.edit_url + '&id='+ id;
	window.location.href = url;

});

/*delete*/
$('.singcms-table #singcms-delete').on('click', function(){
	var id = $(this).attr('attr-id');
	var a = $(this).attr('attr-a');
	var message = $(this).attr('attr-message');
	var url = SCOPE.set_status_url;

	data={};
	data['id'] = id;
	data['status'] = -1;

	layer.open({
		type: 0,
		title: 'delete it ?',
		btn: ['yes', 'no'],
		icon: 3,
		closeBtn: 2,
		content: 'are you sure?' + message,
		scrollbar: true,
		yes: function(){
			todelete(url,data);
		},
	});
});

function todelete(url,data){
	$.post(url,data,function(s){
		if(s.status == 1) {
			return dialog.success(s.message,'');
		}else{
			return dialog.error(s.message);
		}
	},'JSON');
}

/*listorder*/
$('#button-listorder').click(function(){
	var data = $('#singcms-listorder').serializeArray();
	postData = {};
	$(data).each(function(i){
		postData[this.name] = this.value;
	});

	var url = SCOPE.listorder_url;
	$.post(url,data,function(result){
		if(result.status == 1){
			return dialog.success(result.message, result['data']['jump_url']);
		}else{
			return dialog.error(result.message, result['data']['jump_url']);
		}
	},'JSON');
});

$('.singcms-table #singcms-on-off').on('click', function(){
	var id = $(this).attr('attr-id');
	var status = $(this).attr('attr-status');
	var url = SCOPE.set_status_url;

	data={};
	data['id'] = id;
	data['status'] = status;

	layer.open({
		type: 0,
		title: 'submit it ?',
		btn: ['yes', 'no'],
		icon: 3,
		closeBtn: 2,
		content: 'are you sure?',
		scrollbar: true,
		yes: function(){
			todelete(url,data);
		},
	});
});


/*push js*/

$('#singcms-push').click(function(){
	var id = $('#select-push').val();
	
	if(id == 0){
		return dialog.error('please select positioin');
	}
	push ={};
	postData={};
	$("input[name='pushcheck']:checked").each(function(i){
		push[i] = $(this).val();
	});

	postData['push'] = push;
	postData['position_id'] = id;
	var url = SCOPE.push_url;
	$.post(url,postData,function(result){
		if(result.status == 1){
			return dialog.success(result.message,result['data']['jump_url']);
		}
		if(result.status == 0){
			return dialog.error(result.message);
		}
	},'JSON');
});