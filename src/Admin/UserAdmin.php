<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\User;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

final class UserAdmin extends AbstractAdmin
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @required
     *
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager): void
    {
        $this->userManager = $userManager;
    }

    /**
     * @param User $object
     */
    public function prePersist($object): void
    {
        $this->userManager->updateUser($object, false);
    }

    /**
     * @param User $object
     */
    public function preUpdate($object): void
    {
        $this->userManager->updateUser($object, false);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('groups')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('lastLogin')
            ->add('passwordRequestedAt')
            ->add('_action', null, ['actions' => ['show' => [], 'edit' => [], 'delete' => []]])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $passwordRequired = true;

        /** @var User $user */
        if ($user = $this->getSubject()) {
            $passwordRequired = null === $user->getId();
        }

        $formMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('plainPassword', PasswordType::class, [
                'required' => $passwordRequired,
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ])
            ->add('groups')
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('salt')
            ->add('lastLogin')
            ->add('confirmationToken')
            ->add('passwordRequestedAt')
            ->add('groups')
        ;
    }
}
