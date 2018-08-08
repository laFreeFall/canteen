<?php

namespace App\Observers;

use App\Payment;

class PaymentObserver
{
    /**
     * Handle to the payment "created" event.
     *
     * @param  Payment  $payment
     * @return void
     */
    public function created(Payment $payment)
    {
        $balance = $payment->pupil->balanceForWeek($payment->week);
        $balance->increment('current_amount', $payment->amount);
    }

    /**
     * Handle the payment "updated" event.
     *
     * @param  Payment  $payment
     * @return void
     */
    public function updated(Payment $payment)
    {
        $balance = $payment->pupil->balanceForWeek($payment->week);
        $balance->increment('current_amount', $payment->amount - $payment->getOriginal('amount'));
    }
}
