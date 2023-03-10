<?php

namespace App\Providers;

use App\Providers\LoanPayed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkLoanAsPayed
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LoanPayed $event): void
    {
        //
    }
}
