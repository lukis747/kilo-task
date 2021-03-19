<?php


namespace App\Services;


use App\Models\Subscription;
use Illuminate\Support\Collection;

interface SubscriptionServiceInterface
{
    public function __construct(Collection $payload);
    public function process();
    public function subscribe();
    public function extendSubscription();
    public function failedToExtend();
    public function unsubscribe();
}
