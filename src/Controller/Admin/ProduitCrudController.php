<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre', 'Titre'),
            TextField::new('collection', 'Collection'),
            ChoiceField ::new('taille', 'Taille')->setChoices([
                'xs' => 'xs',
                's' => 's',
                'm' => 'm',
                'l' => 'l',
                'xl' => 'xl',
                'xxl' => 'xxl',
        ]),            ImageField::new('photo')->setBasePath('img/produits/')->setUploadedFileNamePattern('[ulid].[extension]')->setUploadDir('public/img/produits')->setRequired(false),
            TextEditorField::new('description', 'Description'),
            MoneyField::new('prix', 'Prix')->setCurrency('EUR')->setStoredAsCents(false),
            NumberField::new('stock', 'Stock'),
            TextField::new('couleur', 'Couleur'),
            DateTimeField::new('dateEnregistrement', 'Date d\'ajout')->setFormat('d/M/y')->onlyOnIndex(),
            DateTimeField::new('updateAt', 'Date de mise à jour')->setFormat('d/M/y')->onlyOnIndex(),
        ];
    }
    
    public function createEntity(string $entityFqcn)
    {
        $produit = new Produit;

        $file = $produit->getImageFile();

        if (!$file)
        {
            $produit->setPhoto('default.jpg');
        }

        $produit->setDateEnregistrement(new \DateTime)->setUpdateAt(new \DateTime);
        return $produit;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdateAt(new \DateTime);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    // public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    // {
        
    // }
}
