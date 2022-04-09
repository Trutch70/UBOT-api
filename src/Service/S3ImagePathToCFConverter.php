<?php

declare(strict_types=1);

namespace App\Service;

class S3ImagePathToCFConverter
{
    private string $awsBucketHost;
    private string $cloudFrontHost;

    public function __construct(string $awsBucketHost, string $cloudFrontHost)
    {
        $this->awsBucketHost = $awsBucketHost;
        $this->cloudFrontHost = $cloudFrontHost;
    }

    public function getConvertedUrl(string $s3Url): string
    {
        return str_replace($this->awsBucketHost, $this->cloudFrontHost, $s3Url);
    }
}
