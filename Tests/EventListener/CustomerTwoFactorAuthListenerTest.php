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

namespace Plugin\TwoFactorAuthCustomer44\Tests\EventListener;

use Eccube\Entity\Customer;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Request\Context;
use Eccube\Tests\EccubeTestCase;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Plugin\TwoFactorAuthCustomer44\Entity\TwoFactorAuthType;
use Plugin\TwoFactorAuthCustomer44\EventListener\CustomerTwoFactorAuthListener;
use Plugin\TwoFactorAuthCustomer44\Repository\TwoFactorAuthCustomerCookieRepository;
use Plugin\TwoFactorAuthCustomer44\Repository\TwoFactorAuthTypeRepository;
use Plugin\TwoFactorAuthCustomer44\Service\CustomerTwoFactorAuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class CustomerTwoFactorAuthListenerTest extends EccubeTestCase
{
    private ?Customer $Customer = null;

    protected function setUp(): void
    {
        parent::setUp();

        static::getContainer()->get(BaseInfoRepository::class)->get()->setTwoFactorAuthUse(true);

        $this->Customer = $this->createCustomer();
    }

    public function testOnLoginSuccessRedirectsToLogoutWhenSelectedAuthTypeIsDisabled(): void
    {
        $this->Customer->setTwoFactorAuthType($this->createAuthType(true));

        $event = $this->createLoginSuccessEvent();
        $this->createListener(self::never())->onLoginSuccess($event);

        $response = $event->getResponse();
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(
            static::getContainer()->get(UrlGeneratorInterface::class)->generate('logout'),
            $response->getTargetUrl()
        );
    }

    public function testOnLoginSuccessDoesNotLogoutWhenSelectedAuthTypeIsEnabled(): void
    {
        $this->Customer->setTwoFactorAuthType($this->createAuthType(false));

        $event = $this->createLoginSuccessEvent();
        // ログアウト判定を通過して多要素認証まで進んだことを確かめる
        $this->createListener(self::once())->onLoginSuccess($event);

        self::assertNull($event->getResponse());
    }

    private function createAuthType(bool $isDisabled): TwoFactorAuthType
    {
        $TwoFactorAuthType = (new TwoFactorAuthType())
            ->setName('APP')
            ->setRoute('plg_customer_2fa_app_create');
        $TwoFactorAuthType->setIsDisabled($isDisabled);

        return $TwoFactorAuthType;
    }

    private function createListener(InvokedCount $hasActiveTypeCalls): CustomerTwoFactorAuthListener
    {
        $container = static::getContainer();

        $requestContext = $this->createMock(Context::class);
        $requestContext->method('isAdmin')->willReturn(false);
        $requestContext->method('getCurrentUser')->willReturn($this->Customer);

        $customerTwoFactorAuthService = $this->createMock(CustomerTwoFactorAuthService::class);
        $customerTwoFactorAuthService->method('getDefaultAuthRoutes')->willReturn([]);
        $customerTwoFactorAuthService->method('getIncludeRoutes')->willReturn([]);

        // 有効な認証方式が無い扱いにして、多要素認証の auth() が持つ別のログアウト経路へ進ませない
        $twoFactorAuthTypeRepository = $this->createMock(TwoFactorAuthTypeRepository::class);
        $twoFactorAuthTypeRepository->expects($hasActiveTypeCalls)->method('hasActiveType')->willReturn(false);

        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $requestStack = new RequestStack();
        $requestStack->push($request);

        return new CustomerTwoFactorAuthListener(
            $requestContext,
            $container->get(UrlGeneratorInterface::class),
            $customerTwoFactorAuthService,
            $twoFactorAuthTypeRepository,
            $container->get(TwoFactorAuthCustomerCookieRepository::class),
            $container->get(BaseInfoRepository::class),
            $requestStack,
        );
    }

    private function createLoginSuccessEvent(): LoginSuccessEvent
    {
        return new LoginSuccessEvent(
            $this->createMock(AuthenticatorInterface::class),
            new SelfValidatingPassport(new UserBadge((string) $this->Customer->getEmail())),
            $this->createMock(TokenInterface::class),
            new Request(),
            null,
            'customer',
        );
    }
}
