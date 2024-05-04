---
title: Scheduling
---

**Note: this feature is still a work in progress and not yet merged.**

`tempest/console` comes with a built-in scheduler to run commands repeatedly in the background. You can schedule console commands, as well as plain functions that aren't directly accessible via the console.

In order for the scheduler to run, you'll have to configure a single cron job on your server:

```
0 * * * * user /path/to/{*tempest schedule:run*}
```

You can manually trigger a schedule run as well:

```
./tempest schedule:run
```

## Scheduling

Any method using the `{php}#[Schedule]` attribute will be run by the scheduler. As with everything Tempest, these methods are discovered automatically.

```php
final readonly class Jobs
{
    #[Schedule(Every::HOUR)]
    public function syncRss(): void
    {
        // …   
    }
}
```

For most common scheduling use-cases, the `{php}Every` enum can be used:

```php
#[Schedule(Every::MINUTE)]
#[Schedule(Every::QUARTER)]
#[Schedule(Every::HALF_HOUR)]
#[Schedule(Every::HOUR)]
#[Schedule(Every::TWELVE_HOURS)]
#[Schedule(Every::DAY)]
#[Schedule(Every::WEEK)]
#[Schedule(Every::MONTH)]
```

In case you need more fine-grained control, you can pass in an `{php}Interval` object instead:

```php
#[Schedule(new Interval(hours: 2, minutes: 30))]
public function syncRss(): void
{
    // …   
}
```

Keep in mind that scheduled task don't have to be console commands, but they _can_ be both if you need to run a task both manually, as well as a scheduled background task.

```php
#[Schedule(Every::HOUR)]
#[ConsoleCommand('rss:sync')]
public function syncRss(): void
{
    // …   
}
```

## Output and logging

Any scheduled task can inject `Console` and write to it as if it's running as a normal console command:

```php
final readonly class Jobs
{
    public function __construct(private Console $console) {}
    
    #[Schedule(Every::HOUR)]
    public function syncRss(): void
    {
        $this->console->writeln('Starting RSS sync…');
        
        // …
        
        $this->console->success('Done');
    }
}
```

The output of scheduled tasks is written to a log file, by default `schedule.log` in your project's root.