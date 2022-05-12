<?php

$router['/sitemap.xml'] = array('Sitemap', 'index');
$router['/thook[/]?'] = array('Telegram', 'index');

$router['/api[/]?'] = array('Api', 'index');
$router['/api/(\w+)[/]?'] = array('Api', 'method');
$router['/search[/]?'] = array('Search', 'index');
$router['([a-zA-Z 0-9~%:_\-/]+).(html|htm)'] = array('File', 'index');
$router['(.*/){0,0}[^\.]*'] = array('Directory', 'index');