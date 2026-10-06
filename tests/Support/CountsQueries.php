<?php

namespace Tests\Support;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

trait CountsQueries
{
    use RefreshDatabase;

    protected array $capturedQueries = [];

    protected function startCounting(): void
    {
        $this->capturedQueries = [];

        DB::listen(function ($query) {
            $this->capturedQueries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
            ];
        });
    }

    protected function queryCount(): int
    {
        return count($this->capturedQueries);
    }

    protected function assertQueryCountAtMost(int $max, string $message = ''): void
    {
        $count = $this->queryCount();

        $this->assertLessThanOrEqual(
            $max,
            $count,
            $message ?: "Expected at most {$max} queries, got {$count}:\n".$this->formatQueries(),
        );
    }

    protected function formatQueries(): string
    {
        return collect($this->capturedQueries)
            ->map(fn ($q, $i) => sprintf('%2d. %s', $i + 1, $q['sql']))
            ->implode("\n");
    }
}
