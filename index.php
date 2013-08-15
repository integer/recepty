<?php

date_default_timezone_set('Europe/Prague');
define('RECIPT_DIR', 'recepty');

if ($_SERVER['REQUEST_URI'] === '/') {
	header('Location: /index.php/');
}

$filterPath = filterPathInfo($_SERVER['PATH_INFO']);

$firstPage = false;
if (empty($filterPath)) {
	$firstPage = true;
	printHead('Úvodní strana');
	echo "<h1>Úvodní strana</h1>";
}

$fullPath = sprintf('./%s/%s', RECIPT_DIR, $filterPath);

if (is_dir($fullPath)) {

	printHead($filterPath);
	if (!$firstPage) {
		// vykresli tlacitko zpet - o uroven výš
	}

	$content = scandir($fullPath);
	printf('<ul>');
	foreach ($content as $item) {
		if ($item !== '.' && $item !== '..') {
			printf('<li><a href="./%s/">%s</a></li>', $item, $item);
		}
	}
	print('</ul>');

} elseif (is_file($fullPath)) {
	printHead($filterPath);

	echo '<pre>'.file_get_contents($fullPath).'</pre>';
	if (!$firstPage) {
		// vykresli tlacitko zpet - o uroven výš
	}

} else {
	header('Location: /index.php/');
}
printTail();

function filterPathInfo($pathInfo)
{
	$parts = explode('/', $pathInfo);
	$filterParts = array_filter($parts, function($var) {
		return (!empty($var) && $var !== '.' && $var !== '..' );
	});
	return implode('/', $filterParts);
}

function printHead($title)
{
	echo <<<EOF
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>$title</title>
	</head>
	<body>
EOF;
}

function printTail()
{
	echo <<<EOF
	&copy; 2013 integer, autoři receptů uvedeni u receptů
	</body>
</html>
EOF;
}
