<?php


namespace App\Services;

use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Collection;

class AppleService implements SubscriptionServiceInterface
{
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function process()
    {
        switch ($this->payload->notification_type) {
            case 'INITIAL_BUY':
                $this->subscribe();
                break;

            case 'DID_RENEW':
                $this->extendSubscription();
                break;

            case 'DID_FAIL_TO_RENEW':
                $this->failedToExtend();
                break;

            case 'CANCEL':
                $this->unsubscribe();
                break;

            default:
                return response()->json([
                    'error' => 'Invalid notification type'
                ], 400);
        }
        $this->saveTransaction();
    }

    public function subscribe()
    {
        $receipt = $this->payload->unified_receipt->latest_receipt_info;

        $subscription = new Subscription();
        $subscription->product_id = $receipt->product_id;
        $subscription->original_transaction_id = $receipt->original_transaction_id;
        $subscription->status = Subscription::ACTIVE;
        $subscription->purchase_date = $receipt->purchase_date;
        $subscription->expires_date = $receipt->expires_date;
        $subscription->save();
   }

    public function extendSubscription()
    {
        $receipt = $this->payload->unified_receipt->latest_receipt_info;
        $subscription = Subscription::where('original_transaction_id', $receipt->original_transaction_id)
            ->firstOrFail();

        $subscription->status = Subscription::ACTIVE;
        $subscription->expires_date = $receipt->expires_date;
        $subscription->save();
    }

    public function failedToExtend()
    {
        $receipt = $this->payload->unified_receipt->latest_receipt_info;
        $subscription = Subscription::where('original_transaction_id', $receipt->original_transaction_id)
            ->firstOrFail();

        $subscription->status = Subscription::FAILED_TO_EXTEND;
        $subscription->save();
    }

    public function unsubscribe()
    {
        $receipt = $this->payload->unified_receipt->latest_receipt_info;
        $subscription = Subscription::where('original_transaction_id', $receipt->original_transaction_id)
            ->firstOrFail();

        $subscription->status = Subscription::CANCELED;
        $subscription->save();
    }

    public function saveTransaction(){
        $notificationType = $this->payload->notification_type;
        $receipt = $this->payload->unified_receipt->latest_receipt_info;

        $transaction = new Transaction();
        $transaction->payload = json_encode($this->payload);
        $transaction->transaction_id = $receipt->transaction_id;
        $transaction->original_transaction_id = $receipt->original_transaction_id;
        $transaction->notification_type = $notificationType;
        $transaction->save();
    }



}
