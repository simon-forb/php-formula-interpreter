<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use FormulaInterpreter\Parser\OperatorParser;

/**
 * Description of OperatorParserTest
 *
 * @author mathieu
 */
class OperatorParserTest extends \PHPUnit\Framework\TestCase {

    public function setUp() {

        $operandParser = $this->createMock('\FormulaInterpreter\Parser\ParserInterface');
        $operandParser
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnCallback([$this, 'mockOperandParser']));

        $this->parser = new OperatorParser($operandParser);
    }

    /**
     * @dataProvider getDataForTestingParse
     */
    public function testParse($expression, $infos) {
        $infos['type'] = 'operation';

        $this->assertEquals($this->parser->parse($expression), $infos);
    }

    public function getDataForTestingParse() {

        return [
            ['2+2', [
                            'firstOperand' => '2',
                            'otherOperands' => [
                                ['operator' => 'add', 'value' => '2']
                             ]
                         ]],
            [' 2+2 ', [
                            'firstOperand' => '2',
                            'otherOperands' => [
                                ['operator' => 'add', 'value' => '2']
                             ]
                         ]],
            ['2-2', [
                            'firstOperand' => '2',
                            'otherOperands' => [
                                ['operator' => 'subtract', 'value' => '2']
                            ]
                         ]],
            ['3+1-2', [
                            'firstOperand' => '3',
                            'otherOperands' => [
                                ['operator' => 'add', 'value' => '1'],
                                ['operator' => 'subtract', 'value' => '2']
                            ]
                         ]],
            ['2*2', [
                            'firstOperand' => '2',
                            'otherOperands' => [
                                ['operator' => 'multiply', 'value' => '2'],
                            ]
                         ]],
            ['2+3*4', [
                            'firstOperand' => '2',
                            'otherOperands' => [
                                ['operator' => 'add', 'value' => '3*4'],
                             ]
                         ]],
            ['4*3/2', [
                            'firstOperand' => '4',
                            'otherOperands' => [
                                ['operator' => 'multiply', 'value' => '3'],
                                ['operator' => 'divide', 'value' => '2'],
                            ]
                         ]],
            ['4*(3+2)', [
                            'firstOperand' => '4',
                            'otherOperands' => [
                                ['operator' => 'multiply', 'value' => '3+2'],
                            ]
                         ]],
            ['4* (3+2) ', [
                            'firstOperand' => '4',
                            'otherOperands' => [
                                ['operator' => 'multiply', 'value' => '3+2'],
                            ]
                         ]],
            ['4+( 3+2 ) ', [
                            'firstOperand' => '4',
                            'otherOperands' => [
                                ['operator' => 'add', 'value' => '3+2'],
                            ]
                         ]],
        ];
    }

    public function mockOperandParser($expression) {
        return $expression;
    }

    /**
     * @expectedException FormulaInterpreter\Parser\ParserException
     * @dataProvider getUncorrectExpressions
     */
    public function testParseUncorrectExpression($expression) {
        $this->parser->parse($expression);
    }

    public function getUncorrectExpressions() {
        return [
            [' what ever '],
            ['2 + '],
            [' 2 + ()']
        ];
    }

}

?>
