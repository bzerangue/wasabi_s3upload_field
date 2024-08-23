<?php

require 'aws/aws-autoloader.php';

use Aws\S3\S3MultiRegionClient;
use Aws\S3\Exception\S3Exception;

if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');

class S3FacadeWasabi
{
    /**
     * @var S3MultiRegionClient
     */
    private $s3;
    private $bucket;

    public function __construct($accessKey = null, $secretKey = null)
    {
        // Use the following code to use AWS credentials.
        $raw_credentials = [
            'credentials' => [
                'key' => $accessKey,
                'secret' => $secretKey,
            ],
            'region' => 'us-central-1',
            'endpoint' => 'https://s3.us-central-1.wasabisys.com',
            'version' => 'latest',
            'use_path_style_endpoint' => true,
        ];

        // Initialize the S3 client with $raw_credentials
        $this->s3 = new S3MultiRegionClient($raw_credentials);
    }

    public function doesBucketExist($bucket)
    {
        return $this->s3->doesBucketExist($bucket);
    }

    public function listBuckets()
    {
        $result = $this->s3->listBuckets();

        return $result['Buckets'];
    }

    public function putObject($bucket, $key, $filePath, $options)
    {
        $options['Bucket'] = $bucket;
        $options['Key'] = $key;
        //$options['Body'] = EntityBody::factory(fopen($filePath, 'r+'));
        $options['SourceFile'] = $filePath;

        try {
            // Upload the file
            return $this->s3->putObject($options);

            //echo "File uploaded successfully: {$result['ObjectURL']}\n";
        } catch (S3Exception $e) {
            echo "Error uploading file: " . $e->getMessage() . "\n";
        }
    }

    public function deleteObject($bucket, $key)
    {
        return $this->s3->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key
        ]);
    }

    // Public function to get the location of a bucket
    public function getBucketLocation($bucket)
    {
        try {
            // Use the S3MultiRegionClient's determineBucketRegion method
            return $this->s3->determineBucketRegion($bucket);
        } catch (S3Exception $e) {
            echo "Error getting bucket location: " . $e->getMessage() . "\n";
        }
    }
}