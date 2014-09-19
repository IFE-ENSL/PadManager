<?php

namespace Ifensl\Bundle\PadManagerBundle\Exception;

use Ifensl\Bundle\PadManagerBundle\Entity\Pad;

class PadApiException extends \Exception
{
    /**
     * {@inheritdoc}
     *
     * @param string $response
     */
    public function __construct($response)
    {
        parent::__construct(sprintf(
            "Pad api error: [%d] %s",
            $response['code'],
            $response['message']
        ));
    }
}
