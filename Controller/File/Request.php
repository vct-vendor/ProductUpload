<?php
/**
 * @copyright Copyright (c)
 *
 * @see       PROJECT_LICENSE.txt
 */

declare(strict_types = 1);

namespace Vct\ProductUpload\Controller\File;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use function __;

class Request implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()
                   ->getTitle()
                   ->set(__('Download products file'));

        return $resultPage;
    }
}
