<?php
/*
 * Copyright 2005-2014 the original author or authors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Mibew;

/**
 * Encapsulates work with system settings.
 */
class Settings
{
    /**
     * An instance of Settings class
     *
     * @var Settings
     */
    protected static $instance = null;

    /**
     * Array of settings
     *
     * @var array
     */
    protected $settings = array();

    /**
     * Array of settings stored in database
     *
     * @var array
     */
    protected $settingsInDb = array();

    /**
     * Returns an instance of Settings class
     *
     * @return Settings
     */
    protected static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Settings class constructor. Set default values and load setting from
     * database.
     */
    protected function __construct()
    {
        // Set default values
        $this->settings = array(
            'dbversion' => 0,
            'featuresversion' => 0,
            'title' => 'Your Company',
            'hosturl' => 'http://mibew.org',
            'logo' => '',
            'usernamepattern' => '{name}',
            'chat_style' => 'default',
            'invitation_style' => 'default',
            'page_style' => 'default',
            'chattitle' => 'Live Support',
            'geolink' => 'http://api.hostip.info/get_html.php?ip={ip}',
            'geolinkparams' => 'width=440,height=100,toolbar=0,scrollbars=0,location=0,status=1,menubar=0,resizable=1',
            'max_uploaded_file_size' => 100000,
            'max_connections_from_one_host' => 10,
            'thread_lifetime' => 600,
            'email' => '', /* inbox for left messages */
            'left_messages_locale' => HOME_LOCALE,
            'sendmessagekey' => 'center',
            'enableban' => '0',
            'enablessl' => '0',
            'forcessl' => '0',
            'usercanchangename' => '1',
            'enablegroups' => '0',
            'enablegroupsisolation' => '0',
            'enablestatistics' => '1',
            'enabletracking' => '0',
            'enablepresurvey' => '1',
            'surveyaskmail' => '0',
            'surveyaskgroup' => '1',
            'surveyaskmessage' => '0',
            'enablepopupnotification' => '0',
            'showonlineoperators' => '0',
            'enablecaptcha' => '0',
            'online_timeout' => 30, /* Timeout (in seconds) when online operator becomes offline */
            'updatefrequency_operator' => 2,
            'updatefrequency_chat' => 2,
            'statistics_aggregation_interval' => 24 * 60 * 60,
            'updatefrequency_tracking' => 10,
            'visitors_limit' => 20, /* Number of visitors to look over */
            'invitation_lifetime' => 60, /* Lifetime for invitation to chat */
            'tracking_lifetime' => 600, /* Time to store tracked old visitors' data */
            'cron_key' => DEFAULT_CRON_KEY,
            // System values are listed below. They cannot be changed via
            // administrative interface. Start names for these values from
            // underscore sign(_).
            // Unix timestamp when cron job ran last time.
            '_last_cron_run' => 0,
        );

        // Load values from database
        $db = Database::getInstance();
        $rows = $db->query(
            "SELECT vckey, vcvalue FROM {chatconfig}",
            null,
            array('return_rows' => Database::RETURN_ALL_ROWS)
        );

        foreach ($rows as $row) {
            $name = $row['vckey'];
            $this->settings[$name] = $row['vcvalue'];
            $this->settingsInDb[$name] = true;
        }
    }

    /**
     * Get setting value.
     *
     * @param string $name Variable's name
     * @return mixed
     */
    public static function get($name)
    {
        $instance = self::getInstance();

        return $instance->settings[$name];
    }

    /**
     * Set setting value.
     *
     * @param string $name Variables's name
     * @param mixed $value Variable's value
     */
    public static function set($name, $value)
    {
        $instance = self::getInstance();
        $instance->settings[$name] = $value;
    }

    /**
     * Updates settings in database.
     */
    public static function update()
    {
        $instance = self::getInstance();
        $db = Database::getInstance();
        foreach ($instance->settings as $key => $value) {
            if (!isset($instance->settingsInDb[$key])) {
                $db->query(
                    "INSERT INTO {chatconfig} (vckey) VALUES (?)",
                    array($key)
                );
            }
            $db->query(
                "UPDATE {chatconfig} SET vcvalue=? WHERE vckey=?",
                array($value, $key)
            );
        }
    }

    /**
     * Implementation of destructor
     */
    public function __destruct()
    {
    }
}
