<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use FormulaInterpreter\Compiler;
use FormulaInterpreter\Parser\ParserException;

/**
 * Description of ParserTest
 *
 * @author mathieu
 */
class CompilerTest extends \PHPUnit\Framework\TestCase {

    /**
     * @dataProvider getCompileAndRunData
     */
    public function testCompileAndRun($expression, $result, $variables = []) {
        $compiler = new Compiler();

        $executable = $compiler->compile($expression);
        $this->assertEquals($executable->run($variables), $result);

    }

    public function getCompileAndRunData() {
        return [
            ['3', 3],
            ['3 + 3', 6],
            ['price', 10, ['price' => 10]],
            ['price + 2 * 3', 16, ['price' => 10]],
            ['pi()', pi()],
            ['pow(3, 2)', 9],
            ['modulo(5, 2)', 1],
            ['cos(0)', 1],
            ['sin(0)', 0],
            ['sqrt(4)', 2],
            ['pow(sqrt(pow(2, 2)), 2)', 4],

            // Issue #4
            ['(((100 * 0.43075) * 1.1 * 1.5) / (1-0.425)) * 1.105', 136.5852065217],
            ['1+(1+1)', 3],

            // Issue 8
            ['pow(foo,bar)', 9, ['foo' => 3, 'bar' => 2]],
            ['pow(foo, bar)', 9, ['foo' => 3, 'bar' => 2]],
        ];
    }

    /**
     * @dataProvider getCompileGetParametersData
     */
    public function testCompileGetParameters($expression, $parameters)
    {
        $compiler = new Compiler();

        $executable = $compiler->compile($expression);

        $this->assertSame($executable->getParameters(), $parameters);
    }

    public function getCompileGetParametersData()
    {
        return [
            ['3', []],
            ['3 + 3', []],
            ['price', ['price']],
            ['price + 2 * 3', ['price']],
            ['pi()', []],
            ['pow(3, 2)', []],
            ['modulo(5, 2)', []],
            ['cos(0)', []],
            ['sin(0)', []],
            ['sqrt(foo)', ['foo']],
            ['foo', ['foo']],
            ['foo + 1', ['foo']],
            ['foo * bar', ['foo', 'bar']],
            ['pow(foo, bar)', ['foo', 'bar']],
            ['pow(sqrt(pow(foo, bar)), baz)', ['foo', 'bar', 'baz']],
        ];
    }
}
