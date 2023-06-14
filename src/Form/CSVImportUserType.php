<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CSVImportUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csv_file', FileType::class, [
                'label' => 'Fichier CSV',
                'attr' => [
                    'accept' => '.csv',
                ],
                'constraints' => [
                    new Callback([$this, 'validateCsvFile']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurez ici les options de validation du formulaire si nécessaire
        ]);
    }

    public function validateCsvFile($file, ExecutionContextInterface $context)
    {
        if ($file === null) {
            $context
                ->buildViolation('Veuillez sélectionner un fichier.')
                ->addViolation();
        }
    }
}
