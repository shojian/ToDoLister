<?php
class TDL_DeadlineTests extends PHPUnit_Framework_TestCase {    
    public function testEuropeanDate() {
        // 1416783599 
        $a = new TDLDeadline();
        $a->fromForm("23. 11. 2014");
        $this->assertEquals(1416783599, $a->getDeadline());
    }
}
?>

