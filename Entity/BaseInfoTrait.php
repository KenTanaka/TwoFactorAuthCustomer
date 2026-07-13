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
use Eccube\Attribute\EntityExtension;
use Eccube\Entity\BaseInfo;

#[EntityExtension(BaseInfo::class)]
trait BaseInfoTrait
{
    /**
     * 2段階認証機能の利用
     */
    #[ORM\Column(name: 'two_factor_auth_use', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
    private bool $two_factor_auth_use = false;

    /**
     * SMS通知の設定
     */
    #[ORM\Column(name: 'option_activate_device', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
    private bool $option_activate_device = false;

    public function isTwoFactorAuthUse(): bool
    {
        return $this->two_factor_auth_use;
    }

    public function setTwoFactorAuthUse(bool $two_factor_auth_use): void
    {
        $this->two_factor_auth_use = $two_factor_auth_use;
    }

    public function isOptionActivateDevice(): bool
    {
        return $this->option_activate_device;
    }

    public function setOptionActivateDevice(bool $option_activate_device): void
    {
        $this->option_activate_device = $option_activate_device;
    }
}
