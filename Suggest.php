<?php
/** 
 * Get a list of search suggestions from Google API.
 * Return array with suggestions in chosen language and/or link to Google results
 * Requires input $query (start search query) and $locale (language)
 * Able to cache results. (recommended) ;
 *
 * Use:
 * $suggest = new Suggest('How old is', 'en');
 * print_r($suggest->data);
 * <a href="$suggest->link">Click here for Google results</a>
 * 
 * @package : Suggest;
 * @version : 1.0;
 * @license : MIT License (MIT);
 * @author : Jochem Stoel;
 * @link : https://github.com/jochemstoel;
 * @param query, locale;
 * @return Mixed;
 */

class Suggest {
	
	var $query;
	var $locale;
	var $xml;
	var $data = array();
	var $cache_path; 
	var $cache_file;

	function Suggest($query, $locale) {
		
		$this->query = urlencode($query);
		$this->locale = $locale;

		$this->cache_path = dirname(__FILE__) . '/Cache/';
		$this->cache_file = $this->locale . "." . preg_replace("/[^a-z0-9.]+/i", "+", $this->query) . '.json';
		
		if (file_exists($this->cache_path.$this->cache_file)) {
			$cache = file_get_contents($this->cache_path.$this->cache_file);
			$this->data = json_decode($cache);
		} else {
			
			$this->Query();
		}
	}

	function Query(){
	 	
	 	$agent = "AAPP Application/1.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.4)";
	    $host = "http://suggestqueries.google.com/complete/search?output=toolbar&hl=".$this->locale."&q=".$this->query;
	    $ch = curl_init();
	    
	    curl_setopt($ch, CURLOPT_URL, $host);
	    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	 
	    $this->xml = curl_exec($ch);
	    curl_close($ch);

	    $this->Parse();
	}

	function Parse() {
		
		$dom = new DOMDocument();
		$dom->loadxml($this->xml);

		$toplevel = $dom->getElementsByTagName('toplevel');
		$suggestions = $dom->getElementsByTagName('suggestion');

		foreach ($suggestions as $suggestion) {
			$this->data[] = $suggestion->getAttribute('data');
		}

		$this->link = 'https://www.google.com/#safe=off&q='.$this->query.'&lr=lang_'.$this->locale;

		$this->Cache();
	}

	function Cache() {
		
		$json = json_encode($this->data);
		
		if (is_writable($this->cache_path)) {
			file_put_contents($this->cache_path.$this->cache_file, $json);
		}
	}
}
?>