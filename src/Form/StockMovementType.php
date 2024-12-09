<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\StockMovement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class StockMovementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateMouvement', null, [
                'label' => 'Date du Mouvement',
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Date(),
                ],
            ])
            ->add('quantite', null, [
                'label' => 'Quantité',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                ],
            ])
            ->add('typeMouvement', ChoiceType::class, [
                'label' => 'Type de Mouvement',
                'choices' => [
                    'Entrée' => 'Entrée',
                    'Sortie' => 'Sortie',
                ],
            ])
            ->add('produit', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'nom',
                'label' => 'Produit Concerné',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StockMovement::class,
        ]);
    }
}
