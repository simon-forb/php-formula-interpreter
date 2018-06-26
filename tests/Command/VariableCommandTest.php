<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use FormulaInterpreter\Command\VariableCommand;

/**
 * Description of ParserTest
 *
 * @author mathieu
 */
class VariableCommandTest extends \PHPUnit\Framework\TestCase {

    /**
     * @dataProvider getData
     */
    public function testRunWhenVariablesExists($name, $variables, $result) {
        $command = new VariableCommand($name, $variables);

        $this->assertEquals($command->run(), $result);
    }

    public function getData() {
        return [
            ['rate', ['rate' => 2], 2],
            ['price', ['price' => 32.2], 32.2],
        ];
    }

    /**
     * @expectedException FormulaInterpreter\Exception\UnknownVariableException
     */
    public function testRunWhenVariableNotExists() {
        $command = new VariableCommand('rate', []);
        $command->run();
    }

    public function testRunWhenVariablesHolderImplementsArrayAccess() {
        $variables = $this->createMock('\ArrayAccess');
        $variables->expects($this->any())
            ->method('offsetExists')
            ->with($this->equalTo('rate'))
            ->will($this->returnValue(true));
        $variables->expects($this->any())
            ->method('offsetGet')
            ->with($this->equalTo('rate'))
            ->will($this->returnValue(23));

        $command = new VariableCommand('rate', $variables);

        $this->assertEquals($command->run(), 23);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getIncorrectNames
     */
    public function testInjectIncorrectName($name) {
        $command = new VariableCommand($name, []);
    }

    public function getIncorrectNames() {
        return [
            [12],
            [False],
            [[]],
            [new StdClass()],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getIncorrectVariables
     */
    public function testInjectIncorrectVariables($variables) {
        $command = new VariableCommand('rate', $variables);
    }

    public function getIncorrectVariables() {
        return [
            [12],
            [False],
            ['string'],
            [new StdClass()],
        ];
    }

}

?>
