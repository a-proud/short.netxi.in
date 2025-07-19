<?php

namespace App\Form;

use App\Entity\ShortUrl;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ShortUrlType extends AbstractType
{
    public function __construct(private ValidatorInterface $validator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $metadata = $this->validator->getMetadataFor(\App\Entity\ShortUrl::class);

        $builder
            ->add('url', UrlType::class, [
                'label' => false,
                'row_attr' => ['class' => 'mb-3'],
                'attr' => ['class' => 'form-control form-control-lg', 'placeholder' => 'Paste your long URL here...'],
                'required' => true,
                'constraints' => $metadata->getPropertyMetadata('url')[0]->getConstraints(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShortUrl::class,
        ]);
    }
}
