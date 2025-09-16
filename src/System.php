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

class System
{
    public function updateHostsFile(string $fqdn, string $ip): void
    {
        $hostsFile = '/etc/hosts';
        $hosts     = file($hostsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        $entry = "{$ip} {$fqdn}";
        $found = false;
        foreach ($hosts as $i => $line) {
            // Split line into tokens
            $tokens = preg_split('/\s+/', trim($line)) ?: [];
            if (count($tokens) < 2) {
                continue;
            }

            $lineIp    = $tokens[0];
            $hostnames = array_slice($tokens, 1);

            if ($lineIp === $ip) {
                if (in_array($fqdn, $hostnames, true)) {
                    $found = true;
                    break;
                } else {
                    // Add fqdn to this line if not present
                    $hostnames[] = $fqdn;
                    $hosts[$i]   = $lineIp . ' ' . implode(' ', array_unique($hostnames));
                    file_put_contents($hostsFile, implode(PHP_EOL, $hosts) . PHP_EOL, LOCK_EX);
                    $found = true;
                    break;
                }
            }
        }
        if ( ! $found) {
            file_put_contents($hostsFile, $entry . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
}
