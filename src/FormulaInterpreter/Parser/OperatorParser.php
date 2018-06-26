<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FormulaInterpreter\Parser;
use FormulaInterpreter\Command\OperationCommand;

/**
 * Description of FunctionParser
 *
 * @author mathieu
 */
class OperatorParser implements ParserInterface
{
    
    /**
     * @var ParserInterface
     */
    protected $operandParser;
    
    public function __construct(ParserInterface $operandParser)
    {
        $this->operandParser = $operandParser;
    }
    
    public function parse($expression)
    {
        $expression = trim($expression);
        
        if ($this->hasOperator($expression, '+') | $this->hasOperator($expression, '-')) {
            return $this->searchOperands($expression, ['+', '-']);
        } elseif ($this->hasOperator($expression, '*') | $this->hasOperator($expression, '/')) {
            return $this->searchOperands($expression, ['*', '/']);
        } elseif ($this->hasOperator($expression, '=')) {
            return $this->searchOperands($expression, ['=']);
        }
        
        throw new ParserException($expression);
    }
    
    public function hasOperator($expression, $operator)
    {
        $parenthesis = 0;
        
        for ($i = 0; $i < strlen($expression); $i++) {
            $substring = substr($expression, $i, strlen($operator));
            if ($substring == $operator && $parenthesis == 0) {
                return true;
            }
            switch ($expression[$i]) {
                case '(':
                    $parenthesis ++;
                    break;
                case ')':
                    $parenthesis --;
                    break;
            }
        }
        return false;
    }
    
    public function searchOperands($expression, $operators)
    {
        $operands = [];
        $nbrCharacters = strlen($expression);
        
        $parenthesis = 0;
        
        $previous = 0;
        $lastOperator = null;
        for ($i = 0; $i < $nbrCharacters; $i++) {
            switch ($expression[$i]) {
                case '(':
                    $parenthesis ++;
                    break;
                case ')':
                    $parenthesis --;
                    break;
                default:
                    if (in_array($expression[$i], $operators) && $parenthesis == 0) {
                        $operands[] = $this->createOperand(
                            substr($expression, $previous, $i - $previous),
                            $lastOperator
                        );
                        $lastOperator = $expression[$i];
                        
                        if ($i+1 < $nbrCharacters && $expression[$i+1] != '(') {
                            $i++;
                            $previous = $i;
                        } else {
                            $previous = $i+1;
                        }
                    }
            }
        }
        
        $operands[] = $this->createOperand(
                substr($expression, $previous, strlen($expression) - $previous),
                $lastOperator
        );
        
        $firstOperand = array_shift($operands);
        
        return [
            'type' => 'operation',
            'firstOperand' => $firstOperand['value'],
            'otherOperands' => $operands
        ];
    }


    public static function getOperatorConstant($operator)
    {
        switch ($operator) {
            case '+':
                return OperationCommand::ADD_OPERATOR;
            case '-':
                return OperationCommand::SUBTRACT_OPERATOR;
            case '*':
                return OperationCommand::MULTIPLY_OPERATOR;
            case '/':
                return OperationCommand::DIVIDE_OPERATOR;
            case '=':
                return OperationCommand::EQUAL_OPERATOR;
        }
        return null;
    }
    
    public function createOperand($value, $operator = null)
    {
        $operand = [];
        $operand['operator'] = self::getOperatorConstant($operator);
        
        $value = trim($value);
        
        if ($value != '') {
            if ($value[0] == '(' && substr($value, -1, 1) == ')') {
                $value = substr($value, 1, -1);
                $value = trim($value);
            }
        }

        if ($value == '') {
            throw new ParserException($value);
        }

        
        $operand['value'] = $this->operandParser->parse($value);
        return $operand;
    }
}
