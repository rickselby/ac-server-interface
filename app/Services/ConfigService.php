<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamPrintVisitor;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use Psr\Log\LoggerInterface;

class ConfigService extends ServerBase
{
    /** @var string */
    protected $configPath;

    const entryList = 'entry_list.ini';
    const serverConfig = 'server_cfg.ini';

    public function __construct(LoggerInterface $log, Filesystem $file)
    {
        parent::__construct($log, $file);
        $this->configPath = $this->fixPath(env('AC_SERVER_CONFIG_PATH'));
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
            self::entryList,
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
            self::serverConfig,
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
            if (!is_dir($localPath)) {
                $this->file->makeDirectory($localPath, 0755, true);
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
        return $this->getConfigFile(self::entryList);
    }

    /**
     * Get the current config file
     *
     * @return string
     */
    public function getCurrentConfigFile()
    {
        return $this->getConfigFile(self::serverConfig);
    }

    /**
     * Check if a config file exists before trying to read it
     *
     * @param $name
     *
     * @return string
     */
    protected function getConfigFile($name)
    {
        if ($this->file->isFile($this->configPath.$name)) {
            return $this->file->get($this->configPath.$name);
        } else {
            return '';
        }
    }

}
