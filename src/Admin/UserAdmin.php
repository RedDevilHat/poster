<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\User;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
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

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var Security
     */
    private $securityHelper;

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
     * @required
     *
     * @param Security $securityHelper
     */
    public function setSecurityHelper(Security $securityHelper): void
    {
        $this->securityHelper = $securityHelper;
    }

    public function createQuery($context = 'list')
    {
        /** @var ProxyQueryInterface $query */
        $query = parent::createQuery($context);

        /** @var User $user */
        if ('list' === $context && !$this->isGranted(UserInterface::ROLE_SUPER_ADMIN) && ($user = $this->securityHelper->getUser())) {
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
            ->add('email', 'email')
            ->add('enabled', null, [
                'editable' => true,
            ])
            ->add('lastLogin')
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
            ->add('email')
            ->add('enabled')
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
            ->add('email', 'email')
            ->add('enabled')
            ->add('lastLogin')
            ->add('passwordRequestedAt')
            ->add('roles', 'array_localized_values')
            ->add('groups')
        ;
    }
}
