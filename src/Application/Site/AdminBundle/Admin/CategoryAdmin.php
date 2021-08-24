<?php

namespace Application\Site\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CategoryAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = "categories";

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
           ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                )
            ));
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ;
    }
}