<?php

namespace App\Analytics;

use App\Support\StoredEvents\StoredEvent;
use DateTimeImmutable;
use Tempest\Clock\Clock;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;
use function Tempest\event;
use function Tempest\Support\str;

final class ParseLogCommand
{
    use HasConsole;

    public function __construct(
        private readonly Clock $clock,
        private readonly AnalyticsConfig $config,
    ) {}

    private const array URL_BLACKLIST = [
        '.png',
        'spotify',
        '/site.webmanifest',
        '/analytics.js',
        '/stats',
        '/rss',
        '/img',
        '/rss/',
        '/feed',
        '/feed/',
        '/favicon.ico',
        '.env',
        '.jpg',
        '.jpeg',
        '.gif',
        '.git/HEAD',
        '.tar',
        '.gz',
        '.zip',
        '.bz2',
        '/robots.txt',
        '/feed.xml',
        '/rss.xml',
    ];

    private const array USER_AGENT_BLACKLIST = [
        'zapier',
        'curl',
        'guzzle',
        'bot',
        'spider',
        'ohdear',
        'crawl',
        'feed',
        'rss',
        'python',
        'uptime-kuma',
    ];

    private const array IP_BLACKLIST = [
        '45.155.205.141',
        '35.246.133.173',
        '34.89.200.43',
        '130.255.160.128',
        '185.13.96.91',
    ];

    /** @var DateTimeImmutable[] */
    private static array $ips = [];

    #[ConsoleCommand]
    public function __invoke(): void
    {
        $handle = fopen($this->config->accessLogPath, 'r');
        $now = $this->clock->now();

        $this->success(sprintf("Parsing <style=\"underline\">%s</style>", $this->config->accessLogPath));

        // Resolve the last stored date
        $lastDate = StoredEvent::query()
            ->where('eventClass = :eventClass', eventClass: PageVisited::class)
            ->orderBy('createdAt DESC')
            ->limit(1)
            ->first()
            ?->createdAt;

        while (true) {
            // Kill the process every hour in order to prevent memory issues.
            // Supervisor will restart it automatically.
            if ($now->format('H:i') === '00:00') {
                $this->error("exit");
                exit;
            }

            $line = str(fgets($handle) ?: '')->trim();

            if ($line->isEmpty()) {
                usleep(0.1 * 1000000);
                fseek($handle, ftell($handle));
                continue;
            }

            // Resolve and check date
            $date = $line->match("/\[([\w\d\/\:]+)/")[1] ?? null;

            if (! $date) {
                continue;
            }

            $date = new DateTimeImmutable($date . ' +0000');

            if ($lastDate && $lastDate >= $date) {
                continue;
            }

            // Resolve and check URL
            $url = $line->match("/(GET|POST)\s([\w\/\.\-]+)/");

            $url = str($url[2] ?? null)->trim();

            // Empty URL
            if ($url->equals('')) {
                continue;
            }

            // URL Blacklist
            if ($url->endsWith(self::URL_BLACKLIST)) {
                continue;
            }

            // Resolve and check IP
            $ip = $line->match("/^([\d\.\w\:]+)/")[1] ?? null;

            if (in_array($ip, self::IP_BLACKLIST)) {
                continue;
            }

            // Resolve and check UserAgent
            $userAgent = str($line->match('/"([\,\-\w\d\.\/\s\(\)\:\;\+\=\&\~\\\\\@\{\}\%\'\!\?\[\]\<\>\|]+)"$/')[1] ?? null)->lower();

            foreach (self::USER_AGENT_BLACKLIST as $blockedUserAgent) {
                if ($userAgent->contains($blockedUserAgent)) {
                    continue 2;
                }
            }

            // Create event
            $event = new PageVisited(
                url: $url,
                visitedAt: $date,
                ip: $ip,
                userAgent: $userAgent,
                raw: $line->toString(),
            );

            $previousDateForIp = self::$ips[$event->ip] ?? null;

            if ($previousDateForIp && $previousDateForIp->diff($event->visitedAt)->s < 3) {
                self::$ips[$event->ip] = $event->visitedAt;

                $this->writeln("<style=\"bg-red fg-white\"> {$date->format('Y-m-d H:i:s')} </style> {$event->url} (throttled)");

                continue;
            }

            event($event);

            self::$ips[$event->ip] = $event->visitedAt;

            $this->writeln("<style=\"bg-blue fg-white\"> {$date->format('Y-m-d H:i:s')} </style> {$event->url}");
        }
    }
}