<?php

include "..\src\Config.php";

use Tingyu\YiConf\Config;

Config::ConfigFile('./config.php');

Config::ConfigDir('E:\workplace\conf\test\conf');

$data = Config::get('env.a');


var_dump($data);