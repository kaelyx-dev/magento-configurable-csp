<?php

namespace Kaelyx\ConfigurableCSP\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Kaelyx\ConfigurableCSP\Helper\Constants;

class CspEntry extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Constants::DATABASE_TABLE_NAME, 'id');
    }
}
