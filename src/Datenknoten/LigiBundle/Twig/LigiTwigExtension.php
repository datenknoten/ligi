<?php
namespace Datenknoten\LigiBundle\Twig;

use Symfony\Component\Intl\Intl;

class LigiTwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('basename', array($this, 'basename')),
            new \Twig_SimpleFilter('languagename', array($this, 'getLanguageName')),
        );
    }

    public function basename($filename)
    {
        return basename($filename);
    }

    public function getLanguageName($iso_code)
    {
        return Intl::getLanguageBundle()->getLanguageName($iso_code);
    }

    public function getName()
    {
        return 'ligi_twig_extension';
    }
}