<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('messageInput', TextType::class, [
                    'mapped' => false,
                    'label' => false,
                    'required' => false
                ])
            ->add('attachment', FileType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false,
                'constraints' => [
                    new File()
                ]
            ]);
    }
}