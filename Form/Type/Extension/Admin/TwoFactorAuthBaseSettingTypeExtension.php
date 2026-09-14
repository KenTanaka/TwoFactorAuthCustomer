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

namespace Plugin\TwoFactorAuthCustomer44\Form\Type\Extension\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Form\Type\Admin\ShopMasterType;
use Eccube\Form\Type\ToggleSwitchType;
use Plugin\TwoFactorAuthCustomer44\Entity\TwoFactorAuthType;
use Plugin\TwoFactorAuthCustomer44\Repository\TwoFactorAuthTypeRepository;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TwoFactorAuthBaseSettingTypeExtension extends AbstractTypeExtension
{
    /**
     * CouponDetailType constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public static function getExtendedTypes(): iterable
    {
        yield ShopMasterType::class;
    }

    /**
     * buildForm.
     *
     * @param FormBuilderInterface $builder
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!empty($options['skip_add_form'])) {
            return;
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            /** @var TwoFactorAuthTypeRepository $typeRepository */
            $typeRepository = $this->entityManager->getRepository(TwoFactorAuthType::class);
            if ($typeRepository->hasActiveType()) {
                $form->add('two_factor_auth_use', ToggleSwitchType::class, [
                    'required' => false,
                    'mapped' => true,
                ]);
            }

            $form->add('option_activate_device', ToggleSwitchType::class, [
                'required' => false,
                'mapped' => true,
            ]);
        });
    }
}
