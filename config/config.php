<?php

define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/TopTips');
define('TICKETS_SCREENSHOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . 'TopTips/img/tickets/');

define('DB_HOST', 'localhost');
define('DB_DATABASE', 'login');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

define('COOKIE_EXPIRE', time() + 60*60*24*7);  // one week

define('FINISHED_TICKETS_DISPLAY_LIMIT', 2);   // finished tickets limit to be displayed in one click

define('TABLE_TIME_BEFORE_MATCH', 60*15);      // time before match untill match is playable
define('PLAYED_REQUIRED_FOR_PERCENTAGE', 1);   // how much tips must be played before it appears in table
define('TOP_TIPS_TABLE_LIMIT', 10);            // top tips table row limit
define('TOP_TIPS_LOWEST_PERCENT', 40);         // lowest percentage that will appear in TopTips table
