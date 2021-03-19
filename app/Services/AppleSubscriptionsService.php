<?php


namespace App\Services;

use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Collection;

class AppleSubscriptionsService implements SubscriptionServiceInterface
{
    private $payload;
    private $subscriptionService;

    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->subscriptionService = new SubscriptionsService();
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

            case 'DID_FAIL_TO_RENEW':;
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

        $this->subscriptionService->subscribe(
            $receipt->product_id,
            $receipt->original_transaction_id,
            $receipt->purchase_date,
            $receipt->expires_date);
   }

    public function extendSubscription()
    {
        $receipt = $this->payload->unified_receipt->latest_receipt_info;

        $this->subscriptionService->extendSubscription(
            $receipt->original_transaction_id,
            $receipt->expires_date
        );
    }

    public function failedToExtend()
    {
        $receipt = $this->payload->unified_receipt->latest_receipt_info;

        $this->subscriptionService->failedToExtend($receipt->original_transaction_id);
    }

    public function unsubscribe()
    {
        $receipt = $this->payload->unified_receipt->latest_receipt_info;

        $this->subscriptionService->unsubscribe(
            $receipt->original_transaction_id,
            $receipt->cancellation_date,
            $receipt->cancellation_reason);
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
