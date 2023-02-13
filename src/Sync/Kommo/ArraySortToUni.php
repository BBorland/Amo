<?php

namespace Sync\Kommo;

use Sync\Core\Controllers\ContactController;
use Sync\Models\Contact;

class ArraySortToUni extends AuthService
{
    /**
     * @param int $i
     * @param array $array
     * @return array
     */
    public function arraySortToUni(int $i, array $array, int $id, string $enum): array
    {
        $name = $array['name'];
        $arrayToSend = [];
        foreach ($array['custom_fields'] as $custom_field) {
            if ($custom_field['code'] == 'EMAIL') {
                foreach ($custom_field['values'] as $value) {
                    if ($value['enum'] == $enum) {
                        $email = $value['value'];
                        if ((new ContactController())->contactFindId($id) and $i) {
                            $email = (new ContactController())->getEmailById($id);
                            $name = (new ContactController())->getNameById($id);
                            Contact::where('contact_id', $id)->first()->delete();
                        }
                        $arrayToSend[] = [$email, $name, $i];
                    }
                }
            }
        }
        return $arrayToSend;
    }
}
