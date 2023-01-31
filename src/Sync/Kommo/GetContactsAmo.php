<?php

namespace Sync\Kommo;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use mysql_xdevapi\Exception;

class GetContactsAmo extends AuthService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $name
     * @return array
     */
    public function getCont(string $name): array
    {
        $accessToken = $this->readToken($name);
        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['base_domain']);
        try {
            $contactsArray = $this->apiClient->contacts()->get()->toArray();
        } catch (AmoCRMMissedTokenException | AmoCRMApiException | AmoCRMoAuthApiException $e) {
            (new AuthService())->auth();
        } catch (Exception $e) {
            echo 'You have a' . $e . 'exception';
            die;
        }
        return $contactsArray;
    }

    /**
     * @param array $array
     * @return array
     */
    public function makeArray(array $array): array
    {
        $goodReturn = [];
        foreach ($array as $key => $value) {
            if (isset($value['custom_fields_values'])) {
                foreach ($value['custom_fields_values'] as $key3 => $field3) {
                    if ($field3['field_code'] == 'EMAIL') {
                        foreach ($field3['values'] as $key4 => $field4) {
                            if ($field4['enum_code'] == 'WORK') {
                                $goodReturn[] = [$field4['value'], $value['name']];
                            }
                        }
                    }
                }
            }
        }
        return $goodReturn;
    }
}
