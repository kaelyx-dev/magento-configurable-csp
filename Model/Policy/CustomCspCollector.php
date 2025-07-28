<?php

namespace Kaelyx\ConfigurableCSP\Model\Policy;

use Magento\Csp\Api\Data\PolicyInterface;
use Magento\Csp\Api\PolicyCollectorInterface;
use Magento\Csp\Model\Policy\FetchPolicy;
use Psr\Log\LoggerInterface;
use Kaelyx\ConfigurableCSP\Model\ResourceModel\CspEntry\CollectionFactory as CspEntryCollectionFactory;
use Kaelyx\ConfigurableCSP\Helper\Enum\Directive;

/**
 * Custom CSP Policy Collector - The proper Magento 2.4 way
 * 
 * This collector adds custom CSP policies to both frontend and admin areas.
 * It integrates seamlessly with Magento's native CSP system and dynamically
 * loads policies from the database.
 */
class CustomCspCollector implements PolicyCollectorInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CspEntryCollectionFactory
     */
    private $cspEntryCollectionFactory;

    /**
     * @param LoggerInterface $logger
     * @param CspEntryCollectionFactory $cspEntryCollectionFactory
     */
    public function __construct(
        LoggerInterface $logger,
        CspEntryCollectionFactory $cspEntryCollectionFactory
    ) {
        $this->logger = $logger;
        $this->cspEntryCollectionFactory = $cspEntryCollectionFactory;
    }

    /**
     * Collect and return custom CSP policies
     * 
     * @inheritDoc
     */
    public function collect(array $defaultPolicies = []): array
    {

        $policies = $defaultPolicies;
        
        // Load all CSP entries from the database
        $cspEntries = $this->loadCspEntries();
        
        // Create policies from database entries
        foreach ($cspEntries as $entry) {
            try {
                $policy = $this->createPolicyFromEntry($entry);
                if ($policy) {
                    $policies[] = $policy;
        
                }
            } catch (\Exception $e) {
                $this->logger->error('Failed to create CSP policy from database entry', [
                    'directive' => $entry->getData('directive'),
                    'value' => $entry->getData('value'),
                    'error' => $e->getMessage()
                ]);
            }
        }
        return $policies;
    }

    /**
     * Load all CSP entries from the database
     * 
     * @return \Kaelyx\ConfigurableCSP\Model\CspEntry[]
     */
    private function loadCspEntries(): array
    {
        $collection = $this->cspEntryCollectionFactory->create();
        return $collection->getItems();
    }

    /**
     * Create a FetchPolicy from a database entry
     * 
     * @param \Kaelyx\ConfigurableCSP\Model\CspEntry $entry
     * @return PolicyInterface|null
     */
    private function createPolicyFromEntry($entry): ?PolicyInterface
    {
        $directive = $entry->getData('directive');
        $value = $entry->getData('value');
        
        // Validate that the directive is supported
        if (!in_array($directive, Directive::getDirectives())) {
            return null;
        }
        
        // Skip report-uri and report-to as they're handled differently
        if (in_array($directive, [Directive::REPORT_URI, Directive::REPORT_TO])) {
            return null;
        }
        
        // Parse the value into hosts/sources
        $hosts = $this->parseValueIntoHosts($value);
        
        // Determine policy settings based on directive and value
        $policySettings = $this->getPolicySettings($directive, $value);
        
        return new FetchPolicy(
            $directive,                                    // policy ID
            false,                                         // not report-only (determined by global config)
            $hosts,                                        // hosts
            [],                                            // no dynamic sources (for now)
            $policySettings['self'],                       // allow 'self'
            $policySettings['inline'],                     // allow inline
            $policySettings['eval'],                       // allow eval
            $policySettings['schemes'] ?? [],              // schemes
            $policySettings['dataSchemes'] ?? [],          // data schemes
            $policySettings['unsafeHashes'] ?? false       // unsafe hashes
        );
    }

    /**
     * Parse value string into array of hosts
     * 
     * @param string $value
     * @return array
     */
    private function parseValueIntoHosts(string $value): array
    {
        // Remove special keywords and parse hosts
        $hosts = [];
        $parts = explode(' ', trim($value));
        
        foreach ($parts as $part) {
            $part = trim($part);
            if (empty($part)) {
                continue;
            }
            
            // Skip special keywords - these are handled by policy settings
            if (in_array($part, ["'self'", "'unsafe-inline'", "'unsafe-eval'", "'unsafe-hashes'", "'strict-dynamic'", "'none'"])) {
                continue;
            }
            
            // Skip data: and blob: schemes - these are handled separately
            if (strpos($part, 'data:') === 0 || strpos($part, 'blob:') === 0) {
                continue;
            }
            
            // Add as host
            $hosts[] = $part;
        }
        
        return $hosts;
    }

    /**
     * Get policy settings based on directive and value
     * 
     * @param string $directive
     * @param string $value
     * @return array
     */
    private function getPolicySettings(string $directive, string $value): array
    {
        $settings = [
            'self' => strpos($value, "'self'") !== false,
            'inline' => strpos($value, "'unsafe-inline'") !== false,
            'eval' => strpos($value, "'unsafe-eval'") !== false,
            'unsafeHashes' => strpos($value, "'unsafe-hashes'") !== false,
            'schemes' => [],
            'dataSchemes' => []
        ];
        
        if (strpos($value, 'data:') !== false) {
            $settings['dataSchemes'][] = 'data';
        }
        if (strpos($value, 'blob:') !== false) {
            $settings['dataSchemes'][] = 'blob';
        }
        
        switch ($directive) {
            case Directive::STYLE_SRC:
                break;
            case Directive::SCRIPT_SRC:
                break;
            case Directive::IMG_SRC:
                break;
        }
        
        return $settings;
    }
}
