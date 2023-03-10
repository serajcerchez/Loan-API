<?php

namespace Tests\Feature;

use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_will_create_a_new_loan()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/loan', [
            'amount' => '100.00',
            'loan_term' => 3
        ]);

        $response->assertStatus(201);
        $response->assertJson(['loan' => 1]);
    }

    public function test_will_not_create_a_new_loan_if_not_authorized()
    {
        $response = $this->postJson('/api/loan', [
            'amount' => '100.00',
            'loan_term' => 3
        ]);

        $response->assertStatus(401);
    }

    public function test_will_not_create_a_new_loan_if_amount_is_missing()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/loan', [
            'amount' => null,
            'loan_term' => 3
        ]);

        $response->assertStatus(422);
    }

    public function test_will_not_create_a_new_loan_if_amount_is_not_decimal()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/loan', [
            'amount' => 1,
            'loan_term' => 3
        ]);

        $response->assertStatus(422);
    }

    public function test_will_not_create_a_new_loan_if_loan_term_is_missing()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/loan', [
            'amount' => '10.00',
            'loan_term' => null
        ]);

        $response->assertStatus(422);
    }

    public function test_will_not_create_a_new_loan_if_loan_term_is_decimal()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/loan', [
            'amount' => '10.00',
            'loan_term' => '2.5'
        ]);

        $response->assertStatus(422);
    }

    public function test_will_not_create_a_new_loan_if_loan_term_is_greater_than_max()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/loan', [
            'amount' => '10.00',
            'loan_term' => '13'
        ]);

        $response->assertStatus(422);
    }

    public function test_will_not_create_a_new_loan_if_loan_term_is_zero()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/loan', [
            'amount' => '10.00',
            'loan_term' => '0'
        ]);

        $response->assertStatus(422);
    }

    public function test_will_view_loan()
    {
        $loggedInUserUser = User::factory()->create();

        Sanctum::actingAs($loggedInUserUser);

        $loan = Loan::factory()->for($loggedInUserUser)->create();

        $response = $this->getJson(sprintf('/api/loan/%s', $loan->id));

        $response->assertStatus(200);
    }

    public function test_will_not_view_loan_if_does_not_belong_to_the_current_user()
    {
        $loggedInUserUser = User::factory()->create();
        $loanUser = User::factory()->create();
        Sanctum::actingAs($loggedInUserUser);

        $loan = Loan::factory()->for($loanUser)->create();

        $response = $this->getJson(sprintf('/api/loan/%s', $loan->id));

        $response->assertStatus(403);
    }

    public function test_will_approve_loan()
    {
        $adminUser = User::factory()->admin()->create();
        $loanUser = User::factory()->create();
        Sanctum::actingAs($adminUser);

        $loan = Loan::factory()->for($loanUser)->create();

        $response = $this->patchJson(sprintf('/api/loan/%s/approve', $loan->id));

        $response->assertStatus(200);
    }

    public function test_will_not_approve_loan_if_user_is_not_admin()
    {
        $notAdminUser = User::factory()->create();
        $loanUser = User::factory()->create();
        Sanctum::actingAs($notAdminUser);

        $loan = Loan::factory()->for($loanUser)->create();

        $response = $this->patchJson(sprintf('/api/loan/%s/approve', $loan->id));

        $response->assertStatus(403);
    }

    public function test_will_repay_partial_loan()
    {
        // @todo: write test if you have time
    }

    public function test_will_repay_the_entire_loan()
    {
        // @todo: write test if you have time
    }

    public function test_will_not_repay_if_loan_does_not_belong_to_the_current_customer()
    {
        // @todo: write test if you have time
    }

    public function test_will_not_repay_if_loan_is_not_approved()
    {
        // @todo: write test if you have time
    }
}
