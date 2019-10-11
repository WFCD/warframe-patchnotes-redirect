<?php

$domainRssUrlMap = [
    // PC domains
    "pc.warframestat.us" => "https://forums.warframe.com/forum/3-pc-update-build-notes.xml",
    "pcs.warframestat.us" => "https://forums.warframe.com/forum/3-pc-update-build-notes.xml",
    // PS4 domains
    "ps4.warframestat.us" => "https://forums.warframe.com/forum/152-ps4-update-build-notes.xml/",
    // Xbox One domains
    "xb1.warframestat.us" => "https://forums.warframe.com/forum/253-xbox-one-update-build-notes.xml/",
    // Nintendo Switch domains
    "switch.warframestat.us" => "https://forums.warframe.com/forum/1196-nintendo-switch-update-build-notes.xml"
];

function rssFirstUrl(string $feed) {
    $content = file_get_contents($feed);
    $xml = new SimpleXmlElement($content);
    return $xml->channel->item[0]->link;
}

$domain = $_SERVER["SERVER_NAME"];

if (!array_key_exists($domain, $domainRssUrlMap)) {
    echo "<h2>Unknown domain: $domain</h2>";
}

$link = rssFirstUrl($domainRssUrlMap[$domain]);
header("Location: $link");
