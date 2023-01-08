<?php

namespace App\Controller\Admin;

use App\Entity\ProjetTER;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProjetTERCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjetTER::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
