<?php
// cover.php — Auto-generates a styled SVG book cover
// Called via: cover.php?title=Dune&author=Frank+Herbert&genre=sciencefiction
// Used as <img src="cover.php?..."> wherever a real cover image is missing.

header('Content-Type: image/svg+xml');
header('Cache-Control: public, max-age=86400');

$title  = htmlspecialchars(strip_tags($_GET['title']  ?? 'Untitled'), ENT_XML1);
$author = htmlspecialchars(strip_tags($_GET['author'] ?? ''), ENT_XML1);
$genre  = strtolower($_GET['genre'] ?? 'fiction');

// Genre colour palettes  [bg-top, bg-bottom, accent, text]
$palettes = [
    'fiction'        => ['#2c3e50', '#4a235a', '#f39c12', '#ffffff'],
    'nonfiction'     => ['#1a3a4a', '#154360', '#1abc9c', '#ffffff'],
    'mystery'        => ['#1c1c2e', '#2d132c', '#e91e63', '#f5f5f5'],
    'sciencefiction' => ['#0d0d2b', '#1a237e', '#00e5ff', '#e8eaf6'],
    'fantasy'        => ['#1b0033', '#4a148c', '#ffd600', '#f3e5f5'],
    'selfhelp'       => ['#1b4332', '#2d6a4f', '#74c69d', '#ffffff'],
];
[$c1, $c2, $accent, $textCol] = $palettes[$genre] ?? $palettes['fiction'];

// Wrap long title into two lines
$words = explode(' ', $title);
$line1 = ''; $line2 = '';
$half = ceil(count($words) / 2);
foreach ($words as $i => $w) {
    if ($i < $half) $line1 .= ($line1 ? ' ' : '') . $w;
    else            $line2 .= ($line2 ? ' ' : '') . $w;
}

// Decorative symbol per genre
$symbols = [
    'fiction'        => '✦',
    'nonfiction'     => '◈',
    'mystery'        => '◉',
    'sciencefiction' => '⬡',
    'fantasy'        => '✸',
    'selfhelp'       => '◎',
];
$sym = $symbols[$genre] ?? '✦';

echo <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 280" width="200" height="280">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="0.4" y2="1">
      <stop offset="0%" stop-color="{$c1}"/>
      <stop offset="100%" stop-color="{$c2}"/>
    </linearGradient>
    <linearGradient id="shine" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0%" stop-color="rgba(255,255,255,0.08)"/>
      <stop offset="100%" stop-color="rgba(255,255,255,0)"/>
    </linearGradient>
  </defs>

  <!-- Background -->
  <rect width="200" height="280" fill="url(#bg)" rx="4"/>
  <rect width="200" height="280" fill="url(#shine)" rx="4"/>

  <!-- Border frame -->
  <rect x="8" y="8" width="184" height="264" fill="none"
        stroke="{$accent}" stroke-width="1.5" rx="2" opacity="0.7"/>
  <rect x="12" y="12" width="176" height="256" fill="none"
        stroke="{$accent}" stroke-width="0.5" rx="1" opacity="0.4"/>

  <!-- Top accent bar -->
  <rect x="8" y="8" width="184" height="3" fill="{$accent}" rx="1"/>

  <!-- Decorative symbol -->
  <text x="100" y="70" text-anchor="middle" font-size="34"
        fill="{$accent}" opacity="0.9" font-family="serif">{$sym}</text>

  <!-- Corner ornaments -->
  <text x="18" y="28" font-size="10" fill="{$accent}" opacity="0.5" font-family="serif">◆</text>
  <text x="177" y="28" font-size="10" fill="{$accent}" opacity="0.5" font-family="serif">◆</text>
  <text x="18" y="270" font-size="10" fill="{$accent}" opacity="0.5" font-family="serif">◆</text>
  <text x="177" y="270" font-size="10" fill="{$accent}" opacity="0.5" font-family="serif">◆</text>

  <!-- Title -->
  <text x="100" y="118" text-anchor="middle" font-size="19"
        font-family="Georgia, serif" fill="{$textCol}" font-weight="bold"
        letter-spacing="0.5">{$line1}</text>
  <text x="100" y="142" text-anchor="middle" font-size="19"
        font-family="Georgia, serif" fill="{$textCol}" font-weight="bold"
        letter-spacing="0.5">{$line2}</text>

  <!-- Divider -->
  <line x1="40" y1="158" x2="160" y2="158" stroke="{$accent}" stroke-width="1" opacity="0.8"/>
  <circle cx="100" cy="158" r="3" fill="{$accent}" opacity="0.9"/>

  <!-- Author -->
  <text x="100" y="182" text-anchor="middle" font-size="11"
        font-family="Arial, sans-serif" fill="{$textCol}" opacity="0.85"
        letter-spacing="1">{$author}</text>

  <!-- Bottom accent bar -->
  <rect x="8" y="269" width="184" height="3" fill="{$accent}" rx="1"/>
</svg>
SVG;
