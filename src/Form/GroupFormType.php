<?php
namespace Pixiekat\AlicantoConsult\Form;

use Pixiekat\AlicantoConsult\Entity;
use Pixiekat\AlicantoConsult\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormTypes;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class GroupFormType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options): void {

    $builder
      ->add('name', FormTypes\TextType::class, [
        'required' => false,
      ])
      ->add('description', FormTypes\TextareaType::class, [
        'required' => false,
      ])
      ->add('groupEmail', FormTypes\EmailType::class, [
        'required' => false,
      ])
      ->add('groupPreferences', Form\GroupPreferencesFormType::class, [
        'error_bubbling' => true,
        'label' => 'Group Preferences',
      ])
      ->add('groupMembers', EntityType::class, [
        'class' => Entity\User::class,
        'choice_label' => 'emailAddress',
        'multiple' => true,
        'expanded' => true,
        'by_reference' => false,
        'required' => false,
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
      'data_class' => Entity\Group::class,
    ]);
  }
}
