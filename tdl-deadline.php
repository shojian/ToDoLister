<?php
	/*
	 *  File which holds Deadline class which works out deadlines
	 */
	 class TDLDeadline {
	 	private $deadline;
	 	private $repeat;
	 
	 	function __construct($rawDeadline) {
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
	 	
	 	public function getDeadline() {
	 		return $deadline;
	 	}
	 	
	 	public function getRepeat() {
	 		return $repeat;
	 	}
	 	
	 	private function namedMonth($str, $time=false) {
	 		$pieces = explode(" ", $str);
	 		$deadline = 0;	 		
	 		/*
	$dlPrep = strptime($_POST["deadline"], '%e. %m. %Y');
	$deadline = mktime(0, 0, 0, $dlPrep['tm_mon']+1, $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);*/
	 		if ($time) {
	 			$dlPrep;
	 			if (strlen($pieces[2]) == 3) {
	 				$dlPrep = strptime($str, "%k:%M %e %b %Y");
	 			} else {
	 				$dlPrep = strptime($str, "%k:%M %e %b %Y");
	 			}
	 			resolveTime($deadline - (24*60*60));
	 		} else {
	 		
	 		}
	 	}
	 	
	 	private function resolveTime($str) {
	 		
	 	}
	 }
?>