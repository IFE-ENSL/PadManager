<?php

namespace Ifensl\Bundle\PadManagerBundle\HttpClient;

use Da\ApiClientBundle\Http\Rest\RestApiClientBridge;
use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Exception\PadApiException;

class EtherpadApiClient extends RestApiClientBridge
{
    /**
     * Delete a EtherPad
     *
     * @param Pad $pad
     * @throws PadApiException
     * @throws ApiHttpResponseException
     */
    public function deletePad(Pad & $pad)
    {
        $data = $this->post('/1/deletePad', array(
            'apikey' => $this->implementor->getSecurityToken(),
            'padID'  => $pad->getPrivateToken(),
        ));
        $apiData = json_decode($data->getContent(), true);

        if ($apiData['code'] != 0) {
            throw new PadApiException($apiData);
        }
    }

    /**
     * Create a new EtherPad
     *
     * @param Pad $pad
     * @throws PadApiException
     * @throws ApiHttpResponseException
     * @return Pad $pad
     */
    public function createNewPad(Pad & $pad)
    {
        $data = $this->post('/1/createPad', array(
            'apikey'  => $this->implementor->getSecurityToken(),
            'padID'   => $pad->getPrivateToken()
        ));

        $apiData = json_decode($data->getContent(), true);
        if ($apiData['code'] != 0) {
            throw new PadApiException($apiData);
        }

        return $pad;
    }
}
