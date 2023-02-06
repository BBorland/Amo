<?php

namespace Sync\Core\Controllers;

use Sync\Models\Account;

class AccountController extends BaseController
{
    public function accountCreate(array $data): void
    {
        foreach ($data as $contact) {
            Account::updateOrCreate($contact);
        }
    }
}