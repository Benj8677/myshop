<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
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
            AssociationField::new('user', 'utilisateur'),
            AssociationField::new('produit', 'produit'),
            ChoiceField ::new('etat')->setChoices([
                'En traitement' => 0,
                'Payement confirmé' => 1,
                'Livraison en cours' => 2,
                'Commande livrée' => 3,
        ]),
            ArrayField::new('quantite'),
            NumberField::new('montant')->onlyOnIndex(),
            DateTimeField::new('dateEnregistrement', 'Date de commande')->setFormat('d/M/y à H:m')->onlyOnIndex(),
        ];
    }
    
}
