<?php

namespace App;

class SubscriptionData
{
    public int $productId;
    public int $transactionId;
    public int $originalTransactionId;
    public string $notificationType;
    public ?string $purchaseDate;
    public ?string $expirationDate;
    public ?string $cancellationDate;
    public ?string $cancellationReason;

    /**
     * @param int $productId
     * @param int $transactionId
     * @param string|null $purchaseDate
     * @param string|null $expirationDate
     * @param string|null $cancellationDate
     * @param string|null $cancellationReason
     */
    public function __construct(
        int     $productId,
        int     $transactionId,
        int     $originalTransactionId,
        string  $notificationType,
        ?string $purchaseDate,
        ?string $expirationDate,
        ?string $cancellationDate,
        ?string $cancellationReason
    )
    {
        $this->productId = $productId;
        $this->transactionId = $transactionId;
        $this->originalTransactionId = $originalTransactionId;
        $this->notificationType = $notificationType;
        $this->purchaseDate = $purchaseDate;
        $this->expirationDate = $expirationDate;
        $this->cancellationDate = $cancellationDate;
        $this->cancellationReason = $cancellationReason;
    }


}
