<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Loan extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'scheduled_repayments' => Repayment::collection($this->repayments)
        ];
    }
}
