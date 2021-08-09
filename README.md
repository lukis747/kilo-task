**Apple Web Hooks implementation**
Kilo Test day task

Using your preferred Framework (Laravel / Lumen / Symfony)
Create backend  logic that handles 3rd party payment provider's "Apple In App purchases" webhook notifications and manages our business subscription logic: (Creates transaction entries, gives or revokes access from Application, Updates subscription entry with latest information.)

**Documentation:**  https://developer.apple.com/documentation/appstoreservernotifications

Business logic should handle these actions:
1. Initial subscription [INITIAL_BUY]
2. Renewed subscription [DID_RENEW]
3. Unsuccessful renewal [DID_FAIL_TO_RENEW]
4. Cancel subscription [CANCEL]

*Business side subscription logic should be agnostic (decoupled) from specific Payment Service Provider (PSP) and reusable with many other PSP’s that could be implemented later on (Stripe, Braintree, Paypal).

1) Estimation. Please spend the first hour researching documentation, creating overall architecture and providing us with full estimation of the task. (1 hour)
2) Create a main implementation of webhook logic - skeleton of code structure with  specific comments on overall logic and code that should be written later on to complete the task. (2 hours)
3) Lunch time. Let's go grab lunch with a couple team members and chit-chat.
4) Based on estimation complete the task while covering part's that couldn't be completed with comments.  (∞ hours)
5) Final review (Let's discuss your code and strategy used to complete the task).

Do not hesitate to ask questions or communicate during test day. It's a real life task and any resources possible should be used to complete the task as fast as possible.
If you feel hungry or thirsty - grab a snack/drink from the kitchen, (ask a colleague where to find it). 


---
Endpoint: `/api/v1/subscriptions/{provider}`

POST request: `/api/v1/subscriptions/apple`

Body:

```json
{
    "notification_type":"DID_RENEW",
    "unified_receipt":{
        "latest_receipt_info":{
            "purchase_date":"2021-03-19",
            "cancellation_date":"",
            "cancellation_reason":"",
            "expires_date":"2021-10-19",
            "product_id":7788,
            "transaction_id":12222222,
            "original_transaction_id":12254444
        }
    }
}
```

Available parameters for _**notification_type**_: 
- INITIAL_BUY
- DID_RENEW
- DID_FAIL_TO_RENEW
- CANCEL


