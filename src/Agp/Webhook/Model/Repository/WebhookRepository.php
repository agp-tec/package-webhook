<?php
/**
 *
 * Data e hora: 2020-09-05 15:34:37
 * Model/Repository gerada automaticamente
 *
 */


namespace Agp\Webhook\Model\Repository;


use Agp\Webhook\Model\Entity\Webhook;
use Illuminate\Database\Eloquent\Collection;


class WebhookRepository
{
    /** Retorna webhooks com o evento informado
     *
     * @param array $eventos Eventos para filstrar
     * @param string $adm_empresa_id ID da empresa para filtro. Utilizado caso seja rota aberta, sem usuario na autenticacao
     * @return Collection
     */
    public static function getByEventos($eventos,$adm_empresa_id)
    {
        $app = config('webhook.id_app');
        if (!$app)
            throw new \Exception('APP não informado.');

        $adm_empresa_id = auth()->check()?auth()->user()->getAdmEmpresaId():$adm_empresa_id;
        if (!$adm_empresa_id)
            throw new \Exception('EMPRESA não informado.');

        $entidade = config('webhook.entidade');
        if (!array_key_exists('webhook',$entidade) || !array_key_exists('webhook_evento',$entidade))
            throw new \Exception('ENTIDADE não configurado.');

        return Webhook::query()
            ->join($entidade['webhook_evento'],$entidade['webhook_evento'].'.adm_webhook_id','=',$entidade['webhook'].'.id')
            ->where([
                $entidade['webhook'].'.situacao' => '1',
                $entidade['webhook'].'.adm_empresa_id' => $adm_empresa_id,
                $entidade['webhook'].'.adm_aplicativo_id' => $app,
            ])
            ->whereIn($entidade['webhook_evento'].'.evento',$eventos)
            ->groupBy($entidade['webhook'].'.id')
            ->select($entidade['webhook'].'.*')
            ->get();
    }

    public function getById($id)
    {
        return Webhook::query()->findOrFail($id);
    }
}
