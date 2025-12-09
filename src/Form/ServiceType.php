<?php
namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $options['is_edit'] sera utile pour rendre l'upload obligatoire seulement à la création
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('name')
            ->add('description')
            ->add('price', MoneyType::class, [
                'currency' => 'USD' // ajuster si besoin
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => !$isEdit, // upload obligatoire si création
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (jpg, png, webp).',
                    ]),
                    $isEdit ? null : new NotBlank(['message' => 'L\'image est obligatoire.'])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
            'is_edit' => false,
        ]);
    }
}
