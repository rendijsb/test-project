<?php

declare(strict_types=1);

namespace App\Models\Customers;

use Database\Factories\Customers\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    public const TABLE = 'customers';
    protected $table = self::TABLE;

    public const ID = 'id';
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PHONE = 'phone';
    public const COMPANY_NAME = 'company_name';
    public const ADDRESS_LINE_1 = 'address_line_1';
    public const ADDRESS_LINE_2 = 'address_line_2';
    public const CITY = 'city';
    public const POSTAL_CODE = 'postal_code';
    public const COUNTRY = 'country';

    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PHONE,
        self::COMPANY_NAME,
        self::ADDRESS_LINE_1,
        self::ADDRESS_LINE_2,
        self::CITY,
        self::POSTAL_CODE,
        self::COUNTRY,
    ];

    public function getId(): int
    {
        return $this->getAttribute(self::ID);
    }

    public function getName(): string
    {
        return $this->getAttribute(self::NAME);
    }

    public function getEmail(): string
    {
        return $this->getAttribute(self::EMAIL);
    }

    public function getPhone(): ?string
    {
        return $this->getAttribute(self::PHONE);
    }

    public function getCompanyName(): ?string
    {
        return $this->getAttribute(self::COMPANY_NAME);
    }

    public function getAddressLine1(): string
    {
        return $this->getAttribute(self::ADDRESS_LINE_1);
    }

    public function getAddressLine2(): ?string
    {
        return $this->getAttribute(self::ADDRESS_LINE_2);
    }

    public function getCity(): string
    {
        return $this->getAttribute(self::CITY);
    }

    public function getPostalCode(): string
    {
        return $this->getAttribute(self::POSTAL_CODE);
    }

    public function getCountry(): string
    {
        return $this->getAttribute(self::COUNTRY);
    }
}