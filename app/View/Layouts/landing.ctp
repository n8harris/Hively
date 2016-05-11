<?php
/**
 *  Part of the Alloy Library
 *
 *  Copyright (c) 2012, Tyler Seymour <tyler@unwitty.com>
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 *  following conditions are met:
 *
 *  Redistributions of source code must retain the above copyright notice, this list of conditions and the following
 *  disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following
 *  disclaimer in the documentation and/or other materials provided with the distribution.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 *  INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 *  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 *  WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
 *  USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */
	App::import('Lib', 'Alloy');


	// Load generated assets for processing
	$jsAppAssets = json_decode(file_get_contents(WWW_ROOT.'assets/jsapp_assets_map.json') , true);

	// use timestamp to mark a version
	$version  = null;
	if(!file_exists(JS .  "build/version")) {
		$version = time();
		file_put_contents(JS . "build/version", "$version");
	} else {
		$version = file_get_contents(JS . "build/version");
	}

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Member Page</title>

	<link href='assets/css/vendor.min.css?v=0.18.2' rel='stylesheet'>
	<link href='assets/css/jsapp.min.css?v=0.18.2' rel='stylesheet'>

	<link href='//fonts.googleapis.com/css?family=Dosis:400,200,300,500,600,700' rel='stylesheet' type='text/css'>

	<!--[if lt IE 9]>
	<script src="/js/html5shiv.js"></script>
	<![endif]-->

	<?php

		// Load jsApp HTML template script tags file for caching templates
		include_once(WWW_ROOT."assets/templates/jsapp_html_templates.php");

		// Output javascripts
		foreach($jsAppAssets['js'] as $key => $value){
			echo "<script type='text/javascript' src='".$value."'></script>\n";
		}

	?>

	<!-- Fav and touch icons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/Icon-144.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/Icon-114.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/ico/Icon-72.png">
	<link rel="apple-touch-icon-precomposed" href="/ico/Icon.png">
	<link rel="shortcut icon" href="/ico/Icon.png">

</head>

<body id="sub-page">
	<div id="inner-outer">
		<div id="headerTop"></div>
		<img class="logo-loader" src="/img/hively-blue-icon-logo.png" style="display:block" />

		<!--[if lt IE 10]>
			<div class="row">
				<div class="col-xs-12" id='ieWarning'>
					<p class='label label-default label-warning'> We're sorry, but your current browser is not supported.  Please upgrade to the newest version.</p>
				</div>
			</div>
		<![endif]-->

		<div id='main'></div>

	</div>
	<div id="globalFooter"></div>
	<script>
		(function() {
			App.environment = '<?php echo Configure::read('environment');?>';

			$(document).ready(function() {
				var header = new App.views.ControlHeader();
				header.inject($("#headerTop"), {method: "replaceWith"});

				var router = App.Router.getInstance();


				//instantiates the router
				Backbone.history.start();
			});

		}());
	</script>
</body>
</html>
