<?php

namespace Spatie\WordPressRay\Loggers;

use Spatie\WordPressRay\Payloads\ExecutedQueryPayload;

class QueryLogger
{
    protected bool $active = false;

    public function showQueries(): self
    {
        if ($this->active) {
            return $this;
        }

        define('SAVEQUERIES', true);

        add_filter('log_query_custom_data', [$this, 'sendQueryToRay'], 1, 3);

        return $this;
    }

    public function stopShowingQueries(): self
    {
        if (! $this->active) {
            return $this;
        }

        remove_filter('log_query_custom_data', [$this, 'sendQueryToRay'], 1, 3);

        $this->active = false;

        return $this;
    }

    public function sendQueryToRay($data, $sql, $timeInSeconds): array
    {
        $timeInMilliSeconds = $timeInSeconds / 1000;

        $payload = new ExecutedQueryPayload($sql, $timeInMilliSeconds);

        ray()->sendRequest($payload);

        return $data;
    }
}
