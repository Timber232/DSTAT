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
		$this->keys = $this->getKeys();
		$this->values = $this->getValues();
	}

	public function getDelimiter() {
		return $this->delimiter;
	}

	public function getKeys() {
		$output_key = null;
		// preg_match_all("/^[\^][^\^]\b\w*/sm", $this->text, $output_key);
		preg_match_all($this->generateKeyRegex(), $this->text, $output_key);
		$result = array_map(array(__CLASS__, "removeKeyDelimiter"), $output_key);
		return $result[0];
	}

	public function getValues() {
		$output_value = null;
		preg_match_all("/^[\^]{3}(.*?)[\^]{3}/sm", $this->text, $output_value);
		preg_match_all($this->generateValueRegex(), $this->text, $output_value);
		return array_map("trim", $output_value[1]);
	}

	public function getBoth() {
		$result_array = array();
		for($i = 0; $i < count($this->keys); $i++) {
			$result_array[$this->keys[$i]] = $this->values[$i];
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
}

$example_text_1 = "
^ Test
^^^ Test test test
test test test
^^^
^ Test2342
^^^ Test test test
test test test
^^^

 Asdasdkasd
 a
";

$example_text_2 = "
~~ Test1
~~~~ Test test test
test test test
~~~~
~~ Test2342
~~~~ Test test test
test test test
~~~~


-- Asdasdk;lcfirst
Asdasdk
##Hello World##
";

$test = new DSTAT($example_text_1, '^', '^^^');
echo $test->text;
var_dump($test->getDelimiter());
var_dump($test->getKeys());
var_dump($test->getValues());
var_dump($test->getBoth());
var_dump($test->getNonKeyValueText());