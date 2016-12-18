<?php

namespace stalkalex\utilities\components;

use yii\base\Component;

/**
 * Class AvCheck
 * Responsible for file and directory check
 * @package stalkalex\utilities\components
 */
class AvCheck extends Component
{
    const QUARANTINE = 'Quarantine';
    const REMOVE = 'Remove';
    const CURE = 'Cure';
    const SKIP = 'Skip';
    const RECOMMENDED = 'Recommended';

    public $binary = 'sudo kav4fs-control';

    /**
     * Returns true if no viruses found
     * @param $file
     * @param string $action
     */
    public function check($file, $action = self::REMOVE)
    {
        $result = $this->execute($this->checkCommand($file, $action));
        $parsedResult = $this->parse($result);
        return $this->validate($parsedResult);
    }

    /**
     * @param array $result
     * @return bool
     */
    private function validate(array $result)
    {
        return $result['Threats found'] === 0
            && $result['Removed'] === 0;
    }

    /**
     * @param $result
     * @return mixed
     */
    private function parse($result)
    {
        $lines = explode("\n", $result);
        return array_reduce($lines, function ($acc, $item) {
            $res = [];
            preg_match_all('/([a-zA-Z ]+):.+(\d)/', $item, $res);
            if (!empty($res) && isset($res[1][0], $res[2][0])) {
                $acc[trim($res[1][0])] = (int)$res[2][0];
            }
            return $acc;
        }, []);
    }

    /**
     * @param $command
     * @return mixed
     */
    private function execute($command)
    {
        return shell_exec($this->binary . ' ' . $command);
    }

    /**
     * @param $file
     * @param $action
     * @return string
     */
    private function checkCommand($file, $action)
    {
        return "--action $action --scan-file $file";
    }
}
