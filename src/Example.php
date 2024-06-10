<?php

declare(strict_types=1);

namespace Yard\SkeletonPackage;

use \Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Webmozart\Assert\Assert;

class Example
{
    /**
     * Create a new Example instance.
     */
    public function __construct(protected Application $app)
    {
    }

    /**
     * Retrieve a random inspirational quote.
     */
    public function getQuote(): string
    {
        $quotes = config('example.quotes');

        Assert::isArray($quotes);

        $quote = Arr::random(
            $quotes
        );

        Assert::string($quote);

        return $quote;
    }
}
