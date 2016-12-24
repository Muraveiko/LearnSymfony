<?php

namespace AppBundle\Twig;

class ImgExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('my_img', [$this, 'buildImage'], ['is_safe' => ['html']])
        ];
    }

    /**
     * Это пример создания своей функии для шаблонизатора
     *
     * @param $src
     * @param array $args
     * @return string - html code
     */
    public function buildImage($src, array $args = [])
    {
        $args = array_merge(
            [
               'width' => null,
               'height' => null,
            ], $args
        );

        $dop = '';

        if (!is_null($args['width'])) {
            $dop .= 'width="' . $args['width'] . '" ';
        }
        if (!is_null($args['height'])) {
            $dop .= 'height="' . $args['height'] . '" ';
        }

        return '<img src="' . $src . '" ' . $dop . '/>';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'img.extension';
    }

}