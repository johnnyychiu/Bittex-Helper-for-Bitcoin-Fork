<?php
	$apikey=$_GET['api'];
	$apisecret=$_GET['apis'];
	$nonce=time();

	$uri='https://bittrex.com/api/v1.1/public/getmarketsummary?market=USDT-BTC&apikey='.$apikey.'&nonce='.$nonce;
	$ch = curl_init($uri);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$execResult = curl_exec($ch);
	$obj = json_decode($execResult);
	$sellprice = ($obj->result[0]->Bid) * 0.95;

	$uri='https://bittrex.com/api/v1.1/account/getbalance?currency=BTC&apikey='.$apikey.'&nonce='.$nonce;
	$sign=hash_hmac('sha512',$uri,$apisecret);
	$ch = curl_init($uri);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'.$sign));
	$execResult = curl_exec($ch);
	$obj = json_decode($execResult);
	$balance = $obj->result->Available;

	$uri='https://bittrex.com/api/v1.1/market/selllimit?apikey='.$apikey.'&market=USDT-BTC&quantity=' . $balance . '&rate=' . $sellprice .'&nonce='.$nonce;
	$sign=hash_hmac('sha512',$uri,$apisecret);
	$ch = curl_init($uri);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'.$sign));
	$execResult = curl_exec($ch);
	$obj = json_decode($execResult);

	if ($obj->success == 1) {
		echo "DONE";# code...
	}
?>