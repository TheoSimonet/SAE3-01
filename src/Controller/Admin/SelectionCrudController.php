<?php

namespace App\Controller\Admin;

use App\Entity\Selection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SelectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Selection::class;
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
