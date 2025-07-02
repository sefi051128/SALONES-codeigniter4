<h2><?= esc($title) ?></h2>
<p><strong>Generado:</strong> <?= esc($generatedAt) ?></p>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Artículo</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= esc($item['name']) ?></td>
                <td><?= esc($item['cantidad']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
