<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * https://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\TwoFactorAuthCustomer44\Tests\Service;

use Eccube\Entity\Customer;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Tests\EccubeTestCase;
use Plugin\TwoFactorAuthCustomer44\Entity\TwoFactorAuthType;
use Plugin\TwoFactorAuthCustomer44\Repository\TwoFactorAuthConfigRepository;
use Plugin\TwoFactorAuthCustomer44\Repository\TwoFactorAuthCustomerCookieRepository;
use Plugin\TwoFactorAuthCustomer44\Service\CustomerTwoFactorAuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class CustomerTwoFactorAuthServiceTest extends EccubeTestCase
{
    private ?Customer $Customer = null;

    /**
     * 認証済みクッキーを付けたリクエスト.
     */
    private ?Request $authedRequest = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->Customer = $this->createCustomer();

        $cookieName = $this->eccubeConfig->get('plugin_eccube_2fa_customer_cookie_name');
        $expire = (int) $this->eccubeConfig->get('plugin_eccube_2fa_customer_expire');
        $cookie = $this->createService(new Request())
            ->createRouteAuthCookie($this->Customer, $cookieName, $expire);

        $this->authedRequest = new Request([], [], [], [$cookieName => $cookie->getValue()]);
    }

    public function testIsAuthedReturnsFalseWhenAuthTypeIsNotSet(): void
    {
        self::assertNull($this->Customer->getTwoFactorAuthType());

        $service = $this->createService($this->authedRequest);

        self::assertFalse($service->isAuthed($this->Customer, 'mypage'));
    }

    public function testIsAuthedReturnsTrueWhenAuthTypeIsSetAndCookieIsValid(): void
    {
        $this->Customer->setTwoFactorAuthType(
            (new TwoFactorAuthType())->setName('APP')->setRoute('plg_customer_2fa_app_create')
        );

        $service = $this->createService($this->authedRequest);

        self::assertTrue($service->isAuthed($this->Customer, 'mypage'));
    }

    /**
     * リクエストはコンストラクタで取り込まれるため、リクエストごとにサービスを生成する.
     */
    private function createService(Request $request): CustomerTwoFactorAuthService
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $container = static::getContainer();

        return new CustomerTwoFactorAuthService(
            $this->entityManager,
            $this->eccubeConfig,
            $container->get(BaseInfoRepository::class),
            $requestStack,
            $container->get(TwoFactorAuthConfigRepository::class),
            $container->get(TwoFactorAuthCustomerCookieRepository::class),
            $container->get(PasswordHasherFactoryInterface::class),
        );
    }
}
