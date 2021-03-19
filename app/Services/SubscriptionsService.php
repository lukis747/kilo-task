<?php


namespace App\Services;


use App\Models\Subscription;

class SubscriptionsService
{
    public function subscribe($product_id,$transaction_id,$purchase_data,$expiration_date){
        $subscription = new Subscription();
        $subscription->product_id = $product_id;
        $subscription->original_transaction_id = $transaction_id;
        $subscription->status = Subscription::ACTIVE;
        $subscription->purchase_date = $purchase_data;
        $subscription->expires_date = $expiration_date;
        $subscription->save();
    }
    public function extendSubscription($transaction_id,$expiration_date){
        $subscription = Subscription::where('original_transaction_id', $transaction_id)
            ->firstOrFail();
        $subscription->status = Subscription::ACTIVE;
        $subscription->expires_date = $expiration_date;
        $subscription->cancellation_date = null;
        $subscription->cancellation_reason = null;
        $subscription->save();
    }
    public function failedToExtend($transaction_id)
    {
        $subscription = Subscription::where('original_transaction_id', $transaction_id)
            ->firstOrFail();

        $subscription->status = Subscription::FAILED_TO_EXTEND;
        $subscription->cancellation_date = null;
        $subscription->cancellation_reason = null;
        $subscription->save();
    }
    public function unsubscribe($transaction_id,$cancellation_date,$cancellation_reason){
        $subscription = Subscription::where('original_transaction_id', $transaction_id)
            ->firstOrFail();

        $subscription->status = Subscription::CANCELED;
        $subscription->cancellation_date = $cancellation_date;
        $subscription->cancellation_reason = $cancellation_reason;
        $subscription->save();
    }
}
