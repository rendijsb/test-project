<?php

declare(strict_types=1);

namespace App\Models\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Models\Customers\Customer;
use App\Models\User;
use Database\Factories\Orders\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public const TABLE = 'orders';
    protected $table = self::TABLE;

    public const ID = 'id';
    public const CUSTOMER_ID = 'customer_id';
    public const USER_ID = 'user_id';
    public const STATUS = 'status';
    public const TOTAL_AMOUNT = 'total_amount';
    public const DESCRIPTION = 'description';
    public const PRODUCT_NAME = 'product_name';
    public const QUANTITY = 'quantity';
    public const ORDER_DATE = 'order_date';

    protected $fillable = [
        self::CUSTOMER_ID,
        self::USER_ID,
        self::STATUS,
        self::TOTAL_AMOUNT,
        self::DESCRIPTION,
        self::PRODUCT_NAME,
        self::QUANTITY,
        self::ORDER_DATE,
    ];

    protected function casts(): array
    {
        return [
            self::STATUS => OrderStatusEnum::class,
            self::TOTAL_AMOUNT => 'decimal:2',
            self::ORDER_DATE => 'date',
        ];
    }

    public function getId(): int
    {
        return $this->getAttribute(self::ID);
    }

    public function getCustomerId(): int
    {
        return $this->getAttribute(self::CUSTOMER_ID);
    }

    public function getUserId(): int
    {
        return $this->getAttribute(self::USER_ID);
    }

    public function getStatus(): OrderStatusEnum
    {
        return $this->getAttribute(self::STATUS);
    }

    public function getTotalAmount(): string
    {
        return $this->getAttribute(self::TOTAL_AMOUNT);
    }

    public function getDescription(): ?string
    {
        return $this->getAttribute(self::DESCRIPTION);
    }

    public function getProductName(): string
    {
        return $this->getAttribute(self::PRODUCT_NAME);
    }

    public function getQuantity(): int
    {
        return $this->getAttribute(self::QUANTITY);
    }

    public function getOrderDate(): string
    {
        return $this->getAttribute(self::ORDER_DATE)->toDateString();
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where(self::STATUS, $status);
    }

    public function scopeByCustomer(Builder $query, int $customerId): Builder
    {
        return $query->where(self::CUSTOMER_ID, $customerId);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, self::CUSTOMER_ID);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }
}
