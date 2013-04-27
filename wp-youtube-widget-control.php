<!-- form for getting the title and desired url from the user -->

<html>
<body>
	<p>
		<label for="youtube_title"><b>Title: <input name="youtube_title" type="text" value="<?php echo $title; ?>" /></label>
		<label for="youtube_size"><b>Size:<select name="youtube_size" value="<?php echo $size; ?>" /></label>
			<option value="auto" selected="selected">Auto</option>
			<option value="long">Long</option>
			<option value="wide">Wide</option>
			</select>
		<label for="youtube_link"><br/><br/>If you want to upload your own youtube video then paste its url here (if you want us to find you relevent video leave it blank) :</b><input name="youtube_link" type="text" value="<?php echo $link; ?>" /></label>
		<input type="hidden" id="youtube_submit" name="youtube_submit" value="1" />
	</p>
</body>
</html>