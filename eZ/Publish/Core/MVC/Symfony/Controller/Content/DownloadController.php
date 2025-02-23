<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\MVC\Symfony\Controller\Content;

use eZ\Bundle\EzPublishIOBundle\BinaryStreamResponse;
use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use eZ\Publish\Core\Helper\TranslationHelper;
use eZ\Publish\Core\IO\IOServiceInterface;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadController extends Controller
{
    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    /** @var \eZ\Publish\Core\IO\IOServiceInterface */
    private $ioService;

    /** @var \eZ\Publish\Core\Helper\TranslationHelper */
    private $translationHelper;

    public function __construct(ContentService $contentService, IOServiceInterface $ioService, TranslationHelper $translationHelper)
    {
        $this->contentService = $contentService;
        $this->ioService = $ioService;
        $this->translationHelper = $translationHelper;
    }

    /**
     * @param mixed $contentId ID of a valid Content
     * @param string $fieldIdentifier Field Definition identifier of the Field the file must be downloaded from
     * @param string $filename
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \eZ\Bundle\EzPublishIOBundle\BinaryStreamResponse
     * @return \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function downloadBinaryFileAction($contentId, $fieldIdentifier, $filename, Request $request)
    {
        if ($request->query->has('version')) {
            $content = $this->contentService->loadContent($contentId, null, $request->query->get('version'));
        } else {
            $content = $this->contentService->loadContent($contentId);
        }

        if ($content->contentInfo->isTrashed()) {
            throw new NotFoundException('File', $filename);
        }

        $field = $this->translationHelper->getTranslatedField(
            $content,
            $fieldIdentifier,
            $request->query->has('inLanguage') ? $request->query->get('inLanguage') : null
        );
        if (!$field instanceof Field) {
            throw new InvalidArgumentException(
                "'{$fieldIdentifier}' Field does not exist in Content item {$content->contentInfo->id} '{$content->contentInfo->name}'"
            );
        }

        $response = new BinaryStreamResponse($this->ioService->loadBinaryFile($field->value->id), $this->ioService);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);

        return $response;
    }
}
