<?php

namespace Kaelyx\ConfigurableCSP\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;
use Kaelyx\ConfigurableCSP\Helper\Enum\Directive;

class CspEntryOptions implements OptionSourceInterface
{
    /**
     * @var Directive
     */
    protected $directive;

    /**
     * Constructor
     *
     * @param Directive $directive
     */
    public function __construct(Directive $directive)
    {
        $this->directive = $directive;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [];
        foreach (Directive::getDirectives() as $directiveValue) {
            $options[] = [
                'value' => $directiveValue,
                'label' => ucwords(str_replace('-', ' ', $directiveValue))
            ];
        }
        
        return $options;
    }
}
