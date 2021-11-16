<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserCrudController extends AbstractCrudController
{


    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserCrudController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {

       /* $password = TextField::new('clearpassword')
            ->setLabel("New Password")
            ->setFormType(PasswordType::class)
            ->setFormTypeOption('empty_data', '')
            ->setRequired(false)
            ->setHelp('If the right is not given, leave the field blank.')
            ->hideOnIndex();
        */
        return array_map(function ($f) use ($pageName) {
            if ($f->getAsDto()->getProperty() === 'password') {
                $field = TextField::new('plain_password', Crud::PAGE_NEW === $pageName ? 'Password' : 'Change password')
                    ->setFormType(PasswordType::class);
                if (Crud::PAGE_NEW === $pageName) {
                    $field->setRequired(true);
                }
                return $field;
            }
            return $f;
        }, parent::configureFields($pageName));
        return [
           
            TextField::new('lastName'),
            EmailField::new('email'),
            TextEditorField::new('description'),
            /*$password*/
            FormField::addPanel('Change password')->setIcon('fa fa-key'),
            Field::new('plainPassword', 'New password')->onlyOnForms()
                
           
        ];
    
    }



    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->update(Crud::PAGE_INDEX, Action::NEW,function(Action $action){

        return $action->setIcon('fa fa-user')->addCssClass('btn btn-success');
         })

        ->update(Crud::PAGE_INDEX, Action::EDIT,function(Action $action){

            return $action->setIcon('fa fa-edit')->addCssClass('btn btn-warning');
        })
        ->update(Crud::PAGE_INDEX, Action::DETAIL,function(Action $action){

            return $action->setIcon('fa fa-edit')->addCssClass('btn btn-info');
        });
       
        
    }
    
    
}
