<?php

/**
 * Sytem info class
 *
 * @description System info class for RaspAP
 * @author      Bill Zimmerman <billzimmerman@gmail.com>
 * @license     https://github.com/raspap/raspap-webgui/blob/master/LICENSE
 */

namespace RaspAP\System;

class Sysinfo
{
    public function hostname()
    {
        return shell_exec("hostname -f");
    }

    public function uptime()
    {
        $uparray = explode(" ", exec("cat /proc/uptime"));
        $seconds = round($uparray[0], 0);
        $minutes = $seconds / 60;
        $hours   = $minutes / 60;
        $days    = floor($hours / 24);
        $hours   = floor($hours   - ($days * 24));
        $minutes = floor($minutes - ($days * 24 * 60) - ($hours * 60));
        $uptime= '';
        if ($days    != 0) {
            $uptime .= $days . ' day' . (($days    > 1)? 's ':' ');
        }
        if ($hours   != 0) {
            $uptime .= $hours . ' hour' . (($hours   > 1)? 's ':' ');
        }
        if ($minutes != 0) {
            $uptime .= $minutes . ' minute' . (($minutes > 1)? 's ':' ');
        }

        return $uptime;
    }

    public function systime()
    {
        $systime = exec("date");
        return $systime;
    }

    public function usedMemory()
    {
        $used = shell_exec("free -m | awk 'NR==2{ total=$2 ; used=$3 } END { print used/total*100}'");
        return floor($used);
    }

    public function processorCount()
    {
        $procs = shell_exec("nproc --all");
        return intval($procs);
    }

    public function loadAvg1Min()
    {
        $load = exec("awk '{print $1}' /proc/loadavg");
        return floatval($load);
    }

    public function systemLoadPercentage()
    {
        return intval(($this->loadAvg1Min() * 100) / $this->processorCount());
    }

    public function systemTemperature()
    {
        $cpuTemp = file_get_contents("/sys/class/thermal/thermal_zone0/temp");
        return number_format((float)$cpuTemp/1000, 1);
    }

    public function hostapdStatus()
    {
        exec('pidof hostapd | wc -l', $status);
        return $status;
    }

    public function operatingSystem()
    {
        $os_desc = shell_exec("lsb_release -sd");
        return $os_desc;
    }

    public function kernelVersion()
    {
        $kernel = shell_exec("uname -r");
        return $kernel;
    }
}

