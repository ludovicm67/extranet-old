<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
@page {
  margin: 0;
}

body {
  background: #fff;
  padding: 20px;
  font-family: sans-serif;
}

h1 {
  margin: 0;
  padding: 50px 20px;
  color : #000;
}

small {
  font-size: 18px;
}

table {
  background: #fff;
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: #000;
  color: #fff;
}

table, td {
  border: 1px solid #000;
}

th {
  border: 1px solid #fff;
}

th, td {
  padding: 5px;
}

.row-2 {
  background: #eee;
}
</style>

<h1>
  <?php echo strip_tags($name); ?>
  <small><?php echo strip_tags($period); ?></small>
</h1>

<table>
  <thead>
    <tr>
      <th>Nom</th>
      <th>Contrat</th>
      <th>Heures sup</th>
      <th>Congés</th>
      <th>Maladie</th>
      <th>Autres abs.</th>
      <th>Transports</th>
      <th>Dépenses</th>
      <th>Observations</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 0; ?>
    <?php foreach ($lines as $line): ?>
    <tr<?php echo (++$i % 2 == 0) ? ' class="row-2"' : ''; ?>>
      <td><?php echo strip_tags($line->name); ?></td>
      <td><?php echo strip_tags($line->contract); ?></td>
      <td style="text-align: right;">
        <?php if ($line->overtime != 0): ?>
          <?php echo number_format($line->overtime, 2, ',', ' '); ?> h
        <?php endif; ?>
      </td>
      <td style="text-align: right;">
        <?php if ($line->conges != 0): ?>
          <?php echo number_format($line->conges, 1, ',', ' '); ?> jour<?php echo ($line->conges > 1) ? 's' : ''; ?>
        <?php endif; ?>
      </td>
      <td style="text-align: right;">
        <?php if ($line->maladie != 0): ?>
          <?php echo number_format($line->maladie, 1, ',', ' '); ?> jour<?php echo ($line->maladie > 1) ? 's' : ''; ?>
        <?php endif; ?>
      </td>
      <td style="text-align: right;">
        <?php if ($line->autre != 0): ?>
          <?php echo number_format($line->autre, 1, ',', ' '); ?> jour<?php echo ($line->autre > 1) ? 's' : ''; ?>
        <?php endif; ?>
      </td>
      <td style="text-align: right;">
        <?php if ($line->transports != 0): ?>
          <?php echo number_format($line->transports, 2, ',', ' '); ?> €
        <?php endif; ?>
      </td>
      <td style="text-align: right;">
        <?php if ($line->expenses != 0): ?>
          <?php echo number_format($line->expenses, 2, ',', ' '); ?> €
        <?php endif; ?>
      </td>
      <td><?php echo nl2br(htmlspecialchars($line->details)); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
