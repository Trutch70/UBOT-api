<?php

declare(strict_types=1);

namespace App\Service;

use Aws\S3\S3Client;
use Symfony\Component\{HttpFoundation\File\UploadedFile, String\Slugger\SluggerInterface};

class S3Uploader
{
    private S3Client $s3Client;

    public function __construct(
        string                   $awsAccessKey,
        string                   $awsSecretKey,
        private string           $awsBucket,
        private SluggerInterface $slugger,
    )
    {
        $this->s3Client = new S3Client([
            'region' => 'eu-central-1',
            'version' => 'latest',
            'credentials' => [
                'key' => $awsAccessKey,
                'secret' => $awsSecretKey,
            ],
        ]);
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $result = $this->s3Client->putObject([
            'Bucket' => $this->awsBucket,
            'Key' => $fileName,
            'Body' => fopen($file->getPathname(), 'r'),
        ]);

        return $result->get('ObjectURL');
    }
}
