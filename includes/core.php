<?php

function kush_micro_news_output($no_of_news=0){

global $wpdb;
$table_name = $wpdb->prefix . "kushmicronews"; 

	 $color = array('#55A4F2','#8bbf36','#fff2a8','#33363B',' #F25555','#222','#999966','#FF66FF'); $i=0;
	if($no_of_news==0)
		{$no_of_news=get_option( "kush_mn_num_news");}
	$showBorder=get_option('kush_mn_show_lborder');
	$cleanHov=get_option('kush_mn_show_linkclean');
	 ?>
<?php $rows = $wpdb->get_results( "SELECT * FROM `$table_name` ORDER BY `time` DESC LIMIT 0,$no_of_news ;" );
?>
<div id="micro-news" class="clearfix">
<h2 class="head"><strong>Micro News</strong></h2>
<?php
foreach ( $rows as $row ) 
{	
 ?>	
	<div class="wrapNews <?php echo $row->id;?>" style="border-color:<?php if($showBorder=='true'){echo $color[$i];}?>">
    	<h3 class="title"><?php echo $row->name;?></h3><span class="postedOn"> on <?php $date=strtotime($row->time); echo date('d M Y',$date);?></span>
    	<div class="text"><?php echo $row->text;?></div>
        
        <?php if($row->url):?>
		<span class="link <?php if($cleanHov!='true'){echo 'clean';}?>"><a href="<?php echo $row->url;?>" title="<?php echo $row->name;?>" target="_blank">Read Full story &raquo;</a></span>
		<?php endif;?>
    </div> 
	
<?php
	if($i>=7)
		$i=0;
	else
		$i++;	
		
	}//foreach loop
?>
</div><?php //micro news ends

}
/////////////////////////////////////////////////////////////////////////////////////

function kush_micro_news_output_admin(){

global $wpdb;
	$table_name = $wpdb->prefix . "kushmicronews"; 	
	$what='';
	
if(isset($_POST['nTitle']) & empty($_POST['nTitle'])===false)
		{
		$title=sanitize($_POST['nTitle']);
		$id=sanitize($_POST['nId']);
		$content=sanitize($_POST['nContent']);
		$link=sanitize($_POST['nLink']);
		
		$query="UPDATE `$table_name` SET `name`='$title' ,`text`='$content' ,`url`='$link',`time`='".date('Y-m-d H:i:s')."' WHERE `id`='$id';";
		
			$chk=$wpdb->query($query);
			
			if($chk)
				$what= 'Updated Sucessfully.';
			else
				$what='Serious Error Occured :O';
		
		}
if(isset($_POST['dId']))
	{$id=sanitize($_POST['dId']);
		$query="DELETE FROM $table_name WHERE `id`='$id';";
		
			$chk=$wpdb->query($query);
			
			if($chk)
				$what= 'Deleted Sucessfully.';
			else
				$what='Serious Error Occured :O';
	}

if(is_admin())
	{	
		
	?>
	<?php 
		$totalpage=$wpdb->get_var( "SELECT COUNT(id) FROM `$table_name`;" );
		
		$perpage=10;		
		$totalpage=ceil($totalpage/$perpage);
		
		$page=(isset($_GET['pgno']))? (int)$_GET['pgno'] :1;
		$start=($page-1)*$perpage;
		
		$rows = $wpdb->get_results( "SELECT * FROM `$table_name` ORDER BY `time` DESC LIMIT $start,$perpage;" );
	?>
	<div class="wrap">
		<h3></h3>
		<div class="icon32" id="icon-edit"> <br /> </div>
		<h2>Micro News Posts</h2>
	
	<?php if($what!=''){echo '<h3>'.$what.'</h3>';}?>
	
		<div id="micro-news" class="clearfix widefat">	
		<?php $i=1;
		foreach ( $rows as $row ) 
		{	
		 ?>	
			<div class="wrapNews" data-id="<?php echo $row->id;?>">
				<span class="number"><?php echo $i;$i++;?>) </span>
				<h3 class="title <?php echo $row->id;?>" id="title-<?php echo $row->id;?>">
					<?php echo $row->name;?>
				</h3>
				<span class="postedOn"> <strong>on</strong> <?php $date=strtotime($row->time); echo date('d M Y',$date);?></span>
				<div class="text <?php echo $row->id;?>" id="text-<?php echo $row->id;?>"><?php echo $row->text;?></div>
				
				<strong>Reference Link : </strong><span class="link" id="link-<?php echo $row->id;?>"><?php echo $row->url;?></a></span>
				<input type="button" value="edit" class="button-primary editB"/>
				<input type="button" value="Delete" class="button-primary delB"/>
			</div>  
		<?php
			}//for each loop
		?>
		</div>
	</div>
	
	<?php //micro news ends
		if($totalpage>=1 && $page<$totalpage)	
			{?>
			<ul class="micro-news-post-nav"><li style="border:0;">Page : </li>
			<?php			
			for($z=1;$z<=$totalpage;$z++)
				if($z!=$page)
				{echo '<li ><a href="?page=micro-news&pgno='.$z.'">'.$z.'</a></li>';}
			}
		if($page!=1)
			{echo '<li><a href="?page=micro-news&pgno=1">Home</a></li>';}
	}// if closed
	?>
	</ul>
	<?php
}

?>