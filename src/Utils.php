<?php

namespace EDACerton\PluginUtils;

/*
    Copyright (C) 2025  Derek Kaser

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

class Utils
{
    private string $pluginName;

    public function __construct(string $pluginName)
    {
        $this->pluginName = $pluginName;
        if ( ! defined(__NAMESPACE__ . "\PLUGIN_NAME")) {
            define(__NAMESPACE__ . "\PLUGIN_NAME", $pluginName);
        }
    }

    public function logmsg(string $message): void
    {
        $timestamp = date('Y/m/d H:i:s');
        $filename  = basename(is_string($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : "");
        file_put_contents("/var/log/" . $this->pluginName . ".log", "{$timestamp} {$filename}: {$message}" . PHP_EOL, FILE_APPEND);
    }

    public static function auto_v(string $file): string
    {
        global $docroot;
        $path = $docroot . $file;
        clearstatcache(true, $path);
        $time    = file_exists($path) ? filemtime($path) : 'autov_fileDoesntExist';
        $newFile = "{$file}?v=" . $time;

        return $newFile;
    }

    public static function make_option(bool|string $selected, string $value, string $text, string $extra = ""): string
    {
        if (is_string($selected)) {
            $selected = $selected === $value;
        }

        return "<option value='{$value}'" . ($selected ? " selected" : "") . (strlen($extra) ? " {$extra}" : "") . ">{$text}</option>";
    }

    /**
    * @param array<mixed> $args
    */
    public function run_task(string $functionName, array $args = array()): void
    {
        try {
            $reflectionMethod = new \ReflectionMethod($functionName);
            $reflectionMethod->invokeArgs(null, $args);
        } catch (\Throwable $e) {
            $this->logmsg("Caught exception in {$functionName} : " . $e->getMessage());
        }
    }
}
