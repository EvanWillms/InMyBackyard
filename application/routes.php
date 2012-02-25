<?php

return array(

	'GET /' => function() {
		Asset::add('styles', 'css/main.css');
		Asset::add('jquery', 'js/jquery.js');
		return View::make('layouts.default')->nest('content', 'content.home')->nest('head', 'partials.head')->nest('footer', 'partials.footer');
	},

	'GET /404' => function() {
		return Response::error('404');
	},

	'GET /500' => function() {
		return Response::error('500');
	},

);