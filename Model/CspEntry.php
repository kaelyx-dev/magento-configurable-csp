<?php

namespace Kaelyx\ConfigurableCSP\Model;

use Magento\Framework\Model\AbstractModel;

class CspEntry extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Kaelyx\ConfigurableCSP\Model\ResourceModel\CspEntry::class);
    }
}
