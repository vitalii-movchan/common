<?php

declare(strict_types=1);

namespace Common\Filter;

abstract class Filter
{
    /**
     * Filter constructor.
     */
    public function __construct(
        private object $object,
        private array $parameters = [],
    ) {}

    /**
     * Execute filter.
     */
    public function execute(): object
    {
        $this->applyQueries();

        if ($this->hasNotOrderBy()) {
            $this->defaultOrderBy();
        }

        return $this->object;
    }

    /**
     * Apply Queries.
     */
    private function applyQueries(): void
    {
        $queries = $this->queries();

        foreach ($this->parameters as $key => $value) {
            $query = $queries[$key] ?? null;

            if (is_callable($query) === true) {
                call_user_func($query, $value);
            }
        }
    }

    /**
     * Check if "order_by" is empty in the parameters.
     */
    private function hasNotOrderBy(): bool
    {
        return empty($this->parameters['orderBy']) === true;
    }

    /**
     * Array of queries by field name.
     */
    abstract protected function queries(): array;

    /**
     * Default query for order_by field.
     */
    abstract protected function defaultOrderBy(): void;
}
