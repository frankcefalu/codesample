<?php include('backend.php'); ?>
<?php if(!isset($_POST['state']) && !isset($_GET['state']) ): ?>

<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/frameworks/chat/chat.css">
<script type="text/javascript" > var nhash='<?php print $hash;?>'; var lastmid = 0; </script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/frameworks/chat/chat.js"></script>


<div class="panel panel-primary">
	<div class="panel-heading">
		<span class="glyphicon glyphicon-comment"></span> Live Stream Chat
	</div>
	<div class="panel-body chatpanel">
		<ul class="chat" id="chatbody">


		</ul>
		<span class="chathidden"></span>
	</div>
	<div class="panel-footer">
		<div class="input-group">
		<?php if (!is_user_logged_in()) { ?>
				Need To Login To Post
		<?php }else{ ?>
			<input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here...">
			<span class="input-group-btn">
				<button class="btn btn-warning btn-sm" id="btnchat">
					Send</button>
			</span>
		<?php } ?>
		</div>
	</div>
</div>


<?php endif; ?>
