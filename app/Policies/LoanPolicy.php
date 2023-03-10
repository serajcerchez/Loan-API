<?php

namespace App\Policies;

use App\Enums\State;
use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    public function view(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id;
    }

    public function approve(User $user, Loan $loan): bool
    {
        return $loan->status < State::APPROVED->value && $user->is_admin;
    }

    public function repay(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id && $loan->state == State::APPROVED->value;
    }
}
