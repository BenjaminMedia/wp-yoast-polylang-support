<?php

/**
 * Plugin Name: WP Yoast Polylang Support
 * Plugin URI: https://github.com/BenjaminMedia/wp-yoast-polylang-support
 * Description: Wordpress mu plugin that enables Yoast settings to be stored per language created in Polylang
 * Version: 1.0.0
 * Author: Bonnier Publications - Alf Henderson
 * License: MIT License
 */

class WPYoastPolylangSupport
{
    function __construct()
    {
        add_action('plugins_loaded', [$this, 'enablePolylangSupport']);
    }

    public function enablePolylangSupport()
    {
        if (class_exists('WPSEO_Options') && function_exists('pll_current_language')) {
            array_walk(WPSEO_Options::$options, function ($value, $optionKey) {
                $this->localizeYoastOption($optionKey);
            });
        }
    }

    /**
     * Generates localized versions fo a specific Yoast setting key
     *
     * @param $optionKey
     */
    private function localizeYoastOption($optionKey)
    {
        add_filter('option_' . $optionKey, function ($defaultOptions) use ($optionKey) {
            if ($language = pll_current_language()) {
                return $this->mergeOptions(
                    $defaultOptions,
                    get_option(
                        $this->getOptionKey($optionKey, $language),
                        $defaultOptions // Return default options if option is not set yet
                    )
                );
            }
            return $defaultOptions;
        });
        add_filter('update_option_' . $optionKey, function ($currentOptions, $newOptions) use ($optionKey) {
            if ($language = pll_current_language()) {
                update_option($this->getOptionKey($optionKey, $language), $newOptions);
            }
            return $currentOptions;
        }, 1, 2);

    }


    /**
     *  Merges options to use default value if key is empty in localized options
     *
     * @param $defaultOptions
     * @param $localizedOptions
     *
     * @return array
     */
    private function mergeOptions($defaultOptions, $localizedOptions)
    {
        $output = [];
        foreach($localizedOptions as $key => $value) {
            if (empty($value)) {
                $output[$key] = $defaultOptions[$key] ?? $value;
            }
            $output[$key] = $value;
        };
        return $output;
    }

    /**
     * Generates a localized option key for wpseo options
     *
     * @param $optionKey
     * @param $language
     *
     * @return string
     */
    private function getOptionKey($optionKey, $language)
    {
        return sprintf('%s_%s', $optionKey, $language);
    }
}

new WPYoastPolylangSupport();

