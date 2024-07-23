<?php
namespace Pixiekat\AlicantoConsult\Form;

use Pixiekat\AlicantoConsult\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormTypes;
use Symfony\Component\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class GroupFilterFormType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options): void {

    $builder
      ->add('sort', FormTypes\ChoiceType::class, [
        'required' => false,
        'label' => 'Sort',
        'choices' => [
          'Name' => 'name',
          'Description' => 'description',
        ],
        'mapped' => false,
      ])
      ->add('direction', FormTypes\ChoiceType::class, [
        'required' => false,
        'label' => 'Direction',
        'choices' => [
          'Ascending' => 'ASC',
          'Descending' => 'DESC',
        ],
        'mapped' => false,
      ])
      ->add('submit', FormTypes\SubmitType::class, [
        'attr' => [
          'class' => 'btn-primary',
        ],
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => null,
    ]);
  }
}
