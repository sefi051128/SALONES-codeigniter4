<?php
namespace App\Controllers;

use App\Models\ReviewsModel;

class Reviews extends BaseController
{
    protected $reviewsModel;

    public function __construct()
    {
        $this->reviewsModel = new ReviewsModel();
    }

    // Mostrar reseñas en la vista pública
    public function index()
    {
        $data = [
            'featuredReviews' => $this->reviewsModel->getApprovedReviews(5, true),
            'recentReviews' => $this->reviewsModel->getApprovedReviews(10),
            'averageRating' => $this->reviewsModel->getAverageRating(),
            'ratingCounts' => $this->reviewsModel->getRatingCounts()
        ];

        return view('reviews/index', $data);
    }

    // Vista de administración de reseñas
    public function manage()
    {
        if (!is_admin()) {
            return redirect()->to('/')->with('error', 'Acceso no autorizado');
        }

        $data = [
            'reviews' => $this->reviewsModel->getReviewsForAdmin()
        ];

        return view('admin/reviews/manage', $data);
    }

    // Guardar una nueva reseña
    public function save()
    {
        $rules = [
            'client_name' => 'required|min_length[3]',
            'rating' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[5]',
            'comment' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'client_name' => $this->request->getPost('client_name'),
            'client_photo' => $this->request->getPost('client_photo'),
            'client_position' => $this->request->getPost('client_position'),
            'event_type' => $this->request->getPost('event_type'),
            'rating' => $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment'),
            'event_date' => $this->request->getPost('event_date'),
            'approved' => is_admin() // Si es admin, se aprueba automáticamente
        ];

        // Si el usuario está logueado, asociamos su ID
        if (logged_in()) {
            $data['user_id'] = user_id();
        }

        $this->reviewsModel->save($data);

        return redirect()->back()->with('message', is_admin() ? 'Reseña publicada' : 'Gracias por tu reseña. Será revisada antes de publicarse.');
    }

    // Aprobar una reseña (admin)
    public function approve($id)
    {
        if (!is_admin()) {
            return redirect()->to('/')->with('error', 'Acceso no autorizado');
        }

        $this->reviewsModel->approveReview($id);
        return redirect()->to('/admin/reviews')->with('message', 'Reseña aprobada');
    }

    // Destacar una reseña (admin)
    public function feature($id)
    {
        if (!is_admin()) {
            return redirect()->to('/')->with('error', 'Acceso no autorizado');
        }

        $feature = $this->request->getPost('feature') ? true : false;
        $this->reviewsModel->featureReview($id, $feature);
        return redirect()->to('/admin/reviews')->with('message', $feature ? 'Reseña destacada' : 'Reseña ya no es destacada');
    }

    // Eliminar una reseña (admin)
    public function delete($id)
    {
        if (!is_admin()) {
            return redirect()->to('/')->with('error', 'Acceso no autorizado');
        }

        $this->reviewsModel->delete($id);
        return redirect()->to('/admin/reviews')->with('message', 'Reseña eliminada');
    }
}