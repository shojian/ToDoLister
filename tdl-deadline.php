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
	 		} else if (preg_match('/eve?r?y? [[:alpha:]]* @ \d?\d\:\d\d/', $rawDeadLine)) {
	 		// every Monday @ 6:00
	 			$this->everyDay($rawDeadLine, true);
	 		} else if (preg_match('/eve?r?y? [[:alpha:]]*/', $rawDeadLine)) {
	 		// every Monday
	 			$this->everyDay($rawDeadLine);
	 		} else if (preg_match('/eve?r?y? \d\d* days @ \d?\d\:\d\d/', $rawDeadLine)) {
	 		// every 33 days @ 6:00
	 			$this->everyNumberOfDays($rawDeadLine, true);
	 		} else if (preg_match('/eve?r?y? \d\d* days/', $rawDeadLine)) {
	 		// every 33 days
	 			$this->everyNumberOfDays($rawDeadLine);
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
	 			$this->makeDeadlineWithTime($dlPrep['tm_hour'], $dlPrep['tm_min'], $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);	
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
	 				$rawDeadLine = implode(" ", $pieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%k:%M %e. %n. %Y"); // 6:00 1. 11. 2014
		 		$this->makeDeadlineWithTime($dlPrep['tm_hour'], $dlPrep['tm_min'], $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);	
	 		} else {
	 			if (strlen($pieces[1]) == 2) {
	 				$rawDeadLine = implode(" ", $pieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%e. %n. %Y"); // 6:00 1. 11. 2014
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
	 				$rawDeadLine = $pieces[0]." ".implode("/", $subPieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%k:%M %e/%n/%Y"); // 6:00 1. 11. 2014
		 		$this->makeDeadlineWithTime($dlPrep['tm_hour'], $dlPrep['tm_min'], $dlPrep['tm_mon'], $dlPrep['tm_mday'], $dlPrep['tm_year']+1900);		 		
	 		} else {	 			
		 		$subPieces = explode("/", $rawDeadLine);
	 			if (strlen($subpieces[1]) == 2) {
	 				$rawDeadLine = implode("/", $subPieces);
	 			}
		 		$dlPrep = strptime($rawDeadLine, "%e/%n/%Y"); // 6:00 1. 11. 2014
		 		$this->makeDeadline($dlPrep['tm_mon'], $dlPrep['tm_mday']+1, $dlPrep['tm_year']+1900);
	 		}
	 	}

	 	private function everyDay($rawDeadLine, $time=false) {	 	
	 		$deadline = -1;
            $dlPrep = null;
            $pieces = explode(" ", $rawDeadLine);
            $diff = $this->getDayDifference(strtolower($pieces[1]));
            $nextNamedDay = date("j")+$diff;
            $month = date("n");
            $year = date("Y");
            if (!$this->isDateValid($month, $nextNamedDay, $year)) {
            	switch ($month) {
            		case 1:
		 			case 3:
		 			case 5:
	 				case 7:
	 				case 8:
	 				case 10:
		 			case 12:
		 				if ($nextNamedDay > 31) {
	 						$nextNamedDay -= 31;
	 						$month++;	 						
	 					}
	 					break;
	 				case 2:
	 					if (date("L", mktime(0, 0, 0, $month, $day, $year)) == 1) {
	 						if ($nextNamedDay > 29) {
		 						$nextNamedDay -= 29;
		 						$month++;	 						
	 						}
	 					} else {
	 						if ($nextNamedDay > 28) {
		 						$nextNamedDay -= 28;
		 						$month++;	 						
	 						}
		 				}
		 				break;
	 				default:
	 					if ($nextNamedDay > 30) {
		 						$nextNamedDay -= 30;
		 						$month++;	 						
	 						}
	 					break;
            	}
            	if ($month > 12) {
            		$month -= 12;
            		$year++;
            	}
            }
            if ($time) {
            	$time = $this->getTimeArray($pieces);
            	if (count($time) == 2)
            	$this->makeDeadlineWithTime($time[0], $time[1], $month, $day, $year);
            } else {
            	$this->makeDeadline($month, $day+1, $year);
            }
            $this->repeat = $rawDeadLine;
	 	}
	 	
	 	private function everyNumberOfDays($rawDeadLine, $time=false) {
	 		
	 	}
	 	
	 	private function getDayDifference($desiredDay) {
	 		$pos = 0;
	 		$found = false;
	 		$listOfDays = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
	 		foreach ($listOfDays as $day) {	 			
	 			if (strpos($day, $desiredDay) !== false) {
		 			$found = true;	 				
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
	 			if ($month > 12)
	 				$month -= 12;
	 			$day = 1;
	 		}
	 		$deadline = mktime(0, 0, 0, $month, $day, $year);
		 	$this->deadline = $deadline;
	 	}
	 	
	 	private function isDateValid($month, $day, $year) {
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
			if ($day < 1)
	 			return false;
	 		if ($month < 1)
	 			return false;
	 		if ($month > 12)
	 			return false;
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
	 	
	 	private function makeDeadlineWithTime($hour, $minute, $month, $day, $year) {
	 		if (!($this->isDateValid($month, $day, $year)
		 			&& $this->isTimeValid($hour, $minute) )) {
		 			$this->deadline = -1;
		 			return;
	 		}
	 		$this->deadline = mktime($hour, $minute, 0, $month, $day, $year);
	 	}
	 	
	 	private function getTimeArray($pieces) {
        	if ($pieces[2] == "@") {
        		return explode(":", $pieces[3]);
        	} else {
        		$this->deadline = -1;
        		return array();
        	}
	 	}
	 }
?>