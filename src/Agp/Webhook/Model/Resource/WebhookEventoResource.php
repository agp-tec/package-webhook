<?php
/**
 *
 * Data e hora: 2020-09-05 15:34:39
 * Model/Resource gerada automaticamente
 *
 */


namespace Agp\Webhook\Model\Resource;


use Illuminate\Http\Resources\Json\JsonResource;


class WebhookEventoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'adm_webhook_id' => $this->adm_webhook_id,
          'evento' => $this->evento,
          'webhook' => new WebhookResource($this->webhook),
        ];
    }
}
