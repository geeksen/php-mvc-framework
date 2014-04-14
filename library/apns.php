<?php

class Apns // Apple Push Notification Service
{
	var $apns_host = array
	(
		'develop' => 'gateway.sandbox.push.apple.com',
		'live' => 'gateway.push.apple.com',
	);

	var $apns_cert = array
	(
		'develop' => '/var/push/develop/apns-develop.pem',
		'live' => '/var/push/live/apns.pem',
	);

	var $gateway_port = 2195;
	var $feedback_port = 2196;

	function send($version = 'develop', $device_token, $message)
	{
		$payload = array('aps' => array('alert' => $message, 'badge' => 0, 'sound' => 'default'));
		$payload = json_encode($payload);

		if (!file_exists($this->apns_cert[$version]))
		{
			return false;
		}

		$stream_context = stream_context_create();
		stream_context_set_option($stream_context, 'ssl', 'local_cert', $this->apns_cert[$version]);
		stream_context_set_option($stream_context, 'ssl', 'veryfy_peer', false);

		$fp = stream_socket_client('ssl://' . $this->apns_host[$version] . ':' . $this->gateway_port, $error, $error_str, 10, STREAM_CLIENT_CONNECT, $stream_context);
		if (!$fp)
		{
			return false;
		}

		$fwrite = fwrite($fp, chr(0) . chr(0) . chr(32) . urldecode('%' . str_replace('-', '%', $device_token)) . chr(0) . chr(strlen($payload)) . $payload);
		fclose($fp);

		return true;
	}

	function result($version = 'develop')
	{
		$stream_context = stream_context_create();
		stream_context_set_option($stream_context, 'ssl', 'local_cert', $this->apns_cert[$version]);
		stream_context_set_option($stream_context, 'ssl', 'veryfy_peer', false);

		$fp = stream_socket_client('ssl://' . $this->apns_host[$version] . ':' . $this->feedback_port, $error, $error_str, 10, STREAM_CLIENT_CONNECT, $stream_context);
		if (!$fp)
		{
			return false;
		}

		$feedbacks = array();
		while (!feof($fp))
		{
			$fread = fread($fp, 38);
			if (0 < strlen($fread))
			{
				$feedbacks[] = unpack("N1timestamp/n1length/H*token", $fread);

			}
		}
		fclose($fp);

		return true;
	}
}
