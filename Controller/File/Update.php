<?php
/**
 * @copyright Copyright (c)
 *
 * @see       PROJECT_LICENSE.txt
 */

declare(strict_types = 1);

namespace Vct\ProductUpload\Controller\File;

use Exception;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList as AppDirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use function in_array;
use function sprintf;

class Update implements HttpPostActionInterface
{
    /**
     * @todo move the values specified in the code to the module settings
     */
    private const SELLRER_PREFIX = 'bobDev';

    private const HIDDEN_SHEET_NAME = 'Werte';

    private const VERSION_NUMBER_CELL_COORDINATE = 'I2';
    private const VERSION_NUMBER_SHEET_NAME = 'Hilfe!';

    private const EMAIL = 'EMAIL';
    private const PASSWORD = 'PASSWORD';

    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var DirectoryList
     */
    private DirectoryList $directoryList;

    /**
     * @var FileFactory
     */
    private FileFactory $fileFactory;

    /**
     * @var File
     */
    private File $file;

    /**
     * @param PageFactory           $pageFactory
     * @param RequestInterface      $request
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param DirectoryList         $directoryList
     * @param FileFactory           $fileFactory
     * @param File                  $file
     */
    public function __construct(
        PageFactory $pageFactory,
        RequestInterface $request,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        DirectoryList $directoryList,
        FileFactory $fileFactory,
        File $file
    ) {
        $this->pageFactory = $pageFactory;
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->fileFactory = $fileFactory;
        $this->file = $file;
    }

    /**
     * Update file
     *
     * @return ResponseInterface
     * @throws FileSystemException
     * @throws NoSuchEntityException
     * @throws Exception
     */
    public function execute(): ResponseInterface
    {
        $storeId = $this->storeManager->getStore()
                                      ->getId();

        $mediaPath = $this->directoryList->getPath(AppDirectoryList::MEDIA);
        $relativeFilePath = $this->scopeConfig->getValue(
            'vct_productupload/general/file',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $filePath = sprintf('%s/vct/productupload/%s', $mediaPath, $relativeFilePath);
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getSheetByName(self::HIDDEN_SHEET_NAME);

        $params = $this->request->getParams();
        $this->setEmailInSheet($sheet, $params['email']);
        $this->setPasswordInSheet($sheet, $params['password']);

        $version = $this->getFileVersion($spreadsheet);
        $filePathInfo = $this->file->getPathInfo($filePath);

        $newFilePath = sprintf(
            '%s/%s-%s-%s.%s',
            $filePathInfo['dirname'],
            $filePathInfo['filename'],
            self::SELLRER_PREFIX,
            $version,
            $filePathInfo['extension']
        );

        IOFactory::createWriter($spreadsheet, IOFactory::WRITER_XLSX)
                 ->setPreCalculateFormulas(false)
                 ->save($newFilePath);

        $newFileName = $this->file->getPathInfo($newFilePath)['basename'];
        $content = ['type' => 'filename', 'value' => $newFilePath, 'rm' => true];

        return $this->fileFactory->create($newFileName, $content);
    }

    /**
     * Get version number from sheet
     *
     * @param Spreadsheet $spreadsheet
     *
     * @return string
     */
    public function getFileVersion(Spreadsheet $spreadsheet): string
    {
        $versionNumber = '';

        if (in_array(self::VERSION_NUMBER_SHEET_NAME, $spreadsheet->getSheetNames(), true)) {
            $sheet = $spreadsheet->getSheetByName(self::VERSION_NUMBER_SHEET_NAME);
            $versionNumberCell = $sheet->getCell(self::VERSION_NUMBER_CELL_COORDINATE);
            $versionNumber = $versionNumberCell->getValue();
        }

        return $versionNumber;
    }

    /**
     * Set email in sheet
     *
     * @param Worksheet $sheet
     * @param string    $email
     */
    public function setEmailInSheet(Worksheet $sheet, string $email): void
    {
        $sheet->setCellValue('A2', self::EMAIL)
              ->setCellValue('C2', $email);
    }

    /**
     * Set password in sheet
     *
     * @param Worksheet $sheet
     * @param string    $password
     */
    public function setPasswordInSheet(Worksheet $sheet, string $password): void
    {
        $sheet->setCellValue('A3', self::PASSWORD)
              ->setCellValue('C3', $password);
    }
}
