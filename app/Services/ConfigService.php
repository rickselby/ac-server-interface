<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class ConfigService
{
    /** @var LoggerInterface */
    private $log;

    // Paths are relative to the AC server base
    const entryList = 'cfg'.DIRECTORY_SEPARATOR.'entry_list.ini';
    const serverConfig = 'cfg'.DIRECTORY_SEPARATOR.'server_cfg.ini';

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * Update the entry list.
     *
     * @param string $contents
     *
     * @return bool
     */
    public function updateEntryList($contents)
    {
        return $this->updateConfigFile(self::entryList, $contents, $this->getCurrentEntryList(), 'entry');
    }

    /**
     * Update the server config.
     *
     * @param $contents
     *
     * @return bool
     */
    public function updateServerConfig($contents)
    {
        return $this->updateConfigFile(self::serverConfig, $contents, $this->getCurrentConfigFile(), 'config');
    }

    /**
     * Update a config file.
     *
     * @param string $destination     Path to server file to update
     * @param string $contents        New contents of file
     * @param string $currentContents Previous contents of file
     * @param string $localPath       Path to store a local copy
     *
     * @return bool
     */
    protected function updateConfigFile($destination, $contents, $currentContents, $localPath)
    {
        if ($contents != $currentContents) {
            $localName = time().'.ini';

            // Keep a local copy of the file
            \Storage::disk('local')->put($localPath.DIRECTORY_SEPARATOR.$localName, $contents);

            // Overwrite the actual config file
            \Storage::disk('ac_server')->put($destination, $contents);

            // Log the action
            $this->log->info('Config: '.$destination.' updated', [
                'file' => $localName,
            ]);

            return true;
        } else {
            // Log the action
            $this->log->info('Config: '.$destination.' not updated; identical to existing file');

            return false;
        }
    }

    /**
     * Get the current entry list contents.
     *
     * @return string
     */
    public function getCurrentEntryList()
    {
        return $this->getConfigFile(self::entryList);
    }

    /**
     * Get the current config file contents.
     *
     * @return string
     */
    public function getCurrentConfigFile()
    {
        return $this->getConfigFile(self::serverConfig);
    }

    /**
     * Check if a config file exists before trying to read it.
     *
     * @param $name
     *
     * @return string
     */
    protected function getConfigFile($name)
    {
        if (\Storage::disk('ac_server')->exists($name)) {
            return \Storage::disk('ac_server')->get($name);
        } else {
            return '';
        }
    }
}
