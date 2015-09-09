<?php
class CurlHelper
{



	/**
	 * Downloads a file from a url and returns the temporary file path.
	 * @param string $url
	 * @return string The file path
	 */
	public static function downloadFile($url,$destination, $options = array())
	{
		if (!is_array($options))
			$options = array();
		$options = array_merge(array(
			'connectionTimeout' => 5, // seconds
			'timeout' => 60, // seconds
			'sslVerifyPeer' => false,
			'followLocation' => false, // if true, limit recursive redirection by
			'maxRedirs' => 1, // setting value for "maxRedirs"
			'userAgent'=>"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.12 Safari/535.11"
		), $options);

		// create a temporary file (we are assuming that we can write to the system's temporary directory)

		$fh = fopen($destination, 'w');

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_FILE, $fh);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $options['connectionTimeout']);
		curl_setopt($curl, CURLOPT_TIMEOUT, $options['timeout']);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $options['sslVerifyPeer']);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_MAXREDIRS, $options['maxRedirs']);
		curl_setopt($curl, CURLOPT_USERAGENT, $options['userAgent']);
	 curl_exec($curl);

		curl_close($curl);
		fclose($fh);

		return $destination;
	}
}?>