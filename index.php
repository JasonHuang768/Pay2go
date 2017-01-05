<?php
require("src/pay2go.php");
// require("AioSDK.php");

$merchantId    = '12074830';
$hashKey       = 'w9HdAJc1hmT2znI4rl2ZqMLNs3sDkz0Q';
$hashIv        = 'IH3jrgkIDeKcCtg4';
$returnUrl     = '';
$customerUrl   = '';
$clientBackUrl = '';


$pay2go = new Pay2go\Pay2go($merchantId, $hashKey, $hashIv, $returnUrl, $customerUrl, $clientBackUrl, true); 

$params = array(
	"MerchantOrderNo" => time(),
	"TimeStamp"       => time(),
	"Amt"             => 100,
);

$result = $pay2go->checkOut($params);

echo $result;
?>