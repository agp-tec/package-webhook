<?php
/**
 *
 * Data e hora: 2020-09-05 15:34:37
 * Model/Entity gerada automaticamente
 *
 */


namespace Agp\Webhook\Model\Entity;


use Agp\Webhook\Model\Resource\WebhookResource;
use Illuminate\Support\Facades\Http;


/**
 * Model Webhook
 * Tabela: adm_webhook
 * Comentario: Contém a configuração de webhooks dos aplicativos.
 *
 * id
 * Chave primária auto incremental.
 * Tipo: int	 | Chave: PRI	 | Obrigatório: Sim	 | Extra: auto_increment
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * adm_empresa_id
 * ID da empresa da configuração.
 * Tipo: int	 | Chave: MUL	 | Obrigatório: Sim	 | Extra:
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * adm_aplicativo_id
 * ID do aplicativo que irá executar o webhook.
 * Tipo: int	 | Chave: MUL	 | Obrigatório: Sim	 | Extra:
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * url
 * URL que irá receber o disparo de eventos.
 * Tipo: varchar(255)	 | Chave: 	 | Obrigatório: Sim	 | Extra:
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * situacao
 * Indica se o webhook está ativo ou inativo.
 * Tipo: tinyint(1)	 | Chave: 	 | Obrigatório: Não	 | Extra:
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

class Webhook extends \Agp\BaseUtils\Model\Entity\BaseModel
{
    protected $table;
    public $timestamps = false;
    protected $fillable = [
        "adm_empresa_id",
        "adm_aplicativo_id",
        "url",
        "situacao",
    ];
    protected $fillableRelations = [
        "webhookEventos",
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('webhook.entidade');

        parent::__construct($attributes);
    }

    public function webhookEventos()
    {
        return $this->hasMany('Agp\Webhook\Model\Entity\WebhookEvento', 'adm_webhook_id');
    }

    public function push()
    {
        $this->save();
    }

    public function save(array $options = [])
    {
        $app = config('webhook.id_app');
        if (!$app)
            throw new \Exception('APP não informado.');

        $url = config('webhook.api_agpadmin');
        if (!$url)
            throw new \Exception('URL não informado.');
        $url .= '/webhook';

        if (!auth()->check())
            throw new \Exception('USUARIO não logado.');

        $adm_empresa_id = auth()->user()->getAdmEmpresaId();
        if (!$adm_empresa_id)
            throw new \Exception('EMPRESA não informado.');

        $this->adm_empresa_id = $adm_empresa_id;
        $this->adm_aplicativo_id = $app;

        $headers = [
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'bearer ' . auth()->getToken(),
        ];

        $body = (new WebhookResource($this))->toArray(request());
        if ($this->exists)
            $response = Http::withHeaders($headers)->put($url . '/' . $this->getKey(), $body);
        else
            $response = Http::withHeaders($headers)->post($url, $body);

        return (($response->status() >= 200) && ($response->status() <= 299));
    }

    public function delete()
    {
        $app = config('webhook.id_app');
        if (!$app)
            throw new \Exception('APP não informado.');

        $url = config('webhook.api_agpadmin');
        if (!$url)
            throw new \Exception('URL não informado.');
        $url .= '/webhook';

        if (!auth()->check())
            throw new \Exception('USUARIO não logado.');

        $headers = [
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'bearer ' . auth()->getToken(),
        ];
        if ($this->exists) {
            $response = Http::withHeaders($headers)->delete($url . '/' . $this->getKey());
            return (($response->status() >= 200) && ($response->status() <= 299));
        }
        return false;
    }
}
