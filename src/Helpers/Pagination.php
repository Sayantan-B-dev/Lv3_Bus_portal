<?php
declare(strict_types=1);
namespace App\Helpers;

class Pagination
{
    public int $total;
    public int $perPage;
    public int $currentPage;
    public int $totalPages;

    public function __construct(int $total, int $perPage, int $currentPage)
    {
        $this->total       = $total;
        $this->perPage     = $perPage;
        $this->currentPage = max(1, $currentPage);
        $this->totalPages  = max(1, (int)ceil($total / $perPage));
    }

    public function hasNext(): bool { return $this->currentPage < $this->totalPages; }
    public function hasPrev(): bool { return $this->currentPage > 1; }
    public function nextPage(): int { return min($this->currentPage + 1, $this->totalPages); }
    public function prevPage(): int { return max($this->currentPage - 1, 1); }
    public function offset(): int   { return ($this->currentPage - 1) * $this->perPage; }

    /** Generate array of page numbers to display. */
    public function pages(int $range = 2): array
    {
        $start = max(1, $this->currentPage - $range);
        $end   = min($this->totalPages, $this->currentPage + $range);
        return range($start, $end);
    }

    public function toArray(): array
    {
        return [
            'page'        => $this->currentPage,
            'per_page'    => $this->perPage,
            'total'       => $this->total,
            'total_pages' => $this->totalPages,
            'has_next'    => $this->hasNext(),
            'has_prev'    => $this->hasPrev(),
        ];
    }
}
