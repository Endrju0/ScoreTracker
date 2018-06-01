<?php

namespace core;

class Browser {
	
	private function __construct() {
        
    }
	
	public function getIpAddress()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	public function exactBrowserName()	{	
		$ExactBrowserNameUA=$_SERVER['HTTP_USER_AGENT'];
		if (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")) {
			// OPERA
			$ExactBrowserNameBR="Opera";
		} elseIf (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "chrome/")) {
			// CHROME
			$ExactBrowserNameBR="Chrome";
		} elseIf (strpos(strtolower($ExactBrowserNameUA), "msie")) {
			// INTERNET EXPLORER
			$ExactBrowserNameBR="Internet Explorer";
		} elseIf (strpos(strtolower($ExactBrowserNameUA), "firefox/")) {
			// FIREFOX
			$ExactBrowserNameBR="Firefox";
		} elseIf (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")==false and strpos(strtolower($ExactBrowserNameUA), "chrome/")==false) {
			// SAFARI
			$ExactBrowserNameBR="Safari";
		} else {
			// OUT OF DATA
			$ExactBrowserNameBR="OUT OF DATA";
		};
		return $ExactBrowserNameBR;
	}
	
}