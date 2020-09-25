<?php
/**
 *
 * Data e hora: 2020-09-05 15:34:37
 * Model/Resource gerada automaticamente
 *
 */


namespace Agp\Webhook\Model\Resource;


use Illuminate\Http\Resources\Json\JsonResource;


class WebhookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'empresa' => $this->adm_empresa_id,
          'aplicativo' => $this->adm_aplicativo_id,
          'url' => $this->url,
          'situacao' => $this->situacao,
          'webhookEventos' => WebhookEventoResource::collection($this->webhookEventos),
        ];
    }
}
