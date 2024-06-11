<?php

declare(strict_types=1);

it('can retrieve a random inspirational quote', function () {
    $quote = app()->make('Example')->getQuote();

    expect($quote)->toBeString();
    expect($quote)->tobe('For every Sage there is an Acorn.');
});
