<?php
// Delimitered String To Array (DSTAT)
class DSTAT {
	public $text = null;
	private $delimiter = null;
	private $keys = null;
	private $values = null;

	function __construct() {
		// Get all arguments
		$args = func_get_args();

		// Number of arguments
		$arg_count = func_num_args();

		// Get the first argument
		$this->text = $args[0];

		$key = '^'; $value = '^^^';
		switch ($arg_count) {
			case 2:
				$key = $args[1][0];
				$value = $args[1][1];
				break;
			case 3: 
				$key = $args[1];
				$value = $args[2];
				break;
		}
		$this->delimiter = compact('key', 'value');
		$this->setKeys();
		$this->setValues();
	}

	public function getDelimiter() {
		return $this->delimiter;
	}

	private function setKeys() {
		$output_key = null;
		// preg_match_all("/^[\^][^\^]\b\w*/sm", $this->text, $output_key);
		preg_match_all($this->generateKeyRegex(), $this->text, $output_key);
		$result = array_map(array(__CLASS__, "removeKeyDelimiter"), $output_key);
		$this->keys = $result[0];
	}

	private function setValues() {
		$output_value = null;
		// preg_match_all("/^[\^]{3}(.*?)[\^]{3}/sm", $this->text, $output_value);
		preg_match_all($this->generateValueRegex(), $this->text, $output_value);
		$this->values = array_map("trim", $output_value[1]);
	}

	public function getKeys() {
		return $this->keys;
	}

	public function getValues() {
		return $this->keys;
	}

	public function getBoth($prettify = false) {
		$result_array = array();
		$keys = $this->keys;
		if($prettify) {
			$keys = array_map(array(__CLASS__,"pretifyKey"), $this->keys);
		}
		for($i = 0; $i < count($keys); $i++) {
			$result_array[$keys[$i]] = $this->values[$i];
		}
		return $result_array;
	}

	public function getNonKeyValueText() {
		$result = null;
		$result  = preg_replace($this->generateKeyRegex(), "", $this->text);
		$result  = preg_replace($this->generateValueRegex(), "", $result);
		return trim($result);
	}

	private function generateKeyRegex() {
		$delim = $this->delimiter["key"];
		$escaped_delim = preg_quote($delim, $delim);
		$regular_expression = "/^[".$escaped_delim."]{".strlen($delim)."}[^".$escaped_delim."]\b\w*/sm";
		return $regular_expression;
	}

	private function generateValueRegex() {
		$delim = $this->delimiter["key"];
		$delim_count = strlen($this->delimiter["value"]);
		$escaped_delim = preg_quote($delim, $delim);
		$regular_expression = "/^[".$escaped_delim."]{".$delim_count."}(.*?)[".$escaped_delim."]{".$delim_count."}/sm";
		return $regular_expression;
	}

	public function isKeyAndValueMatching() {
		$result = true;
		if(count($this->keys) != count($this->values)) {
			$result = false;
		} 
		return $result;
	}

	private function removeKeyDelimiter($text) {
		$delim = $this->delimiter["key"];
		$delim_count = strlen($delim);
		$escaped_delim = preg_quote($delim, $delim);
		return preg_replace("/^[".$escaped_delim."]{".$delim_count."}[^".$escaped_delim."]/sm", "", $text);
	}

	private function pretifyKey($key) {
		$result = str_replace('_', ' ', $key);
		return ucwords($result);
	}
}