<?php

Autoloader::add_namespace('\\Twitter', __DIR__.'/classes/');

Autoloader::add_classes(array(
	'\\Twitter'           => __DIR__.'/classes/twitter.php',
));