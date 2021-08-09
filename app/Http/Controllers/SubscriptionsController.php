<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionsService;
use Exception;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    /**
     * @throws Exception
     */
    public function process(string $gateway, Request $request, SubscriptionsService $subscriptionsService): void
    {
        $subscriptionsService->initialize($gateway, $request);
        $subscriptionsService->process();
    }
}
