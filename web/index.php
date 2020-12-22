<?php

const RSS_FEED_PC = "https://forums.warframe.com/forum/3-pc-update-notes.xml/";
const RSS_FEED_PLAYSTATION = "https://forums.warframe.com/forum/152-playstation-update-notes.xml/";
const RSS_FEED_XBOX = "https://forums.warframe.com/forum/253-xbox-update-notes.xml/";
const RSS_FEED_SWITCH = "https://forums.warframe.com/forum/1196-nintendo-switch-update-notes.xml/";
const CHROME_USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36";

/**
 * Mapping of domains to RSS feeds
 * Key: Incoming domain
 * Value: RSS feed
 */
$domainRssUrlMap = [
    // PC domains
    "pc.warframestat.us" => RSS_FEED_PC,
    "pcs.warframestat.us" => RSS_FEED_PC,
    // PlayStation domains
    "ps4.warframestat.us" => RSS_FEED_PLAYSTATION,
    "playstation.warframestat.us" => RSS_FEED_PLAYSTATION,
    // Xbox domains
    "xb1.warframestat.us" => RSS_FEED_XBOX,
    "xbox.warframestat.us" => RSS_FEED_XBOX,
    // Nintendo Switch domains
    "switch.warframestat.us" => RSS_FEED_SWITCH,
];

/**
 * Load a resource from the web via curl
 * @param string $url
 * @return string|null
 */
function loadContent(string $url): ?string {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, CHROME_USER_AGENT);

    $output = curl_exec($curl);

    curl_close($curl);

    if ($output === false) {
        error_log(curl_error($curl));
        return null;
    }

    return $output;
}

/**
 * Loads the RSS feed, tries to parse it and returns the first link in it. Returns null if it fails.
 * @param string $feed
 * @return string|null
 */
function rssFirstUrl(string $feed): ?string {
    $content = loadContent($feed);

    if ($content === null) {
        return null;
    }

    $xml = new SimpleXmlElement($content);
    return $xml->channel->item[0]->link;
}

$domain = $_SERVER["SERVER_NAME"];
if (!array_key_exists($domain, $domainRssUrlMap)) {
    echo "<h2>Unknown domain: $domain</h2>";
    die();
}

$link = rssFirstUrl($domainRssUrlMap[$domain]);
if ($link === null) {
    echo "<h2>Could not load feed for domain $domain</h2>";
    die();
}

// redirect to the link
header("Location: $link");
