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

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Eccube\Entity\Customer;
use Plugin\TwoFactorAuthCustomer44\Repository\TwoFactorAuthCustomerCookieRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TwoFactorCustomerCookie
 */
#[ORM\Table(name: 'plg_two_factor_customer_cookie')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discriminator_type', type: Types::STRING, length: 255)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TwoFactorAuthCustomerCookieRepository::class)]
#[UniqueEntity('id')]
class TwoFactorAuthCustomerCookie extends AbstractEntity
{
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'TwoFactorAuthCustomerCookies')]
    #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'id')]
    private ?Customer $Customer = null;

    #[ORM\Column(name: 'cookie_name', type: Types::STRING, nullable: false, length: 512)]
    private string $cookie_name;

    #[ORM\Column(name: 'cookie_value', type: Types::STRING, nullable: false, length: 512, unique: true)]
    private string $cookie_value;

    #[ORM\Column(name: 'cookie_expire_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $cookie_expire_date = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTime $updatedAt = null;

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->Customer;
    }

    public function setCustomer(Customer $Customer): void
    {
        $this->Customer = $Customer;
    }

    public function getCookieName(): string
    {
        return $this->cookie_name;
    }

    public function setCookieName(string $cookie_name): void
    {
        $this->cookie_name = $cookie_name;
    }

    public function getCookieValue(): string
    {
        return $this->cookie_value;
    }

    public function setCookieValue(string $cookie_value): void
    {
        $this->cookie_value = $cookie_value;
    }

    public function getCookieExpireDate(): ?\DateTime
    {
        return $this->cookie_expire_date;
    }

    public function setCookieExpireDate(\DateTime $cookie_expire_date): void
    {
        $this->cookie_expire_date = $cookie_expire_date;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
