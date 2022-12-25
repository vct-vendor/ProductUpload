<?php
/**
 * @copyright Copyright (c)
 *
 * @see       PROJECT_LICENSE.txt
 */

declare(strict_types = 1);

namespace Vct\ProductUpload\Block\System\Config\Form\Field;

use Magento\Config\Model\Config\Backend\File as MagentoFile;

class File extends MagentoFile
{
    /**
     * Allow only Microsoft Excel formats
     *
     * @return string[]
     */
    protected function _getAllowedExtensions(): array
    {
        return ['xla', 'xlam', 'xlr', 'xls', 'xlsb', 'xlsm', 'xlsx', 'xlt', 'xltm', 'xltx', 'xlw', 'xml'];
    }
}
