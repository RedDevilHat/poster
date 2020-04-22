<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\UserGroup;
use App\Manager\UserGroupManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class UserGroupAdmin extends AbstractAdmin
{
    protected $translationDomain = 'UserGroupAdmin';

    private UserGroupManager $groupManager;

    /**
     * @required
     */
    public function setGroupManager(UserGroupManager $groupManager): void
    {
        $this->groupManager = $groupManager;
    }

    public function getNewInstance(): UserGroup
    {
        return $this->groupManager->createGroup('Default');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('roles')
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('roles', 'array_localized_values')
            ->add('_action', null, ['actions' => ['show' => [], 'edit' => [], 'delete' => []]])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('name')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('roles', 'array_localized_values')
        ;
    }
}
