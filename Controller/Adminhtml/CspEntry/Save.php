<?php

namespace Kaelyx\ConfigurableCSP\Controller\Adminhtml\CspEntry;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Kaelyx\ConfigurableCSP\Model\CspEntryFactory;

class Save extends Action
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
        $data = $this->getRequest()->getParams();
        
        if ($data) {
            $id = $this->getRequest()->getParam('id');
            
            try {
                $model = $this->cspEntryFactory->create();
                if ($id) {
                    $model->load($id);
                }
                
                $model->setData('directive', $data['directive']);
                $model->setData('value', $data['value']);
                $model->save();
                
                $this->messageManager->addSuccessMessage(__('The CSP entry has been saved.'));
                
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                if ($this->getRequest()->getParam('continue')) {
                    return $resultRedirect->setPath('*/*/new');
                }
                
                return $resultRedirect->setPath('*/*/');
                
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
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
        return $this->_authorization->isAllowed('Kaelyx_ConfigurableCSP::add_csp_entry');
    }
}
