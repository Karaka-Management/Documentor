<?php declare(strict_types=1);

namespace Documentor\src\Application\Views;

class TableOfContentsView extends BaseView
{
    protected array $stats = [];

    protected array $withoutComment = [];

    public function setStats(array $stats): void
    {
        $this->stats = $stats;
    }

    public function setWithoutComment(array $comment): void
    {
        $this->withoutComment = $comment;
    }
}
