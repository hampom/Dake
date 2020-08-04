<?php
declare(strict_types=1);

namespace App\Application\Actions;

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use HolidayJp\HolidayJp;
use Psr\Http\Message\ResponseInterface as Response;

class IcalHolidayAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $today = new \DateTime("now");
        $limitDay = (clone $today)->add(new \DateInterval("P3Y"));
        $holidays = HolidayJp::between($today, $limitDay);

        $vCalendar = new Calendar('dake.local');

        foreach ($holidays as $holiday) {
            $vCalendar->addComponent(
                (new Event)
                    ->setDtStart($holiday['date'])
                    ->setDtEnd($holiday['date'])
                    ->setNoTime(true)
                    ->setSummary($holiday['name'])
            );
        }

        $this->response->getBody()->write($vCalendar->render());

        return $this->response
            ->withHeader('Content-Type', 'text/calendar; charset=utf-8')
            ->withHeader('Content-Disposition', 'attachment; filename="cal.ics"');
    }
}