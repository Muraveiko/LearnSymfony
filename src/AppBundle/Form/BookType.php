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
            ->add('uploadCover',Type\FileType::class, array(
                'required' => false,
                'image_path'=>'getCoverUrl',
                'file_info' =>'infoCover'
            ))
            ->add('uploadBookFile',Type\FileType::class,array(
                'required' => false,
                'file_info' =>'infoBookFile'
            ))
            ->add('allowedDownload',Type\CheckboxType::class,array(
                'required' => false,
            ))
            ->add('dateRead')
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Book'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_book';
    }

}
