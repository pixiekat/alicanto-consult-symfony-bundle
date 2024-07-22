<?php
namespace Pixiekat\AlicantoConsult\Form;

use Pixiekat\AlicantoConsult\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormTypes;
use Symfony\Component\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class GroupPreferencesFormType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options): void {

    $builder
      ->add('isPrivate', FormTypes\CheckboxType::class, [
        'required' => false,
        'label' => 'Private',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => Entity\GroupPreferences::class,
    ]);
  }
}
