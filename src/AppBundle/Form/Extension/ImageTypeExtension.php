<?php

namespace AppBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageTypeExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return FileType::class;
    }

    /**
     * Add the image_path option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['image_path','file_info']);
    }

    /**
     * Pass the image URL to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['image_url'] = null;
        $view->vars['file_info'] = null;

        $parentData = $form->getParent()->getData();
        $accessor = PropertyAccess::createPropertyAccessor();

        if (isset($options['image_path'])) {

            $imageUrl = null;
            if (null !== $parentData) {
                $imageUrl = $accessor->getValue($parentData, $options['image_path']);
            }
            $view->vars['image_url'] = $imageUrl;
        }

        if (isset($options['file_info'])) {

            $fileInfo = null;
            if (null !== $parentData) {
                $fileInfo = $accessor->getValue($parentData, $options['file_info']);
            }
            $view->vars['file_info'] = $fileInfo;
        }
    }
}