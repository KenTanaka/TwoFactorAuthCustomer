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
use Plugin\TwoFactorAuthCustomer44\Repository\TwoFactorAuthConfigRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TwoFactorConfig
 */
#[ORM\Table(name: 'plg_two_factor_auth_config')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discriminator_type', type: Types::STRING, length: 255)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TwoFactorAuthConfigRepository::class)]
#[UniqueEntity('id')]
class TwoFactorAuthConfig extends AbstractEntity
{
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(name: 'api_key', type: Types::STRING, nullable: true, length: 200)]
    private ?string $api_key = null;

    #[ORM\Column(name: 'api_secret', type: Types::STRING, nullable: true, length: 200)]
    private ?string $api_secret = null;

    private ?string $plain_api_secret = null;

    #[ORM\Column(name: 'from_phone_number', type: Types::STRING, nullable: true, length: 200)]
    private ?string $from_phone_number = null;

    #[ORM\Column(name: 'include_routes', type: Types::TEXT, nullable: true)]
    private ?string $include_routes = null;

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
     * Get api_key.
     */
    public function getApiKey(): ?string
    {
        return $this->api_key;
    }

    /**
     * Set api_key.
     *
     * @return $this
     */
    public function setApiKey(?string $apiKey): self
    {
        $this->api_key = $apiKey;

        return $this;
    }

    /**
     * Get api_secret.
     */
    public function getApiSecret(): ?string
    {
        return $this->api_secret;
    }

    /**
     * Set api_secret.
     *
     * @return $this
     */
    public function setApiSecret(?string $apiSecret): self
    {
        $this->api_secret = $apiSecret;

        return $this;
    }

    /**
     * Get from phone number.
     */
    public function getFromPhoneNumber(): ?string
    {
        return $this->from_phone_number;
    }

    /**
     * Set from phone number.
     *
     * @return $this
     */
    public function setFromPhoneNumber(string $fromPhoneNumber): self
    {
        $this->from_phone_number = $fromPhoneNumber;

        return $this;
    }

    /**
     * @return $this
     */
    public function addIncludeRoute(string $route): self
    {
        $routes = $this->getRoutes($this->getIncludeRoutes());

        if (!in_array($route, $routes)) {
            $this->setIncludeRoutes($this->include_routes.PHP_EOL.$route);
        }

        return $this;
    }

    private function getRoutes(?string $routes): array
    {
        if (!$routes) {
            return [];
        }

        return explode(PHP_EOL, $routes);
    }

    /**
     * Get include_routes.
     */
    public function getIncludeRoutes(): ?string
    {
        return $this->include_routes;
    }

    /**
     * Set include_routes.
     *
     * @return $this
     */
    public function setIncludeRoutes(?string $include_routes = null): self
    {
        $this->include_routes = $include_routes;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeIncludeRoute(string $route): self
    {
        $routes = $this->getRoutes($this->getIncludeRoutes());

        if (in_array($route, $routes)) {
            $routes = array_diff($routes, [$route]);
            $this->setIncludeRoutes($this->getRoutesAsString($routes));
        }

        return $this;
    }

    private function getRoutesAsString(array $routes): string
    {
        return implode(PHP_EOL, $routes);
    }

    /**
     * @return $this
     */
    public function setPlainApiSecret(?string $plain_api_secret): self
    {
        $this->plain_api_secret = $plain_api_secret;

        return $this;
    }

    public function getPlainApiSecret(): ?string
    {
        return $this->plain_api_secret;
    }
}
