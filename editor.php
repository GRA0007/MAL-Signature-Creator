<?php
if (isset($_COOKIE['MALSIG_LOGIN'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo unserialize($_COOKIE['MALSIG_LOGIN'])['username']; ?> | MyAnimeList Signature Creator</title>
		<link rel="stylesheet" href="/experiments/MAL_sig/res/ui.css" />
		<link rel="stylesheet" href="/experiments/MAL_sig/res/switchery.min.css" />
		<script src="/experiments/MAL_sig/res/switchery.min.js"></script>
		<script src="/experiments/MAL_sig/res/jquery.min.js"></script>
		<script src='/experiments/MAL_sig/res/spectrum.min.js'></script>
		<link rel='stylesheet' href='/experiments/MAL_sig/res/spectrum.min.css' />
	</head>
	<body>
		<h1><a href="/experiments/MAL_sig/">MyAnimeList Signature Creator</a></h1>
		<div id="content">
			<div id="nav-bar"><a href="/experiments/MAL_sig/editor/" <?php if (empty($_GET['page'])) { echo 'class="current"'; } ?>>General</a> <strong>|</strong> <a href="/experiments/MAL_sig/editor/files/" <?php if ($_GET['page'] == 'files') { echo 'class="current"'; } ?>>Files</a> <strong>|</strong> <a href="/experiments/MAL_sig/editor/elements/" <?php if ($_GET['page'] == 'elements') { echo 'class="current"'; } ?>>Elements</a> <strong>|</strong> <a href="/experiments/MAL_sig/editor/signature/" <?php if ($_GET['page'] == 'signature') { echo 'class="current"'; } ?>>Signature</a> <strong>|</strong> <a href="/experiments/MAL_sig/editor/source/" <?php if ($_GET['page'] == 'source') { echo 'class="current"'; } ?>>Source</a> <strong>|</strong> <a href="/experiments/MAL_sig/editor/help/" <?php if ($_GET['page'] == 'help') { echo 'class="current"'; } ?>>Help</a> <strong>|</strong> <a href="/experiments/MAL_sig/php/logout.php">Logout</a></div>
			<?php
			if (empty($_GET['page'])) {
			?>
			<form id="general-settings" method="POST" action="/experiments/MAL_sig/php/settings.php">
				<h2>General Settings</h2>
				<input type="hidden" name="mode" value="general" />
				<div class="item"><input type="checkbox" class="js-switch" id="public-sig" name="public-sig" checked /><label for="public-sig">Make signature public</label></div>
				<div class="item"><input type="checkbox" class="js-switch" id="png-output" name="png-output" /><label for="png-output">Output the signature as a png file (supports transparency)</label></div>
				<div class="item">
					<select id="sig-size" name="sig-size">
						<option value="550x45">Bar (550 x 45)</option>
						<option value="200x100">Small (200 x 100)</option>
						<option value="400x135" selected>Medium (400 x 135)</option>
						<option value="550x140">Large (550 x 140)</option>
						<option value="custom">Custom Size</option>
					</select>
					<label for="sig-size">Set signature size</label>
				</div>
				<div class="item" id="custom-size">
					<label for="width">Width: </label><input type="number" id="width" name="width" min="5" max="600" value="400" />&nbsp;&nbsp;&nbsp;&nbsp;<label for="height">Height: </label><input type="number" id="height" name="height" min="5" max="150" value="135" />
				</div>
				<div class="item"><button type="submit">Save</button></div>
			</form>
			<script>
				(function() {
					var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
					elems.forEach(function(html) {
						var switchery = new Switchery(html, { color: '#2e51a2'});
					});
					document.getElementById('sig-size').addEventListener("change", function() {
						if (document.getElementById('sig-size').value == 'custom') {
							document.getElementById('custom-size').style.display = 'block';
						} else {
							document.getElementById('custom-size').style.display = 'none';
						}
					});
				})();
			</script>
			<?php
			} else if ($_GET['page'] == 'files') {
			?>
			<form id="manage-files" method="POST" action="/experiments/MAL_sig/php/settings.php">
				<h2>Manage Files</h2>
				<input type="hidden" name="mode" value="files" />
				<div class="item"><button type="button" id="from-url" disabled>Get from URL</button><label id="from-drive">Upload<input type="file" accept="image/png,image/jpeg,image/jpg,image/gif" id="upload-input" multiple /></label><button type="button" id="download" onclick="downloadImage();" disabled>Download</button><button type="button" id="delete" onclick="deleteImage();" disabled>Delete</button></div>
				<div class="item" id="file-manager">
					<span id="drop-here-text">Drop images here</span>
					<div id="dropzone">
						<div id="image-list"></div>
					</div>
				</div>
				<div class="item"><button type="submit" disabled>Save</button></div>
			</form>
			<script src="/experiments/MAL_sig/download.min.js"></script>
			<script>
				function deleteImage() {
					if (confirm('Are you sure? This action is permanent.')) {
						$.ajax({
							url: '/experiments/MAL_sig/php/functions.php?mode=delete&id=' + $('.selected').attr('id'),
							type: 'GET',
							success: function(data) {
								console.log(data);
								$('.selected').hide();
							},
							error: function(jqXHR, textStatus) {
								alert(textStatus);
							}
						});
					}
				}
				function downloadImage() {
					/*var data = $('.selected').css('background-image').replace('url(','').replace(')','');
					var $img = $('<img src="' + data + '" />');
					//$img.attr('src', data);
					$img.on('load', function() {
						var c = document.getElementById("download-image");
						$('#download-image').height($img.height());
						$('#download-image').width($img.width());
						alert($img.height() + '   ' + $img.width());
						var ctx = c.getContext("2d");
						ctx.drawImage($img, 0, 0, $img.height(), $img.width());
						var title = $('.selected').attr('title');
						download(c.toDataURL(), title);
					});*/
					alert('Coming soon...');
				}

				(function() {
					Object.size = function(obj) {
						var size = 0, key;
						for (key in obj) {
							if (obj.hasOwnProperty(key)) size++;
						}
						return size;
					};
					function getFileExtension(filename) {
						return filename.split('.').pop();
					}


					//LOAD IMAGES
					$.ajax({
						url: '/experiments/MAL_sig/php/functions.php?mode=load',
						type: 'GET',
						dataType: 'json',
						success: function(data) {
							console.log(data[0]['title']);
							var length = Object.size(data);
							for (var i = 0; i < length; i++) {
								$('#image-list').append('<div title="' + data[i]['title'] + '" class="uploaded-image selectable" id="' + data[i]['id'] + '" style="background-image:url(/experiments/MAL_sig/images/' + data[i]['id'] + '.' + getFileExtension(data[i]['title']) + ');"></div>');
							}
						},
						error: function(jqXHR, textStatus) {
							alert(textStatus);
						}
					});
					
					
					$('#image-list').on('click', '.uploaded-image.selectable', function() {
						if ($(this).hasClass('selected')) {
							$('.uploaded-image').removeClass('selected');
							$('#delete, #download').attr('disabled', 'disabled');
						} else {
							$('.uploaded-image').removeClass('selected');
							$(this).addClass('selected');
							$('#delete, #download').removeAttr('disabled');
						}
					});
					
					//File upload script
					var obj = $("#dropzone");
					var acceptedTypes = {
						'image/png': true,
						'image/jpeg': true,
						'image/jpg': true,
						'image/gif': true
					};
					function sendFileToServer(formData,status)
					{
						var uploadURL ="/experiments/MAL_sig/php/functions.php?mode=new"; //Upload URL
						var extraData = {}; //Extra Data.
						var jqXHR=$.ajax({
								xhr: function() {
								var xhrobj = $.ajaxSettings.xhr();
								if (xhrobj.upload) {
										xhrobj.upload.addEventListener('progress', function(event) {
											var percent = 0;
											var position = event.loaded;
											var total = event.total;
											if (event.lengthComputable) {
												percent = Math.ceil(position / total * 100);
											}
											//Set progress
											status.setProgress(percent);
										}, false);
									}
								return xhrobj;
							},
						url: uploadURL,
						type: "POST",
						contentType:false,
						processData: false,
							cache: false,
							data: formData,
							success: function(data){
								status.setProgress(100);
								status.setId(data);
							}
						});
					 
						status.setAbort(jqXHR);
					}
					 
					var rowCount = $('#image-list .uploaded-image').length;
					function createStatusbar(obj)
					{
						 rowCount++;
						 this.statusbar = $("<div class='uploaded-image'></div>");
						 this.progressBar = $("<div class='progress-bar'><div></div></div>").appendTo(this.statusbar);
						 this.progressLabel = $("<span class='percentage'></span>").appendTo(this.statusbar);
						 this.abort = $("<div class='cancel-upload'>Cancel</div>").appendTo(this.statusbar);
						 this.cover = $("<div class='cover'></div>").appendTo(this.statusbar);
						 obj.find('#image-list').append(this.statusbar);
					 
						this.setFileNameSize = function(name,size,file)
						{
							var sizeStr="";
							var sizeKB = size/1024;
							if(parseInt(sizeKB) > 1024)
							{
								var sizeMB = sizeKB/1024;
								sizeStr = sizeMB.toFixed(2)+" MB";
							}
							else
							{
								sizeStr = sizeKB.toFixed(2)+" KB";
							}
							
							var reader = new FileReader();
							var thisElement = this;
							function setImage(callback) {
								reader.onload = function (event) {
									//console.log(event.target.result);
									callback(event.target.result);
								};

								reader.readAsDataURL(file);
							}

							setImage(function(result) {
								thisElement.statusbar.css('background-image', 'url(' + result + ')');
							});
							this.statusbar.attr('title', name);
							//this.size.html(sizeStr);
							console.log(size);
						}
						this.setProgress = function(progress)
						{
							this.progressBar.find('div').animate({ width: progress + '%' }, 10);
							this.progressLabel.html(progress + "%");
							if(parseInt(progress) >= 100)
							{
								this.abort.hide();
								this.cover.hide();
								this.progressLabel.hide();
								this.progressBar.hide();
								this.statusbar.addClass('selectable');
							}
						}
						this.setId = function(id) {
							this.statusbar.attr('id', id);
						}
						this.setAbort = function(jqxhr)
						{
							var sb = this.statusbar;
							this.abort.click(function()
							{
								jqxhr.abort();
								sb.hide();
							});
						}
					}
					function handleFileUpload(files,obj)
					{
					   for (var i = 0; i < files.length; i++) 
					   {
							if (acceptedTypes[files[i].type] === true && files[i].size <= 2097152) {
								var fd = new FormData();
								fd.append('file', files[i]);
						 
								var status = new createStatusbar(obj); //Using this we can set progress.
								status.setFileNameSize(files[i].name,files[i].size,files[i]);
								sendFileToServer(fd,status);
							} else {
								alert('Your file must be less that 2MB, and you can only upload the following file types: png, jpg and gif.');
							}
					   }
					}

					obj.on('dragenter', function (e) {
						e.stopPropagation();
						e.preventDefault();
						$(this).css('border', '5px dashed #BCBCBC');
					});
					obj.on('dragover', function (e) {
						e.stopPropagation();
						e.preventDefault();
					});
					obj.on('drop', function (e) {
						$(this).css('border', '5px dashed #DEDEDE');
						e.preventDefault();
						var files = e.originalEvent.dataTransfer.files;

						//We need to send dropped files to Server
						handleFileUpload(files,obj);
					});
					$('#upload-input').on('change', function(e) {
						handleFileUpload(e.target.files,obj);
					});
					
					$(document).on('dragenter', function (e) {
						e.stopPropagation();
						e.preventDefault();
					});
					$(document).on('dragover', function (e) {
					  e.stopPropagation();
					  e.preventDefault();
					  obj.css('border', '5px dashed #DEDEDE');
					});
					$(document).on('drop', function (e) {
						e.stopPropagation();
						e.preventDefault();
					});
				})();
			</script>
			<?php
			} else if ($_GET['page'] == 'elements') {
			?>
			<form id="manage-elements" method="POST" action="/experiments/MAL_sig/php/settings.php">
				<h2>Manage Elements</h2>
				<input type="hidden" name="mode" value="elements" />
				<div class="item"><button type="button" id="create-image" disabled>Create image</button><button type="button" id="create-text" disabled>Create text</button><button type="button" id="delete" disabled>Delete</button></div>
				<div class="item">
					<table id="manage-elements-table">
						<tr>
							<td width="30%">
								<select id="pick-element" name="pick-element" size="2">
									<option value="Caramel Slice.jpg" data-type="image">Caramel Slice.jpg</option>
									<option value="Hello World" data-type="text">Hello World</option>
								</select>
							</td>
							<td valign="top">
								<span id="select-an-item">Select an item to edit it</span>
								<div id="edit-image">
									<div class="miniItem">
										<label for="source">Source</label>
										<select id="select-image">
											<option value="0.jpg">Caramel Slice.jpg</option>
											<option value="1.png">Coffee in a cube.png</option>
											<option value="2.png">Amusphere.png</option>
											<option value="3.jpg">Binary Code.jpg</option>
											<option value="4.jpg">blobfish.jpg</option>
										</select>
									</div>
									<div class="miniItem">
										<span class="fixPos"><label for="xpos">X</label><input type="number" id="xpos" min="-550" max="550" value="0" />&nbsp;&nbsp;&nbsp;&nbsp;<label for="ypos">Y</label><input type="number" id="ypos" min="-550" max="550" value="0" />&nbsp;&nbsp;&nbsp;&nbsp;<label for="rot">Rotation</label><input type="number" id="rot" min="0" max="360" value="0" /></span>
									</div>
									<div class="miniItem">
										<span class="fixPos"><label for="height">Height</label><input type="number" id="height" min="0" max="500" value="0" />&nbsp;&nbsp;&nbsp;&nbsp;<label for="width">Width</label><input type="number" id="width" min="0" max="500" value="0" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="js-switch" id="aspect-ratio" /><label for="aspect-ratio">Keep aspect ratio</label></span>
									</div>
								</div>
								<div id="edit-text">
									<div class="miniItem">
										<label for="content-text">Content</label><input type="text" id="content-text" />&nbsp;&nbsp;<button type="button">Add Variable</button>
									</div>
									<div class="miniItem">
									<span class="fixPos"><label for="xpos">X</label><input type="number" id="xpos" min="-1000" max="1000" value="0" />&nbsp;&nbsp;&nbsp;&nbsp;<label for="ypos">Y</label><input type="number" id="ypos" min="-1000" max="1000" value="0" />&nbsp;&nbsp;&nbsp;&nbsp;<label for="rot">Rotation</label><input type="number" id="rot" min="0" max="360" value="0" /></span>
									</div>
									<div class="miniItem">
										<span class="fixPos"><label for="select-font">Font</label>
										<select id="select-font">
											<option value="arial">Arial</option>
											<option value="test">Test</option>
											<option value="test2">Test2</option>
										</select>
										&nbsp;&nbsp;
										<input type="number" id="font-size" min="1" max="100" value="20" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="font-color" value="#FFFFFF" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="textAlign selected" id="textAlignLeft">&nbsp;</button><button type="button" class="textAlign" id="textAlignCenter">&nbsp;</button><button type="button" class="textAlign" id="textAlignRight">&nbsp;</button></span>
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="item"><button type="submit" disabled>Save</button></div>
			</form>
			<script>
				(function() {
					var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
					elems.forEach(function(html) {
						var switchery = new Switchery(html, { color: '#2e51a2'});
					});

					$("#font-color").spectrum({
						preferredFormat: "hex",
						showInput: true,
						replacerClassName: 'select-color',
						containerClassName: 'select-color-container'
					});
					
					$('.textAlign').click(function() {
						if (!$(this).hasClass('selected')) {
							$('.textAlign').removeClass('selected');
							$(this).addClass('selected');
						}
					});
					$('#pick-element').change(function() {
						if ($(this).find(":selected").attr('data-type') == 'image') {
							$('#edit-text').hide();
							$('#edit-image').show();
							$('#select-an-item').hide();
						} else {
							$('#edit-text').show();
							$('#edit-image').hide();
							$('#select-an-item').hide();
						}
					});
				})();
			</script>
			<?php
			} else if ($_GET['page'] == 'signature') {
			?>
			<form id="edit-signature" method="POST" action="/experiments/MAL_sig/php/settings.php">
				<h2>Edit Signature</h2>
				<input type="hidden" name="mode" value="signature" />
				<div class="item"><button type="button" id="view" disabled>View signature</button><button type="button" id="refresh" disabled>Refresh</button></div>
				<div class="item">
					<table id="edit-signature-table">
						<tr>
							<td width="30%">
								<select id="pick-element" name="pick-element" size="2">
									<option value="test">Test</option>
									<option value="test2">Test 2</option>
									<option value="test3">Test 3</option>
									<option value="test4">Test 4</option>
									<option value="test5">Test 5</option>
								</select>
							</td>
							<td valign="top">
								<div class="miniItem">
									<label for="xpos">X</label><input type="number" id="xpos" min="-550" max="550" value="0" />&nbsp;&nbsp;&nbsp;&nbsp;<label for="ypos">Y</label><input type="number" id="ypos" min="-550" max="550" value="0" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="visibility">Show</label><input type="checkbox" class="js-switch" id="visibility" checked />
								</div>
								<div class="miniItem">
									<canvas id="signature-preview"></canvas>
								</div>
								<div class="miniItem">
									<label for="background-color">Background color</label><input type="text" id="background-color" value="#000000" />
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="item"><button type="submit" disabled>Save</button></div>
			</form>
			<script>
				(function() {
					var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
					elems.forEach(function(html) {
						var switchery = new Switchery(html, { color: '#2e51a2'});
					});
					
					$("#background-color").spectrum({
						preferredFormat: "hex",
						showInput: true,
						replacerClassName: 'select-color',
						containerClassName: 'select-color-container'
					});
				})();
			</script>
			<?php
			} else if ($_GET['page'] == 'source') {
			?>
			<form id="edit-source" method="POST" action="/experiments/MAL_sig/php/settings.php">
				<h2>Edit Source</h2>
				<input type="hidden" name="mode" value="source" />
				<div class="item">
					<textarea id="edit-source-textarea" name="source"><MALsig>
	<config>
		<username>GRA0007</username>
		<public>yes</public>
		<format>jpg</format>
	</config>
	<signature width="400" height="135">
		<element type="image" x="6" y="25" r="0" source="test.png" height="400" width="400" aspect-ratio="keep" />
		<element type="text" x="6" y="25" r="0" font-family="Arial" font-size="20" color="#FFF" text-align="left" limit="30">Template Signature</element>
	</signature>
</MALsig></textarea>
				</div>
				<div class="item"><button type="submit" disabled>Save</button></div>
			</form>
			<?php
			} else if ($_GET['page'] == 'help') {
			?>
			<div id="help">
				<h2>Help</h2>
				<div class="item">
					Help content...
				</div>
			</div>
			<?php
			} else {
			?>
			<span style="text-align:center; display:block; line-height:250px;">An error occured. (002)</span>
			<?php
			}
			?>
		</div>
		<a href="http://myanimelist.net/animelist/Benpai" id="footer">Benpai&#x1F4CE;</a>
		<!--<script src="/experiments/MAL_sig/ui.js"></script>-->
	</body>
</html>
<?php
} else {
	header("Location: ../");
	die('You aren\'t logged in. You may not view this page.');
}
?>