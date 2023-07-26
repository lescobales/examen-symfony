<?php

namespace App\Form;

use App\Entity\Model;
use App\Entity\Listing;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ListingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('producedYear')
            ->add('mileage')
            ->add('price')
            ->add('image', FileType::class, [
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '3M',
                        mimeTypes: ['image/png', 'image/jpeg', 'image/svg'],
                        maxSizeMessage: 'Ton fichier est trop lourd !',
                        mimeTypesMessage: 'Déposer seulement un .jpg ou .png'
                    )
                ]
            ])
            ->add('model', EntityType::class,[
                'label' => 'form.listing.label',
                'required' => false,
                'class' => Model::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Listing::class,
        ]);
    }
}
