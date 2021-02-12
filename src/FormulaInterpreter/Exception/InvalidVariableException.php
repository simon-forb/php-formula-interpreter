<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FormulaInterpreter\Exception;

use Throwable;

/**
 * Description of InvalidVariableException
 *
 * @author khumbal
 */
class InvalidVariableException extends \Exception
{
    protected $name;
    protected $value;
    
    public function __construct($name, $value, ?Throwable $previous = null)
    {
        $this->name = $name;
        $this->value = $value;
        parent::__construct(sprintf('Invlid variable "%s"', $name), 0, $previous);
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getValue()
    {
        return $this->value;
    }
}
