<?php

namespace App\Form;

use App\Entity\Lane;
use App\Entity\Package;
use App\Entity\Reservation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['min' => (new \DateTime())->format('Y-m-d')],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThanOrEqual('today'),
                ],
            ])
            ->add('startHour', TimeType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'html5' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('duration', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    '1 hour' => 1,
                    '2 hours' => 2,
                    '3 hours' => 3,
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('lane', EntityType::class, [
                'class' => Lane::class,
                'choice_label' => function (Lane $lane): string {
                    return 'Lane ' . $lane->number . ($lane->hasBumpers ? ' (with bumpers)' : '');
                },
                'placeholder' => 'Choose a lane...',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('numberOfAdults', IntegerType::class, [
                'attr' => ['min' => 1, 'max' => Lane::MAX_ADULTS],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range(min: 1, max: Lane::MAX_ADULTS),
                ],
            ])
            ->add('numberOfChildren', IntegerType::class, [
                'attr' => ['min' => 0, 'max' => Lane::MAX_CHILDREN_WITH_ADULTS],
                'constraints' => [
                    new Assert\Range(min: 0, max: Lane::MAX_CHILDREN_WITH_ADULTS),
                ],
            ])
            ->add('snackPackage', EntityType::class, [
                'class' => Package::class,
                'required' => false,
                'placeholder' => 'No snack package',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->setParameter('type', Package::TYPE_SNACK)
                        ->orderBy('p.price', 'ASC');
                },
                'choice_label' => function (Package $package): string {
                    return $package->name . ' — €' . number_format((float) $package->price, 2);
                },
            ])
            ->add('partyPackage', EntityType::class, [
                'class' => Package::class,
                'required' => false,
                'placeholder' => 'No party package',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->setParameter('type', Package::TYPE_PARTY)
                        ->orderBy('p.price', 'ASC');
                },
                'choice_label' => function (Package $package): string {
                    return $package->name . ' — €' . number_format((float) $package->price, 2);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
