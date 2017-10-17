<?php

namespace App\Services;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use GuzzleHttp\RequestOptions;

class ResultsService
{
    /** @var Client */
    private $client;
    /** @var LoggerInterface */
    private $log;

    const RESULTS_SENT_FILE = 'results.sent';
    const RESULTS_DIRECTORY = 'results';

    public function __construct(LoggerInterface $log, Client $client)
    {
        $this->log = $log;
        $this->client = $client;
    }

    /**
     * Get the latest results file.
     *
     * @return bool|string
     */
    public function getLatestResults()
    {
        $fileList = $this->getListOfExistingFiles();
        if (count($fileList)) {
            // Files start with date, so last file is freshest
            return \Storage::disk('ac_server')->get(array_pop($fileList));
        } else {
            return false;
        }
    }

    /**
     * Get all results files from the server.
     *
     * @return array
     */
    public function getAllResults()
    {
        $files = [];
        foreach ($this->getListOfExistingFiles() as $file) {
            if (\Storage::disk('ac_server')->exists($file)) {
                $files[basename($file)] = \Storage::disk('ac_server')->get($file);
            }
        }

        return $files;
    }

    /**
     * Check for new results files; send any to the ACSR server.
     */
    public function checkForResults()
    {
        $resultsSent = $this->getListOfResultsSent();
        foreach ($this->getListOfExistingFiles() as $file) {
            if (! in_array($file, $resultsSent)) {
                // send to ACSR server
                $this->sendResults($file);
                $this->setResultsSent($file);
            }
        }
    }

    /**
     * Get a (sorted) list of all results files.
     *
     * @return array
     */
    protected function getListOfExistingFiles()
    {
        $files = \Storage::disk('ac_server')->files(self::RESULTS_DIRECTORY);
        sort($files);

        return $files;
    }

    /**
     * Send a results file to the ACSR server.
     *
     * @param $file
     */
    protected function sendResults($file)
    {
        if (env('MASTER_SERVER_URL')) {
            // We could use client->post but it's harder to test...
            $this->client->request('POST', env('MASTER_SERVER_URL'), [
                RequestOptions::JSON => [
                    'results' => \Storage::disk('ac_server')->get($file),
                ],
            ]);

            $this->log->info('Assetto Corsa Server: results sent', [
                'file' => $file,
            ]);
        }
    }

    /**
     * Get a list of the results files we have already sent.
     *
     * @return string[]
     */
    protected function getListOfResultsSent()
    {
        if (\Storage::disk('local')->exists(self::RESULTS_SENT_FILE)) {
            return array_filter(
                explode("\n", \Storage::disk('local')->get(self::RESULTS_SENT_FILE))
            );
        } else {
            return [];
        }
    }

    /**
     * Add a new file to the list of sent results files.
     *
     * @param $filename
     */
    protected function setResultsSent($filename)
    {
        \Storage::disk('local')->append(self::RESULTS_SENT_FILE, $filename);
    }
}
