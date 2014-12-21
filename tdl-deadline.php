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
	 			$deadline = -1;
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
	 			$this->deadline = mktime($dlPrep['tm_hour'], $dlPrep['tm_min'], 0, $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);
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
	 			$this->makeDeadline($dlPrep['tm_mon'], $dlPrep['tm_mday']+1, $dlPrep['tm_year']+1900);
	 		}
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
	 			$this->deadline = mktime($dlPrep['tm_hour'], $dlPrep['tm_min'], 0, $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);
	 		} else {
	 			if (strlen($pieces[1]) == 2) {
	 				$pieces[1] = "0". $pieces[1];
	 				$rawDeadLine = implode(" ", $pieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%e. %m. %Y"); // 6:00 1. 11. 2014
		 		$this->makeDeadline($dlPrep['tm_mon'], $dlPrep['tm_mday']+1, $dlPrep['tm_year']+1900);
	 		}	 		
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
		 		
		 		// Think, Sarah, think
		 		if (!$this->isDateValid($dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900)) {
		 			$this->deadline = -1;
		 			return;
	 			}
	 			$this->deadline = mktime($dlPrep['tm_hour'], $dlPrep['tm_min'], 0, $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);
	 		} else {	 			
		 		$subPieces = explode("/", $rawDeadLine);
	 			if (strlen($subpieces[1]) == 2) {
	 				$subpieces[1] = "0". $pieces[1];
	 				$rawDeadLine = implode("/", $subPieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%e/%m/%Y"); // 6:00 1. 11. 2014
		 		$this->makeDeadline($dlPrep['tm_mon'], $dlPrep['tm_mday']+1, $dlPrep['tm_year']+1900);
	 		}
	 	}
	 	
	 	private function everyDay($rawDeadLine, $time=false) {	 	
	 		$deadline = -1;
            $dlPrep = null;
            $pieces = explode(" ", $rawDeadLine);
            $diff = $this->getDayDifference(strtolower($pieces[1]));
	 	}
	 	
	 	private function getDayDifference($desiredDay) {
	 		$pos = 1;
	 		$listOfDays = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
	 		foreach ($listOfDays as $day) {	 			
	 			if (strpos($day, $desiredDay) !== false) {	 				
	 				break;
	 			}
	 			$pos++;
	 		}
	 		return abs($pos - date("N"));
	 	}
	 	
	 	private function isLastDayOfMonth($month, $day, $year) {
	 		switch ($month) {
	 			case 1:
	 			case 3:
	 			case 5:
	 			case 7:
	 			case 8:
	 			case 10:
	 			case 12:
	 				if ($day == 31)
	 					return true;
	 				break;
	 			case 2:
	 				if (date("L", mktime(0, 0, 0, $month, $day, $year)) == 1) {
	 					if ($day == 29)
	 						return true;
	 					else
	 						return false;
	 				} else {
	 					if ($day == 28)
	 						return true;
	 					else
	 						return false;
	 				}
	 				break;
	 			default:
	 				if ($day == 30)
	 					return true;
	 				break;
	 		}
	 		return false;
	 	}
	 	
	 	private function makeDeadline($month, $day, $year) {
	 		if (!$this->isDateValid($month, $day, $year)) {
	 			$this->deadline = -1;
	 			return;
	 		}
	 		if (isLastDayOfMonth($month, $day, $year)) {
	 			if (($month == 12) && $day == 31) { // special day, last day of the year
	 				$year++;
	 			}
	 			$month++;
	 			$month %= 12;
	 			$day = 1;
	 		}
	 		$deadline = mktime(0, 0, 0, $month, $day, $year);
		 	$this->deadline = $deadline;
	 	}
	 	
	 	private function isDateValid($month, $day, $year) {
	 		if ($day < 1)
	 			return false;
	 		if ($month < 1)
	 			return false;
	 		if ($month > 12)
	 			return false;
	 		switch ($month) {
	 			case 1:
	 			case 3:
	 			case 5:
	 			case 7:
	 			case 8:
	 			case 10:
	 			case 12:
	 				if ($day > 31)
	 					return false;
	 				break;
	 			case 2:
	 				if (date("L", mktime(0, 0, 0, $month, $day, $year)) == 1) {
	 					if ($day > 29)
	 						return false;
	 				} else {
	 					if ($day > 28)
	 						return false;
	 				}
	 				break;
	 			default:
	 				if ($day > 30)
	 					return false;
	 				break;
	 		}
	 		return true;
	 	}
	 	private function isTimeValid($hour, $minute) {
	 		if ($hour > 23)
	 			return false;
	 		if ($hour < 0)
	 			return false;
	 		if ($minute > 59)
	 			return false;
	 		if ($minute < 0)
	 			return false;
	 		return true;
	 	}	 		
	 }
?>