<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;

class ResultsService extends ServerBase
{
    /** @var Client */
    private $client;

    const resultsSeenFile = 'app'.DIRECTORY_SEPARATOR.'results.list';

    public function __construct(LoggerInterface $log, Filesystem $file, Client $client)
    {
        parent::__construct($log, $file);
        $this->client = $client;
    }

    /**
     * Get the latest results file
     *
     * @return bool|string
     */
    public function getLatestResults()
    {
        $fileList = $this->getResultsSeen();
        // Make sure the file names are sorted (they start with Y-m-d-H-i-s)
        sort($fileList);
        if (count($fileList)) {
            return $this->file->get(array_pop($fileList));
        } else {
            return false;
        }
    }

    /**
     * Get all results files from the server
     *
     * @return array
     */
    public function getAllResults()
    {
        $files = [];
        foreach($this->getResultsSeen() AS $file) {
            if (is_file($file)) {
                $files[basename($file)] = $this->file->get($file);
            }
        }
        return $files;
    }

    /**
     * Check for new results files; send any to the ACSR server
     */
    public function checkForResults()
    {
        $fileList = $this->file->files(env('AC_SERVER_RESULTS_PATH'));

        if (count($fileList)) {
            foreach($fileList AS $file) {
                if (!in_array($file, $this->getResultsSeen())) {
                    // send to ACSR server
                    $this->sendResults($file);
                    $this->addResultsSeen($file);
                }
            }
        }
    }

    /**
     * Send a results file to the ACSR server
     *
     * @param $file
     */
    protected function sendResults($file)
    {
        if (env('MASTER_SERVER_URL')) {
            // We could use client->post but it's harder to test...
            $this->client->request('POST', env('MASTER_SERVER_URL'), [
                RequestOptions::JSON => [
                    'results' => $this->file->get($file),
                ],
            ]);

            $this->log->info('Assetto Corsa Server: results sent', [
                'file' => $file,
            ]);
        }
    }

    /**
     * Get a list of the results files we have already seen
     *
     * @return string[]
     */
    protected function getResultsSeen()
    {
        if ($this->file->exists(storage_path(self::resultsSeenFile))) {
            return array_filter(
                explode("\n", $this->file->get(storage_path(self::resultsSeenFile)))
            );
        } else {
            return [];
        }
    }

    /**
     * Add a new file to the list of seen results files
     *
     * @param $filename
     */
    protected function addResultsSeen($filename)
    {
        $this->file->append(storage_path(self::resultsSeenFile), $filename."\n");
    }
}
