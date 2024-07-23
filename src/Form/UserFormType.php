<?php
namespace Pixiekat\AlicantoConsult\Form;

use Pixiekat\AlicantoConsult\Entity;
use Pixiekat\AlicantoConsult\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormTypes;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class UserFormType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options): void {

    $builder
      ->add('emailAddress', FormTypes\EmailType::class, [
        'required' => false,
      ])
      ->add('isActive', FormTypes\CheckboxType::class, [
        'required' => false,
      ])
      ->add('userProfile', Form\UserProfileFormType::class, [
        'label' => 'Profile',
      ])
      ->add('roles', FormTypes\ChoiceType::class, [
        'required' => true,
        'choices' => [
          'User' => 'ROLE_USER',
          'Admin' => 'ROLE_ADMIN',
          'System Admin' => 'ROLE_SYSTEM_ADMIN',
        ],
        'multiple' => true,
        'expanded' => true,
      ])
      ->add('submit', FormTypes\SubmitType::class, [
        'attr' => [
          'class' => 'btn-primary',
        ],
      ])
      ->add('cancel', FormTypes\SubmitType::class, [
        'attr' => [
          'class' => 'btn-link',
        ],
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => Entity\User::class,
    ]);
  }
}
