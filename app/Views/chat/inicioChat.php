<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Chat Interno</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome para íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        .chat-container {
            height: calc(100vh - 150px);
        }
        .conversations-panel {
            height: 100%;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #dee2e6;
        }
        .conversations-header {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        .conversations-list {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem;
        }
        .conversation-item {
            transition: all 0.2s;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
        }
        .conversation-item:hover {
            background-color: #f8f9fa;
        }
        .conversation-item.active {
            background-color: #0d6efd;
            color: white;
        }
        .conversation-item.active .text-muted {
            color: rgba(255,255,255,0.7) !important;
        }
        .conversation-title {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .conversation-preview {
            font-size: 0.875rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .new-conversation-form {
            padding: 1rem;
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
        .messages-container {
            height: calc(100% - 120px);
        }
        .message-bubble {
            max-width: 75%;
            word-wrap: break-word;
        }
        .message-bubble.sent {
            margin-left: auto;
            background-color: #0d6efd;
            color: white;
            border-radius: 18px 18px 0 18px;
        }
        .message-bubble.received {
            background-color: #f1f1f1;
            border-radius: 18px 18px 18px 0;
        }
        @media (min-width: 768px) {
            .chat-container {
                height: calc(100vh - 180px);
            }
        }
    </style>
</head>
<body class="bg-light">
<div class="container-fluid py-3">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Chat interno</h2>
            <a href="/admin" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Atrás
            </a>
        </div>
        
        <div class="card-body p-0">
            <div class="row g-0 chat-container">
                <!-- Panel de conversaciones - Versión mejorada -->
                <div class="col-md-4 conversations-panel">
                    <div class="conversations-header">
                        <h5 class="mb-0">Conversaciones</h5>
                    </div>
                    
                    <div class="conversations-list">
                        <?php if (!empty($conversaciones)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($conversaciones as $conv): ?>
                                    <a href="<?= base_url("chat/conversacion/{$conv['id']}") ?>" 
                                       class="list-group-item list-group-item-action conversation-item p-3 <?= (isset($conversacionActual) && $conversacionActual['id'] == $conv['id']) ? 'active' : '' ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="me-3 flex-grow-1" style="min-width: 0;">
                                                <div class="conversation-title"><?= esc($conv['titulo']) ?></div>
                                                <div class="conversation-preview text-muted mt-1"><?= esc($conv['ultimo_mensaje']) ?></div>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted"><?= date('H:i', strtotime($conv['actualizado_en'])) ?></small>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column justify-content-center align-items-center h-100 text-center py-5">
                                <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No tienes conversaciones aún</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Formulario para nueva conversación -->
                    <div class="new-conversation-form">
                        <h6 class="mb-3">Nuevo mensaje</h6>
                        <form action="<?= base_url('chat/enviar') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="nueva" value="1" />
                            <div class="mb-2">
                                <label for="destinatario" class="form-label">Para</label>
                                <select name="destinatario_id" id="destinatario" class="form-select form-select-sm" required>
                                    <option value="">Selecciona usuario</option>
                                    <?php foreach ($usuarios as $u): ?>
                                        <?php if ($u['id'] == $usuarioActual['id']) continue; ?>
                                        <option value="<?= esc($u['id']) ?>">
                                            <?= esc($u['username']) ?> (<?= esc($u['role']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="asunto" class="form-label">Asunto</label>
                                <input type="text" name="titulo" id="asunto" class="form-control form-control-sm" required />
                            </div>
                            <div class="mb-2">
                                <label for="mensaje_nuevo" class="form-label">Mensaje</label>
                                <textarea name="mensaje" id="mensaje_nuevo" rows="2" class="form-control form-control-sm" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-plus me-1"></i> Iniciar conversación
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Panel de mensajes (se mantiene igual) -->
                <div class="col-md-8 d-flex flex-column bg-light p-3">
                    <?php if (isset($conversacionActual)): ?>
                        <div class="mb-3 p-2 bg-white rounded shadow-sm">
                            <h5 class="mb-1"><?= esc($conversacionActual['titulo']) ?></h5>
                            <small class="text-muted">Con: <?= esc($conversacionActual['participantes_display']) ?></small>
                        </div>
                        
                        <div class="messages-container overflow-auto mb-3 p-3 bg-white rounded shadow-sm" id="mensajes-box">
                            <?php if (!empty($conversacionActual['mensajes'])): ?>
                                <?php foreach ($conversacionActual['mensajes'] as $msg): ?>
                                    <div class="mb-3 d-flex flex-column <?= ($msg['de_id'] == $usuarioActual['id']) ? 'align-items-end' : 'align-items-start' ?>">
                                        <div class="d-flex align-items-center mb-1">
                                            <strong class="me-2"><?= esc($msg['de_nombre']) ?></strong>
                                            <small class="text-muted"><?= date('d/m H:i', strtotime($msg['fecha'])) ?></small>
                                        </div>
                                        <div class="p-3 message-bubble <?= ($msg['de_id'] == $usuarioActual['id']) ? 'sent' : 'received' ?>">
                                            <?= nl2br(esc($msg['contenido'])) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <p class="text-muted">No hay mensajes en esta conversación todavía.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <form action="<?= base_url('chat/enviar') ?>" method="post" class="mt-auto">
                            <?= csrf_field() ?>
                            <input type="hidden" name="conversacion_id" value="<?= esc($conversacionActual['id']) ?>" />
                            <div class="input-group shadow-sm">
                                <textarea name="mensaje" class="form-control" rows="2" placeholder="Escribe tu mensaje..." required></textarea>
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-paper-plane"></i> Enviar
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="d-flex flex-column justify-content-center align-items-center h-100">
                            <div class="text-center p-5">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Selecciona o crea una conversación</h5>
                                <p class="text-muted">Para comenzar a chatear</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle con Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const box = document.getElementById('mensajes-box');
        if (box) {
            box.scrollTop = box.scrollHeight;
        }
        
        // Auto-focus en el textarea del mensaje
        const messageTextarea = document.querySelector('textarea[name="mensaje"]');
        if (messageTextarea) {
            messageTextarea.focus();
        }
    });
</script>
</body>
</html>