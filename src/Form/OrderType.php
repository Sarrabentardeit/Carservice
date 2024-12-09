<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateCommande', DateType::class, [
                'label' => 'Date de Commande',
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Date(),
                ],
            ])
            ->add('montantTotal', null, [
                'label' => 'Montant Total (DT)',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                ],
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut de la Commande',
                'choices' => [
                    'En attente' => 'En attente',
                    'Confirmée' => 'Confirmée',
                    'Livrée' => 'Livrée',
                    'Annulée' => 'Annulée',
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('client', EntityType::class, [
                'class' => User::class,
                'label' => 'Client',
                'choice_label' => 'email',
            ])
            ->add('produits', EntityType::class, [
                'class' => Product::class,
                'label' => 'Produits Commandés',
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
