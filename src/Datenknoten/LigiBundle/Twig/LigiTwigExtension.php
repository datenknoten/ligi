<?php
namespace Datenknoten\LigiBundle\Twig;

class LigiTwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('basename', array($this, 'basename')),
        );
    }

    public function basename($filename)
    {
        return basename($filename);
    }

    public function getName()
    {
        return 'ligi_twig_extension';
    }
}