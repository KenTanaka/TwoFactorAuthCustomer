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
use Plugin\TwoFactorAuthCustomer44\Repository\TwoFactorAuthTypeRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TwoFactorAuthType
 */
#[ORM\Table(name: 'plg_two_factor_auth_type')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discriminator_type', type: Types::STRING, length: 255)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TwoFactorAuthTypeRepository::class)]
#[UniqueEntity('id')]
class TwoFactorAuthType extends AbstractEntity
{
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'name', type: Types::STRING, nullable: false, length: 200, unique: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'route', type: Types::STRING, nullable: false, length: 200, unique: true)]
    private ?string $route = null;

    #[ORM\Column(name: 'is_disabled', type: Types::BOOLEAN, nullable: false)]
    private bool $isDisabled = false;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get route.
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * Set route.
     *
     * @return $this
     */
    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(bool $isDisabled): void
    {
        $this->isDisabled = $isDisabled;
    }
}
