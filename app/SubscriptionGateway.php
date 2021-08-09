<?php

namespace App;

use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class SubscriptionGateway
{
    public function validate(array $data, array $rules): void
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            abort(400, $validator->errors());
        }
    }

    /**
     * @throws JsonException
     */
    public function saveTransaction(SubscriptionData $data): Transaction
    {
        return Transaction::create([
            'payload' => Json::encode($data),
            'transaction_id' => $data->transactionId,
            'original_transaction_id' => $data->originalTransactionId,
            'notification_type' => $data->notificationType
        ]);
    }

    public function createSubscription(SubscriptionData $data): Subscription
    {
        return Subscription::updateOrCreate([
            'original_transaction_id' => $data->originalTransactionId
        ], [
            'status' => Subscription::ACTIVE,
            'product_id' => $data->productId,
            'original_transaction_id' => $data->originalTransactionId,
            'purchase_date' => $data->purchaseDate,
            'expires_date' => $data->expirationDate,
        ]);
    }

    public function extendExistingSubscription(SubscriptionData $data): bool
    {
        $subscription = Subscription::where('original_transaction_id', $data->transactionId)
            ->firstOrFail();

        return $subscription->update([
            'status' => Subscription::ACTIVE,
            'expires_date' => $data->expirationDate,
            'cancellation_date' => null,
            'cancellation_reason' => null
        ]);
    }

    public function markSubscriptionAsFailedToExtend(SubscriptionData $data): bool
    {
        $subscription = Subscription::where('original_transaction_id', $data->transactionId)
            ->firstOrFail();

        return $subscription->update([
            'status' => Subscription::FAILED_TO_EXTEND,
        ]);
    }

    public function cancelSubscription(SubscriptionData $data): bool
    {
        $subscription = Subscription::where('original_transaction_id', $data->transactionId)
            ->firstOrFail();

        return $subscription->update([
            'status' => Subscription::CANCELED,
            'cancellation_date' => $data->cancellationDate,
            'cancellation_reason' => $data->cancellationReason
        ]);
    }
}
