<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function createPayment(Submission $submission,User $finance){
        return Payment::create([
            'submission_id' => $submission->id,
            'finance_user_id' => $finance->id,
            'amount' => $submission->amount,
            'status' => Payment::WAITING,
        ]);
    }


    public function markPaid(Payment $payment){
        DB::transaction(function () use ($payment) {

            $payment->update([
                'status' => Payment::PAID,
                'paid_at' => now(),
            ]);


            $payment->submission->update([
                'status' => Submission::PAID,
            ]);
        });
    }


    public function reject(Payment $payment){

        DB::transaction(function () use ($payment) {

            $payment->update([
                'status' => Payment::REJECTED,
            ]);


            $payment->submission->update([
                'status' => Submission::REJECTED,
            ]);
        });
    }
}
