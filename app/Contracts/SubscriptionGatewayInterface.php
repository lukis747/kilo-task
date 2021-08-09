<?php


namespace App\Contracts;

use Illuminate\Http\Request;

interface SubscriptionGatewayInterface
{
    public function setData(Request $request): void;

    public function validate(array $data, array $rules);

    public function process(): void;

    public function subscribe(): void;

    public function extendSubscription(): void;

    public function failedToExtend(): void;

    public function unsubscribe(): void;
}
