<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Utilisateur'),
            AssociationField::new('produit', 'Produit')->setTemplatePath('admin/field/produits.html.twig')->onlyOnIndex(),
            ChoiceField ::new('etat')->setChoices([
                'En traitement' => 0,
                'Payement confirmé' => 1,
                'Livraison en cours' => 2,
                'Commande livrée' => 3,
            ]),
            ArrayField::new('quantite')->onlyOnIndex(),
            NumberField::new('montant')->onlyOnIndex(),
            DateTimeField::new('dateEnregistrement', 'Date de commande')->setFormat('d/M/y à H:m')->onlyOnIndex(),
        ];
    }
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::DELETE)
        ;
    }
}
