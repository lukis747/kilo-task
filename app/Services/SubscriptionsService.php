<?php


namespace App\Services;


use App\Contracts\SubscriptionGatewayInterface;
use Exception;
use Illuminate\Http\Request;

class SubscriptionsService
{
    private SubscriptionGatewayInterface $subscriptionGateway;

    /**
     * @throws Exception
     */
    public function initialize(string $gateway, Request $request): void
    {
        $subscriptionGatewayClass = config("subscription.$gateway.class");

        try {
            $this->subscriptionGateway = new $subscriptionGatewayClass();
            $this->subscriptionGateway->setData($request);
        } catch (Exception $exception) {
            throw new Exception("Subscription gateway initialization failed");
        }

    }

    public function process(): void
    {
        $this->subscriptionGateway->process();
    }
}
