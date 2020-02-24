define(function(require, exports, module) {

	var $ = require('jquery'); //加载Jquery 1.11.1
	var common = require('common'); //加载公共函数文件（语言文字获取等）

	/*弹出框*/
	require('own_tem/remodal/jquery.remodal.css');
	require('own_tem/remodal/jquery.remodal.min');
	if($(".temset").length>0){
		function schange(dm){
			var tr = dm.parents("tr"),
				id = tr.find("input[name='id']").val(),
				vl = dm.val();
			if(vl==1){
				$("input[name='bigclass-"+id+"']").val('');
				$("input[name='defaultvalue-"+id+"']").hide();
				$("input[name='tips-"+id+"']").hide();
				var name=$("input[name='name-"+id+"']").val();
				if(name) $("input[name='name-"+id+"']").after('<span style="padding: 0 5px;">'+name+'</span>').attr({hidden:true});// 分区变量名不可编辑
				tr.addClass('fenqu');
				tr.find(".nowaddlist").show();
				tr.find("i").show();
			}else{
				var bigclass=tr.prev().length?(tr.prev().hasClass('fenqu')?tr.prev().find('input[name*=id]').val():tr.prev().find('input[name*=bigclass]').val()):null;
				$("input[name='bigclass-"+id+"']").val(bigclass);
				$("input[name='defaultvalue-"+id+"']").show();
				$("input[name='tips-"+id+"']").show();
				$("input[name='name-"+id+"']").show();
				tr.removeClass('fenqu');
				tr.find(".nowaddlist").hide();
				tr.find("i").hide();
			}
			if(vl==4||vl==6){
				tr.find(".selectd").show();
			}else{
				tr.find(".selectd").hide();
			}
		}

		$(document).on( 'init.dt', function (e, settings, json) {
			if($(".temset tr.fenqu").length==0){
				$(".temset tr").removeClass('xuanxiang');
			}
			$(".temset tr.fenqu:eq(0)").prevAll().removeClass('xuanxiang');
			$("select.temset_select").each(function(){
				schange($(this));
			});
			/*拖曳排序*/
			require('own_tem/js/jquery.dragsort-0.5.2.min');
			var $dragsort=$("table.ui-table tbody");
			$dragsort.dragsort({
				dragSelector: "tr",
				dragBetween: false ,
				dragSelectorExclude:"input,textarea,select,a,i,span",
				dragEnd: function() {
					var $prev = $(this).prev('tr'),
						id  = $("input[name=id]",this).val();
					// 拖动分区选项到达位置后，其子选项跟随移动（分区标签只能拖动到另一个分区所有子选项之后）
					if($(this).hasClass('fenqu')){
						// 分区选项位置调整
						if($prev.hasClass('fenqu') || !$prev.hasClass('xuanxiang')){
							var prev_bigclass=$prev.find("input[name*=bigclass]").val()||$prev.find("input[name*=id]").val()||null,
								$prev_bigclass=$dragsort.find("tr:not(.fenqu) input[name*=bigclass][value="+prev_bigclass+"]"),
								after_index=$prev_bigclass.length-1;
							$prev_bigclass.eq(after_index).parents('tr').after($(this));
						}
						// 子选项跟随移动
						var sub_options=new Array();
						$dragsort.find("tr:not(.fenqu) input[name*=bigclass][value="+id+"]").each(function(index, el) {
							var $tr=$(this).parents('tr');
							sub_options.push($tr);
							$tr.remove();
						});
						$(this).after(sub_options);
					}else{// 拖动子选项后，其分区bigclass改变
						var prev_bigclass=$prev.find("input[name*=bigclass]").val()||null,
							$fenqu_fa_caret=$dragsort.find("tr.fenqu input[name*=bigclass][value="+prev_bigclass+"]").parents('tr').find('.fa-caret-right');
						$("input[name*=bigclass]",this).val(prev_bigclass);
						if($fenqu_fa_caret.length) $fenqu_fa_caret.click();
					}

					$("input[name='id']").attr("checked",true);
					$("input[name='no_order-"+id+"']").val($prev.find("input[name='id']").val());
				}
			});
		});

		$(document).on('change',"select.temset_select",function(){
			schange($(this));
		});

		var box = $('[data-remodal-id=modal]');

		$(document).on('click',"a.selectd",function(){
			var url = $(this).attr('href'),
				tr = $(this).parents("tr"),
				id = tr.find("input[name='id']").val(),
				da = {
					selectd:$("input[name='selectd-"+id+"']").val(),
					type:$("select[name='type-"+id+"']").val(),
					style:$("input[name='style-"+id+"']").val(),
					id:id
				};

				box.find(".temset_box").load(url,da,function(){
					var inst = $.remodal.lookup[box.data('remodal')];
					common.defaultoption($(this));
					inst.open();
					common.AssemblyLoad($(this));
				});
			return false;
		});

		$(document).on('click',".temsetlist input[name='style-submit']",function(){
			var vl = $(".temsetlist input[name='temstyle']:checked").val();
			$("input[name='style-"+$(".temsetlist").attr("data-temid")+"']").val(vl);
			var inst = $.remodal.lookup[box.data('remodal')];
			inst.close();
			return false;
		});

		$(document).on('click',".temsetlist input[name='selectd-submit']",function(){
			var vl = $(".temsetlist input[name='selectd']").val();
				vl = vl.replace(/\|/g,'$T$');
			$("input[name='selectd-"+$(".temsetlist").attr("data-temid")+"']").val(vl);
			var inst = $.remodal.lookup[box.data('remodal')];
			inst.close();
			return false;
		});
		$(document).on('click',".temset input[name='save']",function(){
			$("input[name='id").attr('checked',true);
			var dom = $(".temset .dataTable tr .met-center i");
			$('.temset .dataTable tbody tr').removeClass('xuanxiang');
			dom.attr('class','fa fa-caret-down');
		});
		$(document).on('click',".temset tr.fenqu i",function(){
			var tr = $(this).parents('tr.fenqu');
			var trlist = tr.nextUntil('tr.fenqu');
			if($(this).attr('class')=='fa fa-caret-right'){
				trlist.removeClass('xuanxiang');
				$(this).attr('class','fa fa-caret-down');
			}else{
				trlist.addClass('xuanxiang');
				$(this).attr('class','fa fa-caret-right');
			}
		});

		$(document).on('click',".temset .dataTable tfoot tr .met-center i,.temset .dataTable thead tr .met-center i",function(){
			var dom = $(".temset .dataTable tr .met-center i");
			var tr = $('.temset .dataTable tbody tr.fenqu');
			if($(this).attr('class')=='fa fa-caret-right'){
				$('.temset .dataTable tbody tr').removeClass('xuanxiang');
				dom.attr('class','fa fa-caret-down');
			}else{
				$('.temset .dataTable tbody tr').not(tr).addClass('xuanxiang');
				dom.attr('class','fa fa-caret-right');
			}
		});

		var ainew = 10000;
		$(document).on('click',".nowaddlist:visible",function(){
			var i = $(this).parents('tr.fenqu');
			var d = i.nextUntil('tr.fenqu');
			d.removeClass('xuanxiang');
			i.find('i').attr('class','fa fa-caret-down');
			d = d.length>0?d.last():i;
			//AJAX获取HTML并追加到页面
			d.after('<tr><td colspan="'+d.find('td').length+'">Loading...</td></tr>');

			$.ajax({
				url: $(this).attr("href"),//新增行的数据源
				type: "POST",
				data: 'ai=' + ainew,
				success: function(data) {
					d.next("tr").remove();
					d.after(data);
					d.next("tr").find("input[name*=bigclass]").val(d.find("input[name*=bigclass]").val());// 加上分区bigclass
					d.next("tr").find("input[type='text']").eq(0).focus();
					common.defaultoption(d.next("tr"));
				}
			});

			ainew++;
			return false;
		});

		// 底部自定义标签加上分区bigclass
		$(document).on('click',"[data-table-addlist]",function(){
			var $self=$(this);
			setTimeout(function(){
				var newlist_last=$self.parents('.dataTable').find('tbody tr.newlist:last-of-type'),
					bigclass=newlist_last.prev('tr').find("input[name*=bigclass]").val()||newlist_last.prev('tr').find("input[name*=id]").val();
				newlist_last.find("input[name*=bigclass]").val(bigclass);// 加上分区bigclass
			},1000)
		})
	}else{
		$(document).on('click',"a.addtem",function(){
			var url = $(this).attr('href');
			var box = $('[data-remodal-id=modal]');
			box.find(".temset_box").load(url,'',function(){
				var inst = $.remodal.lookup[box.data('remodal')];
				common.defaultoption($(this));
				inst.open();
				// common.modexecution();
			});
			return false;
		});
	}


});
