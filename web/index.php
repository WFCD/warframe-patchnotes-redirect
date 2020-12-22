<?php

/**
 * Mapping of domains to RSS feeds
 * Key: Incoming domain
 * Value: RSS feed
 */
$domainRssUrlMap = [
    // PC domains
    "pc.warframestat.us" => "https://forums.warframe.com/forum/3-pc-update-notes.xml/",
    "pcs.warframestat.us" => "https://forums.warframe.com/forum/3-pc-update-notes.xml/",
    // PlayStation domains
    "ps4.warframestat.us" => "https://forums.warframe.com/forum/152-playstation-update-notes.xml/",
    "ps5.warframestat.us" => "https://forums.warframe.com/forum/152-playstation-update-notes.xml/",
    // Xbox domains
    "xb1.warframestat.us" => "https://forums.warframe.com/forum/253-xbox-update-notes.xml/",
    "xsx.warframestat.us" => "https://forums.warframe.com/forum/253-xbox-update-notes.xml/",
    "xbox.warframestat.us" => "https://forums.warframe.com/forum/253-xbox-update-notes.xml/",
    // Nintendo Switch domains
    "switch.warframestat.us" => "https://forums.warframe.com/forum/1196-nintendo-switch-update-notes.xml/",
];

/**
 * Loads the RSS feed, tries to parse it and returns the first link in it. Returns null if it fails.
 */
function rssFirstUrl(string $feed): ?string {
    $content = file_get_contents($feed);

    if ($content === false) {
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
