<?php

namespace Tests\Feature;

use App\AppleSubscriptionsGateway;
use App\Services\SubscriptionsService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Psy\Exception\FatalErrorException;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @throws \Exception
     */
    public function test_initialize_subscription_gateway()
    {
        $subscriptionsService = new SubscriptionsService();

        $request = new Request([
            'notification_type' => 'INITIAL_BUY',
            'unified_receipt' => [
                'latest_receipt_info' => [
                    "purchase_date" => "2021-03-19",
                    "cancellation_date" => "",
                    "cancellation_reason" => "",
                    "expires_date" => "2021-04-19",
                    "product_id" => 7788,
                    "transaction_id" => 122544442,
                    "original_transaction_id" => 122544442
                ]
            ]
        ]);

        $subscriptionsService->initialize('apple', $request);

        $this->assertInstanceOf(AppleSubscriptionsGateway::class, $subscriptionsService->getSubscriptionGateway());
    }

    public function test_fail_to_initialize_subscription_gateway()
    {
        $subscriptionsService = new SubscriptionsService();

        $request = new Request([]);

        $this->expectException(\Exception::class);
        $subscriptionsService->initialize('apple', $request);
    }

//    public function test_fail_to_initialize_subscription_gateway_with_wrong_gateway()
//    {
//        $subscriptionsService = new SubscriptionsService();
//
//        $request = new Request([]);
//
//        $this->expectException(FatalErrorException::class);
//        $subscriptionsService->initialize('swedbank', $request);
//    }
}
