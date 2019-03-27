<?php

declare(strict_types=1);

namespace App\Application\MessageBus\Recorder;

use App\Application\Contract\MessageBus\Recorder\ContainsRecordedEvents;
use App\Application\Contract\MessageBus\Recorder\RecordsEvents;

class EventRecorder implements RecordsEvents, ContainsRecordedEvents
{
	use EventRecorderCapabilities;
}
