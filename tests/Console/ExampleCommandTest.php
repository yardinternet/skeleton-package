<?php

it('can retrieve a random inspirational quote', function () {
    $this->artisan('example')
        ->expectsOutput('For every Sage there is an Acorn.')
        ->assertExitCode(0);
});
