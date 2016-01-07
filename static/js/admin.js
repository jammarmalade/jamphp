
$(function(){
	$('#addtagbtn').unbind("click").click(function(){
		var tagname=$('#addtag').val();
		if(tagname==''){
			alert('请填入标签名称');
			return false;
		}
		var url='admin.php?action=tag&do=add';
		var data={tagname:tagname};
		_ajax(url,data,function(res){
			callback_tag(res);
		});
	})

	function callback_tag(res){
		res=_ajax_return(res);
		if(res==0){
			alert('请求失败');
			return false;
		}
		if(res['status']==1){
			alert('添加成功');
			$('#addtag').val('');
			$('#addtag').focus();
		}else{
			$('.label-danger').text(res['data']);
		}
	}
	//nav
	$('.update-nav').unbind("click").click(function(){
		updatenav($(this));
	})
	function updatenav(dom){
		var id=dom.attr('data');
		var inputdom=dom.parents('tr').find('input');
		var name=inputdom.eq(0).val();
		var displayorder=inputdom.eq(1).val();
		var link=inputdom.eq(2).val();
		var showstatus=inputdom.eq(3).is(':checked');
		if(name==''){
			alert('导航名称不能为空');
			inputdom.eq(0).focus();
			return false;
		}
		var url='admin.php?action=setting&do=nav&type=update';
		var data={id:id,name:name,displayorder:displayorder,link:link,status:showstatus};
		_ajax(url,data,function(res){
			callback_navupdate(res);
		});
	}
	function callback_navupdate(res){
		res=_ajax_return(res);
		if(res==0){
			alert('请求失败');
			return false;
		}
		if(res['status']!=1){
			alert(res['data']);
		}
	}

	$('.addnav').unbind("click").click(function(){
		var id=$(this).attr('data');
		var html='';
		if(id=='' || typeof id =='undefined'){
			html='<tr><td><input type="text" class="form-control"></td><td><input type="text" class="form-control"></td><td><input type="text" class="form-control"></td><td><div class="checkbox"><label><input type="checkbox" checked="checked"> 是</label></div></td><td><button type="button" class="btn btn-default insertnav" data="0">添加</button>';
		}else{
			html='<tr><td style="padding-left:40px;"><input type="text" class="form-control"></td><td><input type="text" class="form-control"></td><td><input type="text" class="form-control"></td><td><div class="checkbox"><label><input type="checkbox" checked="checked"> 是</label></div></td><td><button type="button" class="btn btn-default insertnav" data="'+id+'">添加</button>';
		}
		html+=' <button type="button" class="btn btn-default del-nav" data="0">删除</button></td></tr>';
		$(this).parent('td').parent('tr').before(html);
		$('.insertnav').unbind("click").click(function(){
			insertnav($(this));
		})
		$('.del-nav').unbind("click").click(function(){
			delnav($(this));
		})
	})

	function insertnav(dom){
		var inputdom=dom.parents('tr').find('input');
		var name=inputdom.eq(0).val();
		var displayorder=inputdom.eq(1).val();
		var link=inputdom.eq(2).val();
		var showstatus=inputdom.eq(3).is(':checked');
		var pid=dom.attr('data');
		if(name==''){
			alert('导航名称不能为空');
			inputdom.eq(0).focus();
			return false;
		}
		var url='admin.php?action=setting&do=nav&type=add';
		var data={pid:pid,name:name,displayorder:displayorder,link:link,status:showstatus};
		_ajax(url,data,function(res){
			res=_ajax_return(res);
			if(res==0){
				alert('请求失败');
				return false;
			}
			if(res['status']!=1){
				alert(res['data']);
			}else{
				dom.text('修改');
				dom.attr('data',res['data']);
				dom.next('button').attr('data',res['data']);
				dom.removeClass('insertnav').addClass('update-nav');
				$('.update-nav').unbind("click").click(function(){
					updatenav($(this));
				})
			}
		});
	}
	//del nav
	$('.del-nav').unbind("click").click(function(){
		delnav($(this));
	})
	function delnav(dom){
		var id=dom.attr('data');
		if(id==0 || id==''){
			alert('请先添加');
			return false;
		}
		if(confirm("确认删除？")){
			var url='admin.php?action=setting&do=nav&type=del';
			var data={id:id};
			_ajax(url,data,function(res){
				res=_ajax_return(res);
				if(res==0){
					alert('请求失败');
					return false;
				}
				if(res['status']==1){
					var trdom=dom.parents('tr');
					trdom.hide(500,function(){
						trdom.remove();
					});
				}else{
					alert(res['data']);
				}
			});
		}
	}

	//del nav
	$('.cache-nav').unbind("click").click(function(){
		setting_cache('nav');
	})
	function setting_cache(dotype){
		var url='admin.php?action=setting&do='+dotype+'&type=cache';
		var data={};
		_ajax(url,data,function(res){
			res=_ajax_return(res);
			if(res==0){
				alert('请求失败');
				return false;
			}
			if(res['status']!=1){
				alert(res['data']);
			}
		});
	}
})
