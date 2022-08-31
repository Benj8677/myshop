<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('pseudo'),
            TextField::new('password')->setFormType(PasswordType::class)->onlyOnForms()->onlyWhenCreating(),
            TextField::new('email'),
            TextField::new('nom'),
            TextField::new('prenom'),
            ChoiceField ::new('civilite')->setChoices([
                'M.' => 'M.',
                'Mme' => 'Mme',
                'Mx' => 'Mx',
        ]),
            //CollectionField::new('roles')->setTemplatePath('admin/field/roles.html.twig'),
        ];
    }
    
    public function createEntity(string $entityFqcn)
    {
        $user = new User;

        $user->setDateEnregistrement(new \DateTime);
        return $user;
    }
}
