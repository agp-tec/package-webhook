<?php
/**
 *
 * Data e hora: 2020-09-05 15:34:39
 * Model/Entity gerada automaticamente
 *
 */


namespace Agp\Webhook\Model\Entity;


/**
 * Model WebhookEvento
 * Tabela: adm_webhook_evento
 * Comentario: Contém os eventos registrados para disparo do webhook.
 *
 * id
 *
 * Tipo: int	 | Chave: PRI	 | Obrigatório: Sim	 | Extra: auto_increment
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * adm_webhook_id
 * ID da configuração do webhook.
 * Tipo: int	 | Chave: MUL	 | Obrigatório: Sim	 | Extra:
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * evento
 * Nome do evento.
 * Tipo: varchar(45)	 | Chave: 	 | Obrigatório: Sim	 | Extra:
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

class WebhookEvento extends \Agp\BaseUtils\Model\Entity\BaseModel
{

    protected $table = "pag_webhook_evento";
    public $timestamps = false;
    protected $fillable = [
        "adm_webhook_id",
        "evento",
    ];
    protected $fillableRelations = [
        "webhook",
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('webhook.entidade') . '_evento';

        parent::__construct($attributes);
    }

    public function webhook()
    {
        return $this->belongsTo('Agp\Webhook\Model\Entity\Webhook', 'adm_webhook_id');
    }

}
