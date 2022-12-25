<?php
/**
 * @copyright Copyright (c)
 *
 * @see       PROJECT_LICENSE.txt
 */

declare(strict_types = 1);

namespace Vct\ProductUpload\ViewModel\File;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Request implements ArgumentInterface
{
    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * Request constructor
     *
     * @param UrlInterface $urlBuilder
     */
    public function __construct(UrlInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormAction(): string
    {
        return $this->urlBuilder->getUrl('products/file/update', ['_secure' => true]);
    }
}
