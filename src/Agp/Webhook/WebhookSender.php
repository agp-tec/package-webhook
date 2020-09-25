<?php


namespace Agp\Webhook;


use Agp\Log\Jobs\LogJob;
use Agp\Log\Log;
use Agp\Webhook\Model\Repository\WebhookRepository;
use HttpException;
use Illuminate\Support\Facades\Http;

class WebhookSender
{
    /** Executa os webhooks existes com os dados em $data
     * @param int $webhookId Id do webhook
     * @param array $data Dados a serem enviados no formato [ "events": ["ev_xx_created"], "data": "webhookdata" ]
     */
    public static function sender($webhookId, $data)
    {
        try {
            $webhook = (new WebhookRepository)->getById($webhookId);
            if ($webhook) {
                $response = Http::post($webhook->url, $data);
                if (($response->status() < 200) || ($response->status() > 299))
                    throw new HttpException($response->getReasonPhrase(), $response->status());
            }
        } catch (\Throwable $throwable) {
            LogJob::dispatch(new Log(5, 'Falha ao executar webhook: ' . substr($throwable->getMessage(), 0, 200), 'webhook'));
        }
    }
}
