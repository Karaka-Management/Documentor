<?php

namespace Documentor\src\Application\Views;

use Reflection;

class MethodView extends DocView
{
    public function getMethod() : string
    {
        $method          = $this->ref;
        $parameters      = $method->getParameters();
        $parameterString = '';

        foreach ($parameters as $parameter) {
            $parameterString .= ($parameter->hasType() ? $this->linkType($parameter->getType()) . ' ' : '')
                . ($parameter->isPassedByReference() ? '&' : '')
                . $this->formatVariable('$' . $parameter->getName())
                . ($parameter->isDefaultValueAvailable() ? ' = '
                    . ($parameter->isDefaultValueConstant() ? $parameter->getDefaultValueConstantName() : $this->formatValue($parameter->getDefaultValue())) : '')
                . ', ';
        }

        $methodString = $this->formatModifier(implode(' ', Reflection::getModifierNames($method->getModifiers())))
            . ' ' . $this->formatFunction() . ' ' . $method->getShortName()
            . '(' . trim($parameterString, ', ') . ')'
            . ($method->hasReturnType() ? ' : ' . $this->linkType($method->getReturnType()) : '');

        return $methodString;
    }

    public function getClassLink() : string
    {
        return '<a href="' . $this->base . '/' . $this->ref->getDeclaringClass()->getName() . '.html">' . $this->ref->getDeclaringClass()->getShortName() . '</a>';
    }
}