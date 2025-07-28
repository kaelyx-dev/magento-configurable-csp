<?php

namespace Kaelyx\ConfigurableCSP\Helper;

class DirectiveHelper extends \Magento\Framework\App\Helper\AbstractHelper 
{

    public const KEBAB = 'kebab';
    public const CAMEL = 'camel';
    public const NORMALIZE = 'normalize';

    public function kebabify($directives)
    {
        $kebabDirectives = [];
        foreach ($directives as $directive) {
            $kebabDirectives[] = strtolower(str_replace('_', '-', $directive));
        }
        return $kebabDirectives;
    }

    public function camelify($directives)
    {
        $camelDirectives = [];
        foreach ($directives as $directive) {
            $camelDirectives[] = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $directive))));
        }
        return $camelDirectives;
    }

    public function normalize($directives)
    {
        $normalizedDirectives = [];
        foreach ($directives as $directive) {
            $normalizedDirectives[] = strtolower(trim($directive));
        }
        return $normalizedDirectives;
    }

    public function getAllDirectives($as)
    {
        $directives = \Kaelyx\ConfigurableCSP\Helper\Enum\Directive::getDirectives();
        switch($as) {
            case self::KEBAB:
                return $this->kebabify($directives);
            case self::CAMEL:
                return $this->camelify($directives);
            case self::NORMALIZE:
                return $this->normalize($directives);
            default:
                throw new \InvalidArgumentException("Invalid format specified: $as");
        }
    }
}