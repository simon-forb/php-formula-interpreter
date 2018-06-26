<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use FormulaInterpreter\Command\CommandFactory;

/**
 * Description of ParserTest
 *
 * @author mathieu
 */
class CommandFactoryTest extends \PHPUnit\Framework\TestCase {

    public function testCreate() {
        $factory = new CommandFactory();

        $command = new CommandFactoryTest_FakeCommand();
        $numericFactory = $this->createMock('\FormulaInterpreter\Command\CommandFactory');
        $numericFactory->expects($this->once())
                ->method('create')
                ->will($this->returnValue($command));
        $factory->registerFactory('numeric', $numericFactory);

        $this->assertEquals($factory->create(['type' => 'numeric']), $command);
    }

    /**
     * @expectedException FormulaInterpreter\Command\CommandFactory\CommandFactoryException
     */
    public function testMissingTypeOption() {
        $factory = new CommandFactory();

        $factory->create([]);
    }

    /**
     * @expectedException FormulaInterpreter\Command\CommandFactory\CommandFactoryException
     */
    public function testUnknownType() {
        $factory = new CommandFactory();

        $factory->create(['type' => 'numeric']);
    }

}

class CommandFactoryTest_FakeCommand implements \FormulaInterpreter\Command\CommandInterface {
    public function run() {}

    public function getParameters()
    {
        return [];
    }
}

?>
