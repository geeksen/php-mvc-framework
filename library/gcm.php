<?php

class gcm // Google Cloud Messaging
{
	var $url = 'https://android.googleapis.com/gcm/send';

	// you must register the server ip to google gcm console
	var $key = 'AIzaSyDxreGX77sPZayHF-OV2s0qioKj4SpzfiA';

	function send($registration_ids, $data)
	{
		$result = array
		(
			'code' => 100,
			'json' => '',
		);

		if (false === is_array($registration_ids) || 0 == count($registration_ids) || false === is_array($data))
		{
			return $result;
		}

		$curl = curl_init();
		curl_setopt_array($curl, array
		(
			CURLOPT_URL => $this->url,
			CURLOPT_FRESH_CONNECT => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array
			(
				'Content-Type: application/json',
				'Authorization: key=' . $this->key,
			),
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode(array
			(
				'registration_ids' => $registration_ids,
				'data' => $data,
			)),
		));

		$json = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		return array_merge($result, array
		(
			'code' => $code,
			'json' => $json,
		));
	}
}

