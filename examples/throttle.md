## Example usage for the throttle() method

How to ignore intermittent errors unless they become more frequent:
```php
try{
    $this->someModuleMethod();
}
catch(\Exception $exception){
    $exceptionMessage = 'An exception has occurred';
    $this->log($exceptionMessage, [
        'details' => $exception->__toString()
    ])

    $maxOccurrences = 3;
    $limitReached = $this->throttle("message = ?", $exceptionMessage, 60*60, $maxOccurrences);
    if($limitReached){
        $this->sendErrorEmail("
            More than $maxOccurrences exceptions were thrown in the past hour.
            Details on past exceptions can be found in the log.
            Here is the latest exception: $exception
        ");
    }
}
```

The `if($limitReached)` code block in the above example can also be modified to limit the frequency of error emails,
in ADDITION to a minimum threshold before reporting them:
```php
...

$maxOccurrences = 3;
$limitReached = $this->throttle("message = ?", $exceptionMessage, 60*60, $maxOccurrences);
if($limitReached){
    $emailSentMessage = 'An error email was sent';
    $emailRecentlySent = $this->throttle("message = ?", $emailSentMessage, 60*15, 1);
    if(!$emailRecentlySent){
        $this->sendErrorEmail("
            This email is still only sent if more than $maxOccurences exception happen within the last hour,
            but it is ALSO limited to once every 15 minutes, regardless of how many exceptions are thrown.
        ");
    }
}
```