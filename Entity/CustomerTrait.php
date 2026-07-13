<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\TwoFactorAuthCustomer44\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Attribute\EntityExtension;
use Eccube\Entity\Customer;

#[EntityExtension(Customer::class)]
trait CustomerTrait
{
    #[ORM\Column(name: 'device_auth_one_time_token', type: Types::STRING, length: 255, nullable: true)]
    private ?string $device_auth_one_time_token = null;

    #[ORM\Column(name: 'device_auth_one_time_token_expire', type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTime $device_auth_one_time_token_expire = null;

    #[ORM\Column(name: 'device_authed', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
    private bool $device_authed = false;

    #[ORM\Column(name: 'device_authed_phone_number', type: Types::STRING, length: 14, nullable: true)]
    private ?string $device_authed_phone_number = null;

    /**
     * 2段階認証機能の設定
     */
    #[ORM\Column(name: 'two_factor_auth_type', type: Types::INTEGER, nullable: true)]
    private ?int $two_factor_auth_type = null;

    #[ORM\ManyToOne(targetEntity: TwoFactorAuthType::class)]
    #[ORM\JoinColumn(name: 'two_factor_auth_type_id', referencedColumnName: 'id')]
    private ?TwoFactorAuthType $TwoFactorAuthType = null;

    /**
     * @var Collection<int, TwoFactorAuthCustomerCookie>|null
     */
    #[ORM\OneToMany(targetEntity: TwoFactorAuthCustomerCookie::class, mappedBy: 'Customer')]
    private $TwoFactorAuthCustomerCookies;

    public function getDeviceAuthOneTimeToken(): ?string
    {
        return $this->device_auth_one_time_token;
    }

    public function setDeviceAuthOneTimeToken(?string $device_auth_one_time_token): void
    {
        $this->device_auth_one_time_token = $device_auth_one_time_token;
    }

    /**
     * Get resetExpire.
     */
    public function getDeviceAuthOneTimeTokenExpire(): ?\DateTime
    {
        return $this->device_auth_one_time_token_expire;
    }

    /**
     * Set oneTimeTokenExpire.
     *
     * @return Customer
     */
    public function setDeviceAuthOneTimeTokenExpire($deviceAuthOneTimeTokenExpire = null)
    {
        $this->device_auth_one_time_token_expire = $deviceAuthOneTimeTokenExpire;

        return $this;
    }

    public function isDeviceAuthed(): bool
    {
        return $this->device_authed;
    }

    public function setDeviceAuthed(bool $device_authed): void
    {
        $this->device_authed = $device_authed;
    }

    public function getDeviceAuthedPhoneNumber(): ?string
    {
        return $this->device_authed_phone_number;
    }

    public function setDeviceAuthedPhoneNumber(?string $device_authed_phone_number): void
    {
        $this->device_authed_phone_number = $device_authed_phone_number;
    }

    /**
     * Get two-factor auth type.
     */
    public function getTwoFactorAuthType(): ?TwoFactorAuthType
    {
        return $this->TwoFactorAuthType;
    }

    /**
     * Set two-factor auth type.
     *
     * @return $this
     */
    public function setTwoFactorAuthType(?TwoFactorAuthType $twoFactorAuthType = null)
    {
        $this->TwoFactorAuthType = $twoFactorAuthType;

        return $this;
    }

    public function getTwoFactorAuthCustomerCookies(): Collection
    {
        return $this->TwoFactorAuthCustomerCookies;
    }

    public function setTwoFactorAuthCustomerCookies(Collection $TwoFactorAuthCustomerCookies): void
    {
        $this->TwoFactorAuthCustomerCookies = $TwoFactorAuthCustomerCookies;
    }
}
