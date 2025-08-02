<?php namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table = 'conversaciones';
    protected $primaryKey = 'id';

    protected $allowedFields = ['titulo', 'creado_en', 'actualizado_en'];

    public function obtenerConversaciones(int $usuarioId): array
    {
        // Subconsulta para obtener último mensaje por conversación
        $subQuery = $this->db->table('mensajes')
            ->select('MAX(id) as ultimo_mensaje_id, conversacion_id')
            ->groupBy('conversacion_id')
            ->getCompiledSelect();

        $builder = $this->db->table('conversaciones c');
        $builder->select('c.id, c.titulo, c.actualizado_en, m.contenido AS ultimo_mensaje');
        $builder->join('participantes_conversacion pc', 'pc.conversacion_id = c.id');
        $builder->join("($subQuery) lm", 'lm.conversacion_id = c.id', 'left');
        $builder->join('mensajes m', 'm.id = lm.ultimo_mensaje_id', 'left');
        $builder->where('pc.usuario_id', $usuarioId);
        $builder->orderBy('c.actualizado_en', 'DESC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function obtenerConversacionPorId(int $convId, int $usuarioId): ?array
    {
        $builder = $this->db->table('participantes_conversacion');
        $builder->where('conversacion_id', $convId);
        $builder->where('usuario_id', $usuarioId);
        $existe = $builder->countAllResults(false);
        if ($existe == 0) return null;

        $conv = $this->find($convId);
        if (!$conv) return null;

        $builder = $this->db->table('mensajes m');
        $builder->select('m.*, u.username as de_nombre, m.de_usuario_id as de_id');
        $builder->join('users u', 'u.id = m.de_usuario_id');
        $builder->where('m.conversacion_id', $convId);
        $builder->orderBy('m.fecha', 'ASC');
        $mensajes = $builder->get()->getResultArray();

        $builder = $this->db->table('participantes_conversacion pc');
        $builder->select('u.username');
        $builder->join('users u', 'u.id = pc.usuario_id');
        $builder->where('pc.conversacion_id', $convId);
        $participantes = $builder->get()->getResultArray();

        $session = session();
        $usuarioActual = [
            'username' => $session->get('username'),
        ];

        $nombres = [];
        foreach ($participantes as $p) {
            if ($p['username'] !== $usuarioActual['username']) {
                $nombres[] = $p['username'];
            }
        }
        $conv['participantes_display'] = implode(', ', $nombres);
        $conv['mensajes'] = $mensajes;

        return $conv;
    }

    public function crearConversacion(int $deUsuarioId, int $paraUsuarioId, string $titulo, string $mensaje): int
    {
        $db = $this->db;
        $db->transStart();

        $this->insert([
            'titulo' => $titulo,
            'creado_en' => date('Y-m-d H:i:s'),
            'actualizado_en' => date('Y-m-d H:i:s'),
        ]);
        $convId = $this->getInsertID();

        $db->table('participantes_conversacion')->insertBatch([
            ['conversacion_id' => $convId, 'usuario_id' => $deUsuarioId],
            ['conversacion_id' => $convId, 'usuario_id' => $paraUsuarioId],
        ]);

        $db->table('mensajes')->insert([
            'conversacion_id' => $convId,
            'de_usuario_id' => $deUsuarioId,
            'contenido' => $mensaje,
            'fecha' => date('Y-m-d H:i:s'),
        ]);

        $db->table('conversaciones')->where('id', $convId)->update(['actualizado_en' => date('Y-m-d H:i:s')]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return 0;
        }

        return $convId;
    }

    public function enviarMensaje(int $conversacionId, int $deUsuarioId, string $mensaje): bool
    {
        $db = $this->db;

        $inserted = $db->table('mensajes')->insert([
            'conversacion_id' => $conversacionId,
            'de_usuario_id' => $deUsuarioId,
            'contenido' => $mensaje,
            'fecha' => date('Y-m-d H:i:s'),
        ]);

        if (!$inserted) {
            return false;
        }

        $db->table('conversaciones')->where('id', $conversacionId)->update(['actualizado_en' => date('Y-m-d H:i:s')]);

        return true;
    }
}
