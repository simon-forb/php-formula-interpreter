<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\FormulaInterpreter\Command\CommandFactory;

use FormulaInterpreter\Command\CommandFactory\CommandFactoryException;
use FormulaInterpreter\Command\NumericCommand;
use FormulaInterpreter\Command\CommandFactory\NumericCommandFactory;

/**
 * Description of NumericCommandFactory
 *
 * @author mathieu
 */
class NumericCommandFactoryTest extends \PHPUnit\Framework\TestCase
{
    
    /**
     *  @dataProvider getData
     */
    public function testCreate($value)
    {
        $factory = new NumericCommandFactory();
        $options = ['value' => $value];
        $this->assertEquals($factory->create($options), new NumericCommand($value));
    }
    
    public function getData()
    {
        return [
            ['2'],
            ['4'],
        ];
    }
    
    public function testCreateWithMissingValueOption()
    {
        $this->expectException(CommandFactoryException::class);
        $factory = new NumericCommandFactory();
        $factory->create([]);
    }
}
