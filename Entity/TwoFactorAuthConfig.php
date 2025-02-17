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

namespace Plugin\TwoFactorAuthCustomer42\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TwoFactorConfig
 *
 * @ORM\Table(name="plg_two_factor_auth_config")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Plugin\TwoFactorAuthCustomer42\Repository\TwoFactorAuthConfigRepository")
 * @UniqueEntity("id")
 */
class TwoFactorAuthConfig extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", nullable=true, length=200)
     */
    private $api_key = null;

    /**
     * @var string
     *
     * @ORM\Column(name="api_secret", type="string", nullable=true, length=200)
     */
    private $api_secret = null;

    /**
     * @var string
     *
     * @ORM\Column(name="from_phone_number", type="string", nullable=true, length=200)
     */
    private $from_phone_number = null;

    /**
     * @var string
     *
     * @ORM\Column(name="include_routes", type="text", nullable=true)
     */
    private $include_routes = null;

    /**
     * @var string
     *
     * @ORM\Column(name="exclude_routes", type="text", nullable=true)
     */
    private $exclude_routes = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set api_key.
     *
     * @param string $apiKey
     *
     * @return TwoFactorAuthConfig
     */
    public function setApiKey($apiKey)
    {
        $this->api_key = $apiKey;

        return $this;
    }

    /**
     * Get api_key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Set api_secret.
     *
     * @param string $apiSecret
     *
     * @return TwoFactorAuthConfig
     */
    public function setApiSecret($apiSecret)
    {
        $this->api_secret = $apiSecret;

        return $this;
    }

    /**
     * Get api_secret.
     *
     * @return string
     */
    public function getApiSecret()
    {
        return $this->api_secret;
    }

    /**
     * Set from phone number.
     *
     * @param string $fromTel
     *
     * @return TwoFactorAuthConfig
     */
    public function setFromPhonenumber($fromTel)
    {
        $this->from_phone_number = $fromTel;

        return $this;
    }

    /**
     * Get from phone number.
     *
     * @return string
     */
    public function getFromPhonenumber()
    {
        return $this->from_phone_number;
    }

    /**
     * Set exclude_routes.
     *
     * @param string|null $exclude_routes
     *
     * @return TwoFactorAuthConfig
     */
    public function setExcludeRoutes($exclude_routes = null)
    {
        $this->exclude_routes = $exclude_routes;

        return $this;
    }

    /**
     * Get exclude_routes.
     *
     * @return string|null
     */
    public function getExcludeRoutes()
    {
        return $this->exclude_routes;
    }

    /**
     * Set include_routes.
     *
     * @param string|null $include_routes
     *
     * @return TwoFactorAuthConfig
     */
    public function setIncludeRoutes($include_routes = null)
    {
        $this->include_routes = $include_routes;

        return $this;
    }

    /**
     * Get include_routes.
     *
     * @return string|null
     */
    public function getIncludeRoutes()
    {
        return $this->include_routes;
    }

    public function addIncludeRoute(string $route)
    {
        $routes = $this->getRoutes($this->getIncludeRoutes());

        if (!in_array($route, $routes)) {
            $this->setIncludeRoutes($this->include_routes . PHP_EOL . $route);
        }

        return $this;
    }

    public function removeIncludeRoute(string $route)
    {
        $routes = $this->getRoutes($this->getIncludeRoutes());

        if (in_array($route, $routes)) {
            $routes = array_splice($routes, array_search($route, $routes, true), 1);
            $this->setIncludeRoutes($this->getRoutesAsString($routes));
        }

        return $this;
    }

    public function addExcludeRoute(string $route)
    {
        $routes = $this->getRoutes($this->getExcludeRoutes());

        if (!in_array($route, $routes)) {
            $this->setExcludeRoutes($this->exclude_routes . PHP_EOL . $route);
        }

        return $this;
    }

    public function removeExcludeRoute(string $route)
    {
        $routes = $this->getRoutes($this->getExcludeRoutes());

        if (in_array($route, $routes)) {
            $routes = array_splice($routes, array_search($route, $routes, true), 1);
            $this->setExcludeRoutes($this->getRoutesAsString($routes));
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

    private function getRoutesAsString(array $routes): string
    {
        return implode(PHP_EOL, $routes);
    }

}
