<?php
/**
 *	This file is part of Pay2go
 *
 * @author JasonHuang <>
 *
 * @package Pay2go
 * @since Pay2go Ver 1.0
**/

namespace Pay2go;

class Pay2go {

	protected $LiveEndPoint = "https://api.pay2go.com/MPG/mpg_gateway";
	protected $TestEndPoint = "https://capi.pay2go.com/MPG/mpg_gateway";

	public function __construct($merchantId, $hashKey, $hashIv, $returnUrl, $customerUrl, $clientBackUrl, $mode = false){

		$this->parameters = array(
			"MerchantID"      => $merchantId,
			"MerchantOrderNo" => "",
			"RespondType"     => "JSON",
			"TimeStamp"       => "",
			"Version"         => "1.2",
			"Amt"             => "",
			"ItemDesc"        => "網路訂單一筆",
			"Email"           => "Jason79706@gmail.com",
			"LoginType"       => "0",
			"NotifyURL"       => "", 		 		#支付通知網址 : 國民旅遊卡
			"CustomerURL"     => $customerUrl, 		#商店取號網址 : ATM 轉帳(VACC)、超商代碼繳費(CVS) 、條碼繳費(BARCODE)。
			"ReturnURL"       => $returnUrl, 		#支付完成返回商店網址
			"ClientBackURL"   => $clientBackUrl,	#支付取消返回商店網址
        );

        $this->parametersCode = array(
        	"MerchantID"      => $merchantId,
			"MerchantOrderNo" => "",
			"TimeStamp"       => "",
			"Version"         => "1.2",
			"Amt"             => "",
        );

		$this->hashKey  = $hashKey;
		$this->hashIv   = $hashIv;
		$this->testMode = $mode;
	}

	public function checkOut($params = array()){

		if ($params == null){
            throw new Exception('Params are not set.');
        }

        $paramsCode = array_merge($this->parametersCode, $params);

        #資料排序 php 5.3以下不支援
        uksort($paramsCode, array($this, 'merchantSort'));

        $paramsCode['CheckValue'] = $this->_getMacValue($paramsCode);

        $paramsSend = array_merge($this->parameters, $paramsCode);

        $Html  = '<!DOCTYPE html>';
    	$Html .= '<html>';
    	$Html .= '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>';
    	$Html .= '<body>';
        $Html .= '<form name="Pay2goForm" method = "post" action="'.$this->setEndPointMode().'">';
		foreach ($paramsSend as $key => $val) {
		    $Html .= "<input type='hidden' name='".$key."' value='".$val."'>";
		}
		$Html .= '</form>';
		$Html .= '</body>';
		$Html .= '</html>';
		$Html .= '<script>document.Pay2goForm.submit();</script>';
		
		sleep(1);
		return $Html;
	}

	# 路徑
	protected function setEndPointMode(){
        return $this->testMode ? $this->TestEndPoint : $this->LiveEndPoint;
    }

    # 仿自然排序法
    protected function merchantSort($a, $b){
		return strcasecmp($a, $b);
	}

    # 產生檢查碼
	protected function _getMacValue($formArr){
		$encodeStr = "HashKey=" . $this->hashKey;
		foreach ($formArr as $key => $value){
			$encodeStr .= "&" . $key . "=" . $value;
		}
		$encodeStr .= "&HashIV=" . $this->hashIv;

		$sMacValue =  hash('sha256', $encodeStr);

		return strtoupper($sMacValue);
	}
}

?>