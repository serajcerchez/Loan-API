<?php

namespace App\Services;

use App\Enums\State;
use App\Events\LoanPayed;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class LoanService
{
    public function create(User $user, array $data): int
    {
        $loan = $user->loans()->create($data);
        $this->storeRepayments($loan, (int)$data['loan_term']);

        return $loan->id;
    }

    public function storeRepayments(Loan $loan, int $repaymentTerm): void
    {
        $repaymentAmount = number_format($loan->amount / $repaymentTerm, 2);
        $remainingAmount = 0;
        
        if ($repaymentAmount * $repaymentTerm !== $loan->amount) {
            $remainingAmount = $loan->amount - $repaymentAmount * $repaymentTerm;
        }
        
        $today = Date::today();

        for ($i = 1; $i <= $repaymentTerm; $i++) {
            $today->addWeek();

            if ($i == $repaymentTerm) {
                $repaymentAmount += $remainingAmount;
            }

            $loan->repayments()->create([
                'amount' => $repaymentAmount,
                'scheduled_at' => $today
            ]);
        }

    }

    public function repay(Loan $loan, float $amount): array
    {
        $repayments = $loan->repayments()->where('state', '<>', State::PAID->value)->get();
        $nextRepaymentAmount = 0;
        $paid = 0;

        foreach ($repayments as $repayment) {
            if ($amount < $repayment->amount) {
                $nextRepaymentAmount = $repayment->amount;
                break;
            }

            $repayment->state = State::PAID->value;
            $repayment->save();

            $amount -= $repayment->amount;
            $paid++;
        }

        if ($paid === $repayments->count()) {
            LoanPayed::dispatch($loan);
        }

        return [
            'unused_amount' => $amount,
            'next_repayment' => $nextRepaymentAmount
        ];
    }
}
