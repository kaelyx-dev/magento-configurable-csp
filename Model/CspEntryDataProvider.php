<?php

namespace Kaelyx\ConfigurableCSP\Model;

use Kaelyx\ConfigurableCSP\Model\ResourceModel\CspEntry\Collection as CspEntryCollection;
use Magento\Framework\App\Request\DataPersistorInterface;

class CspEntryDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CspEntryCollection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CspEntryCollection $collection
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CspEntryCollection $collection,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collection;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $cspEntry) {
            $this->loadedData[$cspEntry->getId()] = $cspEntry->getData();
        }

        $data = $this->dataPersistor->get('kaelyx_csp_entry');
        if (!empty($data)) {
            $cspEntry = $this->collection->getNewEmptyItem();
            $cspEntry->setData($data);
            $this->loadedData[$cspEntry->getId()] = $cspEntry->getData();
            $this->dataPersistor->clear('kaelyx_csp_entry');
        }

        return $this->loadedData;
    }
}
