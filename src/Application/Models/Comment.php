<?php

namespace Documentor\src\Application\Models;

class Comment
{
    private $version = null;
    private $php = 'PHP 7.1';
    private $description = null;
    private $license = null;
    private $var = null;
    private $package = null;
    private $category = null;
    private $since = null;
    private $deprecated = null;
    private $todo = null;
    private $author = null;
    private $link = null;
    private $param = [];
    private $throws = null;
    private $return = null;
    private $title = null;
    private $latex = null;
    private $example = [];
    private $empty = true;

    public function __construct(string $comment)
    {
        $comment = str_replace("\r\n", "\n", $comment);
        $comment = str_replace("\t", "    ", $comment);
        $this->parse($comment);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getLicense()
    {
        return $this->license;
    }

    public function getVar()
    {
        return $this->var;
    }

    public function getLatex()
    {
        return $this->latex;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getSince()
    {
        return $this->since;
    }

    public function getReturn()
    {
        return $this->return;
    }

    public function getThrows()
    {
        return $this->throws;
    }

    public function getParameters() : array
    {
        return $this->param;
    }

    public function isEmpty() : bool
    {
        return $this->empty;
    }

    public function isDeprecated() : bool
    {
        return $this->deprecated;
    }

    public function getExamples() : array
    {
        return $this->example;
    }

    private function parse(string $comment)
    {
        $this->license    = $this->findKey('@license', $comment)[0] ?? null;
        $this->var        = $this->findKey('@var', $comment)[0] ?? null;
        $this->package    = $this->findKey('@package', $comment)[0] ?? null;
        $this->category   = $this->findKey('@category', $comment)[0] ?? null;
        $this->since      = $this->findKey('@since', $comment)[0] ?? null;
        $this->deprecated = isset($this->findKey('@deprecated', $comment)[0]);
        $this->todo       = $this->findKey('@todo', $comment)[0] ?? null;
        $this->author     = $this->findKey('@author', $comment)[0] ?? null;
        $this->link       = $this->findKey('@link', $comment)[0] ?? null;
        $this->version    = $this->findKey('@version', $comment)[0] ?? null;
        $this->latex      = $this->findKey('@latex', $comment)[0] ?? null;
        $this->example    = $this->findKey('@example', $comment);

        $this->param       = $this->parseParameter($comment);
        $this->description = $this->parseDescription($comment);
        $this->throws      = $this->parseThrows($comment);
        $this->return      = $this->parseReturn($comment);

        $this->empty = empty(trim($comment, '\\ *'));
    }

    private function findKey(string $key, string $comment) : array
    {
        $matches = [];
        $pos     = 0;

        while (($pos = strpos($comment, $key, $pos)) !== false) {
            $match = trim(substr($comment, $pos + strlen($key), (strpos($comment, "\n", $pos + strlen($key))) - $pos - strlen($key)));

            if (isset($match[0]) && $match[0] === '`') {
                $start = strpos($comment, '`', $pos);
                $end   = strpos($comment, '`', $start + 1);

                $match = substr($comment, $start + 1, $end - $start - 2);
                $lines = explode("\n", $match);

                foreach ($lines as $key => $line) {
                    $lines[$key] = trim($line, ' *');
                }

                $match = implode(' ', $lines);
            }

            $match = trim($match);

            if (!empty($match)) {
                $matches[] = $match;
            }

            $pos++;
        }

        return $matches;
    }

    private function parseReturn(string $comment)
    {
        $return = $this->findKey('@return', $comment)[0] ?? null;

        if (!isset($return)) {
            return null;
        }

        $return = explode(' ', $return);

        return ['type' => $return[0], 'desc' => $return[1] ?? null];
    }

    private function parseDescription(string $comment) : string
    {
        $lines       = explode("\n", $comment);
        $description = '';

        foreach ($lines as $key => $line) {
            $line = substr($line, strpos($line, '*') + 2);

            if (isset($line[0]) && ($line[0] === '@' || $line[0] === '{')) {
                break;
            }

            if ($line !== '') {
                $description .= htmlspecialchars($line) . "\n";
            }
        }

        return trim($description, "\n ");
    }

    private function parseParameter(string $comment) : array
    {
        $params = $this->findKey('@param', $comment);
        $params = preg_replace('!\s+!', ' ', $params);
        $parsed = [];

        foreach ($params as $param) {
            $param = explode(' ', $param);

            if (count($param) > 2) {
                $parsed[] = [
                    'type' => array_shift($param),
                    'var'  => array_shift($param),
                    'desc' => implode(' ', $param),
                ];
            }
        }

        return $parsed;
    }

    private function parseThrows(string $comment) : array
    {
        $throws = $this->findKey('@throws', $comment);
        $throws = preg_replace('!\s+!', ' ', $throws);
        $parsed = [];

        foreach ($throws as $throw) {
            $throw = explode(' ', $throw);

            if (count($throw) > 1) {
                $parsed[] = [
                    'type' => array_shift($throw),
                    'desc' => implode(' ', $throw),
                ];
            }
        }

        return $parsed;
    }
}