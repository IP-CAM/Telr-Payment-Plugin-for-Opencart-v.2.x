<iframe src="https://secure.telrcdn.com/preload.html" id="telr_frame" name="telr_frame" style="width:100%;height:600px;display:none;border:0;margin:0;" frameborder="0"></iframe>

<form action="<?php echo $action; ?>" method="post" id="telr_form" target="telr_frame">
<div class="buttons">
   <div class="pull-right">
     <input type="submit" value="<?php echo $button_confirm; ?>" id="telr_submit" class="btn btn-primary" />
   </div>
 </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
	$("#telr_submit").click(function() {
		$("#telr_frame").show('slow');
		$("#telr_submit").hide();
	});
});
</script>
