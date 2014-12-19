<?php
	/*
	 *  File which holds Deadline class which works out deadlines
	 */
	 class TDLDeadline {
	 	private $deadline = 0;
	 	private $repeat = "";
	 
	 	function __construct() {
	 		$deadline = 0;
	 	}
	 	
	 	public static function fromForm($rawDeadLine) {
	 		if (preg_match('/\d\d?\:\d\d \d\d? [[:alpha:]]* \d\d\d?\d?/', $rawDeadline)) {
	 		// 6:00 19 November 1989
	 			namedMonth($rawDeadline, true);	
	 		} else if (preg_match('/\d\d? [[:alpha:]]* \d\d\d\d/', $rawDeadline)) {
	 		// 19 November 1989
	 			namedMonth($rawDeadline);
	 		} else if (preg_match('/\d\d?\:\d\d \d\d?\. \d\d?\. \d\d\d?\d?/', $rawDeadline)) {
	 		// 6:00 1. 1. 1090
	 		
	 		} else if (preg_match('/\d\d?\. \d\d?. \d\d\d?\d?/', $rawDeadline)) {
	 		// 11. 11. 1090
	 		
	 		} else if (preg_match('/\d\d?\:\d\d \d\d?\/\d\d?\/\d\d/', $rawDeadline)) {
	 		// 6:00 11/11/11
	 		
	 		} else if (preg_match('/\d\d?\/\d\d?\/\d\d/', $rawDeadline)) {
	 		// 11/11/11
	 		
	 		} else if (preg_match('/eve?r?y? [[:alpha:]]*/', $rawDeadline)) {
	 		// every Monday
	 		
	 		} else if (preg_match('/eve?r?y? [[:alpha:]]* @ \d?\d\:\d\d/', $rawDeadline)) {
	 		// every Monday @ 6:00
	 		
	 		} else if (preg_match('/eve?r?y? \d\d* days @ \d?\d\:\d\d/', $rawDeadline)) {
	 		// every 33 days @ 6:00
	 		
	 		} else if (preg_match('/eve?r?y? \d\d* days/', $rawDeadline)) {
	 		// every 33 days
	 		
	 		} else if (preg_match('/eve?r?y? \d\d? @ \d?\d\:\d\d/', $rawDeadline)) {
	 		// every 25 @ 6:00
	 		
	 		} else if (preg_match('/eve?r?y? \d\d?/', $rawDeadline)) {
	 		// every 25
	 		
	 		}
	 	}
	 	
	 	public static function fromRepeat($repeat) {
	 	
	 	}
	 	
	 	public function getDeadline() {
	 		return $this->deadline;
	 	}
	 	
	 	public function getRepeat() {
	 		return $this->repeat;
	 	}
	 	
	 	private function namedMonth($str, $time=false) {
	 		$pieces = explode(" ", $str);
	 		if ($time) {
	 			$dlPrep;
	 			if (strlen($pieces[2]) == 3) {
	 				$dlPrep = strptime($str, "%k:%M %e %b %Y");
	 			} else {
	 				$dlPrep = strptime($str, "%k:%M %e %B %Y");
	 			}
	 			$this->deadline = mktime(0, $dlPrep['tm_min'], $dlPrep['tm_hour'], $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);
	 		} else {
	 			$dlPrep;
	 			if (strlen($pieces[2]) == 3) {
	 				$dlPrep = strptime($str, "%e %b %Y");
	 			} else {
	 				$dlPrep = strptime($str, "%e %B %Y");
	 			}
	 			$this->deadline = mktime(0, 0, 0, $dlPrep['tm_mon'], $dlPrep['tm_mday']+1, $dlPrep['tm_year']+1900);
	 		}
	 	}
	 }
?>