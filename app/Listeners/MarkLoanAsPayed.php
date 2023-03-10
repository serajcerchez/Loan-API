<?php

namespace App\Listeners;

use App\Enums\State;
use App\Events\LoanPayed;

class MarkLoanAsPayed
{
    public function handle(LoanPayed $event): void
    {
        $loan = $event->loan;
        $loan->state = State::PAID->value;
        $loan->save();
    }
}
