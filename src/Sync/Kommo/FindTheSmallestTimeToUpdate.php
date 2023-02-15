<?php

namespace Sync\Kommo;

use Carbon\Carbon;

class FindTheSmallestTimeToUpdate
{
    /**
     * Находит пользователя с наименьшим временем до истечения токена
     * @param array $arrayAllAccounts
     * @param int $timeToUpdate
     * @param bool $flagForFirstAccount
     * @return array|void
     */
    public function findTheSmallestTimeToUpdate(array $arrayAllAccounts, int $timeToUpdate, bool $flagForFirstAccount)
    {
        if (preg_match('/^\d+$/', $timeToUpdate) != 0) {
            $timeToUpdate = (int)$timeToUpdate;
        } else {
            exit('Ошибка ввода' . PHP_EOL);
        }
        foreach ($arrayAllAccounts as $account1 => $value) {
            $expires = Carbon::createFromTimestamp($value['expires']);
            $diff = Carbon::now()->diffInSeconds($expires);
            if ($diff < $timeToUpdate * 3600) {
                if ($flagForFirstAccount) {
                    $minTimeToExpire = $diff;
                    $arrayOfTimeToUpdate[] = [$account1 => $diff];
                    $flagForFirstAccount = false;
                }
                if ($diff < $minTimeToExpire) {
                    $arrayOfTimeToUpdate = [];
                    $arrayOfTimeToUpdate[] = [$account1=> $diff];
                }
            }
        }
        return $arrayOfTimeToUpdate;
    }
}