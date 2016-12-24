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
            new \Twig_SimpleFunction('my_img', [$this, 'buildImage'],array('is_safe' => array('html'))),
        ];
    }

    public function buildImage($src,array $args = array())
    {
       $o = array_merge(
           array(
               'width' => null,
               'height'=> null
           ),$args
       );

       $dop = '';

       if(!is_null($o['width'])) {
         $dop .= 'width="'.$o['width'].'" ';
       }
        if(!is_null($o['height'])) {
            $dop .= 'height="'.$o['height'].'" ';
        }

       return '<img src="'.$src.'" '.$dop.'/>';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'img.extension';
    }

}