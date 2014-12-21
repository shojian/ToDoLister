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
	 	
	 	public function fromForm($rawDeadLine) {
		 	$rawDeadLine = trim($rawDeadLine);		 	
	 		if (preg_match("/\d:\d\d \d?\d [[:alpha:]]* \d\d\d?\d?/", $rawDeadLine)) {
	 		// 6:00 19 November 1989
	 			$this->namedMonth($rawDeadLine, true);	
	 		} else if (preg_match("/\d?\d [[:alpha:]]* \d\d\d?\d?/", $rawDeadLine)) {
	 		// 19 November 1989
	 			$this->namedMonth($rawDeadLine);
	 		} else if (preg_match('/\d\d?\:\d\d \d\d?\. \d\d?\. \d\d\d?\d?/', $rawDeadLine)) {
	 		// 6:00 1. 1. 1090
	 			$this->europeanMonth($rawDeadLine, true);
	 		} else if (preg_match('/\d\d?\. \d\d?. \d\d\d?\d?/', $rawDeadLine)) {
	 		// 11. 11. 1090
	 			$this->europeanMonth($rawDeadLine);
	 		} else if (preg_match('/\d\d?\:\d\d \d\d?\/\d\d?\/\d\d/', $rawDeadLine)) {
	 		// 6:00 11/11/11
	 			$this->americanMonth($rawDeadLine, true);
	 		} else if (preg_match('/\d\d?\/\d\d?\/\d\d/', $rawDeadLine)) {
	 		// 11/11/11
	 			$this->americanMonth($rawDeadLine);
	 		} else if (preg_match('/eve?r?y? [[:alpha:]]* @ \d?\d\:\d\d//', $rawDeadLine)) {
	 		// every Monday @ 6:00
	 			$this->everyDay($rawDeadLine, true);
	 		} else if (preg_match('/eve?r?y? [[:alpha:]]*/', $rawDeadLine)) {
	 		// every Monday
	 			$this->everyDay($rawDeadLine);
	 		} else if (preg_match('/eve?r?y? \d\d* days @ \d?\d\:\d\d/', $rawDeadLine)) {
	 		// every 33 days @ 6:00
	 		
	 		} else if (preg_match('/eve?r?y? \d\d* days/', $rawDeadLine)) {
	 		// every 33 days
	 		
	 		} else if (preg_match('/eve?r?y? \d\d? @ \d?\d\:\d\d/', $rawDeadLine)) {
	 		// every 25 @ 6:00
	 		
	 		} else if (preg_match('/eve?r?y? \d\d?/', $rawDeadLine)) {
	 		// every 25
	 		
	 		} else {
	 			echo "err";
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
	 	
	 	private function namedMonth($rawDeadLine, $time=false) {		 	
	 		$pieces = explode(" ", $rawDeadLine);
	 		$deadline = -1;
            $dlPrep = null;
	 		if ($time) {	 			
	 			if (strlen($pieces[2]) == 3) {
	 				if (strlen($pieces[3]) == 4) {
		 				$dlPrep = strptime($rawDeadLine, "%k:%M %e %b %Y"); // 6:00 1 Nov 2014
		 			} else {
		 				$dlPrep = strptime($rawDeadLine, "%k:%M %e %b %y"); // 6:00 1 Nov 14
		 			}
	 			} else {
	 				if (strlen($pieces[3]) == 4) {
		 				$dlPrep = strptime($rawDeadLine, "%k:%M %e %B %Y"); // 6:00 1 November 2014
		 			} else {
		 				$dlPrep = strptime($rawDeadLine, "%k:%M %e %B %y"); // 6:00 1 November 14
		 			}
	 			}
	 			$deadline = mktime($dlPrep['tm_hour'], $dlPrep['tm_min'], 0, $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);
	 		} else {
	 			if (strlen($pieces[2]) == 3) {
	 				if (strlen($pieces[3]) == 4) {
		 				$dlPrep = strptime($rawDeadLine, "%e %b %Y"); // 1 Nov 2014
		 			} else {
		 				$dlPrep = strptime($rawDeadLine, "%e %b %y"); // 1 Nov 14
		 			}
	 			} else {
	 				if (strlen($pieces[3]) == 4) {
		 				$dlPrep = strptime($rawDeadLine, "%e %B %Y"); // 1 November 2014
		 			} else {
		 				$dlPrep = strptime($rawDeadLine, "%e %B %y"); // 1 November 14
		 			}
	 			}
	 			$deadline = mktime(0, 0, 0, $dlPrep['tm_mon'], $dlPrep['tm_mday']+1, $dlPrep['tm_year']+1900);
	 		}
	 		$this->deadline = $deadline;
	 	}	 	
	 	
	 	private function europeanMonth($rawDeadLine, $time=false) {		 	
	 		$pieces = explode(" ", $rawDeadLine);
	 		$deadline = -1;
            $dlPrep = null;
	 		if ($time) {	 			
	 			if (strlen($pieces[2]) == 2) {
	 				$pieces[2] = "0". $pieces[2];
	 				$rawDeadLine = implode(" ", $pieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%k:%M %e. %m. %Y"); // 6:00 1. 11. 2014
	 			$deadline = mktime($dlPrep['tm_hour'], $dlPrep['tm_min'], 0, $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);
	 		} else {
	 			if (strlen($pieces[1]) == 2) {
	 				$pieces[1] = "0". $pieces[1];
	 				$rawDeadLine = implode(" ", $pieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%e. %m. %Y"); // 6:00 1. 11. 2014
	 			$deadline = mktime(0, 0, 0, $dlPrep['tm_mon'], $dlPrep['tm_mday']+1, $dlPrep['tm_year']+1900);
	 		}
	 		$this->deadline = $deadline;
	 	}
	 	
	 	private function americanMonth($rawDeadLine, $time=false) {		 		 		
	 		$deadline = -1;
            $dlPrep = null;
	 		if ($time) {
		 		$pieces = explode(" ", $rawDeadLine);	 			
		 		$subPieces = explode("/", $pieces[1]);
	 			if (strlen($subpieces[1]) == 2) {
	 				$subpieces[1] = "0". $pieces[1];
	 				$rawDeadLine = $pieces[0]." ".implode("/", $subPieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%k:%M %e/%m/%Y"); // 6:00 1. 11. 2014
	 			$deadline = mktime($dlPrep['tm_hour'], $dlPrep['tm_min'], 0, $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);
	 		} else {	 			
		 		$subPieces = explode("/", $rawDeadLine);
	 			if (strlen($subpieces[1]) == 2) {
	 				$subpieces[1] = "0". $pieces[1];
	 				$rawDeadLine = implode("/", $subPieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%e/%m/%Y"); // 6:00 1. 11. 2014
	 			$deadline = mktime(0, 0, 0, $dlPrep['tm_mon'], $dlPrep['tm_mday']+1, $dlPrep['tm_year']+1900);
	 		}
	 		$this->deadline = $deadline;
	 	}
	 	
	 	private function everyDay($rawDeadLine, $time=false) {	 	
	 		$deadline = -1;
            $dlPrep = null;
            $pieces = explode(" ", $rawDeadLine);
            $diff = $this->getDayDifference(strtolower($pieces[1]));
	 	}
	 	
	 	private function getDayDifference($desiredDay) {
	 		$diff = 0;
	 		$listOfDays = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
	 		foreach ($listOfDays as $day) {	 			
	 			if (strpos($day, $desiredDay) !== false) {	 				
	 				break;
	 			}
	 			$diff++;
	 		}
	 		return $diff;
	 	}
	 	
	 	private function checkEndOfMonth($month) {
	 	
	 	}
	 		
	 }
?>