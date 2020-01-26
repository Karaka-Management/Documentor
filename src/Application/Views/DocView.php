<?php

namespace Documentor\src\Application\Views;

use Documentor\src\Application\Models\Comment;

class DocView extends BaseView
{
    protected $ref = null;
    
    protected ?Comment $comment = null;
    
    protected array $coverage = [];
    
    protected string $code = '';

    public function __construct()
    {
        $this->comment = new Comment('');
    }

    public function setReflection($ref)
    {
        $this->ref = $ref;
    }

    public function getComment() : Comment
    {
        return $this->comment;
    }

    public function setComment(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function setCode(string $code)
    {
        $trim  = \strlen($code) - \strlen(ltrim($code, ' '));
        $lines = \explode("\n", $code);

        foreach ($lines as $key => $line) {
            $lines[$key] = \substr($line, $trim);
        }

        $this->code = implode("\n", $lines);
    }

    public function setCoverage(array $coverage)
    {
        $this->coverage = $coverage;
    }

    protected function formatModifier(string $modifier) : string
    {
        return '<span class="modifier">' . $modifier . '</span>';
    }

    protected function formatFunction() : string
    {
        return '<span class="function">function</span>';
    }

    protected function linkType($type, string $output = null) : string
    {
        if ($type instanceof \ReflectionType) {
            $type = (string) $type;
        } elseif ($type instanceof \ReflectionClass && $type->isUserDefined()) {
            $type = $type->getName();
        }

        if ($type instanceof \ReflectionClass && !$type->isUserDefined()) {
            return $this->formatType($type->getShortName());
        } elseif ($type === 'string' || $type === 'int' || $type === 'float' || $type === 'bool' || $type === 'array') {
            return $this->formatType($type);
        } elseif (\strpos($type, '&') !== false || \strpos($type, '[') !== false | \strpos($type, '<') !== false) {
            return $this->formatType(htmlspecialchars($output ?? $type));
        } else {
            $text = explode('\\', $type);

            return '<a href="' . $this->base . '/' . $type . '.html">' . $this->formatType((isset($output) ? $output : end($text))) . '</a>';
        }
    }

    protected function formatValue($value, string $type = null)
    {
        if (!isset($type)) {
            $type = \gettype($value);
        }

        if ($type === 'bool' || $type === 'boolean') {
            return ((bool) $value) ? 'true' : 'false';
        } elseif ($type === 'string') {
            return "'" . $value . "'";
        } elseif ($type === 'object' && !isset($value)) {
            return 'null';
        } elseif ($type === 'NULL') {
            return 'null';
        } elseif ($type === 'array') {
            return '[...]';
        }

        return $value;
    }

    protected function formatType(string $type) : string
    {
        return '<span class="type">' . $type . '</span>';
    }

    protected function linkFunction(string $type, string $output = null) : string
    {
        $text = \explode('\\', $type);

        return '<a href="' . $this->base . '/' . $type . '.html">' . $this->formatVariable((isset($output) ? $output : end($text))) . '</a>';
    }

    protected function formatVariable(string $var) : string
    {
        return '<span class="variable">' . $var . '</span>';
    }

    protected function getPercentage($value) : int
    {
        $min = !isset($this->coverage['complexity']) || $this->coverage['complexity'] < 1 ? -8 : $this->coverage['complexity'];
        $out = (int) (100 / (1 + \exp(-\log($min / 50))));

        return $out < 1 ? 1 : $out;
    }
}
