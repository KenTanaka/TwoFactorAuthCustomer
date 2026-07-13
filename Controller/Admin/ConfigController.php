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

namespace Plugin\TwoFactorAuthCustomer44\Controller\Admin;

use Eccube\Controller\AbstractController;
use Plugin\TwoFactorAuthCustomer44\Form\Type\TwoFactorAuthConfigType;
use Plugin\TwoFactorAuthCustomer44\Repository\TwoFactorAuthConfigRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class ConfigController
 */
class ConfigController extends AbstractController
{
    /**
     * ConfigController constructor.
     */
    public function __construct(
        private readonly TwoFactorAuthConfigRepository $smsConfigRepository,
    ) {
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|array
     */
    #[Route(path: '/%eccube_admin_route%/two_factor_auth_customer44/config', name: 'two_factor_auth_customer44_admin_config', methods: ['GET', 'POST'])]
    #[Template('@TwoFactorAuthCustomer44/admin/config.twig')]
    public function index(Request $request)
    {
        // 設定情報、フォーム情報を取得
        $SmsConfig = $this->smsConfigRepository->findOne();
        // APIの秘密キーが設定されている場合、マスキングする
        if (!empty($SmsConfig->getApiSecret())) {
            // APIの秘密キーをマスキングできるため、デフォルト値を設定
            $SmsConfig->setPlainApiSecret($this->eccubeConfig['eccube_default_password']);
        }

        $form = $this->createForm(TwoFactorAuthConfigType::class, $SmsConfig);
        $form->handleRequest($request);

        // 設定画面で登録ボタンが押されたらこの処理を行う
        if ($form->isSubmitted() && $form->isValid()) {
            // フォームの入力データを取得
            $SmsConfig = $form->getData();

            // APIの秘密キーが変更されていたら、APIの秘密キーを保存する
            if ($SmsConfig->getPlainApiSecret() !== $this->eccubeConfig['eccube_default_password']) {
                $SmsConfig->setApiSecret($SmsConfig->getPlainApiSecret());
            }

            // フォームの入力データを保存
            $this->entityManager->persist($SmsConfig);
            $this->entityManager->flush();

            // 完了メッセージを表示
            log_info('config', ['status' => 'Success']);
            $this->addSuccess('プラグインの設定を保存しました。', 'admin');

            // 設定画面にリダイレクト
            return $this->redirectToRoute('two_factor_auth_customer44_admin_config');
        }

        return [
            'SmsConfig' => $SmsConfig,
            'form' => $form->createView(),
        ];
    }
}
