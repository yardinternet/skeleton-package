<?php

namespace Yard\SkeletonPackage;

use Illuminate\Support\Arr;
use \Illuminate\Contracts\Foundation\Application;
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
