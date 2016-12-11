<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;

class AppExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $locales;

    public function __construct($locales)
    {
        $this->locales = $locales;
    }

    /**
     * {@inheritdoc}
     */
    /*
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('md2html', [$this, 'markdownToHtml'], ['is_safe' => ['html']]),
        ];
    }
   */

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('locales', [$this, 'getLocales']),
        ];
    }


    /**
     * Takes the list of codes of the locales (languages) enabled in the
     * application and returns an array with the name of each locale written
     * in its own language (e.g. English, Français, Español, etc.).
     *
     * @return array
     */
    public function getLocales()
    {
        $localeCodes = explode('|', $this->locales);

        $locales = [];
        foreach ($localeCodes as $localeCode) {
            $locales[] = ['code' => $localeCode, 'name' => Intl::getLocaleBundle()->getLocaleName($localeCode, $localeCode)];
        }

        return $locales;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        // the name of the Twig extension must be unique in the application. Consider
        // using 'app.extension' if you only have one Twig extension in your application.
        return 'app.extension';
    }
}
