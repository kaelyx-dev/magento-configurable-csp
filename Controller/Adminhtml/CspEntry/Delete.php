<?php

namespace Kaelyx\ConfigurableCSP\Controller\Adminhtml\CspEntry;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Kaelyx\ConfigurableCSP\Model\CspEntryFactory;

class Delete extends Action
{
    /**
     * @var CspEntryFactory
     */
    protected $cspEntryFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param CspEntryFactory $cspEntryFactory
     */
    public function __construct(
        Context $context,
        CspEntryFactory $cspEntryFactory
    ) {
        parent::__construct($context);
        $this->cspEntryFactory = $cspEntryFactory;
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        
        if ($id) {
            try {
                $model = $this->cspEntryFactory->create();
                $model->load($id);
                if ($model->getId()) {
                    $model->delete();
                    $this->messageManager->addSuccessMessage(__('The CSP entry has been deleted.'));
                } else {
                    $this->messageManager->addErrorMessage(__('This CSP entry no longer exists.'));
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        
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
