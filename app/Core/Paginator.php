<?php

namespace App\Core;

class Paginator
{
    public int $total;
    public int $perPage;
    public int $currentPage;
    public int $lastPage;
    public int $offset;

    public function __construct(int $total, int $perPage = 12, int $currentPage = 1)
    {
        $this->total = $total;
        $this->perPage = max(1, $perPage);
        $this->currentPage = max(1, $currentPage);
        $this->lastPage = max(1, (int) ceil($total / $this->perPage));
        $this->currentPage = min($this->currentPage, $this->lastPage);
        $this->offset = ($this->currentPage - 1) * $this->perPage;
    }

    public function hasPages(): bool
    {
        return $this->lastPage > 1;
    }

    public function hasPrev(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNext(): bool
    {
        return $this->currentPage < $this->lastPage;
    }

    public function prevPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function nextPage(): int
    {
        return min($this->lastPage, $this->currentPage + 1);
    }

    public function pages(): array
    {
        $range = 2;
        $pages = [];
        $start = max(1, $this->currentPage - $range);
        $end = min($this->lastPage, $this->currentPage + $range);

        if ($start > 1) {
            $pages[] = 1;
            if ($start > 2) $pages[] = '...';
        }

        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        if ($end < $this->lastPage) {
            if ($end < $this->lastPage - 1) $pages[] = '...';
            $pages[] = $this->lastPage;
        }

        return $pages;
    }
}
