<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
@page {
  margin: 0;
}

body {
  background: #000;
  padding: 20px;
  font-family: sans-serif;
}

h1 {
  margin: 0;
  padding: 50px 20px;
  color : #fff;
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
</style>

<h1>
  <?php
  echo $this->db->dc->getConfValueDefault('site_name', null, 'Gestion');
  ?>
  <small>Juin 2018</small>
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
    <?php foreach ($lines as $line): ?>
    <tr>
      <td><?php echo $line->name; ?></td>
      <td><?php echo $line->contract; ?></td>
      <td style="text-align: right;"><?php echo number_format(round(rand(0, 14) * 2) / 2, 2, ',', ' '); ?> h</td>
      <td style="text-align: right;"><?php echo number_format($line->conges, 1, ',', ' '); ?> jour<?php echo ($line->conges > 1) ? 's' : ''; ?></td>
      <td style="text-align: right;"><?php echo number_format($line->maladie, 1, ',', ' '); ?> jour<?php echo ($line->maladie > 1) ? 's' : ''; ?></td>
      <td style="text-align: right;"><?php echo number_format($line->autre, 1, ',', ' '); ?> jour<?php echo ($line->autre > 1) ? 's' : ''; ?></td>
      <td style="text-align: right;"><?php echo number_format(rand(0, 142 * 100) / 100, 2, ',', ' '); ?> €</td>
      <td style="text-align: right;"><?php echo number_format(rand(0, 4242 * 100) / 100, 2, ',', ' '); ?> €</td>
      <td><?php echo $line->details; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
