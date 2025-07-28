<?php

namespace Kaelyx\ConfigurableCSP\Controller\Adminhtml\CspEntry;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Kaelyx\ConfigurableCSP\Model\ResourceModel\CspEntry\Collection;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var Collection
     */
    protected $collectionFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Filter $filter
     * @param Collection $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        Collection $collectionFactory
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory);
        $collectionSize = $collection->getSize();

        foreach ($collection as $cspEntry) {
            $cspEntry->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Check if admin has permissions to view this page
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Kaelyx_ConfigurableCSP::list_csp_entries');
    }
}
