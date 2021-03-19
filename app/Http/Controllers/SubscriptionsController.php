<?php

namespace App\Http\Controllers;

use App\Services\AppleSubscriptionsService;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionsController extends Controller
{
    public function process($provider,Request $request)
    {
        // TODO Dynamic validation

        $data = json_decode(json_encode($request->all(),FALSE));

        switch ($provider){
            case 'apple':
                $provider  = new AppleSubscriptionsService($data);
                break;

            default:
                return response(['error'=> 'Provider not found'],404);
        }

        $provider->process();
    }
}
