<?php

namespace App\Http\Controllers;

use App\Http\Resources\Loan as ResourcesLoan;
use App\Enums\State;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct(private LoanService $loanService) {}

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount' => 'required|decimal:2|gt:0',
            'loan_term' => 'required|integer|max:12|gt:0'
        ]);

        $loadId = $this->loanService->create($request->user(), $data);

        return response()->json(['loan' => $loadId], 201);
    }

    public function show(Loan $loan): JsonResponse
    {
        Gate::authorize('view', $loan);

        return response()->json(new ResourcesLoan($loan));
    }

    public function approve(Loan $loan): JsonResponse
    {
        Gate::authorize('approve', $loan);

        $loan->state = State::APPROVED->value;
        $loan->save();

        return response()->json(['message' => 'success']);
    }

    public function repay(Loan $loan, Request $request): JsonResponse
    {
        Gate::authorize('repay', $loan);

        $data = $request->validate([
            'amount' => 'required|decimal:2'
        ]);

        $result = $this->loanService->repay($loan, $data['amount']);

        return response()->json([
            'success' => true,
            'unused_amount' => number_format($result['unused_amount'], 2),
            'next_repayment' => $result['next_repayment'],
        ]);
    }
}
