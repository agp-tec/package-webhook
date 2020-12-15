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
            if ($webhook)
                $response = Http::post($webhook->url, $data);
        } catch (\Throwable $throwable) {
            LogJob::dispatch(new Log(6, 'Falha ao executar webhook[' . $webhookId . ']: ' . substr($throwable->getMessage(), 0, 200)));
            throw $throwable;
        }
        if ($webhook) {
            if (($response->status() < 200) || ($response->status() > 299)) {
                $arr = [
                    'message' => 'Falha ao executar webhook[' . $webhookId . ']: ' . $response->status(),
                    'errors' => $response->body()
                ];
                LogJob::dispatch(new Log(6, json_encode($arr)));
                throw new HttpException($response->getReasonPhrase(), $response->status());
            }
        }
    }
}
