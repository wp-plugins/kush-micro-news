jQuery(function ($) {$(document).ready(function(){	$('#micro-news .editB').click(function(){								parent=$(this).parent();				id=$(parent).attr('data-id');				var title=$.trim($('#mn-title-'+id).html());		var content=$.trim($('#mn-text-'+id).html());		var link=$('#mn-link-'+id).html();				var fields='<form action="" method="post" id="update-micro-news" class="update-micro-news-'+id+'"><div class="row"><label for="nTitle">Title:</label><input type="text" name="nTitle" class="title-updated regular-text" value="'+title+'"/></div><div class="row"><label for="nContent">Content:</label><textarea class="text-updated" name="nContent">'+content+'</textarea></div><div class="row"><label for="nLink">Link:</label><input type="text" name="nLink" class="link-updated regular-text" value="'+link+'"/></div><input type="hidden" name="nId" value="'+id+'"/><div class="row"><input type="submit" class="submit-update"></div></form>';						dir=object.url;		dirpost=dir+'update-news.php';				var checkopen=parent.hasClass('open');		if(!checkopen)			{parent.append(fields);			parent.addClass('open');						$(this).attr('value','Update');}		else			{						title=$('#update-micro-news .title-updated').val();			content=$('#update-micro-news .text-updated').val();			link=$('#update-micro-news .link-updated').val();										$.ajax({					url:location.href,					cache:false,					type:"post",					data:{nId:id,nTitle:title,nContent:content,nLink:link},					success:function(){						window.location.reload();					}				});								$('#update-micro-news').remove();				parent.removeClass('open');				$(this).attr('value','edit');			}						});			$('#micro-news .delB').click(function(){		parent=$(this).parent();				id=$(parent).attr('data-id');					$.ajax({					url:location.href,					cache:false,					type:"post",					data:{dId:id},					success:function(){						parent.hide('slow');					}				});		});		$('#micro-news .closeB').click(function(){		parent=$(this).parent();				id=$(parent).attr('data-id');			$('#micro-news .update-micro-news-'+id).remove();		parent.removeClass('open');		$('#micro-news .editB').attr('value','edit');			});});});