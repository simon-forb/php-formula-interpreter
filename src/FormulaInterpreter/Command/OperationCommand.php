<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FormulaInterpreter\Command;

/**
 * Description of FunctionParser
 *
 * @author mathieu
 */
class OperationCommand implements CommandInterface
{
    const ADD_OPERATOR = 'add';
    const SUBTRACT_OPERATOR = 'subtract';
    const MULTIPLY_OPERATOR = 'multiply';
    const DIVIDE_OPERATOR = 'divide';
    const EQUAL_OPERATOR = 'equal';

    /**
     * @var CommandInterface
     */
    protected $firstOperand;

    /**
     * @var array
     */
    protected $otherOperands = [];

    public function __construct(CommandInterface $firstOperand)
    {
        $this->firstOperand = $firstOperand;
    }

    public function addOperand($operator, CommandInterface $command)
    {
        $this->otherOperands[] = [
            'operator' => $operator,
            'command' => $command
        ];
    }

    public function run()
    {
        $result = $this->firstOperand->run();
        foreach ($this->otherOperands as $otherOperand) {
            $operator = $otherOperand['operator'];
            $command = $otherOperand['command'];
            $result = self::calculateResult($result, $operator, $command);
        }
        return $result;
    }

    private static function calculateResult($value, $operator, $command)
    {
        $value2 = $command->run();
        switch ($operator) {
            case self::ADD_OPERATOR:
                return $value + $value2;
            case self::MULTIPLY_OPERATOR:
                return $value * $value2;
            case self::SUBTRACT_OPERATOR:
                return $value - $value2;
            case self::DIVIDE_OPERATOR:
                return $value / $value2;
            case self::EQUAL_OPERATOR:
                return $value == $value2;
        }
        return $value;
    }

    public function getParameters()
    {
        $parameters = $this->firstOperand->getParameters();

        foreach ($this->otherOperands as $otherOperand) {
            $command = $otherOperand['command'];
            $parameters = array_merge($parameters, $command->getParameters());
        }

        return $parameters;
    }
}
