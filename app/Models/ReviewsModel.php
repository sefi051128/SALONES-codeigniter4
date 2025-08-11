<?php
namespace App\Models;

use CodeIgniter\Model;

class ReviewsModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',           // ID del usuario que hizo la reseña (si está registrado)
        'event_id',          // ID del evento relacionado (opcional)
        'client_name',       // Nombre del cliente
        'client_photo',      // URL o path de la foto
        'client_position',   // Puesto o relación con el evento
        'event_type',       // Tipo de evento (bodas, corporativos, etc.)
        'rating',           // Calificación (1-5)
        'comment',          // Texto de la reseña
        'event_date',       // Fecha del evento
        'approved',         // Si está aprobada para mostrarse
        'featured'          // Si es destacada
    ];
    
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validación
    protected $validationRules = [
        'client_name' => 'required|min_length[3]|max_length[100]',
        'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'comment' => 'required|min_length[10]|max_length[1000]',
        'event_type' => 'permit_empty|max_length[50]'
    ];
    
    protected $validationMessages = [
        'client_name' => [
            'required' => 'El nombre del cliente es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres'
        ],
        'rating' => [
            'required' => 'La calificación es obligatoria',
            'greater_than_equal_to' => 'La calificación mínima es 1',
            'less_than_equal_to' => 'La calificación máxima es 5'
        ],
        'comment' => [
            'required' => 'El comentario es obligatorio',
            'min_length' => 'El comentario debe tener al menos 10 caracteres'
        ]
    ];

    /**
     * Obtiene reseñas aprobadas
     */
    public function getApprovedReviews($limit = null, $featured = false)
    {
        $builder = $this->where('approved', true);
        
        if ($featured) {
            $builder->where('featured', true);
        }
        
        $builder->orderBy('created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    /**
     * Obtiene reseñas para administración (incluye no aprobadas)
     */
    public function getReviewsForAdmin()
    {
        return $this->orderBy('approved', 'ASC')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Obtiene reseñas por tipo de evento
     */
    public function getReviewsByEventType($eventType, $limit = null)
    {
        $builder = $this->where('approved', true)
                       ->where('event_type', $eventType)
                       ->orderBy('created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    /**
     * Aprueba una reseña
     */
    public function approveReview($id)
    {
        return $this->update($id, ['approved' => true]);
    }

    /**
     * Destaca una reseña
     */
    public function featureReview($id, $feature = true)
    {
        return $this->update($id, ['featured' => $feature]);
    }

    /**
     * Obtiene el promedio de calificaciones
     */
    public function getAverageRating()
    {
        return $this->where('approved', true)
                   ->selectAvg('rating', 'average')
                   ->first()['average'];
    }

    /**
     * Obtiene el conteo de reseñas por calificación
     */
    public function getRatingCounts()
    {
        return $this->where('approved', true)
                   ->select('rating, COUNT(*) as count')
                   ->groupBy('rating')
                   ->orderBy('rating', 'DESC')
                   ->findAll();
    }
}