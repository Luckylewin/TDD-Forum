<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Spam;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_validates_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));
    }
}
