<?php

namespace Kaelyx\ConfigurableCSP\Model\ResourceModel\CspEntry;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize collection model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Kaelyx\ConfigurableCSP\Model\CspEntry::class,
            \Kaelyx\ConfigurableCSP\Model\ResourceModel\CspEntry::class
        );
    }
}
