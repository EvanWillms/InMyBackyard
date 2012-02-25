<?php

return array(

	'GET /' => function() {
		return View::make('layouts.default')->nest('content', 'content.home')->nest('head', 'partials.head');
	},

	'GET /404' => function() {
		return Response::error('404');
	},

	'GET /500' => function() {
		return Response::error('500');
	},

);