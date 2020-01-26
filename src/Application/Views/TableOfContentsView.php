<?php

namespace Documentor\src\Application\Views;

class TableOfContentsView extends BaseView
{
    protected array $stats = [];

    protected array $withoutComment = [];

    public function setStats(array $stats)
    {
        $this->stats = $stats;
    }

    public function setWithoutComment(array $comment)
    {
        $this->withoutComment = $comment;
    }
}
