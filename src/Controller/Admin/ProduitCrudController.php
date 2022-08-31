<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

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
            ImageField::new('photo')->setBasePath('img/produits/')->setUploadedFileNamePattern('[ulid].[extension]')->setUploadDir('public/img/produits')->setRequired(false),
            TextEditorField::new('description', 'Description')->onlyOnForms(),
            TextareaField::new('description', 'Description')->setMaxLength(200)->hideOnForm(),//->renderAsHtml()
            NumberField::new('prix', 'Prix'),
            NumberField::new('stock', 'Stock'),
            TextField::new('couleur', 'Couleur'),
            TextField::new('taille', 'Taille'),
            DateTimeField::new('dateEnregistrement', 'Date d\'ajout')->setFormat('d/M/y à H:m')->onlyOnIndex(),
            DateTimeField::new('updateAt', 'Date de mise à jour')->onlyOnIndex(),
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
}
