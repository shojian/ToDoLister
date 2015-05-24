<?php
/**
	This class creates a deadline timestamp from a string.
  */
  
  require_once('ITDLDeadlineNew.php');
  class TDLDeadlineNew extends ITDLDeadlineNew {
  
  	/**  	  
  	  *  @var int $deadline Variable which stores calculated deadline value
  	  */
  	private $deadline = -1;
  	/**
  	  *	@var string $repeat Variable which stores period
  	  */
  	private $repeat = "";
  	
  	/**
  	  *  Constructor which chooses proper parsing function for input string
  	  *
  	  *	@param string $rawString
  	  */
  	function __construct($rawString) {
  	
  	}
  }