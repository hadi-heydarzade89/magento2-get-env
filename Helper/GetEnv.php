<?php
/**
 * This file is part of the Klarna Core module
 *
 * (c) Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace H2\GetEnv\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\State;
use Magento\Framework\Module\ResourceInterface;
use Magento\Framework\Config\File\ConfigFilePool;

/**
 * Class GetEnv
 *
 * @package H2\GetEnv\Helper
 */
class GetEnv
{
    /**
     * @var State
     */
    private $appState;

    /**
     * @var ResourceInterface
     */
    private $resource;

    /**
     * ProductMetadataInterface
     */
    private $productMetadata;

    private $directoryList;


    /**
     * GetEnv constructor.
     *
     * @param ProductMetadataInterface $productMetadata
     * @param State                    $appState
     * @param ResourceInterface        $resource
     */
    public function __construct(
        ProductMetadataInterface $productMetadata,
        State $appState,
        ResourceInterface $resource,
        DirectoryList $directoryList
    ) {
        $this->appState = $appState;
        $this->productMetadata = $productMetadata;
        $this->resource = $resource;
        $this->directoryList = $directoryList;
    }

    /**
     * Get module version info
     *
     * @param string $packageName
     * @return array|bool
     */
    public function getVersion($packageName)
    {
        return $this->resource->getDataVersion($packageName);
    }

    /**
     * Gets the current MAGE_MODE setting
     *
     * @return string
     */
    public function getMageMode()
    {
        return $this->appState->getMode();
    }

    /**
     * Gets the current Magento version
     *
     * @return string
     */
    public function getMageVersion()
    {
        return $this->productMetadata->getVersion();
    }

    /**
     * Gets the current Magento Edition
     *
     * @return string
     */
    public function getMageEdition()
    {
        return $this->productMetadata->getEdition();
    }

    /**
     * Get Env array
     * 
     * @return mixed
     * 
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getEnv()
    {
        $deploymentConfig = $this->directoryList->getPath(DirectoryList::CONFIG);
        $configPool = new ConfigFilePool();
        $envPath = $deploymentConfig . '/' . $configPool->getPath(ConfigFilePool::APP_ENV);
        return include $envPath;
    }
}