<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\User;
use App\Manager\UserManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Security;

final class UserAdmin extends AbstractAdmin
{
    protected $translationDomain = 'UserAdmin';

    private UserManager $userManager;

    private Security $securityHelper;

    /**
     * @required
     */
    public function setUserManager(UserManager $userManager): void
    {
        $this->userManager = $userManager;
    }

    /**
     * @required
     */
    public function setSecurityHelper(Security $securityHelper): void
    {
        $this->securityHelper = $securityHelper;
    }

    public function createQuery($context = 'list')
    {
        /** @var ProxyQueryInterface $query */
        $query = parent::createQuery($context);

        if ('list' === $context && !$this->isGranted('ROLE_SUPER_ADMIN') && ($user = $this->securityHelper->getUser())) {
            $query->andWhere($query->expr()->neq($query->getRootAliases()[0].'.id', ':id'));
            $query->setParameter('id', $user->getId());
        }

        return $query;
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
            ->add('groups')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('username')
            ->add('groups')
            ->add('roles', 'array_localized_values')
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
            ->add('plainPassword', PasswordType::class, [
                'required' => $passwordRequired,
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ])
            ->add('groups', null, [
                'required' => false,
                'expanded' => true,
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('username')
            ->add('roles', 'array_localized_values')
            ->add('groups')
        ;
    }
}
