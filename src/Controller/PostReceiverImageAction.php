<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Receiver;
use App\Service\S3ImagePathToCFConverter;
use App\Service\S3Uploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PostReceiverImageAction
{
    private S3Uploader $uploader;
    private ManagerRegistry $registry;
    private S3ImagePathToCFConverter $imagePathToCFConverter;

    public function __construct(
        S3Uploader $uploader,
        ManagerRegistry $registry,
        S3ImagePathToCFConverter $imagePathToCFConverter
    )
    {
        $this->uploader = $uploader;
        $this->registry = $registry;
        $this->imagePathToCFConverter = $imagePathToCFConverter;
    }

    public function __invoke(Request $request, Receiver $data): Receiver
    {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile instanceof UploadedFile) {
            throw new BadRequestHttpException('file is required');
        }

        $objectUrl = $this->uploader->upload($uploadedFile);
        $imagePath = $this->imagePathToCFConverter->getConvertedUrl($objectUrl);

        $data->addImage($imagePath);

        $this->registry->getManager()->persist($data);
        $this->registry->getManager()->flush();

        return $data;
    }
}
