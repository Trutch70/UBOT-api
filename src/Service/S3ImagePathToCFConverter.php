<?php

declare(strict_types=1);

namespace App\Service;

class S3ImagePathToCFConverter
{
    public function __construct(private string $awsBucketHost, private string $cloudFrontHost) {}

    public function getConvertedUrl(string $s3Url): string
    {
        return str_replace($this->awsBucketHost, $this->cloudFrontHost, $s3Url);
    }
}
