<?php

namespace Tunacan\Bundle\Util;

class DateTimeBuilder
{
    /**
     * @Inject("server.remote.addr")
     * @var string
     */
    private $remoteAddr;
    /**
     * @Inject("server.timezone")
     * @var string
     */
    private $defaultTimezone;

    public function getCurrentUtcDateTime(): \DateTime
    {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }

    public function getCurrentDateTime(): \DateTime
    {
        return new \DateTime('now', $this->getUserTimezone());
    }

    public function getUserTimezone(): \DateTimeZone
    {
        try {
            return new \DateTimeZone($this->getUserTimezoneString());
        } catch (\Throwable $e) {
            return new \DateTimeZone($this->defaultTimezone);
        }
    }

    /**
     * @return string
     * @throws \Throwable
     */
    private function getUserTimezoneString(): string
    {
        try {
            $countryCode = \geoip_country_code_by_name($this->remoteAddr);
            $regionCode = \geoip_region_by_name($this->remoteAddr);
            return \geoip_time_zone_by_country_and_region($countryCode, $regionCode);
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}