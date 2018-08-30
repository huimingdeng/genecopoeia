<?php 
/**
 * 响应函数
 */
class Response
{
	public $nosend = FALSE;
	public $session_timeout = FALSE;
	private $_headers_sent = FALSE;				// TRUE when headers are sent on instantiation
	private $_capture = FALSE;					// TRUE when capturing output

	public function __construct($send_headers = FALSE)
	{
		if ($send_headers) {
			$this->_send_headers(TRUE);
		}

		if (!is_user_logged_in())
			$this->session_timeout = 1;
	}

	/**
	 * Send the HTTP headers for the JSON response
	 */
	private function _send_headers($capture = FALSE)
	{
		if (!$this->_headers_sent) {
			global $wp_version;

			header(self::HEADER_SYNC_VERSION . ': ' . SearchMenuOptions::PLUGIN_VERSION);		// send this header so sources will know that they're talking to SYNC
			header(self::HEADER_WP_VERSION . ': ' . $wp_version);								// send this header so sources will know that they're talking to WP
			header('Content-Type: application/json');
			$this->_headers_sent = TRUE;

			if ($this->_capture) {
				// headers have already been sent and ob_start() has been called.
				// clear the output buffer so our JSON data can be sent
				ob_get_clean();
			}
			if ($capture) {
				ob_start();
				$this->_capture = TRUE;
			}
		}
	}

	/**
	 * Sends the contents of the ApiResponse instance to the caller of the API
	 * @param boolean $exit TRUE if script is to end after sending data; otherwise FALSE (default)
	 */
	public function send($exit = TRUE)
	{
		if ($this->nosend)
			return;

		$this->_send_headers();

		if ($this->has_errors()) {
			$this->success = 0;					// force this
			$this->set('message', Request::error_code_to_string($this->error_code));
		}

		$output = $this->__toString();			// construct data to send to browser
		echo $output;							// send data to browser

		if ($exit)
			exit(0);							// stop script
	}



}