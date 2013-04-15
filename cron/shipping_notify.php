<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Timur Khamrakulov
 * Date: 17.04.12
 * Time: 11:47
 * To change this template use File | Settings | File Templates.
 */

define('BASE_URL', 'http://sandbox.josiemarancosmetics.com');

$url = BASE_URL . '/cron/shipping_notify';
echo file_get_contents($url);