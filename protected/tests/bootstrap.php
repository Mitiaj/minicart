<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../../../../NetBeans 7.3.1/yii-master/framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

Yii::createWebApplication($config);
