<?php 
/*
	function WmApiLogin($fqdn, $apitoken) {
		$reply = array('result'=>false,'error'=>'unspecified error');
		$data = http_build_query(
			array(
				'token' => $apitoken,
				'clientip' => $_SERVER['REMOTE_ADDR']
			)
		);
		$opts = array('http' =>
			array(
			'method'=>'POST',
			'header'=>'Content-type: application/x-www-form-urlencoded',
			'content'=>$data
			)
		);
		$context  = stream_context_create($opts);
		if (!$json=@file_get_contents('https://'.$fqdn.'/api/webinar/login', false, $context)) {
			$reply['error']=error_get_last()["message"];
		} else {
			$res = @json_decode($json, true);
			if (!is_array($res) || empty($res) || empty($res['sessionId'])){
				$reply['error'] = 'Invalid reply';
			} else {
				$reply['sessionid']=$res['sessionId'];
				$reply['result']=true;
			}
		}	
		return $reply;
	}
	
	function GetMyPhoneSessionCookie($url) {
		$html=file_get_contents($url);
		error_log($http_response_header);
		$headers = $http_response_header;
	}	
	*/
	
?>