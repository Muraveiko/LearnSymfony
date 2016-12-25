<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Type;

class BookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('author')
            ->add('uploadCover', Type\FileType::class, [
                'required' => false,
                'image_path' => 'getCoverUrl',
                'file_info' => 'infoCover',
            ])
            ->add('uploadBookFile', Type\FileType::class, [
                'required' => false,
                'file_info' => 'infoBookFile',
            ])
            ->add('allowedDownload', Type\CheckboxType::class, [
                'required' => false,
            ])
            ->add('dateRead', Type\DateType::class, [
                'required' => true,
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Book',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'book_form';
    }

}
