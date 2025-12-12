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
        // Vérifie si le formulaire est en mode édition
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('name')
            ->add('description')
            ->add('price', MoneyType::class, [
                'currency' => 'USD',
            ]);

        // Gestion des contraintes pour l'image
        $constraints = [
            new File([
                'maxSize' => '5M',
                'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                'mimeTypesMessage' => 'Veuillez uploader une image valide (jpg, png, webp).',
            ])
        ];

        if (!$isEdit) {
            $constraints[] = new NotBlank(['message' => 'L\'image est obligatoire.']);
        }

        $builder->add('imageFile', FileType::class, [
            'mapped' => false,          // non lié à l'entité
            'required' => !$isEdit,     // obligatoire seulement pour la création
            'constraints' => $constraints,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
            'is_edit' => false,  // option personnalisée pour différencier création/édition
        ]);
    }
}
