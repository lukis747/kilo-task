<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Services\AppleService;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionsController extends Controller
{
    public function process($provider,Request $request)
    {
        // TODO Dynamic validation

        switch ($provider){
            case 'paypal':
                $provider  = new AppleService(json_decode(json_encode($request->all(),FALSE)));
                break;

            default:
                return response(['error'=> 'Provider not found'],404);
        }

        return $provider->process();
    }
}
