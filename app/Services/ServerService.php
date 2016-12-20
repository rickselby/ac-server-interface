<?php

namespace App\Services;

use Httpful\Mime;
use Httpful\Request;
use Psr\Log\LoggerInterface;
use Illuminate\Filesystem\Filesystem;

class ServerService
{
    protected $configPath;
    protected $entryList = 'entry_list.ini';
    protected $serverConfig = 'server_cfg.ini';
    protected $logFile = 'acServer.log';
    protected $resultsSeenFile = 'results.list';

    /** @var LoggerInterface */
    protected $log;

    /** @var Filesystem */
    protected $file;

    /*
     * Messages we expect from the script
     */
    protected $running = 'Server is Running';
    protected $notRunning = 'Server is Not Running';

    /**
     * ServerService constructor. Initialise the requirements
     *
     * @param LoggerInterface $log
     * @param Filesystem $file
     */
    public function __construct(LoggerInterface $log, Filesystem $file)
    {
        $this->log = $log;
        $this->file = $file;
        $this->configPath = $this->fixPath(env('AC_SERVER_CONFIG_PATH'));
    }

    /**
     * Start the server
     */
    public function start()
    {
        $this->run('start');
        // Log the action
        $this->log->info('Assetto Corsa Server: started');
    }

    /**
     * Stop the server
     */
    public function stop()
    {
        $this->run('stop');
        // Log the action
        $this->log->info('Assetto Corsa Server: stopped');
    }

    /**
     * Get the status of the server
     * @return string
     */
    public function status()
    {
        $out = $this->run('status');
        return $out[0];
    }

    /**
     * Check if the server is running
     * @return bool
     */
    public function isRunning()
    {
        return $this->status() == $this->running;
    }

    /**
     * Check if the server is stopped
     * @return bool
     */
    public function isStopped()
    {
        return $this->status() == $this->notRunning;
    }

    /**
     * Update the entry list
     *
     * @param string $contents
     *
     * @return bool
     */
    public function updateEntryList($contents)
    {
        return $this->updateConfigFile(
            $contents,
            $this->entryList,
            $this->getCurrentEntryList(),
            'entry'
        );
    }

    /**
     * Update the server config
     *
     * @param $contents
     *
     * @return bool
     */
    public function updateServerConfig($contents)
    {
        return $this->updateConfigFile(
            $contents,
            $this->serverConfig,
            $this->getCurrentConfigFile(),
            'config'
        );
    }

    /**
     * Update a config file
     * @param string $contents
     * @param string $name
     * @param string $currentFile
     * @param string $path
     * @return bool
     */
    protected function updateConfigFile($contents, $name, $currentFile, $path)
    {
        if ($contents != $currentFile) {
            // Get the path to local storage, where we will keep a copy of this file
            $localPath = storage_path('app'.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR);
            if (!$this->file->isDirectory($localPath)) {
                $this->file->makeDirectory($localPath);
            }
            $localName = time().'-'.$name;

            // Set the contents of the file
            $this->file->put($localPath.$localName, $contents);
            // Then copy the file to the server config
            $this->file->copy($localPath.$localName, $this->configPath.$name);
            // Log the action
            $this->log->info('Assetto Corsa Server: file uploaded', [
                'file' => $localName,
            ]);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the current entry list
     *
     * @return string
     */
    public function getCurrentEntryList()
    {
        return $this->file->get($this->configPath.$this->entryList);
    }

    /**
     * Get the current config file
     *
     * @return string
     */
    public function getCurrentConfigFile()
    {
        return $this->file->get($this->configPath.$this->serverConfig);
    }

    /**
     * Get the server log file
     *
     * @return string
     */
    public function getLogFile()
    {
        $path =  $this->fixPath(env('AC_SERVER_LOG_PATH')).$this->logFile;
        if (!$this->file->exists($path)) {
            $path .= '.last';
            if (!$this->file->exists($path)) {
                return '';
            }
        }

        return $this->file->get($path);
    }

    /**
     * Get the latest results file
     *
     * @return bool|string
     */
    public function getLatestResults()
    {
        $fileList = $this->getResultsSeen();
        // Make sure the file names are sorted (they start with Y-m-d-H-i-s
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
            if ($this->file->isFile($file)) {
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
        if (env('ACSR_SERVER_URL')) {
            Request::post(env('ACSR_SERVER_URL'))
                ->sendsType(Mime::JSON)
                ->body(json_encode([
                    'results' => $this->file->get($file)
                ]))
                ->send();

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
        if ($this->file->exists(storage_path($this->resultsSeenFile))) {
            return array_filter(
                explode("\n", $this->file->get(storage_path($this->resultsSeenFile)))
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
        $this->file->append(storage_path($this->resultsSeenFile), $filename."\n");
    }

    /**
     * Run a server command
     *
     * @param $cmd
     *
     * @return mixed
     */
    private function run($cmd)
    {
        exec(env('AC_SERVER_SCRIPT').' '.$cmd, $out);
        return $out;
    }

    /**
     * Check the trailing slash exists on a path
     *
     * @param $path
     *
     * @return string
     */
    private function fixPath($path)
    {
        return (substr($path,-1) != DIRECTORY_SEPARATOR) ? $path.DIRECTORY_SEPARATOR : $path;
    }
}
