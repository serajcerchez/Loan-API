<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class LoanServiceTest extends TestCase
{
    private LoanService $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new LoanService();
    }

    public function testWillStoreRepayments(): void
    {
        $loan = $this->createMock(Loan::class);
        $loan->method('__get')->with('amount')->willReturn('100.00');
        $loan->amount = '100.00';
        
        $date = Carbon::setTestNow('2023-03-10');
        $now = Carbon::now();
        
        $hasMany = $this->getMockBuilder(HasMany::class)
        ->disableOriginalConstructor()->getMock();
        $loan->method('repayments')->willReturn($hasMany);

        $loanCreateArguments = [];

        $hasMany->expects($this->exactly(3))->method('create')
        ->will($this->returnCallback(
            function($data) use (&$loanCreateArguments){
                $loanCreateArguments[] = $data;
            }
        ));

        $expected = [
            [
                'amount' => '33.33',
                'scheduled_at' => $now->addWeek()
            ],
            [
                'amount' => '33.33',
                'scheduled_at' => $now->addWeek()
            ],
            [
                'amount' => '33.34',
                'scheduled_at' => $now->addWeek()
            ],
        ];

        $this->subject->storeRepayments($loan, 3);
        
        $this->assertEquals($expected, $loanCreateArguments);
    }

    public function testWillCreateLoan(): void
    {
        // @todo: write test if you have time
    }

    public function testWillRepayLoan(): void
    {
        // @todo: write test if you have time
    }
}
