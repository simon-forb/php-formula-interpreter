<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\FormulaInterpreter\Command;

use FormulaInterpreter\Command\NumericCommand;

/**
 * Description of ParserTest
 *
 * @author mathieu
 */
class NumericCommandTest extends \PHPUnit\Framework\TestCase
{
    
    /**
     * @dataProvider getData
     */
    public function testRun($value, $result)
    {
        $command = new NumericCommand($value);
        
        $this->assertEquals($command->run(), $result);
    }
    
    public function getData()
    {
        return [
            [2, 2],
            [2.2, 2.2],
        ];
    }
    
    /**
     * @dataProvider getIncorrectValues
     */
    public function testInjectIncorrectValue($value)
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new NumericCommand($value);
        $command->run();
    }

    public function getIncorrectValues()
    {
        return [
            ['string'],
            [false],
            [[]],
        ];
    }
}
