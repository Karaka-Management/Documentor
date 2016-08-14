<?php

namespace Documentor\src\Application\Models;

class Comment
{
    private $version = '';
    private $php = 'PHP 7.1';
    private $description = '';
    private $license = '';
    private $var = '';
    private $package = '';
    private $category = '';
    private $since = '';
    private $deprecated = '';
    private $todo = '';
    private $author = '';
    private $link = '';
    private $param = '';
    private $throws = '';
    private $return = '';
    private $title = '';

    public function __construct(string $comment)
    {
        $comment = str_replace("\r\n", "\n", $comment);
        $comment = str_replace("\t", "    ", $comment);
        $this->parse($comment);
    }

    private function parse(string $comment)
    {
        $this->license    = $this->findKey('@license', $comment);
        $this->var        = $this->findKey('@var', $comment);
        $this->package    = $this->findKey('@package', $comment);
        $this->category   = $this->findKey('@category', $comment);
        $this->since      = $this->findKey('@since', $comment);
        $this->deprecated = $this->findKey('@deprecated', $comment);
        $this->todo       = $this->findKey('@todo', $comment);
        $this->author     = $this->findKey('@author', $comment);
        $this->link       = $this->findKey('@link', $comment);
        $this->param      = $this->findKey('@param', $comment);
        $this->throws     = $this->findKey('@throws', $comment);
        $this->return     = $this->findKey('@return', $comment);
        $this->version     = $this->findKey('@version', $comment);
    }

    private function findKey(string $key, string $comment) : string
    {
        $pos = strpos($comment, $key);

        if ($pos === false) {
            return '';
        }

        $match = trim(substr($comment, $pos + strlen($key), (strpos($comment, "\n", $pos + strlen($key))) - $pos - strlen($key)));

        if (isset($match[0]) && $match[0] === '`') {
            $start = strpos($comment, '`', $pos);
            $end   = strpos($comment, '`', $start + 1);

            $match = substr($comment, $start + 1, $end - $start - 2);
            $lines = explode("\n", $match);

            foreach ($lines as $key => $line) {
                $lines[$key] = trim($line, ' *');
            }

            implode(' ', $lines);
        }

        return trim($match);
    }

    public function getLicense() : string
    {
        return $this->license;
    }

    public function getVar() : string
    {
        return $this->var;
    }
}