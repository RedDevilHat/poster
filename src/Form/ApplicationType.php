<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\OAuth2\Client;
use OAuth2\OAuth2;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'required' => true,
            ])
            ->add('redirectUris', CollectionType::class, [
                'required' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => UrlType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'attr' => [
                    'class' => 'collection',
                ],
            ])
            ->add('allowedGrantTypes', ChoiceType::class, [
                'required' => true,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'authorization_code' => OAuth2::GRANT_TYPE_AUTH_CODE,
                    'token' => OAuth2::GRANT_TYPE_IMPLICIT,
                    'password' => OAuth2::GRANT_TYPE_USER_CREDENTIALS,
                    'client_credentials' => OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS,
                    'refresh_token' => OAuth2::GRANT_TYPE_REFRESH_TOKEN,
                    'extensions' => OAuth2::GRANT_TYPE_EXTENSIONS,
                ],
                'translation_domain' => 'ProfileApplication',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'translation_domain' => 'ProfileApplication',
        ]);
    }
}
