<?php

namespace Sync\Core\Controllers;

use Illuminate\Database\QueryException;
use Sync\Models\Contact;

class ContactController extends BaseController
{
    /**
     * Создает или обновляет запись в бд
     * @param array $data
     * @return void
     * @throws QueryException
     */
    public function contactCreate(array $data): void
    {
        Contact::updateOrCreate([
            'email' => $data['email'],
        ], [
            'contact_name' => $data['contact_name'],
            'account_id' => $data['account_id'],
            'contact_id' => $data['contact_id'],
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function getEmailById(int $id): string
    {
        return Contact::where('contact_id', $id)->first()->email;
    }

    /**
     * @param int $id
     * @return string
     */
    public function getNameById(int $id): string
    {
        return Contact::where('contact_id', $id)->first()->contact_name;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function contactFindId(int $id): bool
    {
        return Contact::where('contact_id', $id)->exists();
    }
}