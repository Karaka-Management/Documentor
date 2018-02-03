<?php

namespace Documentor\src\Application\Views;

class TableOfContentsView extends BaseView
{
    protected $stats = [];

    protected $withoutComment = [];

    public function setStats(array $stats)
    {
        $this->stats = $stats;
    }

    public function setWithoutComment(array $comment)
    {
        $this->withoutComment = $comment;
    }
}
