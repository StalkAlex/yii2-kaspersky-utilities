<?php

namespace stalkalex\utilities\components;

use yii\base\Component;

/**
 * Class AvCheck
 * @package stalkalex\utilities
 * Component responsible for file and directory check
 */
class AvCheck extends Component
{
    public $binary = 'kav4fs-control';
    const QUARANTINE = 'Quarantine';
    const REMOVE = 'Remove';
    const CURE = 'Cure';
    const SKIP = 'Skip';
    const RECOMMENDED = 'Recommended';


    /**
     * @param $file
     * @param string $action
     */
    public function check($file, $action = self::REMOVE)
    {
        $result = $this->execute($this->checkCommand($file, $action));
    }

    /**
     * @param $command
     * @return mixed
     */
    private function execute($command)
    {
        system($this->binary . ' ' . $command, $result);
        return $result;
    }

    /**
     * @param $file
     * @param $action
     * @return string
     */
    private function checkCommand($file, $action)
    {
        return "--action  --scanfile $file";
    }


   /* $command = "/opt/kaspersky/kav4fs/bin/kav4fs-control --action <действие> --scan-file <путь к
файлу или директории>;";*/

}