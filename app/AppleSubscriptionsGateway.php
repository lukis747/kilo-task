<?php


namespace App;

use App\Contracts\SubscriptionGatewayInterface;
use Exception;
use Illuminate\Http\Request;

class AppleSubscriptionsGateway extends SubscriptionGateway implements SubscriptionGatewayInterface
{
    private SubscriptionData $data;

    public function setData(Request $request): void
    {
        $this->validate($request->all(), [
            'notification_type' => 'required|string',
            'unified_receipt' => 'required|array',
            'unified_receipt.latest_receipt_info' => 'required|array',
        ]);

        $this->data = new SubscriptionData(
            $request->input('unified_receipt.latest_receipt_info.product_id'),
            $request->input('unified_receipt.latest_receipt_info.transaction_id'),
            $request->input('unified_receipt.latest_receipt_info.original_transaction_id'),
            $request->input('notification_type'),
            $request->input('unified_receipt.latest_receipt_info.purchase_date'),
            $request->input('unified_receipt.latest_receipt_info.expires_date'),
            $request->input('unified_receipt.latest_receipt_info.cancellation_date'),
            $request->input('unified_receipt.latest_receipt_info.cancellation_reason'),
        );
    }

    /**
     * @throws Exception
     */
    public function process(): void
    {
        switch ($this->data->notificationType) {
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
                throw new Exception('Notification type not found');
        }
        $this->saveTransaction($this->data);
    }

    public function subscribe(): void
    {
        $this->createSubscription($this->data);
    }

    public function extendSubscription(): void
    {
        $this->extendExistingSubscription($this->data);
    }

    public function failedToExtend(): void
    {
        $this->markSubscriptionAsFailedToExtend($this->data);
    }

    public function unsubscribe(): void
    {
        $this->cancelSubscription($this->data);
    }
}
