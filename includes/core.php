<?php

function kush_micro_news_output($no_of_news=0,$header="true"){
	//this is responsible for displaying the final output to user site in widgets or anywhere this function is called!
	//$header attribute will decide whether to show Micro News Header or not
global $wpdb;
$table_name = $wpdb->prefix . "kushmicronews"; 

	$color = array('#55A4F2','#8bbf36','#fff2a8','#33363B','#F25555','#666666','#999966','#FF66FF');
	$i=0;//counter for multiple colors.
	if($no_of_news==0)
		{$no_of_news=get_option( "kush_mn_num_news");}
	$showBorder=get_option('kush_mn_show_lborder');
	$cleanHov=get_option('kush_mn_show_linkclean');
	$widgetName = get_option('kush_mn_widget_name');
	$titleColor = get_option('kush_mn_color_title');
	$textColor = get_option('kush_mn_color_text');
	$linkColor = get_option('kush_mn_color_link');
	
	$rows = $wpdb->get_results( "SELECT * FROM `$table_name` ORDER BY `time` DESC LIMIT 0, $no_of_news ");

	$output_html = "";//this will contain final output


	$output_html .= '<div id="micro-news" class="clearfix">';

	if($header=="true"){
		$output_html .= '<h2 class="head"><strong>'.$widgetName.'</strong></h2>';
	}//header if closed 

	
	foreach ( $rows as $row ) 		
	{	
		$date=strtotime($row->time);
		$formateddate = date('d M Y',$date);
	
		$output_html .='<div class="wrapNews '.$row->id.'" style="border-color:';
		if($showBorder=='true')//check border color
			{$output_html .=$color[$i];}
		$output_html .='">';

			//show title
	    	$output_html .='<h3 class="title" style="color:'.$titleColor.'">'.$row->name.'</h3>';  	
	    	
	    	//show text
	    	$output_html .='<div class="text" style="color:'.$textColor.'">'.$row->text.' ';
	    		$output_html .='<span class="postedOn"> on '.$formateddate.'</span>';
	    	$output_html .='</div>';
	        
	        //show url if present
	        if($row->url):
				$output_html .='<span class="link ';
				if($cleanHov!='true')//check if show link color inverted
					$output_html .='clean';
				$output_html .='"><a href="'.$row->url.'" title="'.$row->name.'" target="_blank" style="color:'.$linkColor.'">Read Full story &raquo;</a></span>';
			endif;

	    $output_html .='</div>';//wrapNews ends
	
		//this will reloop border color
		if($i>=7)
			$i=0;
		else
			$i++;	
			
	}//foreach loop
	
$output_html .='</div>';//micro news ends

return $output_html;
}//kush_micro_news_output function ends

//////----------------------///////

//this will handle the admin page of Micro news
function kush_micro_news_output_admin(){

global $wpdb;
	$table_name = $wpdb->prefix . "kushmicronews"; 	
	$what='';
	
if(isset($_POST['nTitle']) & empty($_POST['nTitle'])===false)
		{
		if(get_option('kush_mn_parse_html')=='false')
		{
			$title=sanitize($_POST['nTitle']);
			$id=sanitize($_POST['nId']);
			$content=sanitize($_POST['nContent']);
			$link=sanitize($_POST['nLink']);
		}
		else
		{
			$title=$_POST['nTitle'];
			$id=$_POST['nId'];
			$content=$_POST['nContent'];
			$link=$_POST['nLink'];
		}
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
	
		<div id="micro-news-board" class="clearfix widefat">	
		<?php $i=1;
		foreach ( $rows as $row ) 
		{	
		 ?>	
			<div class="wrapNews" data-id="<?php echo $row->id;?>">
				<span class="number"><?php echo $i;$i++;?>) </span>
				<h2 class="title" id="mn-title-<?php echo $row->id;?>">
					<?php echo $row->name;?>
				</h2>
				
				<div class="text" id="mn-text-<?php echo $row->id;?>">
					<?php echo $row->text;?>
				</div>
				<div class="container-admin-meta-link">
					<span> <strong>on</strong> <?php $date=strtotime($row->time); echo date('d M Y',$date);?></span>
					|
					<strong>Reference Link : </strong><span id="mn-link-<?php echo $row->id;?>"><?php echo $row->url;?></a></span>
				</div>
				<input type="button" value="Edit" class="button-primary editB" data-id="mn-edit-<?php echo $row->id;?>"/>
				<input type="button" value="X" class="button-primary closeB" />
				<input type="button" value="Delete" class="button-primary delB"/>
			</div>  
			<hr>
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