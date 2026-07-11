<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Submission;
use App\Models\User;

class PaymentService
{

    public function createPayment(
        Submission $submission,
        User $finance
    ): Payment {

        return Payment::create([
            'submission_id' => $submission->id,
            'finance_id' => $finance->id,
            'amount' => $submission->amount,
            'status' => Payment::WAITING,
        ]);
    }


    public function markPaid(
        Payment $payment
    ): void {

        $payment->update([
            'status' => Payment::PAID,
            'paid_at' => now(),
        ]);


        $payment->submission->update([
            'status' => Submission::PAID,
        ]);
    }


    public function reject(
        Payment $payment
    ): void {

        $payment->update([
            'status' => Payment::REJECTED,
        ]);


        $payment->submission->update([
            'status' => Submission::REJECTED,
        ]);
    }
}
